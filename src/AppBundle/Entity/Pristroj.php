<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Pristroj
 *
 * @ORM\Entity(repositoryClass="AppBundle\Repository\PristrojRepository")
 */
class Pristroj
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
     * @ORM\Column(name="Nazev", type="string", length=255)
     */
    private $nazev;

    /**
     * @ORM\ManyToOne(targetEntity="Category", inversedBy="pristroje")
     * @ORM\JoinColumn(name="category_id", referencedColumnName="id")
     */
    private $category;

    /**
     * @var string
     *
     * @ORM\Column(name="CisloUlozeni", type="string", length=255, unique=true)
     */
    private $cisloUlozeni;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="DatumPridani", type="datetime")
     */
    private $datumPridani;

    /**
     * @var string
     *
     * @ORM\Column(name="PridanoUzivatelem", type="string", length=255)
     */
    private $pridanoUzivatelem;


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
     * Set nazev
     *
     * @param string $nazev
     *
     * @return Pristroj
     */
    public function setNazev($nazev)
    {
        $this->nazev = $nazev;

        return $this;
    }

    /**
     * Get nazev
     *
     * @return string
     */
    public function getNazev()
    {
        return $this->nazev;
    }

    /**
     * Set cisloUlozeni
     *
     * @param string $cisloUlozeni
     *
     * @return Pristroj
     */
    public function setCisloUlozeni($cisloUlozeni)
    {
        $this->cisloUlozeni = $cisloUlozeni;

        return $this;
    }

    /**
     * Get cisloUlozeni
     *
     * @return string
     */
    public function getCisloUlozeni()
    {
        return $this->cisloUlozeni;
    }

    /**
     * Set datumPridani
     *
     * @param \DateTime $datumPridani
     *
     * @return Pristroj
     */
    public function setDatumPridani($datumPridani)
    {
        $this->datumPridani = $datumPridani;

        return $this;
    }

    /**
     * Get datumPridani
     *
     * @return \DateTime
     */
    public function getDatumPridani()
    {
        return $this->datumPridani;
    }

    /**
     * Set pridanoUzivatelem
     *
     * @param string $pridanoUzivatelem
     *
     * @return Pristroj
     */
    public function setPridanoUzivatelem($pridanoUzivatelem)
    {
        $this->pridanoUzivatelem = $pridanoUzivatelem;

        return $this;
    }

    /**
     * Get pridanoUzivatelem
     *
     * @return string
     */
    public function getPridanoUzivatelem()
    {
        return $this->pridanoUzivatelem;
    }



    /**
     * Set category
     *
     * @param \AppBundle\Entity\Category $category
     *
     * @return Pristroj
     */
    public function setCategory(\AppBundle\Entity\Category $category = null)
    {
        $this->category = $category;

        return $this;
    }

    /**
     * Get category
     *
     * @return \AppBundle\Entity\Category
     */
    public function getCategory()
    {
        return $this->category;
    }

    public function __toString()
    {
        return (string) $this->getCategory();
    }
}
