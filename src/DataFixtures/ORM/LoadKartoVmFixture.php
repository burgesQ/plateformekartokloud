<?php

namespace App\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\Persistence\ObjectManager;
use App\Entity\KartoVm;

class LoadKartoVmFixture extends AbstractFixture implements OrderedFixtureInterface
{
    /**
     * @var array
     */
    private $arrayKartoVm = [
        [
            "uniqueId" => "small_azure",
            "provider" => "Microsoft Azure",
            "cpu"      => "1.5",
            "ram"      => "2048",
            "cost"     => "0,006",
            "region"   => "europe"
        ],
        [
            "uniqueId" => "small_azure",
            "provider" => "Microsoft Azure",
            "cpu"      => "2",
            "ram"      => "2048",
            "cost"     => "0,006",
            "region"   => "europe"
        ],
        [
            "uniqueId" => "small_azure",
            "provider" => "Microsoft Azure",
            "cpu"      => "3",
            "ram"      => "2048",
            "cost"     => "0,006",
            "region"   => "europe"
        ],
        [
            "uniqueId" => "medium_aws",
            "provider" => "Amazone Web Server",
            "cpu"      => "3",
            "ram"      => "4096",
            "cost"     => "0,02",
            "region"   => "north_america"
        ],
        [
            "uniqueId" => "large_gpc",
            "provider" => "Google Cloud Platform",
            "cpu"      => "4.5",
            "ram"      => "8182",
            "cost"     => "0.20",
            "region"   => "asia"
        ]
    ];

    /**
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {
        foreach ($this->arrayKartoVm as $oneVm) {
            $kartoVm =  new KartoVm();

            try {
                $kartoVm
                    ->setUniqueId($oneVm["uniqueId"])
                    ->setProvider($oneVm["provider"])
                    ->setCpu((float)$oneVm["cpu"])
                    ->setRam((int)$oneVm["ram"])
                    ->setCost((float)$oneVm["cost"])
                    ->setRegion($oneVm["region"])
                ;

                $manager->persist($kartoVm);
            } catch (\Error $e) {
                echo $e->getTraceAsString();
            }
        }
        $manager->flush();
    }

    /**
     * Loading order of the fixture.
     *
     * @return int
     */
    public function getOrder() { return 3; }
}