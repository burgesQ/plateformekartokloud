<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\PersistentCollection;
use JMS\Serializer\Annotation as JMS;
use Doctrine\ORM\Mapping as ORM;

/**
 * Class Company
 * @package App\Entity
 *
 * @ORM\Entity
 * @ORM\Table(name="company")
 * @ORM\HasLifecycleCallbacks()
 * @JMS\ExclusionPolicy("all")
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
     * @var \DateTime
     * @ORM\Column(name="creation_date", type="datetime", nullable=false)
     */
    private $creationDate;

    /**
     * @var \DateTime
     * @ORM\Column(name="update_date", type="datetime", nullable=false)
     */
    private $updateDate;

    /**
     * @var string
     * @ORM\Column(name="name", type="string")
     * @JMS\Expose()
     */
    private $name;

    /**
     * @var ArrayCollection
     * @ORM\OneToMany(targetEntity="UserCompany", mappedBy="company")
     */
    private $users;

    /**
     * @var ArrayCollection
     * @ORM\OneToMany(targetEntity="Map", mappedBy="company")
     * @JMS\Expose()
     * @JMS\MaxDepth(1)
     */
    private $maps;

    /**
     * Company constructor.
     */
    public function __construct()
    {
        $this->creationDate = new \Datetime();
        $this->updateDate   = new \Datetime();
        $this->users        = new ArrayCollection();
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * Get CreationDate
     *
     * @return \DateTime
     */
    public function getCreationDate() : \DateTime
    {
        return $this->creationDate;
    }

    /**
     * Set CreationDate
     *
     * @param \DateTime $creationDate
     *
     * @return Company
     */
    public function setCreationDate(\DateTime $creationDate) : Company
    {
        $this->creationDate = $creationDate;

        return $this;
    }

    /**
     * Get UpdateDate
     *
     * @return \DateTime
     */
    public function getUpdateDate() : \DateTime
    {
        return $this->updateDate;
    }

    /**
     * Set UpdateDate
     *
     * @param \DateTime $updateDate
     *
     * @return Company
     */
    public function setUpdateDate(\DateTime $updateDate) : Company
    {
        $this->updateDate = $updateDate;

        return $this;
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
    public function getName(): ?string
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
     * Get Users
     *
     * @return ArrayCollection|PersistentCollection
     */
    public function getUsers() : PersistentCollection
    {
        return $this->users;
    }

    /**
     * Add User
     *
     * @param UserCompany $user
     *
     * @return Company
     */
    public function addUsers(UserCompany $user) : Company
    {
        $this->users[] = $user;

        return $this;
    }

    /**
     * Remove Users
     *
     * @param UserCompany $user
     *
     * @return Company
     */
    public function removeUsers(UserCompany $user) : ?Company
    {
        $this->users->remove($user);

        return $this;
    }

    /**
     * Get Maps
     *
     * @return ArrayCollection|PersistentCollection
     */
    public function getMaps() : PersistentCollection
    {
        return $this->maps;
    }

    /**
     * Add Map
     *
     * @param Map $map
     *
     * @return Company
     */
    public function addMap(Map $map) : Company
    {
        $this->maps[] = $map;

        return $this;
    }

    /**
     * Remove Map
     *
     * @param Map $map
     *
     * @return Company
     */
    public function removeMap(Map $map) : ?Company
    {
        $this->maps->remove($map);

        return $this;
    }


}
