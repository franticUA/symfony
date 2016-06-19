<?php

namespace AppBundle\Api;


use AppBundle\Controller\ApiController;
use Doctrine\ORM\EntityManager;
use Symfony\Component\Config\Definition\Exception\Exception;
use Symfony\Bridge\Doctrine;

abstract class BlogApi extends ApiController
{
    protected $em;
    protected $repositoryLikes;
    protected $repository;
    protected $id;
    protected $like;
    protected $type;
    protected $entityName;
    protected $entity;
    protected $author;
    protected $userId;
    protected $ratingCoefficient = 1;
    public static $types = ['like' => 1, 'dislike' => -1, 'unlike' => 0];

    function __construct($id)
    {
        $this->id = $id;
    }

    function setType($type)
    {
        $this->type = $type;
    }

    function setEm(EntityManager $em)
    {
        $this->em = $em;
    }

    function setUserId($userId)
    {
        $this->userId = $userId;
    }

    function setEntity()
    {
        if (!$entity = $this->em->createQueryBuilder()
            ->select('e')
            ->addSelect('l')
            ->from($this->repository, 'e')
            ->leftJoin('e.likes', 'l', 'WITH', 'l.userId = :userId')
            ->where('e.id=:id')
            ->setParameters(['id' => $this->id, 'userId' => $this->userId])
            ->getQuery()
            ->getOneOrNullResult()) {
            throw new Exception('OBJECT_NOT_FOUND');
        }
        $this->entity = $entity;

        $this->author = $this->em->createQueryBuilder()
            ->select('u')
            ->from('UserBundle:User', 'u')
            ->where('u.id=:id')
            ->setParameters(['id' => $entity->getUserId()])
            ->getQuery()
            ->getOneOrNullResult();
    }

    function getEntity()
    {
        return $this->entity;
    }

    function votedCheck()
    {
        $deleted_value = 0;
        
        if ($like = $this->em->getRepository($this->repositoryLikes)
            ->findOneBy([$this->entityName.'Id' => $this->id, 'userId' => $this->userId])
        ) {
            if ($this->type != 0 && ($this->type * $like->getVal()) > 0 ) {
                //запись в базе есть, если её не надо удалять, и знак совпадает с имеющимся,
                //значит второй раз жмякнули по кнопке
                throw new Exception('ALREADY_VOTED');
            } else {
                //либо надо знак поменять, либо удалить
                $deleted_value = $like->getVal();
                $this->em->remove($like);
                $this->em->flush();
            }
        } elseif ($this->type == 0) {
            throw new Exception('NOT_EXIST');
        }

        if ($deleted_value) {
            $this->entity->setRating($this->entity->getRating() - $deleted_value);
            $this->em->persist($this->entity);
            $this->em->flush();

            $this->updateAuthorRating(-$deleted_value);
        }
    }

    protected function updateAuthorRating($val)
    {
        $this->author->setRating($this->author->getRating() + $this->ratingCoefficient * $val);
        $this->em->persist($this->author);
        $this->em->flush();
    }

    abstract function liking();
}