<?php

namespace LSoft\AdBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;


/**
 * Class Domain
 *
 * @ORM\Entity()
 * @ORM\Table(name="lsoft_ad_domain")
 *
 * @ORM\HasLifecycleCallbacks()
 * @UniqueEntity("name", message="name.duplicate")
 * @package LSoftAdBundle\Entity
 */
class Domain
{
    /**
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\OneToMany(targetEntity="LSoft\AdBundle\Entity\AdsProvider", mappedBy="domain")
     *
     */
    protected $adsProvider;

    /**
     * @ORM\Column(name="name", type="string", unique=true)
     */
    protected $name;

    /**
     * Get id
     *
     * @return integer
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
     * @return Domain
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }


    /**
     * The __toString method allows a class to decide how it will react when it is converted to a string.
     *
     * @return string
     */
    function __toString()
    {
        return $this->id ? $this->name: '';
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
     * Constructor
     */
    public function __construct()
    {
        $this->adsProvider = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add adsProvider
     *
     * @param \LSoft\AdBundle\Entity\AdsProvider $adsProvider
     *
     * @return Domain
     */
    public function addAdsProvider(\LSoft\AdBundle\Entity\AdsProvider $adsProvider)
    {
        $this->adsProvider[] = $adsProvider;

        return $this;
    }

    /**
     * Remove adsProvider
     *
     * @param \LSoft\AdBundle\Entity\AdsProvider $adsProvider
     */
    public function removeAdsProvider(\LSoft\AdBundle\Entity\AdsProvider $adsProvider)
    {
        $this->adsProvider->removeElement($adsProvider);
    }

    /**
     * Get adsProvider
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getAdsProvider()
    {
        return $this->adsProvider;
    }
}
