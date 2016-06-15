<?php

namespace AppBundle\Api\Vote;


use AppBundle\Api\BlogApi;
use Doctrine\ORM\EntityManager;
use BlogBundle\Entity\ArticleLikes;

class ArticleVote extends BlogApi
{
    function __construct($id)
    {
        $this->repositoryLikes = 'BlogBundle:ArticleLikes';
        $this->repository = 'BlogBundle:Article';
        $this->entityName = 'article';
        parent::__construct($id);
    }

    public function liking(EntityManager $em)
    {
        $new_rating = 0;
        if ($this->type) {
            $new_rating = $this->entity->getRating() + 1*$this->type;
            $this->entity->setRating($new_rating);

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

        return ['message' => $message, 'rating' => $new_rating];
    }
}