<?php

namespace App\DataFixtures\ORM;

use App\Entity\KartoVm;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

class LoadKartoVmFixture extends AbstractFixture implements OrderedFixtureInterface
{
    /**
     * @var array
     */
    private $arrayKartoVm = [
        [
            "type" => "db",
            "size" => "small",
            "uniqueId" => "small_azure",
            "provider" => "Microsoft Azure",
            "cpu"      => "1.5",
            "ram"      => "2048",
            "cost"     => "0,006",
            "region"   => "europe"
        ],
        [
            "size" => "medium",
            "type" => "db",
            "uniqueId" => "small_azure",
            "provider" => "Microsoft Azure",
            "cpu"      => "2",
            "ram"      => "2048",
            "cost"     => "0,006",
            "region"   => "europe"
        ],
        [
            "size" => "big",
            "type" => "vm",
            "uniqueId" => "small_azure",
            "provider" => "Microsoft Azure",
            "cpu"      => "3",
            "ram"      => "2048",
            "cost"     => "0,006",
            "region"   => "europe"
        ],
        [
            "size" => "small",
            "type" => "db",
            "uniqueId" => "medium_aws",
            "provider" => "Amazone Web Server",
            "cpu"      => "3",
            "ram"      => "4096",
            "cost"     => "0,02",
            "region"   => "north_america"
        ],
        [
            "size" => "medium",
            "type" => "vm",
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
            $kartoVm = new KartoVm();
            $kartoVm
                ->setUniqueId($oneVm["uniqueId"])
                ->setProvider($oneVm["provider"])
                ->setCpu((float)$oneVm["cpu"])
                ->setRam((int)$oneVm["ram"])
                ->setCost((float)$oneVm["cost"])
                ->setSize($oneVm["size"])
                ->setType($oneVm["type"])
            ;
            $manager->persist($kartoVm);
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