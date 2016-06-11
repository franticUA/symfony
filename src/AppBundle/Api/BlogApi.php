<?php

namespace AppBundle\Api;


use AppBundle\Controller\ApiController;
use Doctrine\ORM\EntityManager;
use Symfony\Component\Config\Definition\Exception\Exception;
use Symfony\Bridge\Doctrine;

abstract class BlogApi extends ApiController
{
    protected $repositoryLikes;
    protected $repository;
    protected $id;
    protected $like;
    protected $type;
    protected $entityName;
    protected $entity;
    protected $userId;
    public static $types = ['like' => 1, 'dislike' => -1, 'unlike' => 0];

    function __construct($id)
    {
        $this->id = $id;
    }

    function setType($type)
    {
        $this->type = $type;
    }

    function setUserId($userId)
    {
        $this->userId = $userId;
    }

    function setEntity(EntityManager $em)
    {
        $this->entity = $em->createQueryBuilder()
            ->select('e')
            ->addSelect('l')
            ->from($this->repository,  'e')
            ->leftJoin('e.likes', 'l', 'WITH', 'l.userId = :userId')
            ->where('e.id=:id')
            ->setParameters(['id' => $this->id, 'userId' => $this->userId])
            ->getQuery()
            ->getOneOrNullResult();
    }

    function getEntity()
    {
        return $this->entity;
    }

    function votedCheck(EntityManager $em)
    {
        $deleted_value = 0;

        if ($like = $em->getRepository($this->repositoryLikes)
            ->findOneBy([$this->entity.'Id' => $this->id, 'userId' => $this->userId])
        ) {
            if ($this->type != 0 && ($this->type * $like->getVal()) > 0 ) {
                //запись в базе есть, если её не надо удалять, и знак совпадает с имеющимся,
                //значит второй раз жмякнули по кнопке
                throw new Exception('ALREADY_VOTED');
            } else {
                //либо надо знак поменять, либо удалить
                $deleted_value = $like->getVal();
                $em->remove($like);
                $em->flush();
            }
        } elseif ($this->type == 0) {
            throw new Exception('NOT_EXIST');
        }

        if ($deleted_value) {
            $this->entity->setRating($this->entity->getRating() - $deleted_value);
            $em->persist($this->entity);
            $em->flush();
        }
    }

    abstract function liking(EntityManager $em);
}