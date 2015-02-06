<?php

namespace FlorProject\BackendBundle\Entity;

use Ali\DatatableBundle\Util\DatatableTest;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Doctrine\Common\ArrayCollection;

/**
 * Gift
 *
 * @ORM\Table(name="payablegive")
 * @ORM\Entity
 * @ORM\HasLifecycleCallbacks
 */
class PayableGive
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
     * @var Give
     *
     * @ORM\OneToOne(targetEntity="Give")
     */
    private $give;

    /**
     * @var string
     *
     * @ORM\Column(name="sender_email", type="string", length=255)
     */
    protected $senderEmail;

    /**
     * @var string
     *
     * @ORM\Column(name="receptor_email", type="string", length=255)
     */
    protected $receptorEmail;

    /**
     * @var string
     *
     * @ORM\Column(name="message", type="string", length=255)
     */
    protected $message;

    /**
     * @var string
     *
     * @ORM\Column(name="time_in_mills", type="string", length=255)
     */
    protected $timeInMills;

    /**
     * @var string
     *
     * @ORM\Column(name="media", type="string", length=255)
     */
    protected $media;

    /**
     * @var boolean
     *
     * @ORM\Column(name="sended", type="boolean")
     */
    protected $sended;

    /**
     * @Assert\File(maxSize="6000000")
     */

    function __construct()
    {
        $this->sended = false;
        $timePattern = new \DateTime();
        $timePattern->setDate(2014, 1, 1);
        $timePattern->setTime(12, 0, 0);
        $now = new \DateTime('now');
        $diff = $now->diff($timePattern);
        $this->timeInMills = ((($diff->days * 24 + $diff->h) * 60 + $diff->m) * 60 + $diff->s);
    }

    /**
     * @return Give
     */
    public function getGive()
    {
        return $this->give;
    }

    /**
     * @param Give $give
     */
    public function setGive($give)
    {
        $this->give = $give;
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
     * @return boolean
     */
    public function isSended()
    {
        return $this->sended;
    }

    /**
     * @param boolean $sended
     */
    public function setSended($sended)
    {
        $this->sended = $sended;
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
    public function getTimeInMills()
    {
        return $this->timeInMills;
    }

    /**
     * @param string $timeInMills
     */
    public function setTimeInMills($timeInMills)
    {
        $this->timeInMills = $timeInMills;
    }




    /**
     * @param string $media
     * @return $this
     */
    public function setMedia($media)
    {
        $this->media = $media;
        return $this;
    }

    /**
     * @return string
     */
    public function getMedia()
    {
        return $this->media;
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


    protected function getUploadRootDir()
    {
        // la ruta absoluta del directorio donde se deben
        // guardar los archivos cargados
        return __DIR__.'/../../../../app/'.$this->getUploadDir();
    }

    protected function getUploadDir()
    {
        // se deshace del __DIR__ para no meter la pata
        // al mostrar el documento/imagen cargada en la vista.
        return 'upload/attachments';
    }

    public function getAbsolutePath()
    {
        return null === $this->path
            ? null
            : $this->getUploadRootDir().'/'.$this->media;
    }

    public function getWebPath()
    {
        return null === $this->path
            ? null
            : $this->getUploadDir().'/'.$this->media;
    }

    private $temp;

    /**
     * Sets file.
     *
     * @param UploadedFile $file
     */
    public function setFile(UploadedFile $file = null)
    {
        $this->file = $file;
        // check if we have an old image path
        if (isset($this->path)) {
            // store the old name to delete after the update
            $this->temp = $this->path;
            $this->media = null;
        } else {
            $this->media = 'initial';
        }
    }

    /**
     * @ORM\PrePersist()
     * @ORM\PreUpdate()
     */
    public function preUpload()
    {
        if (null !== $this->getFile()) {
            // haz lo que quieras para generar un nombre único
            $filename = sha1(uniqid(mt_rand(), true));
            $this->media = $filename.'.'.$this->getFile()->guessExtension();
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
            unlink($this->getUploadRootDir().'/'.$this->temp);
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
     * @return mixed
     */
    public function getFile()
    {
        return $this->file;
    }


}
