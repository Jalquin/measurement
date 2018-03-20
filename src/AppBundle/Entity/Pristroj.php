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
     * @var string
     *
     * @ORM\Column(name="Kategorie", type="string", length=255)
     */
    private $kategorie;

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
     * Set kategorie
     *
     * @param string $kategorie
     *
     * @return Pristroj
     */
    public function setKategorie($kategorie)
    {
        $this->kategorie = $kategorie;

        return $this;
    }

    /**
     * Get kategorie
     *
     * @return string
     */
    public function getKategorie()
    {
        return $this->kategorie;
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
}

