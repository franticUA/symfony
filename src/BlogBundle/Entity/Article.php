<?php

namespace BlogBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use UserBundle\Entity\User;

/**
 * Article
 *
 * @ORM\Table(name="articles")
 * @ORM\Entity(repositoryClass="BlogBundle\Repository\ArticleRepository")
 */
class Article
{
    public function __construct()
    {
        $this->likes = new ArrayCollection();
        $this->contentTexts = new ArrayCollection();
        $this->contentFiles = new ArrayCollection();
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
     * @var string
     *
     * @ORM\Column(name="title", type="string", length=255)
     * @Assert\NotBlank()
     */
    private $title;

    /**
     * @var int
     * @ORM\Column(name="user_id", type="integer")
     */
    private $userId;

    /**
     * @ORM\ManyToOne(targetEntity="UserBundle\Entity\User")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id", onDelete="CASCADE")
     */
    private $user;

    /**
     * @ORM\OneToMany(targetEntity="BlogBundle\Entity\ArticleTexts", mappedBy="article", cascade={"persist"})
     * @ORM\OrderBy({"sort" = "ASC"})
     * @Assert\Valid
     */
    private $contentTexts;

    /**
     * @ORM\OneToMany(targetEntity="BlogBundle\Entity\ArticleFiles", mappedBy="article", cascade={"persist"})
     * @ORM\OrderBy({"sort" = "ASC"})
     * @Assert\Valid
     */
    private $contentFiles;

    /**
     * @var float
     *
     * @ORM\Column(name="rating", type="float", options={"default" = 0})
     * @Assert\NotBlank()
     */
    private $rating;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="created", type="datetime")
     * @Assert\NotBlank()
     * @Assert\Type("\DateTime")
     */
    private $created;

    /**
     * @ORM\OneToMany(targetEntity="BlogBundle\Entity\ArticleLikes", mappedBy="article")
     */
    private $likes;

    /**
     * @ORM\OneToMany(targetEntity="BlogBundle\Entity\Comments", mappedBy="article")
     * @ORM\OrderBy({"id" = "ASC"})
     */
    private $comments;


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
     * Set title
     *
     * @param string $title
     *
     * @return Article
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

    /**
     * Set userId
     *
     * @param integer $userId
     *
     * @return Article
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
     * Get content
     *
     * @return string
     */
    public function getContent()
    {
        $content = [];
        $texts = $this->contentTexts->getValues();
        foreach ($texts as $element) {
            $content[$element->getSort()] = ['val' => $element->getContent(), 'type' => 'text'];
        }

        $files = $this->contentFiles->getValues();
        foreach ($files as $element) {
            $content[$element->getSort()] = ['val' => $element->getWebPath(), 'type' => 'img'];
        }
        ksort($content);

        return $content;
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
     * Set addDate
     *
     * @param \DateTime $created
     *
     * @return Article
     */
    public function setCreated($created)
    {
        $this->created = $created;

        return $this;
    }

    /**
     * Get addDate
     *
     * @return \DateTime
     */
    public function getCreated()
    {
        return $this->created;
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
     * Set contentTexts
     *
     * @param ArrayCollection
     *
     * @return Article
     */
    public function setContentTexts($texts = null)
    {
        $this->contentTexts = $texts;

        return $this;
    }

    /**
     * Get contentTexts
     *
     * @param ArrayCollection
     *
     * @return Article
     */
    public function getContentTexts()
    {
        return $this->contentTexts;
    }

    /**
     * Set contentFiles
     *
     * @param ArrayCollection
     *
     * @return Article
     */
    public function setContentFiles($files = null)
    {
        $this->contentFiles = $files;

        return $this;
    }

    /**
     * Get contentTexts
     *
     * @param ArrayCollection
     *
     * @return Article
     */
    public function getContentFiles()
    {
        return $this->contentFiles;
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
     * Get comments
     *
     * @return ArrayCollection
     */
    public function getComments()
    {
        return $this->comments;
    }

    /**
     * Get comments
     *
     * @return ArrayCollection
     */
    public function getCommentsByParents()
    {
        $commentsByParents = [];
        foreach ($this->comments as $comm) {
            $commentsByParents[$comm->getParentId()][] = $comm;
        }

        return $commentsByParents;
    }

    /**
     * Set likes
     *
     * @param ArrayCollection
     *
     * @return Article
     */
    public function setComments($comments = null)
    {
        $this->comments = $comments;

        return $this;
    }

    /**
     * Get article
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
            'id' => $this->getId(),
            'title' => $this->getTitle(),
            'content' => $this->getContent(),
            'userId' => $this->getUserId(),
            'user' => $user,
            'userLike' => $this->getUserLike(),
            'rating' => $this->getRating(),
            'created' => $this->getCreated()->format('Y-m-d\TH:i:s'),
        ];

        return $data;
    }

    public function getUserLike()
    {
        $val = 0;

        if ($likes = $this->getLikes()->toArray()) {
            $val = $likes[0]->getVal();
        }

        return $val;
    }

    /**
     * Add contentText
     *
     * @param ArticleTexts
     *
     * @return Article
     */
    public function addContentText(ArticleTexts $text)
    {
        $text->setArticle($this);
        $this->contentTexts[] = $text;

        return $this;
    }

    /**
     * Remove contentText
     *
     * @param ArticleTexts
     *
     * @return Article
     */
    public function removeContentText(ArticleTexts $text)
    {
        $this->contentTexts->removeElement($text);

        return $this;
    }

    /**
     * Add contentFile
     *
     * @param ArticleFiles
     *
     * @return Article
     */
    public function addContentFile(ArticleFiles $file)
    {
        $file->setArticle($this);
        $this->contentFiles[] = $file;

        return $this;
    }

    /**
     * Remove contentFile
     *
     * @param ArticleFiles
     *
     * @return Article
     */
    public function removeContentFile(ArticleFiles $file)
    {
        $this->contentFiles->removeElement($file);

        return $this;
    }
}

