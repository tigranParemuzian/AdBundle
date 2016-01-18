<?php

namespace LSoft\AdBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\DataCollector\DataCollectorInterface;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class Ad
 * @package LSoft\AdBundle\Entity
 *
 * @ORM\Entity(repositoryClass="LSoft\AdBundle\Entity\Repository\AdRepository")
 * @ORM\Table(name="ad_ads", indexes={
 *           @ORM\Index(name="group_name", columns={"name"}),
 *     })
 * @ORM\HasLifecycleCallbacks()
 * @UniqueEntity("code", message="code.duplicate")
 */
class Ad
{
    /**
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     *
     */
    protected $id;

    /**
     * @Assert\NotBlank(message = "ad.name.not_blank")
     * @Assert\Length(max=50, maxMessage = "ad.length")
     * @ORM\Column(name="name", type="string", length=50, nullable=false)
     *
     */
    protected $name;

    /**
     * @ORM\Column(name="code", type="text", nullable=true)
     *
     */
    protected $code;

    /**
     * @var
     * @ORM\OneToMany(targetEntity="LSoft\AdBundle\Entity\AdAnalyticsProvider", mappedBy="ad")
     */
    protected $adAnalytics;

    /**
     * The __toString method allows a class to decide how it will react when it is converted to a string.
     *
     * @return string
     */
    function __toString()
    {
        return $this->id ? $this->name : '';
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
     * Set name
     *
     * @param string $name
     *
     * @return Ad
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
     * Set code
     *
     * @param string $code
     *
     * @return Ad
     */
    public function setCode($code)
    {
        $this->code = $code;

        return $this;
    }

    /**
     * Get code
     *
     * @return string
     */
    public function getCode()
    {
        return $this->code;
    }
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->adAnalytics = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add adAnalytic
     *
     * @param \LSoft\AdBundle\Entity\AdAnalyticsProvider $adAnalytic
     *
     * @return Ad
     */
    public function addAdAnalytic(\LSoft\AdBundle\Entity\AdAnalyticsProvider $adAnalytic)
    {
        $this->adAnalytics[] = $adAnalytic;

        return $this;
    }

    /**
     * Remove adAnalytic
     *
     * @param \LSoft\AdBundle\Entity\AdAnalyticsProvider $adAnalytic
     */
    public function removeAdAnalytic(\LSoft\AdBundle\Entity\AdAnalyticsProvider $adAnalytic)
    {
        $this->adAnalytics->removeElement($adAnalytic);
    }

    /**
     * Get adAnalytics
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getAdAnalytics()
    {
        return $this->adAnalytics;
    }
}
