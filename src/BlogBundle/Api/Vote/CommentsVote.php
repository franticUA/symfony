<?php

namespace BlogBundle\Api\Vote;


use BlogBundle\Api\VoteApi;
use Doctrine\ORM\EntityManager;
use BlogBundle\Entity\CommentsLikes;

class CommentsVote extends VoteApi
{
    function __construct($id, $type)
    {
        $this->repositoryLikes = 'BlogBundle:CommentsLikes';
        $this->repository = 'BlogBundle:Comments';
        $this->entity = 'comment';
        parent::__construct($id, $type);
    }

    public function liking(EntityManager $em)
    {
        if ($this->type) {
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