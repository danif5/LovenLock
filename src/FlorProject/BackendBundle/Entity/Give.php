<?php

namespace FlorProject\BackendBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Doctrine\Common\ArrayCollection;

/**
 * Give
 *
 * @ORM\Table(name="give")
 * @ORM\Entity
 * @ORM\HasLifecycleCallbacks
 */
class Give
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var Gift
     *
     * @ORM\ManyToOne(targetEntity="Gift")
     */
    private $gift;

    /**
     * @var string
     *
     * @ORM\Column(name="latitude", type="string", length=255, nullable=true)
     */
    private $latitude;

    /**
     * @var string
     *
     * @ORM\Column(name="longitude", type="string", length=255, nullable=true)
     */
    private $longitude;

    /**
     * @var string
     *
     * @ORM\Column(name="code_give", type="string", length=255, unique=true)
     */
    protected $codeGive;

    /**
     * @var string
     *
     * @ORM\Column(name="sender_email", type="string", length=255, nullable=true)
     */
    protected $senderEmail;

    /**
     * @var string
     *
     * @ORM\Column(name="receptor_email", type="string", length=255, nullable=true)
     */
    protected $receptorEmail;

    /**
     * @var string
     *
     * @ORM\Column(name="message", type="string", length=255, nullable=true)
     */
    protected $message;

    /**
     * @var boolean
     *
     * @ORM\Column(name="payed", type="boolean")
     */
    protected $payed;

    /**
     * @var User
     *
     * @ORM\ManyToOne(targetEntity="User")
     *
     */
    private $user;

    /**
     * @var string
     *
     * @ORM\Column(name="media", type="string", length=255, nullable=true)
     */
    protected $media;

    /**
     * @Assert\File(maxSize="6000000")
     */
    private $file;

    /**
     * @var string
     *
     * @ORM\Column(name="latitude2", type="string", length=255, nullable=true)
     */
    protected $latitude2;

    /**
     * @var string
     *
     * @ORM\Column(name="longitude2", type="string", length=255, nullable=true)
     */
    protected $longitude2;

    /**
     * @var string
     *
     * @ORM\Column(name="zoom", type="string", length=255, nullable=true)
     */
    protected $zoom;

    /**
     * @var string
     *
     * @ORM\Column(name="latitude3", type="string", length=255, nullable=true)
     */
    protected $latitude3;

    /**
     * @var string
     *
     * @ORM\Column(name="longitude3", type="string", length=255, nullable=true)
     */
    protected $longitude3;

    /**
     * @var string
     *
     * @ORM\Column(name="zoom2", type="string", length=255, nullable=true)
     */
    protected $zoom2;

    /**
     * @var string
     *
     * @ORM\Column(name="heading", type="string", length=255, nullable=true)
     */
    protected $heading;

    /**
     * @var string
     *
     * @ORM\Column(name="pitch", type="string", length=255, nullable=true)
     */
    protected $pitch;


    /**
     * @var string
     *
     * @ORM\Column(name="transactionId", type="string", length=255, nullable=true)
     */
    protected $transactionId;


    /**
     * @var string
     *
     * @ORM\Column(name="sended", type="string", length=255, nullable=true)
     */
    protected $sended;


    function __construct()
    {
        $this->codeGive = sha1(uniqid(mt_rand(), true));
        $this->payed = false;
    }

    /**
     * @return Gift
     */
    public function getGift()
    {
        return $this->gift;
    }

    /**
     * @param Gift $gift
     */
    public function setGift($gift)
    {
        $this->gift = $gift;
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }


    /**
     * @return string
     */
    public function getLatitude()
    {
        return $this->latitude;
    }

    /**
     * @param string $latitude
     */
    public function setLatitude($latitude)
    {
        $this->latitude = $latitude;
    }

    /**
     * @return string
     */
    public function getLongitude()
    {
        return $this->longitude;
    }

    /**
     * @param string $longitude
     */
    public function setLongitude($longitude)
    {
        $this->longitude = $longitude;
    }

    /**
     * @return mixed
     */
    public function getFile()
    {
        return $this->file;
    }

    /**
     * Sets file.
     *
     * @param UploadedFile $file
     */
    public function setFile(UploadedFile $file = null)
    {
        $this->file = $file;
        // check if we have an old image path
        if (isset($this->media)) {
            // store the old name to delete after the update
            $this->temp = $this->media;
            $this->media = null;
        } else {
            $this->media = 'initial';
        }
    }

    /**
     * @return string
     */
    public function getMedia()
    {
        return $this->media;
    }

    /**
     * @param string $media
     */
    public function setMedia($media)
    {
        $this->media = $media;
    }

    /**
     * @return string
     */
    public function getMessage()
    {
        return $this->message;
    }

    /**
     * @param string $message
     */
    public function setMessage($message)
    {
        $this->message = $message;
    }

    /**
     * @return string
     */
    public function getReceptorEmail()
    {
        return $this->receptorEmail;
    }

    /**
     * @param string $receptorEmail
     */
    public function setReceptorEmail($receptorEmail)
    {
        $this->receptorEmail = $receptorEmail;
    }

    /**
     * @return string
     */
    public function getSenderEmail()
    {
        return $this->senderEmail;
    }

    /**
     * @param string $senderEmail
     */
    public function setSenderEmail($senderEmail)
    {
        $this->senderEmail = $senderEmail;
    }

    /**
     * @return string
     */
    public function getCodeGive()
    {
        return $this->codeGive;
    }

    /**
     * @param string $codeGive
     */
    public function setCodeGive($codeGive)
    {
        $this->codeGive = $codeGive;
    }

    /**
     * @return boolean
     */
    public function getPayed()
    {
        return $this->payed;
    }

    /**
     * @param boolean $payed
     */
    public function setPayed($payed)
    {
        $this->payed = $payed;
    }

    /**
     * @return User
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * @param User $user
     */
    public function setUser($user)
    {
        $this->user = $user;
    }


    protected function getUploadRootDir()
    {
        // la ruta absoluta del directorio donde se deben
        // guardar los archivos cargados
        return __DIR__ . '/../../../../app/' . $this->getUploadDir();
    }

    protected function getUploadDir()
    {
        // se deshace del __DIR__ para no meter la pata
        // al mostrar el documento/imagen cargada en la vista.
        return 'upload/attach';
    }

    public function getAbsolutePath()
    {
        return null === $this->media
            ? null
            : $this->getUploadRootDir() . '/' . $this->media;
    }

    public function getWebPath()
    {
        return null === $this->media
            ? null
            : $this->getUploadDir() . '/' . $this->media;
    }

    private $temp;

    /**
     * @ORM\PrePersist()
     * @ORM\PreUpdate()
     */
    public function preUpload()
    {
        if (null !== $this->getFile()) {
            // haz lo que quieras para generar un nombre único
            $filename = sha1(uniqid(mt_rand(), true));
            $this->media = $filename . '.' . $this->getFile()->guessExtension();
        }
    }

    /**
     * @ORM\PostPersist()
     * @ORM\PostUpdate()
     */
    public function upload()
    {
        if (null === $this->getFile()) {
            return;
        }

        // si hay un error al mover el archivo, move() automáticamente
        // envía una excepción. This will properly prevent
        // the entity from being persisted to the database on error
        $this->getFile()->move($this->getUploadRootDir(), $this->media);

        // check if we have an old image
        if (isset($this->temp)) {
            // delete the old image
            unlink($this->getUploadRootDir() . '/' . $this->temp);
            // clear the temp image path
            $this->temp = null;
        }
        $this->file = null;
    }

    /**
     * @ORM\PostRemove()
     */
    public function removeUpload()
    {
        if ($file = $this->getAbsolutePath()) {
            unlink($file);
        }
    }

    /**
     * Set latitude2
     *
     * @param string $latitude2
     * @return Give
     */
    public function setLatitude2($latitude2)
    {
        $this->latitude2 = $latitude2;

        return $this;
    }

    /**
     * Get latitude2
     *
     * @return string 
     */
    public function getLatitude2()
    {
        return $this->latitude2;
    }

    /**
     * Set longitude2
     *
     * @param string $longitude2
     * @return Give
     */
    public function setLongitude2($longitude2)
    {
        $this->longitude2 = $longitude2;

        return $this;
    }

    /**
     * Get longitude2
     *
     * @return string 
     */
    public function getLongitude2()
    {
        return $this->longitude2;
    }

    /**
     * Set zoom
     *
     * @param string $zoom
     * @return Give
     */
    public function setZoom($zoom)
    {
        $this->zoom = $zoom;

        return $this;
    }

    /**
     * Get zoom
     *
     * @return string 
     */
    public function getZoom()
    {
        return $this->zoom;
    }

    /**
     * Set latitude3
     *
     * @param string $latitude3
     * @return Give
     */
    public function setLatitude3($latitude3)
    {
        $this->latitude3 = $latitude3;

        return $this;
    }

    /**
     * Get latitude3
     *
     * @return string 
     */
    public function getLatitude3()
    {
        return $this->latitude3;
    }

    /**
     * Set longitude3
     *
     * @param string $longitude3
     * @return Give
     */
    public function setLongitude3($longitude3)
    {
        $this->longitude3 = $longitude3;

        return $this;
    }

    /**
     * Get longitude3
     *
     * @return string 
     */
    public function getLongitude3()
    {
        return $this->longitude3;
    }

    /**
     * Set zoom2
     *
     * @param string $zoom2
     * @return Give
     */
    public function setZoom2($zoom2)
    {
        $this->zoom2 = $zoom2;

        return $this;
    }

    /**
     * Get zoom2
     *
     * @return string 
     */
    public function getZoom2()
    {
        return $this->zoom2;
    }

    /**
     * Set heading
     *
     * @param string $heading
     * @return Give
     */
    public function setHeading($heading)
    {
        $this->heading = $heading;

        return $this;
    }

    /**
     * Get heading
     *
     * @return string 
     */
    public function getHeading()
    {
        return $this->heading;
    }

    /**
     * Set pitch
     *
     * @param string $pitch
     * @return Give
     */
    public function setPitch($pitch)
    {
        $this->pitch = $pitch;

        return $this;
    }

    /**
     * Get pitch
     *
     * @return string 
     */
    public function getPitch()
    {
        return $this->pitch;
    }



    /**
     * @return string
     */
    public function getTransactionId()
    {
        return $this->transactionId;
    }

    /**
     * @param string $transactionId
     */
    public function setTransactionId($transactionId)
    {
        $this->transactionId = $transactionId;
    }


    /**
     * @return string
     */
    public function getSended()
    {
        return $this->sended;
    }

    /**
     * @param string $sended
     */
    public function setSended($sended)
    {
        $this->sended = $sended;
    }


}
