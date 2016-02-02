<?php

namespace LSoft\AdBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Lsoft\AdBundle\Entity\Ad;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;


/**
 * Class AdsProvider
 *
 * @ORM\Entity()
 * @ORM\Table(name="ad_ads_provider", uniqueConstraints={@ORM\UniqueConstraint(name="ads_unique_idx", columns={"ad_id", "zone"})})
 *
 * @ORM\HasLifecycleCallbacks()
 * @UniqueEntity(fields={"ad", "zone"}, message="entity.duplicate")
 * @package LSoft\AdBundle\Entity
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
     * @ORM\ManyToOne(targetEntity="Ad", inversedBy="adsProviders", cascade={"persist"})
     * @ORM\JoinColumn(name="ad_id", referencedColumnName="id")
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
     * @param Ad $ad
     *
     * @return AdsProvider
     */
    public function setAd(Ad $ad = null)
    {
        $this->ad = $ad;

        return $this;
    }

    /**
     * Get ad
     *
     * @return Ad
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
     * @param Domain $domain
     *
     * @return AdsProvider
     */
    public function addDomain(Domain $domain)
    {
        $this->domain[] = $domain;

        return $this;
    }

    /**
     * Remove domain
     *
     * @param Domain $domain
     */
    public function removeDomain(Domain $domain)
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
