<?php

namespace BlogBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use UserBundle\Entity\User;

/**
 * Comments
 *
 * @ORM\Table(name="comments")
 * @ORM\Entity(repositoryClass="BlogBundle\Repository\CommentsRepository")
 */
class Comments
{
    public function __construct()
    {
        $this->likes = new ArrayCollection();
        $this->setCreated(new \DateTime());
        $this->setRating(0);
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
     * @var string
     *
     * @ORM\Column(name="content", type="text")
     */
    private $content;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="created", type="datetime")
     */
    private $created;

    /**
     * @var int
     *
     * @ORM\Column(name="article_id", type="integer")
     */
    private $articleId;

    /**
     * @var int
     *
     * @ORM\Column(name="parent_id", type="integer")
     */
    private $parentId;

    /**
     * @var float
     *
     * @ORM\Column(name="rating", type="float")
     * @Assert\NotBlank()
     */
    private $rating;

    /**
     * @ORM\ManyToOne(targetEntity="UserBundle\Entity\User")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     */
    private $user;

    /**
     * @ORM\OneToMany(targetEntity="BlogBundle\Entity\CommentsLikes", mappedBy="comment")
     */
    private $likes;

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
     * @return Comments
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
     * Set content
     *
     * @param string $content
     *
     * @return Comments
     */
    public function setContent($content)
    {
        $this->content = $content;

        return $this;
    }

    /**
     * Get content
     *
     * @return string
     */
    public function getContent()
    {
        return $this->content;
    }

    /**
     * Set created
     *
     * @param \DateTime $created
     *
     * @return Comments
     */
    public function setCreated($created)
    {
        $this->created = $created;

        return $this;
    }

    /**
     * Get created
     *
     * @return \DateTime
     */
    public function getCreated()
    {
        return $this->created;
    }

    /**
     * Set articleId
     *
     * @param integer $articleId
     *
     * @return Comments
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
     * Set parentId
     *
     * @param integer $parentId
     *
     * @return Comments
     */
    public function setParentId($parentId)
    {
        $this->parentId = $parentId;

        return $this;
    }

    /**
     * Get parentId
     *
     * @return int
     */
    public function getParentId()
    {
        return $this->parentId;
    }

    /**
     * Set rating
     *
     * @param float $rating
     *
     * @return Article
     */
    public function setRating($rating)
    {
        $this->rating = $rating;

        return $this;
    }

    /**
     * Get rating
     *
     * @return float
     */
    public function getRating()
    {
        return $this->rating;
    }

    /**
     * Get user
     *
     * @return User
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * Set user
     *
     * @param UserBundle\Entity\User
     *
     * @return Article
     */
    public function setUser($user = null)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Get likes
     *
     * @return ArrayCollection
     */
    public function getLikes()
    {
        return $this->likes;
    }

    /**
     * Set likes
     *
     * @param ArrayCollection
     *
     * @return Article
     */
    public function setLikes($likes = null)
    {
        $this->likes = $likes;

        return $this;
    }

    /**
     * Get comment
     *
     * @return array
     */
    public function getArray()
    {
        $user = [
            'username' => $this->getUser()->getUsername(),
            'lastLogin' => $this->getUser()->getLastLogin()->format('Y-m-d\TH:i:s'),
        ];

        $data = [
            'content' => $this->getContent(),
            'userId' => $this->getUserId(),
            'user' => $user,
            'likes' => null,
            'rating' => $this->getRating(),
            'created' => $this->getCreated()->format('Y-m-d\TH:i:s'),
        ];
        if ($likes = $this->getLikes()->toArray()) {
            $data['likes'] = $likes[0]->getVal();
        }

        return $data;
    }
}

