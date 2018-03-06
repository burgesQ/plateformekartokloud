<?php
/**
 * Created by PhpStorm.
 * User: mikaz3
 * Date: 12/12/17
 * Time: 12:55 PM
 */

namespace App\DataFixtures\ORM;

use App\Entity\Company;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class LoadCompanyFixture extends Fixture
{
    private $arrayCompany = [ "KartoKloud", "Donut", "ThinkEat"];

    public function load(ObjectManager $manager)
    {
        foreach ($this->arrayCompany as $oneCompany) {
            $company = new Company();
            $company->setName($oneCompany);
            $manager->persist($company);
        }
        $manager->flush();
    }
}