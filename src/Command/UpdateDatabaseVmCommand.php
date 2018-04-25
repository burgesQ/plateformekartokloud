<?php

namespace App\Command;


use App\Entity\DailyKartoVm;
use App\Entity\KartoVm;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\NonUniqueResultException;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class UpdateDatabaseVmCommand extends Command
{
    /** @var string */
    protected static $defaultName = 'app:kartokloud:sync';
    /** @var array  */
    protected static $arraySize = [ "small", "medium", "big"];
    /** @var array  */
    protected static $arrayType = [ "vm", "db"];
    /** @var array  */
    protected static $arrayFiled = ["cost", "cpu", "ram"];
    /** @var EntityManagerInterface */
    private $em;

    /**
     * JsonToDatabaseCommand constructor.
     *
     * @param EntityManagerInterface $em
     * @param null|string            $name
     */
    public function __construct(EntityManagerInterface $em, ?string $name = null)
    {
        parent::__construct($name);

        $this->em = $em;
    }

    /**
     * Configure the command
     * @TODO auto
     */
    protected function configure()
    {
        $this
            ->setDescription('Sync the kartoVm with the daily karto vm (per provider).')
            ->addArgument('provider', InputArgument::OPTIONAL, 'Provider to sync.')
            // todo not required
        ;
    }

    /**
     * @param string $provider
     * @param string $size
     * @param string $type
     * @param string $field
     * @param int    $nbEntry
     * @param int    $val
     */
    private function majKvm(string $provider, string $size, string $type, string $field, int $nbEntry, int $val) : void
    {
        $setter = "set" . ucfirst($field);

        // update kvm
        /** @var KartoVm $kvm */
        if (!($kvm = $this->em->getRepository(KartoVm::class)->findOneBy([
            "provider" => $provider,
            "size"     => $size,
            "type"     => $type
        ]))) {
            $kvm = new KartoVm();
            $kvm
                ->setSize($size)
                ->setProvider($provider)
                ->setUniqueId("{$size}_{$provider}")
                ->$setter($val / $nbEntry)
                ->setType($type)
            ;

            $this->em->persist($kvm);
        } else
            $kvm->$setter($val / $nbEntry);
    }

    /**
     * @param string $provider
     * @param string $size
     * @param string $type
     * @param string $field
     * @param int    $nbEntry
     *
     * @throws NonUniqueResultException
     */
    private function syncAFiled(string $provider, string $size, string $type, string $field, int $nbEntry) : void
    {
        /** @var int $val */
        $val = $this->em->getRepository(DailyKartoVm::class)
                        ->findAverageBySizeAndProvider($size, $provider, $type, $field);

        $this->majKvm($provider, $size, $type, $field, $nbEntry, $val);
    }

    /**
     * @param SymfonyStyle $io
     * @param string       $provider
     * @param string       $size
     * @param string       $type
     *
     * @return int
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    private function syncBySize(SymfonyStyle $io, string $provider, string $size, string $type) :int
    {
        $dailyKartoVmRepo = $this->em->getRepository(DailyKartoVm::class);
        $dailyKVm = $dailyKartoVmRepo->findBy([
            "provider" => $provider,
            "size"     => $size,
            "type"     => $type
        ]);

        $nbEntry = count($dailyKVm);
        $io->section("Their is {$nbEntry} daily karto {$type} for the provider {$provider} ({$size} size).");

        if (!$nbEntry)
            return $nbEntry;

        foreach (self::$arrayFiled as $field)
            $this->syncAFiled($provider, $size, $type, $field, $nbEntry);

        $this->em->flush();

        return $nbEntry;
    }

    /**
     * @param SymfonyStyle $io
     * @param string       $provider
     *
     * @throws NonUniqueResultException
     */
    public function runForAProvider(SymfonyStyle $io, string $provider) :void
    {
        $io->section("Sync for provider {$provider}.");
        $tot = 0;

        foreach (self::$arraySize as $oneSize)
            foreach (self::$arrayType as $oneType)
                $tot +=  $this->syncBySize($io, $provider, $oneSize, $oneType);

        $io->success("Sync for provider {$provider} done. {$tot} db entry checked.");
    }

    /**
     * @param InputInterface  $input
     * @param OutputInterface $output
     */
    protected function execute(InputInterface $input, OutputInterface $output) : void
    {
        $provider = $input->getArgument('provider');
        $io = new SymfonyStyle($input, $output);

        if (!$provider)
            $provider = $this->em->getRepository(DailyKartoVm::class)->getProviderInDatabase();

        if (is_array($provider))
            foreach ($provider as $oneProvider)
                $this->runForAProvider($io, $oneProvider[1]);
        else
            $this->runForAProvider($io, $provider);
    }
}