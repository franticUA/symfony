<?php

namespace BlogBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * CommentsLikes
 *
 * @ORM\Table(name="comments_likes")
 * @ORM\Entity(repositoryClass="BlogBundle\Repository\CommentsLikesRepository")
 */
class CommentsLikes
{
    function __construct()
    {
    }

    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var int
     *
     * @ORM\Column(name="user_id", type="integer")
     */
    private $userId;

    /**
     * @var int
     *
     * @ORM\Column(name="comment_id", type="integer")
     */
    private $commentId;

    /**
     * @var float
     *
     * @ORM\Column(name="val", type="float")
     */
    private $val;

    /**
     * @ORM\ManyToOne(targetEntity="BlogBundle\Entity\Comments", inversedBy="likes")
     * @ORM\JoinColumn(name="comment_id", referencedColumnName="id", onDelete="CASCADE")
     */
    private $comment;

    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set userId
     *
     * @param integer $userId
     *
     * @return CommentsLikes
     */
    public function setUserId($userId)
    {
        $this->userId = $userId;

        return $this;
    }

    /**
     * Get userId
     *
     * @return int
     */
    public function getUserId()
    {
        return $this->userId;
    }

    /**
     * Set commentId
     *
     * @param integer $commentId
     *
     * @return CommentsLikes
     */
    public function setCommentId($commentId)
    {
        $this->commentId = $commentId;

        return $this;
    }

    /**
     * Get commentId
     *
     * @return int
     */
    public function getCommentId()
    {
        return $this->commentId;
    }

    /**
     * Set val
     *
     * @param float $val
     *
     * @return CommentsLikes
     */
    public function setVal($val)
    {
        $this->val = $val;

        return $this;
    }

    /**
     * Get val
     *
     * @return float
     */
    public function getVal()
    {
        return $this->val;
    }

    /**
     * Get comment
     *
     * @return Comments
     */
    public function getComment()
    {
        return $this->comment;
    }

    /**
     * Set article
     *
     * @param BlogBundle\Entity\Comments
     *
     * @return CommentsLikes
     */
    public function setComment($comment = null)
    {
        $this->comment = $comment;

        return $this;
    }
}

