<?php

namespace App\Command;

use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Console\Command\Command;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Finder\Finder;
use App\Entity\DailyKartoVm;

class JsonToDatabaseCommand extends Command
{
    /**
     * @var string
     */
    protected static $defaultName = 'app:kartokloud:update';

    /**
     * @var array
     */
    protected static $toIgnore = ['.', '..'];

    /**
     * @var string
     */
    protected static $basePath = "jsonData/output";

    /**
     * @var string
     */
    private $rootDir;

    /**
     * @var EntityManagerInterface
     */
    private $em;

    /**
     * JsonToDatabaseCommand constructor.
     *
     * @param                        $kernel_root_dir
     * @param EntityManagerInterface $em
     * @param null|string            $name
     */
    public function __construct($kernel_root_dir, EntityManagerInterface $em, ?string $name = null)
    {
        parent::__construct($name);

        $this->rootDir = $kernel_root_dir;
        $this->em      = $em;
    }

    /**
     * Configure the command
     */
    protected function configure()
    {
        $this
            ->setDescription('Scrap the json to update the data in db.')
            // specific dlInterface Tags
            // specific users
            // specific base directory
        ;
    }

    /**
     * @param string $path
     *
     * @return mixed
     */
    private function epur_json(string $path)
    {
        $jsonData   = file_get_contents($path);
        $jsonData   = str_replace("'", '"', $jsonData);
        $jsonData   = str_replace(" ", '', $jsonData);

        return json_decode($jsonData, true);
    }

    /**
     * @param array $arrayToTest
     *
     * @return bool
     */
    private static function checkArray(array $arrayToTest) :bool
    {
        return (
            array_key_exists("size", $arrayToTest)
        &&     array_key_exists("nb_ram", $arrayToTest)
        &&     array_key_exists("nb_cpu", $arrayToTest)
        );
    }

    /**
     * @param InputInterface  $input
     * @param OutputInterface $output
     *
     * @return void
     */
    protected function execute(InputInterface $input, OutputInterface $output) : void
    {
        $io = new SymfonyStyle($input, $output);

        $path = $this->rootDir . '/../' . self::$basePath;
        $io->section("Let check the {$path} directory");
        $tot = 0;
        $error = 0;

        $finder = new Finder();
        $finder->files()->in($path);

        $io->progressStart(count($finder));

        $arrayProvider = array();

        foreach ($finder as $file) {
            $usableJson = $this->epur_json($file->getRealPath());

            if (self::checkArray($usableJson)) {
                if (!($dailyKartoVm = $this->em->getRepository(DailyKartoVm::class)
                                               ->findOneBy(["uniqueId" => $usableJson["name"]]))) {
                    $dailyKartoVm = new DailyKartoVm();
                }

                if (!in_array($usableJson["provider"], $arrayProvider))
                    $arrayProvider[] = $usableJson["provider"];

                $dailyKartoVm
                    ->setSize($usableJson["size"])
                    ->setProvider($usableJson["provider"])
                    ->setRegion($usableJson["region"])
                    ->setCost($usableJson["cost"])
                    ->setCpu((int)$usableJson["nb_cpu"])
                    ->setRam($usableJson["nb_ram"])
                    ->setUniqueId($usableJson["name"])
                    ->setType($usableJson["type"])
                ;

                $this->em->persist($dailyKartoVm);
                $this->em->flush();
                $tot += 1;
            } else
                $error++;

            $io->progressAdvance();
        }
        $io->progressFinish();

        foreach ($arrayProvider as $oneProvider)
            $io->success("{$oneProvider} successfully imported.");

        $io->success("{$tot} Daily Karto VM updated !");
        $io->error("{$error} corrupted json.");
    }
}