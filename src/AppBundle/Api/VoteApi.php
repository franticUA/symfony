<?php

namespace AppBundle\Api;


use AppBundle\Controller\ApiController;
use Doctrine\ORM\EntityManager;
use Symfony\Component\Config\Definition\Exception\Exception;
use Symfony\Bridge\Doctrine;

abstract class VoteApi extends ApiController
{
    protected $repositoryLikes;
    protected $repository;
    protected $id;
    protected $like;
    protected $type;
    protected $entityName;
    protected $entity;
    protected $userId;

    function __construct($id, $type)
    {
        $this->id = $id;
        $this->type = $type;
    }

    function votedCheck($userId, EntityManager $em)
    {
        $back_val = 0;
        $this->userId = $userId;
        if ($like = $em->getRepository($this->repositoryLikes)
            ->findOneBy([$this->entity.'Id' => $this->id, 'userId' => $this->userId])
        ) {
            if ($this->type != 0 && ($this->type * $like->getVal()) > 0 ) {
                //запись в базе есть, если её не надо удалять, и знак совпадает с имеющимся,
                //значит второй раз жмякнули по кнопке
                throw new Exception('ALREADY_VOTED');
            } else {
                //либо надо знак поменять, либо удалить
                $back_val = $like->getVal();
                $em->remove($like);
                $em->flush();
            }
        } elseif ($this->type == 0) {
            throw new Exception('NOT_EXIST');
        }

        $this->entity = $em->createQueryBuilder()
            ->select('e')
            ->from($this->repository,  'e')
            ->where('e.id=:id')
            ->setParameter('id', $this->id)
            ->getQuery()
            ->getOneOrNullResult();
        if ($back_val) {
            $this->entity->setRating($this->entity->getRating() - $back_val);
            $em->persist($this->entity);
            $em->flush();
        }
    }

    abstract function liking(EntityManager $em);
}