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
    protected $form_factory;

    /**
     * @var DocumentManager
     */
    protected $dm;

    /**
     * @var string
     */
    protected $entity_class;

    /**
     * @var DocumentRepository
     */
    protected $repository;

    public function __construct(FormFactoryInterface $form_factory, ManagerRegistry $mr, $entity_class)
    {
        $this->form_factory = $form_factory;
        $this->dm = $mr->getManager();
        $this->entity_class = $entity_class;
        $this->repository = $this->dm->getRepository($entity_class);
    }

    protected function createEntity()
    {
        return new $this->entity_class();
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