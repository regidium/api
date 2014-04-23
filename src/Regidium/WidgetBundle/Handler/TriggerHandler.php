<?php

namespace Regidium\WidgetBundle\Handler;

use Regidium\CommonBundle\Handler\AbstractHandler;
use Regidium\WidgetBundle\Form\TriggerForm;

use Regidium\CommonBundle\Document\Widget;
use Regidium\CommonBundle\Document\Trigger;

class TriggerHandler extends AbstractHandler
{
    /**
     * Получение одного триггера по критерию.
     *
     * @param array $criteria
     *
     * @return Trigger
     */
    public function one(array $criteria)
    {
        return $this->repository->findOneBy($criteria);
    }

    /**
     * Получить список тригеров виджета.
     *
     * @param string $widget_uid UID виджета
     *
     * @return array
     */
    public function all($widget_uid)
    {
        return $this->repository->findBy(['widget.id' => $widget_uid]);
    }

    /**
     * Создание нового триггера
     *
     * @param array $data
     *
     * @return object
     */
    public function post(array $data)
    {
        $entity = $this->createEntity();

        return $this->processForm($entity, $data, 'POST');
    }

    /**
     * Редактирование триггера.
     *
     * @param Trigger $entity
     * @param array  $parameters
     *
     * @return Trigger
     */
    public function put(Trigger $entity, array $parameters)
    {
        return $this->processForm($entity, $parameters, 'PUT');
    }

    /**
     * Удаление трггера
     *
     * @param string $criteria
     *
     * @return bool|int
     */
    public function delete($criteria) {
        $entity = $this->one($criteria);
        if (!$entity instanceof Trigger) {
            return 404;
        }

        try {
            $this->dm->remove($entity);
            $this->dm->flush($entity);
            return 200;
        } catch (\Exception $e) {
            return 500;
        }
    }

    /**
     * Сохранение триггера
     *
     * @param Trigger $entity
     *
     * @return Trigger
     */
    public function edit(Trigger $entity) {
        $this->dm->persist($entity);
        $this->dm->flush($entity);
        return $entity;
    }

    /**
     * Обработка формы.
     *
     * @param Trigger $trigger Триггер для обработки
     * @param array   $data    Данные для обработки
     * @param string  $method  HTTP метод
     *
     * @return string|array|Trigger
     *
     */
    public function processForm(Trigger $trigger, array $data, $method = 'PUT')
    {
        $form = $this->formFactory->create(new TriggerForm(), $trigger, ['method' => $method]);
        $form->submit($data, 'PATCH' !== $method);
        if ($form->isValid()) {
            $trigger = $form->getData();

            $widget_uid = $form->get('widget_uid')->getData();
            $widget = $this->dm->getRepository('Regidium\CommonBundle\Document\Widget')->findOneBy(['uid' => $widget_uid]);
            $trigger->setWidget($widget);

            $this->dm->persist($trigger);
            $this->dm->flush($trigger);

            return $trigger;
        }

        return $this->getFormErrors($form);
    }
}