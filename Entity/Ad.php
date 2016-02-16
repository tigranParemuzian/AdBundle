<?php

namespace LSoft\AdBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\DataCollector\DataCollectorInterface;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class Ad
 * @package LSoft\AdBundle\Entity
 *
 * @ORM\Entity(repositoryClass="LSoft\AdBundle\Entity\Repository\AdRepository")
 * @ORM\Table(name="lsoft_ad_ads")
 * @ORM\HasLifecycleCallbacks()
 * @UniqueEntity("name", message="name.duplicate")
 * @UniqueEntity("dimensionIndex", message="dimensionIndex.duplicate")
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
     * @ORM\Column(name="dimension_index", type="integer", nullable=true, unique=true)
     */
    protected $dimensionIndex;

    /**
     * @var
     * @ORM\OneToMany(targetEntity="LSoft\AdBundle\Entity\AdsProvider", mappedBy="ad")
     */
    protected $adsProviders;
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
        $this->adsProviders = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add adsProviders
     *
     * @param \LSoft\AdBundle\Entity\AdsProvider $adsProviders
     *
     * @return Ad
     */
    public function addAdsProviders(\LSoft\AdBundle\Entity\AdsProvider $adsProviders)
    {
        $this->adsProviders[] = $adsProviders;

        return $this;
    }

    /**
     * Remove adsProviders
     *
     * @param \LSoft\AdBundle\Entity\AdsProvider $adsProviders
     */
    public function removeAdsProviders(\LSoft\AdBundle\Entity\AdsProvider $adsProviders)
    {
        $this->adsProviders->removeElement($adsProviders);
    }

    /**
     * Get adsProviders
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getAdsProviders()
    {
        return $this->adsProviders;
    }

    /**
     * @return mixed
     */
    public function getDimensionIndex()
    {
        return $this->dimensionIndex;
    }

    /**
     * @param mixed $dimensionIndex
     */
    public function setDimensionIndex($dimensionIndex)
    {
        $this->dimensionIndex = $dimensionIndex;
    }



    /**
     * @Assert\Image()
     */
    protected  $file;

    /**
     * @ORM\Column(name="file_original_name", type="string", length=255, nullable=true)
     */
    protected $fileOriginalName;

    /**
     * @ORM\Column(name="file_name", type="string", length=255, nullable=true)
     */
    protected $fileName;

    /**
     * Sets file.
     *
     * @param UploadedFile $file
     */
    public function setFile(UploadedFile $file = null)
    {
        $this->file = $file;
    }

    /**
     * Get file.
     *
     * @return UploadedFile
     */
    public function getFile()
    {
        return $this->file;
    }

    /**
     * Set FileOriginalName
     *
     * @param string $fileOriginalName
     * @return $this
     */
    public function setFileOriginalName($fileOriginalName)
    {
        $this->fileOriginalName = $fileOriginalName;

        return $this;
    }

    /**
     * Get fileOriginalName
     *
     * @return string
     */
    public function getFileOriginalName()
    {
        return $this->fileOriginalName;
    }

    /**
     * Set fileName
     *
     * @param string $fileName
     * @return $this
     */
    public function setFileName($fileName)
    {
        $this->fileName = $fileName;

        return $this;
    }

    /**
     * Get fileName
     *
     * @return string
     */
    public function getFileName()
    {
        return $this->fileName;
    }

    /**
     * This function is used to return file web path
     *
     * @return string
     */
    public function getDownloadLink()
    {
        return '/' . $this->getUploadDir() . '/' . $this->getPath() . '/' . $this->fileName;
    }

    /**
     * @return string
     */
    public function getAbsolutePath()
    {
        return $this->getUploadRootDir() . '/' . $this->getPath() .'/';
    }

    /**
     * This function is used to return file web path
     *
     * @return string
     */
    public function getUploadRootDir()
    {
        return __DIR__. '/../../../../../../web/' . $this->getUploadDir();
    }

    /**
     * @return string
     */
    protected function getPath()
    {
        return 'l_soft_ad_images';
    }

    /**
     * Upload folder name
     *
     * @return string
     */
    protected function getUploadDir()
    {
        return 'uploads';
    }

    /**
     * This function is used to move(physically download) file
     *
     * @ORM\PrePersist
     * @ORM\PreUpdate
     */
    public function uploadFile()
    {
        // the file property can be empty if the field is not required
        if (null == $this->getFile())
        {
            return;
        }
        // check file name
        if($this->getFileName()){
            // get file path
            $path = $this->getAbsolutePath() . $this->getFileName();
            // check file
            if(file_exists($path) && is_file($path)){
                // remove file
                unlink($path);
            }
        }

        // get file originalName
        $this->setFileOriginalName($this->getFile()->getClientOriginalName());

        // get file
        $path_parts = pathinfo($this->getFile()->getClientOriginalName());

        // generate filename
        $this->setFileName(md5(microtime()) . '.' . $path_parts['extension']);

        // upload file
        $this->getFile()->move($this->getAbsolutePath(), $this->getFileName());

        // set file to null
        $this->setFile(null);
    }

    /**
     * This function is used to remove file
     *
     * @ORM\PreRemove
     */
    public function preRemove()
    {
        // get origin file path
        $filePath = $this->getAbsolutePath() . $this->getFileName();

        // check file and remove
        if (file_exists($filePath) && is_file($filePath)){
            unlink($filePath);
        }
    }


    /**
     * This function is used to get object class name
     *
     * @return string
     */
    public function getClassName(){

        return get_class($this);
    }

}
