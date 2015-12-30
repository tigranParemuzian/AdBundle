<?php

namespace LsoftAdBundle\Entity;

use LsoftAdBundle\Admin\AdsProviderAdmin;
use Doctrine\ORM\Mapping as ORM;
use LsoftAdBundle\Entity\Ad;
use Symfony\Component\Validator\Constraints as Assert;


/**
 * @ORM\Entity()
 * @ORM\Table(name="ad_ads_provider")
 * Class AdsProvider
 *
 *
 * @package LsoftAdBundle\Entity
 */
class AdsProvider
{
    /**
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @var
     * @ORM\ManyToOne(targetEntity="Ad", cascade={"persist"})
     */
    protected $ad;

    /**
     * @ORM\ManyToMany(targetEntity="Domain", mappedBy="adsProvider")
     *
     */
    protected $domain;

    /**
     * @ORM\Column(name="zone", type="string")
     *
     */
    protected $zone;

    function __toString()
    {
        return $this->id ? (string)$this->ad : '';
    }


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
     * Set ad
     *
     * @param \LsoftAdBundle\Entity\Ad $ad
     *
     * @return AdsProvider
     */
    public function setAd(\LsoftAdBundle\Entity\Ad $ad = null)
    {
        $this->ad = $ad;

        return $this;
    }

    /**
     * Get ad
     *
     * @return \LsoftAdBundle\Entity\Ad
     */
    public function getAd()
    {
        return $this->ad;
    }

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->domain = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add domain
     *
     * @param \LsoftAdBundle\Entity\Domain $domain
     *
     * @return AdsProvider
     */
    public function addDomain(\LsoftAdBundle\Entity\Domain $domain)
    {
        $this->domain[] = $domain;

        return $this;
    }

    /**
     * Remove domain
     *
     * @param \LsoftAdBundle\Entity\Domain $domain
     */
    public function removeDomain(\LsoftAdBundle\Entity\Domain $domain)
    {
        $this->domain->removeElement($domain);
    }

    /**
     * Get domain
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getDomain()
    {
        return $this->domain;
    }

    /**
     * Set zone
     *
     * @param string $zone
     *
     * @return AdsProvider
     */
    public function setZone($zone)
    {
        $this->zone = $zone;

        return $this;
    }

    /**
     * Get zone
     *
     * @return string
     */
    public function getZone()
    {
        return $this->zone;
    }
}
