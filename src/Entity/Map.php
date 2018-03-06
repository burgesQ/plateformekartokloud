<?php

namespace App\Entity;

use JMS\Serializer\Annotation as JMS;
use Doctrine\ORM\Mapping as ORM;

/**
 * Class Map
 * @package App\Entity
 *
 * @ORM\Entity
 * @ORM\Table(name="map")
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
}
