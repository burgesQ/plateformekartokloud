<?php

namespace App\Command;


use App\Entity\DailyKartoVm;
use App\Entity\KartoVm;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class UpdateDatabaseVmCommand extends Command
{
    /**
     * @var string
     */
    protected static $defaultName = 'app:kartokloud:sync';

    /**
     * @var EntityManagerInterface
     */
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
     */
    protected function configure()
    {
        $this
            ->setDescription('Sync the kartoVm with the daily karto vm (per provider).')
            ->addArgument('provider', InputArgument::REQUIRED, 'Provider to sync.')
        ;
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

        // calc value kvm
        $totCost = $dailyKartoVmRepo->findArevageBySizeAndProvider($size, $provider, $type, "cost");
        $totCpu  = $dailyKartoVmRepo->findArevageBySizeAndProvider($size, $provider, $type,"cpu");
        $totRam  = $dailyKartoVmRepo->findArevageBySizeAndProvider($size, $provider, $type,"ram");

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
                ->setCpu($totCpu / $nbEntry)
                ->setCost($totCost / $nbEntry)
                ->setRam($totRam / $nbEntry)
                ->setType($type)
            ;

            $this->em->persist($kvm);
        } else {
            $kvm
                ->setCost($totCost / $nbEntry)
                ->setCpu($totCpu / $nbEntry)
                ->setRam($totRam / $nbEntry)
            ;
        }

        $this->em->flush();

        return $nbEntry;
    }

    /**
     * @param InputInterface  $input
     * @param OutputInterface $output
     *
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    protected function execute(InputInterface $input, OutputInterface $output) : void
    {
        $provider = $input->getArgument('provider');

        $io = new SymfonyStyle($input, $output);
        $io->section("Sync for provider {$provider}.");

        $totVm =  $this->syncBySize($io, $provider, "small", "vm");
        $totVm += $this->syncBySize($io, $provider, "medium", "vm");
        $totVm += $this->syncBySize($io, $provider, "big", "vm");

        $totDb =  $this->syncBySize($io, $provider, "small", "db");
        $totDb += $this->syncBySize($io, $provider, "medium", "db");
        $totDb += $this->syncBySize($io, $provider, "big", "db");

        $io->success("Sync for provider {$provider} done. {$totVm} vm entry checked.");
        $io->success("Sync for provider {$provider} done. {$totDb} db entry checked.");
    }
}