<?php

namespace App\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\Persistence\ObjectManager;

use App\Entity\Company;

class LoadCompanyFixture extends AbstractFixture implements OrderedFixtureInterface
{
    /**
     * @var array
     */
    private $arrayCompany = [ "KartoKloud", "Donut", "ThinkEat"];

    /**
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {
        foreach ($this->arrayCompany as $oneCompany) {
            $company = new Company();
            $company->setName($oneCompany);
            $manager->persist($company);
        }
        $manager->flush();
    }

    /**
     * Loading order of the fixture.
     *
     * @return int
     */
    function getOrder() { return 1; }
}