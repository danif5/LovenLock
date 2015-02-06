<?php

    namespace FlorProject\BackendBundle\Entity;

    use Doctrine\ORM\Mapping as ORM;
    use Symfony\Component\Form\Tests\Extension\Core\Type\RepeatedTypeTest;

    /**
     * Blog
     *
     * @ORM\Table(name="blog")
     * @ORM\Entity
     */
    class Blog
    {

        public function __construct()
        {
            $this->creationDate = new \DateTime();
        }

        /**
         * @var integer
         *
         * @ORM\Column(name="id", type="integer")
         * @ORM\Id
         * @ORM\GeneratedValue(strategy="AUTO")
         */
        private $id;

        /**
         * @var string
         *
         * @ORM\Column(name="title", type="string", length=255)
         */
        private $title;

        /**
         * @var string
         *
         * @ORM\Column(name="body", type="text")
         */
        private $body;

        /**
         * @var datetime
         *
         * @ORM\Column(name="creationDate", type="datetime")
         */
        private $creationDate;

        /**
         * @var Comments
         *
         * @ORM\OneToMany(targetEntity="Comment", mappedBy="blog")
         */
        private $comments;

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
         * Set body
         *
         * @param string $body
         *
         * @return Blog
         */
        public function setBody($body)
        {
            $this->body = $body;

            return $this;
        }

        /**
         * Get body
         *
         * @return string
         */
        public function getBody()
        {
            return $this->body;
        }

        /**
         * Set creationDate
         *
         * @param \datetime $creationDate
         *
         * @return Blog
         */
        public function setCreationDate(\datetime $creationDate)
        {
            $this->creationDate = $creationDate;

            return $this;
        }

        /**
         * Get creationDate
         *
         * @return \datetime
         */
        public function getCreationDate()
        {
            return $this->creationDate;
        }

        /**
         * Set title
         *
         * @param string $title
         *
         * @return Blog
         */
        public function setTitle($title)
        {
            $this->title = $title;

            return $this;
        }

        /**
         * Get title
         *
         * @return string
         */
        public function getTitle()
        {
            return $this->title;
        }

        public function getResume()
        {
            return $this->wordLimiter($this->body);
        }

        public function getResumeFront()
        {
            return $this->wordLimiter($this->body, 50);
        }

        private function wordLimiter($str, $limit = 100, $end_char = '&#8230;')
        {
            if (trim($str) == '') {
                return $str;
            }

            preg_match('/^\s*+(?:\S++\s*+){1,' . (int)$limit . '}/', $str, $matches);

            if (strlen($str) == strlen($matches[0])) {
                $end_char = '';
            }

            $text = rtrim($matches[0]) . $end_char;
            $text = str_replace('table table-bordered','',$text);
            return $text;
        }


        /**
         * Add comments
         *
         * @param \FlorProject\BackendBundle\Entity\Comment $comments
         *
         * @return Blog
         */
        public function addComment(\FlorProject\BackendBundle\Entity\Comment $comments)
        {
            $this->comments[] = $comments;

            return $this;
        }

        /**
         * Remove comments
         *
         * @param \FlorProject\BackendBundle\Entity\Comment $comments
         */
        public function removeComment(\FlorProject\BackendBundle\Entity\Comment $comments)
        {
            $this->comments->removeElement($comments);
        }

        /**
         * Get comments
         *
         * @return \Doctrine\Common\Collections\Collection
         */
        public function getComments()
        {
            return $this->comments;
        }

        public  function __toString(){
            return (string)$this->id;
        }
    }
