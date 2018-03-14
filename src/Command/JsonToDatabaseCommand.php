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

        $finder = new Finder();
        $finder->files()->in($path);

        foreach ($finder as $file) {
            $io->section("Process of {$file->getFilename()} ({$file->getRealPath()})");


            $usableJson = $this->epur_json($file->getRealPath());

            if (key_exists("cpu", $usableJson)) {

                if (!($dailyKartoVm = $this->em->getRepository(DailyKartoVm::class)
                                             ->findOneBy(["uniqueId" => $usableJson["name"]])))
                    $dailyKartoVm = new DailyKartoVm();

                $dailyKartoVm
                    ->setProvider($usableJson["provider"])
                    ->setRegion($usableJson["region"])
                    ->setCost($usableJson["cost"])
                    ->setRam($usableJson["nb_ram"])
                    ->setOs($usableJson["cost"])
                    ->setUniqueId($usableJson["name"])
                    ->setType($usableJson["type"])
                ;

                $this->em->persist($dailyKartoVm);
            }
        }
        $this->em->flush();
    }
}