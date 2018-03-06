<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Class USerCompany
 * @package App\Entity
 *
 * @ORM\Entity
 * @ORM\Table(name="user_company")
 * @ORM\HasLifecycleCallbacks()
 */
class UserCompany
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
     * @var User
     * @ORM\OneToOne(targetEntity="User", mappedBy="company")
     */
    private $user;

    /**
     * @var Company
     * @ORM\ManyToOne(targetEntity="Company", inversedBy="users")
     */
    private $company;

    /**
     * @var bool
     * @ORM\Column(name="accepted", type="boolean", nullable=false)
     */
    private $accepted;

    /**
     * Company constructor.
     */
    public function __construct()
    {
        $this->creationDate = new \Datetime();
        $this->updateDate   = new \Datetime();
        $this->accepted     = false;
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
     * @return UserCompany
     */
    public function setCreationDate(\DateTime $creationDate) : UserCompany
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
     * @return UserCompany
     */
    public function setUpdateDate(\DateTime $updateDate) : UserCompany
    {
        $this->updateDate = $updateDate;

        return $this;
    }

    /**
     * Get User
     *
     * @return User
     */
    public function getUser() : User
    {
        return $this->user;
    }

    /**
     * Set User
     *
     * @param User $user
     *
     * @return UserCompany
     */
    public function setUser(User $user) : UserCompany
    {
        $this->user = $user;
        $user->setCompany($this);

        return $this;
    }

    /**
     * Get Company
     *
     * @return Company
     */
    public function getCompany() : Company
    {
        return $this->company;
    }

    /**
     * Set Company
     *
     * @param Company|null $company
     *
     * @return UserCompany
     */
    public function setCompany(?Company $company) : UserCompany
    {
        $this->company = $company;
        $company->addUsers($this);

        return $this;
    }

    /**
     * Is Accepted
     *
     * @return bool
     */
    public function isAccepted() : bool
    {
        return $this->accepted;
    }

    /**
     * Set Accepted
     *
     * @param bool $accepted
     *
     * @return UserCompany
     */
    public function setAccepted(bool $accepted) : UserCompany
    {
        $this->accepted = $accepted;

        return $this;
    }
}
