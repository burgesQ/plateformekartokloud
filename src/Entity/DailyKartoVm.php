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
 * @ORM\Table(name="daily_karto_vm")
 * @JMS\ExclusionPolicy("all")
 * @ORM\HasLifecycleCallbacks()
 */
class DailyKartoVm
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
     * @ORM\Column(name="unqiue_id", type="string", nullable=false, unique=true)
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
     * @var int
     * @ORM\Column(name="cpu", type="integer", nullable=true)
     * @JMS\Expose()
     */
    private $cpu;

    /**
     * @var int
     * @ORM\Column(name="ram", type="bigint", nullable=false)
     * @JMS\Expose()
     */
    private $ram;

    /**
     * @var float
     * @ORM\Column(name="cost", type="float", nullable=false)
     * @JMS\Expose()
     */
    private $cost;

    /**
     * @var string
     * @ORM\Column(name="region", type="string", nullable=false)
     * @JMS\Expose()
     */
    private $region;

    /**
     * @var string
     * @ORM\Column(name="type", type="string", nullable=false)
     * @JMS\Expose()
     */
    private $type;

    /**
     * @var string
     * @ORM\Column(name="os", type="string", nullable=false)
     * @JMS\Expose()
     */
    private $os;

    /**
     * Company constructor.
     */
    public function __construct()
    {
        $this->creationDate = new \Datetime();
        $this->updateDate   = new \Datetime();
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
     * @return DailyKartoVm
     */
    public function setCreationDate(\DateTime $creationDate) : DailyKartoVm
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
     * @return DailyKartoVm
     */
    public function setUpdateDate(\DateTime $updateDate) : DailyKartoVm
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
     * @return DailyKartoVm
     */
    public function setUniqueId(string $uniqueId) : DailyKartoVm
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
     * @return DailyKartoVm
     */
    public function setProvider(string $provider) : DailyKartoVm
    {
        $this->provider = $provider;

        return $this;
    }

    /**
     * Get Cpu
     *
     * @return int
     */
    public function getCpu() : int
    {
        return $this->cpu;
    }

    /**
     * Set Cpu
     *
     * @param int $cpu
     *
     * @return DailyKartoVm
     */
    public function setCpu(int $cpu) : DailyKartoVm
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
     * @return DailyKartoVm
     */
    public function setRam(int $ram) : DailyKartoVm
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
     * @return DailyKartoVm
     */
    public function setCost(float $cost) : DailyKartoVm
    {
        $this->cost = $cost;

        return $this;
    }

    /**
     * Get Region
     *
     * @return string
     */
    public function getRegion() : string
    {
        return $this->region;
    }

    /**
     * Set Region
     *
     * @param string $region
     *
     * @return DailyKartoVm
     */
    public function setRegion(string $region) : DailyKartoVm
    {
        $this->region = $region;

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
     * @return DailyKartoVm
     */
    public function setType(string $type) : DailyKartoVm
    {
        $this->type = $type;

        return $this;
    }

    /**
     * Get Os
     *
     * @return string
     */
    public function getOs() : string
    {
        return $this->os;
    }

    /**
     * Set Os
     *
     * @param string $os
     *
     * @return DailyKartoVm
     */
    public function setOs(string $os) : DailyKartoVm
    {
        $this->os = $os;

        return $this;
    }
}
