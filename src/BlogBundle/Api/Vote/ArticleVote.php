<?php

namespace BlogBundle\Api\Vote;


use BlogBundle\Api\VoteApi;
use Doctrine\ORM\EntityManager;
use BlogBundle\Entity\ArticleLikes;

class ArticleVote extends VoteApi
{
    function __construct($id, $type)
    {
        $this->repositoryLikes = 'BlogBundle:ArticleLikes';
        $this->repository = 'BlogBundle:Article';
        $this->entity = 'article';
        parent::__construct($id, $type);
    }

    public function liking(EntityManager $em)
    {
        if ($this->type) {
            $articleLike = new ArticleLikes();
            $articleLike->setUserId($this->userId);
            $articleLike->setArticleId($this->id);
            $articleLike->setArticle($this->entity);
            $articleLike->setVal(1*$this->type);
            $em->persist($articleLike);
            $em->flush();
            $message = 'VOTED';
        } else {
            $message = 'DELETED';
        }

        return $message;
    }
}