<?php

namespace BlogBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * ArticleFiles
 *
 * @ORM\Table(name="article_files")
 * @ORM\Entity(repositoryClass="BlogBundle\Repository\ArticleFilesRepository")
 * @ORM\HasLifecycleCallbacks
 */
class ArticleFiles
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
     * @var string
     *
     * @ORM\Column(name="path", type="string", length=255)
     */
    private $path;

    /**
     * @var int
     *
     * @ORM\Column(name="sort", type="integer")
     */
    private $sort;

    /**
     * @ORM\ManyToOne(targetEntity="BlogBundle\Entity\Article", inversedBy="contentFiles")
     * @ORM\JoinColumn(name="article_id", referencedColumnName="id")
     */
    private $article;


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
     * @return ArticleFiles
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
     * Set path
     *
     * @param string $path
     *
     * @return ArticleFiles
     */
    public function setPath($path)
    {
        $this->path = $path;

        return $this;
    }

    /**
     * Get filename
     *
     * @return string
     */
    public function getPath()
    {
        return $this->path;
    }

    public function getAbsolutePath()
    {
        return null === $this->path
            ? null
            : $this->getUploadRootDir().$this->path;
    }

    public function getWebPath()
    {
        return null === $this->path
            ? null
            : $this->getUploadDir().$this->path;
    }

    protected function getUploadRootDir()
    {
        // the absolute directory path where uploaded
        // documents should be saved
        return __DIR__.'/../../../web'.$this->getUploadDir();
    }

    protected function getUploadDir()
    {
        // get rid of the __DIR__ so it doesn't screw up
        // when displaying uploaded doc/image in the view.
        return '/article_files/';
    }

    /**
     * @ORM\PrePersist()
     * @ORM\PreUpdate()
     */
    public function preUpload()
    {
        if (null !== $this->file) {
            $id = $this->getArticle()->getId();

            $path = ceil($id / 1000)."/";
            if (!is_dir($this->getUploadRootDir().$path)) {
                mkdir($this->getUploadRootDir().$path);
            }
            $path .= $id.'/';
            if (!is_dir($this->getUploadRootDir().$path)) {
                mkdir($this->getUploadRootDir().$path);
            }

            $filename = uniqid(rand(100, 999)."f").".".$this->getFile()->guessExtension();

            $this->setPath($path.$filename);
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

        $dir = explode("/", $this->getPath());
        $filename = array_pop($dir);
        $this->getFile()->move($this->getUploadRootDir().implode("/", $dir), $filename );

        unset($this->file);
    }

    /**
     * Set sort
     *
     * @param integer $sort
     *
     * @return ArticleFiles
     */
    public function setSort($sort)
    {
        $this->sort = $sort;

        return $this;
    }

    /**
     * Get sort
     *
     * @return int
     */
    public function getSort()
    {
        return $this->sort;
    }

    /**
     * @Assert\File(
     *     maxSize="12000000",
     *     mimeTypes = {"image/jpeg", "image/png"},
     *     mimeTypesMessage = "Please upload a valid image file"
     * )
     */
    private $file;

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
     * Get article
     *
     * @return Article
     */
    public function getArticle()
    {
        return $this->article;
    }

    /**
     * Set article
     *
     * @param BlogBundle\Entity\Article
     *
     * @return ArticleLikes
     */
    public function setArticle($article = null)
    {
        $this->article = $article;

        return $this;
    }
}

