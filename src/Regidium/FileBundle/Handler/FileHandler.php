<?php

namespace Regidium\FileBundle\Handler;

use Regidium\CommonBundle\Handler\AbstractHandler;
use Regidium\FileBundle\Form\FileForm;
use Regidium\FileBundle\Document\File;

class FileHandler extends AbstractHandler implements FileHandlerInterface
{
    /**
     * Get one file by criteria.
     *
     * @param array $criteria
     *
     * @return File
     */
    public function one(array $criteria)
    {
        return $this->repository->findOneBy($criteria);
    }

    /**
     * Get files by criteria.
     *
     * @param array $criteria
     *
     * @return File
     */
    public function get(array $criteria)
    {
        return $this->repository->findBy($criteria);
    }

    /**
     * Upload new file.
     *
     * @param array $parameters
     *
     * @return File
     */
    public function post(array $parameters)
    {
        $file = $this->createFile();

        return $this->processForm($file, $parameters, 'POST');
    }

    /**
     * Remove exist File
     *
     * @param string $criteria
     *
     * @return bool|int
     */
    public function delete($criteria) {
        $file = $this->one($criteria);
        if (!$file instanceof File) {
            return 404;
        }

        try {
            $this->dm->remove($file);
            $this->dm->flush();
            return 200;
        } catch (\Exception $e) {
            return 500;
        }
    }

    /**
     * Processes the form.
     *
     * @param File   $file
     * @param array  $parameters
     * @param string $method
     *
     * @return File|\Symfony\Component\Form\FormError[]
     *
     */
    private function processForm(File $file, array $parameters, $method = 'PUT')
    {
        $form = $this->formFactory->create(new FileForm(), $file, array('method' => $method));
        $form->submit($parameters, 'PATCH' !== $method);
        if ($form->isValid()) {
            $file = $form->getData();
            $this->dm->persist($file);
            $this->dm->flush($file);
            return $file;
        }

        return $form->getErrors();
    }

    private function createFile()
    {
        return new $this->entityClass();
    }
}