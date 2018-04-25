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
     *
     * @return int
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    private function syncBySize(SymfonyStyle $io, string $provider, string $size)
    {

        $dailyKartoVmRepo = $this->em->getRepository(DailyKartoVm::class);
        $dailyKVm = $dailyKartoVmRepo->findBy([
            "provider" => $provider,
            "size"     => $size
        ]);
        $nbEntry = count($dailyKVm);
        $io->section("Their is {$nbEntry} daily karto vm for the provider {$provider} ({$size} size).");

        if (!$nbEntry)
            return $nbEntry;

        // calc value kvm
        $totCost = $dailyKartoVmRepo->findArevageBySizeAndProvider($size, $provider, "cost");
        $totCpu  = $dailyKartoVmRepo->findArevageBySizeAndProvider($size, $provider, "cpu");
        $totRam  = $dailyKartoVmRepo->findArevageBySizeAndProvider($size, $provider, "ram");

        // update kvm
        /** @var KartoVm $kvm */
        if (!($kvm = $this->em->getRepository(KartoVm::class)->findOneBy([
            "provider" => $provider,
            "size"     => $size
        ]))) {
            $kvm = new KartoVm();
            $kvm
                ->setSize($size)
                ->setProvider($provider)
                ->setUniqueId("{$size}_{$provider}")
                ->setCpu($totCpu / $nbEntry)
                ->setCost($totCost / $nbEntry)
                ->setRam($totRam / $nbEntry)
                ->setType("VM")
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

        $tot =  $this->syncBySize($io, $provider, "small");
        $tot += $this->syncBySize($io, $provider, "medium");
        $tot += $this->syncBySize($io, $provider, "big");

        $io->success("Sync for provider {$provider} done. {$tot} entry checked.");
    }
}