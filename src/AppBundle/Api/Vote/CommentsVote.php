<?php

namespace AppBundle\Api\Vote;


use AppBundle\Api\BlogApi;
use Doctrine\ORM\EntityManager;
use BlogBundle\Entity\CommentsLikes;

class CommentsVote extends BlogApi
{
    function __construct($id)
    {
        $this->repositoryLikes = 'BlogBundle:CommentsLikes';
        $this->repository = 'BlogBundle:Comments';
        $this->entityName = 'comment';
        parent::__construct($id);
    }

    public function liking(EntityManager $em)
    {
        $new_rating = 0;
        if ($this->type) {
            $new_rating = $this->entity->getRating() + 1*$this->type;
            $this->entity->setRating($new_rating );

            $entityLike = new CommentsLikes();
            $entityLike->setUserId($this->userId);
            $entityLike->setCommentId($this->id);
            $entityLike->setComment($this->entity);
            $entityLike->setVal(1*$this->type);
            $em->persist($entityLike);
            $em->flush();
            $message = 'VOTED';
        } else {
            $message = 'DELETED';
        }

        return ['message' => $message, 'rating' => $new_rating];
    }
}