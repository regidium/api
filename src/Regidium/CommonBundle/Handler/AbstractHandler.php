<?php

namespace Regidium\CommonBundle\Handler;

use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Form\Form;
use Doctrine\Bundle\MongoDBBundle\ManagerRegistry;
use Doctrine\ODM\MongoDB\DocumentManager;
use Doctrine\ODM\MongoDB\DocumentRepository;

abstract class AbstractHandler
{
    /**
     * @var FormFactoryInterface
     */
    protected $formFactory;

    /**
     * @var DocumentManager
     */
    protected $dm;

    /**
     * @var string
     */
    protected $entityClass;

    /**
     * @var DocumentRepository
     */
    protected $repository;

    public function __construct(FormFactoryInterface $formFactory, ManagerRegistry $mr, $entityClass)
    {
        $this->formFactory = $formFactory;
        $this->dm = $mr->getManager();
        $this->entityClass = $entityClass;
        $this->repository = $this->dm->getRepository($this->entityClass);
    }

    protected function createEntity()
    {
        return new $this->entityClass();
    }

    /**
     * Получение одного документа по условию.
     *
     * @param array $criteria
     *
     * @return Object
     */
    public function one(array $criteria)
    {
        return $this->repository->findOneBy($criteria);
    }

    /**
     * Получение документов по условию.
     *
     * @param array $criteria
     *
     * @return Object[]
     */
    public function get(array $criteria)
    {
        return $this->repository->findBy($criteria);
    }

    /**
     * Сохранение документа.
     *
     * @param mixed $entity
     *
     * @return mixed
     */
    public function save($entity)
    {
        $this->dm->persist($entity);
        $this->dm->flush($entity);

        return $entity;
    }

    /**
     * * Получение списка всех документов.
     *
     * @param int $limit  количество результатов
     * @param int $offset начальная позиция списка
     *
     * @return Object[]
     */
    public function all($limit = 5, $offset = 0)
    {
        return $this->repository->findBy([], null, $limit, $offset);
    }

    /**
     * @param Form $form
     *
     * @return array
    */
    protected function getFormErrors(Form $form)
    {
        $return = [];
        $errors = $form->getErrors();

        foreach ($errors as $error) {
            $return[] = $error->getMessage();
        }

        return $return;
    }
}