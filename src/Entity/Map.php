<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\PersistentCollection;
use JMS\Serializer\Annotation as JMS;
use Doctrine\ORM\Mapping as ORM;

/**
 * Class Map
 * @package App\Entity
 *
 * @ORM\Entity
 * @ORM\Table(name="map")
 * @JMS\ExclusionPolicy("all")
 * @ORM\HasLifecycleCallbacks()
 */
class Map
{
    /**
     * @var int
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @JMS\Expose()
     */
    protected $id;

    /**
     * @var \DateTime
     * @ORM\Column(name="creation_date", type="datetime", nullable=false)
     * @JMS\Expose()
     */
    private $creationDate;

    /**
     * @var \DateTime
     * @ORM\Column(name="update_date", type="datetime", nullable=false)
     * @JMS\Expose()
     */
    private $updateDate;

    // we should have a array of data & pos     /** @MaxDepth(1) */

    /**
     * @var Company
     *
     * @ORM\ManyToOne(targetEntity="Company", inversedBy="maps")
     * @JMS\Expose()
     * @JMS\MaxDepth(2)
     */
    private $company;

    /**
     * @var ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="KartoVmMap", mappedBy="map")
     * @JMS\Expose()
     * @JMS\MaxDepth(3)
     */
    private $kartoVms;

    /**
     * Company constructor.
     */
    public function __construct()
    {
        $this->creationDate = new \Datetime();
        $this->updateDate   = new \Datetime();
        $this->kartoVms     = new ArrayCollection();
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
     * @return Map
     */
    public function setCreationDate(\DateTime $creationDate) : Map
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
     * @return Map
     */
    public function setUpdateDate(\DateTime $updateDate) : Map
    {
        $this->updateDate = $updateDate;

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
     * @param Company $company
     *
     * @return Map
     */
    public function setCompany(Company $company) : Map
    {
        $this->company = $company;

        return $this;
    }

    /**
     * Get KartoVms
     *
     * @return ArrayCollection|PersistentCollection
     */
    public function getKartoVms() : PersistentCollection
    {
        return $this->kartoVms;
    }

    /**
     * Add KartoVm
     *
     * @param KartoVmMap $kartoVm
     *
     * @return Map
     */
    public function addKartoVm(KartoVmMap $kartoVm) : Map
    {
        $this->kartoVms[] = $kartoVm;

        return $this;
    }

    /**
     * Remove Map
     *
     * @param KartoVmMap $kartoVm
     *
     * @return Map
     */
    public function removeKartoVm(KartoVmMap $kartoVm) : Map
    {
        $this->kartoVms->remove($kartoVm);

        return $this;
    }
}
