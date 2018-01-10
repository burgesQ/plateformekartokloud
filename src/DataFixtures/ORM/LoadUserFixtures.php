<?php

namespace App\DataFixtures\ORM;

use App\Helper\TrackGeneratorHelper;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\Persistence\ObjectManager;
use App\Entity\User;

class LoadUserFixtures extends AbstractFixture
{
    /**
     * Array of the different users.
     *
     * @var array
     */
    private $userArray = [
        [
            "fName"    => "Karto",
            "lName"    => "Boi",
            "email"    => "kartoboi@kartokloud.com",
            "password" => "password",
            "roles"    => ["ROLE_ADMIN", "ROLE_USER"],
        ],
        [
            "fName"    => "Karto",
            "lName"    => "Girlz",
            "email"    => "kartogirlz@kartokloud.com",
            "password" => "password",
            "roles"    => ["ROLE_USER"],
        ]
    ];

    /**
     * Load user according to the userArray.
     *
     * @param \Doctrine\Common\Persistence\ObjectManager $em
     */
    public function load(ObjectManager $em)
    {
        foreach ($this->userArray as $userData) {

            /** @var User $user */
            $user = new User();
            $user
                ->setFirstName($userData['fName'])
                ->setLastName($userData['lName'])
                ->setPlainPassword($userData['password'])
                ->setEmail($userData['email'])
                ->setEnabled(true)
                ->setRoles($userData['roles']);
            $em->persist($user);
            $em->flush($user);

        }
        $em->flush();
    }

    /**
     * Loading order of the fixture.
     *
     * @return int
     */
    function getOrder()
    {
        return 2;
    }
}