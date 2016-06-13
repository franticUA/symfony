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
        if ($this->type) {
            $this->entity->setRating($this->entity->getRating() + 1*$this->type);

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

        return $message;
    }
}