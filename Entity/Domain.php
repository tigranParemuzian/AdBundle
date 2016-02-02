<?php

namespace LSoft\AdBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;


/**
 * Class Domain
 *
 * @ORM\Entity()
 * @ORM\Table(name="ad_domain")
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
     * @ORM\ManyToMany(targetEntity="AdsProvider", inversedBy="domain", cascade={"persist"})
     * @ORM\JoinColumn(name="ad_domain_id", referencedColumnName="id")
     *
     */
    protected $adsProvider;

    /**
     * @ORM\Column(name="name", type="string", name="true")
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
     * @param AdsProvider $adsProvider
     *
     * @return Domain
     */
    public function addadsProvider(AdsProvider $adsProvider)
    {
        $this->adsProvider[] = $adsProvider;

        return $this;
    }

    /**
     * Remove adsProvider
     *
     * @param AdsProvider $adsProvider
     */
    public function removeadsProvider(AdsProvider $adsProvider)
    {
        $this->adsProvider->removeElement($adsProvider);
    }

    /**
     * Get adsProvider
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getadsProvider()
    {
        return $this->adsProvider;
    }
}
