<?php
/**
 * Created by PhpStorm.
 * User: mikaz3
 * Date: 12/12/17
 * Time: 12:15 PM
 */

namespace App\DataFixtures;

use App\Entity\Company;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class UserFixture extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $user = new User();

        $user->setUsername("Test");
        $user->setEmail("tom@gmail.com");
        $user->setPassword("123et567");
        $user->setFirstName("Thomas");
        $user->setLastName("Billot");
        $user->addRole("ROLE_ADMIN");

        $companiesResult = $manager->getRepository(Company::class)->findByName("RandomCompany");

        if (sizeof($companiesResult) == 1) {
            $user->setCompany($companiesResult[0]);
        }

        $manager->persist($user);
        $manager->flush();
    }
}