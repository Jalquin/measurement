<?php

namespace AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * Location
 *
 * @UniqueEntity(fields="shortcut", message="Tato místnost je již zaznamenána")
 *
 * @ORM\Entity(repositoryClass="AppBundle\Repository\LocationRepository")
 */
class Location
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255 )
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="shortcut", type="string", length=3, unique=true)
     */
    private $shortcut;


    /**
     * @ORM\OneToMany(targetEntity="Device", mappedBy="location")
     */
    private $device;

    public function __construct()
    {
        $this->device = new ArrayCollection();
    }

    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set name
     *
     * @param string $name
     *
     * @return Location
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set shortcut
     *
     * @param string $shortcut
     *
     * @return Location
     */
    public function setShortcut($shortcut)
    {
        $this->shortcut = $shortcut;

        return $this;
    }

    /**
     * Get shortcut
     *
     * @return string
     */
    public function getShortcut()
    {
        return $this->shortcut;
    }
    /**
     * Add device
     *
     * @param \AppBundle\Entity\Device $device
     *
     * @return Location
     */
    public function addDevice(\AppBundle\Entity\Device $device)
    {
        $this->device[] = $device;

        return $this;
    }

    /**
     * Remove device
     *
     * @param \AppBundle\Entity\Device $device
     */
    public function removeDevice(\AppBundle\Entity\Device $device)
    {
        $this->device->removeElement($device);
    }

    /**
     * Get device
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getDevice()
    {
        return $this->device;
    }
}

