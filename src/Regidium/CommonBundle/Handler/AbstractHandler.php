<?php

namespace Regidium\CommonBundle\Handler;

use Symfony\Component\Form\FormFactoryInterface;
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

    protected function getFormErrors($form)
    {
        $return = [];
        $errors = $form->getErrors();
        foreach ($errors as $error) {
            $return[] = $error->getCause();
        }

        return $return;
    }
}