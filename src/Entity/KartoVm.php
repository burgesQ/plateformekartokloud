<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\PersistentCollection;
use JMS\Serializer\Annotation as JMS;
use Doctrine\ORM\Mapping as ORM;

/**
 * Class KartoVM
 *
 * @package App\Entity
 *
 * @ORM\Entity
 * @ORM\Table(name="karto_vm")
 * @JMS\ExclusionPolicy("all")
 * @ORM\HasLifecycleCallbacks()
 */
class KartoVm
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
     * @ORM\Column(name="unqiue_id", type="string", nullable=false)
     * @JMS\Expose()
     */
    private $uniqueId;

    /**
     * @var string
     * @ORM\Column(name="provider", type="string", nullable=false)
     * @JMS\Expose()
     */
    private $provider;

    /**
     * @var float
     * @ORM\Column(name="cpu", type="float")
     * @JMS\Expose()
     */
    private $cpu;

    /**
     * @var int
     * @ORM\Column(name="ram", type="bigint")
     * @JMS\Expose()
     */
    private $ram;

    /**
     * @var float
     * @ORM\Column(name="cost", type="float")
     * @JMS\Expose()
     */
    private $cost;

    /**
     * @var string
     * @ORM\Column(name="size", type="string", nullable=false)
     * @JMS\Expose()
     */
    private $size;

    /**
     * @var string
     * @ORM\Column(name="type", type="string", nullable=false)
     * @JMS\Expose()
     */
    private $type;

    /**
     * @var ArrayCollection
     * @ORM\OneToMany(targetEntity="KartoVmMap", mappedBy="kartoVm")
     */
    private $maps;

    /**
     * Company constructor.
     */
    public function __construct()
    {
        $this->creationDate = new \Datetime();
        $this->updateDate   = new \Datetime();
        $this->maps         = new ArrayCollection();
    }

    /**
     * @return int
     */
    public function getId() : int
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
     * @return KartoVm
     */
    public function setCreationDate(\DateTime $creationDate) : KartoVm
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
     * @return KartoVm
     */
    public function setUpdateDate(\DateTime $updateDate) : KartoVm
    {
        $this->updateDate = $updateDate;

        return $this;
    }

    /**
     * Get UniqueId
     *
     * @return string
     */
    public function getUniqueId() : string
    {
        return $this->uniqueId;
    }

    /**
     * Set UniqueId
     *
     * @param string $uniqueId
     *
     * @return KartoVm
     */
    public function setUniqueId(string $uniqueId) : KartoVm
    {
        $this->uniqueId = $uniqueId;

        return $this;
    }

    /**
     * Get Provider
     *
     * @return string
     */
    public function getProvider() : string
    {
        return $this->provider;
    }

    /**
     * Set Provider
     *
     * @param string $provider
     *
     * @return KartoVm
     */
    public function setProvider(string $provider) : KartoVm
    {
        $this->provider = $provider;

        return $this;
    }

    /**
     * Get Cpu
     *
     * @return float
     */
    public function getCpu() : float
    {
        return $this->cpu;
    }

    /**
     * Set Cpu
     *
     * @param float $cpu
     *
     * @return KartoVm
     */
    public function setCpu(float $cpu) : KartoVm
    {
        $this->cpu = $cpu;

        return $this;
    }

    /**
     * Get Ram
     *
     * @return int
     */
    public function getRam() : int
    {
        return $this->ram;
    }

    /**
     * Set Ram
     *
     * @param int $ram
     *
     * @return KartoVm
     */
    public function setRam(int $ram) : KartoVm
    {
        $this->ram = $ram;

        return $this;
    }

    /**
     * Get Cost
     *
     * @return float
     */
    public function getCost() : float
    {
        return $this->cost;
    }

    /**
     * Set Cost
     *
     * @param float $cost
     *
     * @return KartoVm
     */
    public function setCost(float $cost) : KartoVm
    {
        $this->cost = $cost;

        return $this;
    }

    /**
     * Get Size
     *
     * @return string
     */
    public function getSize() : string
    {
        return $this->size;
    }

    /**
     * Set Size
     *
     * @param string $size
     *
     * @return KartoVm
     */
    public function setSize(string $size) : KartoVm
    {
        $this->size = $size;

        return $this;
    }

    /**
     * Get Type
     *
     * @return string
     */
    public function getType() : string
    {
        return $this->type;
    }

    /**
     * Set Type
     *
     * @param string $type
     *
     * @return KartoVm
     */
    public function setType(string $type) : KartoVm
    {
        $this->type = $type;

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
     * @param KartoVmMap $map
     *
     * @return KartoVm
     */
    public function addMap(KartoVmMap $map) : KartoVm
    {
        $this->maps[] = $map;

        return $this;
    }

    /**
     * Remove Map
     *
     * @param KartoVmMap $map
     *
     * @return KartoVm
     */
    public function removeMap(KartoVmMap $map) : KartoVm
    {
        $this->maps->remove($map);

        return $this;
    }
}
