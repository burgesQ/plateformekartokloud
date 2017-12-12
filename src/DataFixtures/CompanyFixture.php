<?php
/**
 * Created by PhpStorm.
 * User: mikaz3
 * Date: 12/12/17
 * Time: 12:55 PM
 */

namespace App\DataFixtures;

use App\Entity\Company;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class CompanyFixture extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $company = new Company();

        $company->setName("RandomCompany");

        $manager->persist($company);
        $manager->flush();
    }
}