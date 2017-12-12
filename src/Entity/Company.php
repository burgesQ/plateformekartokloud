<?php
/**
 * Created by PhpStorm.
 * User: mikaz3
 * Date: 12/12/17
 * Time: 12:10 PM
 */

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Class Company
 * @package App\Entity
 *
 * @ORM\Entity
 * @ORM\Table(name="company")
 * @ORM\HasLifecycleCallbacks()
 */
class Company
{
    /**
     * @var int
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @var string
     * @ORM\Column(name="name", type="string")
     */
    private $name;

    /**
     * @var User
     * @ORM\OneToMany(targetEntity="User", mappedBy="company")
     */
    private $users;

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @param int $id
     */
    public function setId(int $id): void
    {
        $this->id = $id;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName(string $name): void
    {
        $this->name = $name;
    }

    /**
     * @return User
     */
    public function getUsers(): User
    {
        return $this->users;
    }

    /**
     * @param User $users
     */
    public function setUsers(User $users): void
    {
        $this->users = $users;
    }

}
