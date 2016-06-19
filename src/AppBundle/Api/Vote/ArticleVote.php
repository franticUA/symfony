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

    public function liking()
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
            $this->em->persist($articleLike);
            $this->em->flush();
            $message = 'VOTED';
            
            $this->updateAuthorRating(1*$this->type);
        } else {
            $message = 'DELETED';
        }

        return ['message' => $message, 'rating' => $new_rating];
    }
}