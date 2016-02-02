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
 * @ORM\Table(name="lsoft_ads_provider", uniqueConstraints={@ORM\UniqueConstraint(name="ads_unique_idx", columns={"ad_id", "domain", "zone"})})
 *
 * @ORM\HasLifecycleCallbacks()
 * @UniqueEntity(fields={"ad", "domain", "zone"}, message="entity.duplicate")
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
     * @ORM\ManyToOne(targetEntity="LSoft\AdBundle\Entity\Domain", inversedBy="adsProvider")
     * @ORM\JoinColumn(name="domain", referencedColumnName="id")
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

    /**
     * Set domain
     *
     * @param \LSoft\AdBundle\Entity\Domain $domain
     *
     * @return AdsProvider
     */
    public function setDomain(\LSoft\AdBundle\Entity\Domain $domain = null)
    {
        $this->domain = $domain;

        return $this;
    }

    /**
     * Get domain
     *
     * @return \LSoft\AdBundle\Entity\Domain
     */
    public function getDomain()
    {
        return $this->domain;
    }
}
