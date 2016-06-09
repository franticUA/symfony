<?php

namespace BlogBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Likes
 *
 * @ORM\Table(name="articles_likes")
 * @ORM\Entity(repositoryClass="BlogBundle\Repository\ArticleLikesRepository")
 */
class ArticleLikes
{
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
     * @ORM\Column(name="article_id", type="integer")
     */
    private $articleId;

    /**
     * @var int
     *
     * @ORM\Column(name="user_id", type="integer")
     */
    private $userId;

    /**
     * @var float
     *
     * @ORM\Column(name="val", type="float")
     */
    private $val;


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
     * Set articleId
     *
     * @param integer $articleId
     *
     * @return ArticleLikes
     */
    public function setArticleId($articleId)
    {
        $this->articleId = $articleId;

        return $this;
    }

    /**
     * Get articleId
     *
     * @return int
     */
    public function getArticleId()
    {
        return $this->articleId;
    }

    /**
     * Set userId
     *
     * @param integer $userId
     *
     * @return ArticleLikes
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
     * Set val
     *
     * @param float $val
     *
     * @return ArticleLikes
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
}

