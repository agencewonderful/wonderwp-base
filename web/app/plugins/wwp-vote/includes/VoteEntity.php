<?php

namespace WonderWp\Plugin\Vote;
use Doctrine\ORM\Mapping as ORM;
use WonderWp\Entity\AbstractEntity;

/**
 * VoteEntity
 *
 * @ORM\Table(name="vote")
 * @ORM\Entity
 */
class VoteEntity extends AbstractEntity
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var integer
     *
     * @ORM\Column(name="score", type="integer", nullable=false)
     */
    private $score;

    /**
     * @var string
     *
     * @ORM\Column(name="entityName", type="string", length=45, nullable=false)
     */
    private $entityname;

    /**
     * @var integer
     *
     * @ORM\Column(name="entityId", type="integer", nullable=false)
     */
    private $entityid;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="datetime", type="datetime", nullable=false)
     */
    private $datetime;

    /**
     * @var string
     *
     * @ORM\Column(name="data", type="text", length=65535, nullable=false)
     */
    private $data;

    /**
     * @var integer
     *
     * @ORM\Column(name="post", type="integer", nullable=false)
     */
    private $post;

    /**
     * @var string
     *
     * @ORM\Column(name="uid", type="string", length=45, nullable=false)
     */
    private $uid;


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
     * Set score
     *
     * @param integer $score
     * @return VoteEntity
     */
    public function setScore($score)
    {
        $this->score = $score;

        return $this;
    }

    /**
     * Get score
     *
     * @return integer
     */
    public function getScore()
    {
        return $this->score;
    }

    /**
     * Set entityname
     *
     * @param string $entityname
     * @return VoteEntity
     */
    public function setEntityname($entityname)
    {
        $this->entityname = $entityname;

        return $this;
    }

    /**
     * Get entityname
     *
     * @return string
     */
    public function getEntityname()
    {
        return $this->entityname;
    }

    /**
     * Set entityid
     *
     * @param string $entityid
     * @return VoteEntity
     */
    public function setEntityid($entityid)
    {
        $this->entityid = $entityid;

        return $this;
    }

    /**
     * Get entityid
     *
     * @return string
     */
    public function getEntityid()
    {
        return $this->entityid;
    }

    /**
     * Set datetime
     *
     * @param \DateTime $datetime
     * @return VoteEntity
     */
    public function setDatetime($datetime)
    {
        $this->datetime = $datetime;

        return $this;
    }

    /**
     * Get datetime
     *
     * @return \DateTime
     */
    public function getDatetime()
    {
        return $this->datetime;
    }

    /**
     * @return string
     */
    public function getData()
    {
        return $this->data;
    }

    /**
     * @param string $data
     */
    public function setData($data)
    {
        $this->data = $data;
    }

    /**
     * @return int
     */
    public function getUid()
    {
        return $this->uid;
    }

    /**
     * @param int $uid
     */
    public function setUid($uid)
    {
        $this->uid = $uid;
    }

    /**
     * @return int
     */
    public function getPost()
    {
        return $this->post;
    }

    /**
     * @param int $post
     */
    public function setPost($post)
    {
        $this->post = $post;
    }
}
