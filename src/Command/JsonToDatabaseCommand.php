<?php

namespace App\Command;

use App\Entity\DailyKartoVm;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Finder\Finder;

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
    private $path;

    /**
     * @var string
     */
    private $rootDir;

    /**
     * @var EntityManagerInterface
     */
    private $em;

    private $arrayProvider;

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

        $this->rootDir       = $kernel_root_dir;
        $this->em            = $em;
        $this->path          = $this->rootDir . '/../' . self::$basePath;
        $this->arrayProvider = [];
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
     * @param InputInterface  $input
     * @param OutputInterface $output
     *
     * @return void
     */
    protected
    function execute(
        InputInterface $input,
        OutputInterface $output
    ) : void {
        $io            = new SymfonyStyle($input, $output);
        $tot           = 0;
        $error         = 0;
        $finder        = new Finder();

        // get path to scrap
        $finder->files()->in($this->path);
        $io->section("Let check the {$this->path} directory");
        $io->progressStart(count($finder));

        foreach ($finder as $file) {
            $usableJson = $this->epur_json($file->getRealPath());
            if (self::checkArray($usableJson)) {
                $this->createDailyKartoStuffs($usableJson);

                $this->increaseCounters($usableJson["provider"], $usableJson["type"]);
                $tot++;
            } else {
                $error++;
            }

            $io->progressAdvance();
        }

        $io->progressFinish();
        $this->outputProvider($io);
        $io->success("{$tot} Daily Karto VM updated !");
        $io->error("{$error} corrupted json (No size specified nor ram nor cpu).");
    }

    /**
     * @param string $path
     *
     * @return array
     */
    private
    function epur_json(
        string $path
    ) : array {
        $jsonData = file_get_contents($path);
        $jsonData = str_replace("'", '"', $jsonData);
        $jsonData = str_replace(" ", '', $jsonData);

        return json_decode($jsonData, true);
    }

    /**
     * @param array $arrayToTest
     *
     * @return bool
     */
    private
    static function checkArray(
        array $arrayToTest
    ) : bool {
        return (
            array_key_exists("size", $arrayToTest)
            && array_key_exists("nb_ram", $arrayToTest)
            && array_key_exists("nb_cpu", $arrayToTest)
        );
    }

    /**
     * @param array $usableJson
     */
    private function createDailyKartoStuffs(array $usableJson) : void
    {
        // if dkvs don't exist; create it
        if (!($dailyKartoVm = $this->em->getRepository(DailyKartoVm::class)
                                       ->findOneBy(["uniqueId" => $usableJson["name"]]))) {
            $dailyKartoVm = new DailyKartoVm();
        }

        // set dkvm
        $dailyKartoVm
            ->setSize($usableJson["size"])
            ->setProvider($usableJson["provider"])
            ->setRegion(strtolower($usableJson["region"]))
            ->setCost($usableJson["cost"])
            ->setCpu((int)$usableJson["nb_cpu"])
            ->setRam($usableJson["nb_ram"])
            ->setUniqueId($usableJson["name"])
            ->setType(strtolower($usableJson["type"]))
        ;

        // save dkvm
        $this->em->persist($dailyKartoVm);
        $this->em->flush();
    }

    /**
     * @param string $provider
     * @param string $type
     */
    private function increaseCounters(string $provider, string $type) : void
    {
        // count by provider & by type
        if (!array_key_exists($provider, $this->arrayProvider)) {
            $this->arrayProvider[$provider]["tot"] = 1;
        } else {
            $this->arrayProvider[$provider]["tot"]++;
        }

        if (!array_key_exists($type, $this->arrayProvider[$provider])) {
            $this->arrayProvider[$provider][$type] = 1;
        } else {
            $this->arrayProvider[$provider][$type]++;
        }
    }

    /**
     * @param SymfonyStyle $io
     */
    private function outputProvider(SymfonyStyle $io) : void
    {
        foreach ($this->arrayProvider as $provider => $data) {
            $io->success("{$provider} successfully imported. {$data['tot']} entries.");
            foreach ($data as $type => $nb) {
                if ($type != "tot") {
                    $io->text("{$nb} {$type}.");
                }
            }
        }
    }
}