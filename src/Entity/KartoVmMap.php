<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use JMS\Serializer\Annotation as JMS;
use Doctrine\ORM\Mapping as ORM;

/**
 * Class KartoVM
 *
 * @package App\Entity
 *
 * @ORM\Entity
 * @ORM\Table(name="karto_vm_map")
 * @JMS\ExclusionPolicy("all")
 * @ORM\HasLifecycleCallbacks()
 */
class KartoVmMap
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
     * @var KartoVm
     *
     * @ORM\ManyToOne(targetEntity="KartoVm", inversedBy="maps")
     * @JMS\Expose()
     * @JMS\MaxDepth(1)
     */
    private $kartoVm;

    /**
     * @var Map
     * @ORM\ManyToOne(targetEntity="Map", inversedBy="kartoVms")
     * @JMS\Expose("map")
     * @JMS\MaxDepth(1)
     */
    private $map;

    /**
     * @var int
     * @ORM\Column(name="x_pos", type="smallint", nullable=false)
     * @JMS\Expose()
     */
    private $x_pos;

    /**
     * @var int
     * @ORM\Column(name="y_pos", type="smallint", nullable=false)
     * @JMS\Expose()
     */
    private $y_pos;

    /**
     * Company constructor.
     *
     * @param int $x_pos
     * @param int $y_pos
     */
    public function __construct(int $x_pos, int $y_pos)
    {
        $this->creationDate = new \Datetime();
        $this->updateDate   = new \Datetime();
        $this->x_pos        = $x_pos;
        $this->y_pos        = $y_pos;
        $this->kartoVm      = new ArrayCollection();
        $this->map          = new ArrayCollection();
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
     * @return KartoVmMap
     */
    public function setCreationDate(\DateTime $creationDate) : KartoVmMap
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
     * @return KartoVmMap
     */
    public function setUpdateDate(\DateTime $updateDate) : KartoVmMap
    {
        $this->updateDate = $updateDate;

        return $this;
    }

    /**
     * Get KartoVm
     *
     * @return KartoVm
     */
    public function getKartoVm() : KartoVm
    {
        return $this->kartoVm;
    }

    /**
     * Set KartoVm
     *
     * @param KartoVm $kartoVm
     *
     * @return KartoVmMap
     */
    public function setKartoVm(KartoVm $kartoVm) : KartoVmMap
    {
        $this->kartoVm = $kartoVm;

        return $this;
    }

    /**
     * Get Map
     *
     * @return Map
     */
    public function getMap() : Map
    {
        return $this->map;
    }

    /**
     * Set Map
     *
     * @param Map $map
     *
     * @return KartoVmMap
     */
    public function setMap(Map $map) : KartoVmMap
    {
        $this->map = $map;

        return $this;
    }

    /**
     * Get XPos
     *
     * @return int
     */
    public function getXPos() : int
    {
        return $this->x_pos;
    }

    /**
     * Set XPos
     *
     * @param int $x_pos
     *
     * @return KartoVmMap
     */
    public function setXPos(int $x_pos) : KartoVmMap
    {
        $this->x_pos = $x_pos;

        return $this;
    }

    /**
     * Get YPos
     *
     * @return int
     */
    public function getYPos() : int
    {
        return $this->y_pos;
    }

    /**
     * Set YPos
     *
     * @param int $y_pos
     *
     * @return KartoVmMap
     */
    public function setYPos(int $y_pos) : KartoVmMap
    {
        $this->y_pos = $y_pos;

        return $this;
    }
}
