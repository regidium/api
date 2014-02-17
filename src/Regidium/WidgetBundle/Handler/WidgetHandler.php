<?php

namespace Regidium\WidgetBundle\Handler;

use Regidium\CommonBundle\Handler\AbstractHandler;
use Regidium\WidgetBundle\Form\WidgetForm;
use Regidium\WidgetBundle\Form\WidgetSettingsForm;

use Regidium\CommonBundle\Document\Widget;

class WidgetHandler extends AbstractHandler
{
    /**
     * Get one widget by criteria.
     *
     * @param array $criteria
     *
     * @return Widget
     */
    public function one(array $criteria)
    {
        return $this->repository->findOneBy($criteria);
    }

    /**
     * Get widgets by criteria.
     *
     * @param array $criteria
     *
     * @return Widget
     */
    public function get(array $criteria)
    {
        return $this->repository->findBy($criteria);
    }

    /**
     * Get a list of widgets.
     *
     * @param int $limit  the limit of the result
     * @param int $offset starting from the offset
     *
     * @return array
     */
    public function all($limit = 5, $offset = 0)
    {
        return $this->repository->findBy([], null, $limit, $offset);
    }

    /**
     * Create a new widget.
     *
     * @param array $parameters
     *
     * @return Widget
     */
    public function post(array $parameters)
    {
        $widget = $this->createEntity();

        return $this->processForm($widget, $parameters, 'POST');
    }

    /**
     * Edit a widget.
     *
     * @param Widget $widget
     * @param array  $parameters
     *
     * @return Widget
     */
    public function put(Widget $widget, array $parameters)
    {
        return $this->processForm($widget, $parameters, 'PUT');
    }

    /**
     * Edit a widget settings.
     *
     * @param Widget $widget
     * @param array  $parameters
     *
     * @return Widget
     */
    public function putSettings(Widget $widget, array $parameters)
    {
        return $this->processSettingsForm($widget, $parameters);
    }

    /**
     * Partially update a widget.
     *
     * @param Widget $widget
     * @param array  $parameters
     *
     * @return Widget
     */
    public function patch(Widget $widget, array $parameters)
    {
        return $this->processForm($widget, $parameters, 'PATCH');
    }

    /**
     * Remove exist widget
     *
     * @param string $criteria
     *
     * @return bool|int
     */
    public function delete($criteria) {
        $widget = $this->one($criteria);
        if (!$widget instanceof Widget) {
            return 404;
        }

        try {
            $this->dm->remove($widget);
            $this->dm->flush();
            return 200;
        } catch (\Exception $e) {
            return 500;
        }
    }

    /**
     * Save edit Widget
     *
     * @param Widget  $widget
     *
     * @return Widget
     */
    public function edit(Widget $widget) {
        $this->dm->flush($widget);
        return $widget;
    }

    /**
     * Обработка формы.
     *
     * @param Widget $widget     Виджет для обработки
     * @param array  $parameters Параметры для обработки
     * @param string $method     HTTP метод
     *
     * @return string|array|Widget
     *
     */
    private function processForm(Widget $widget, array $parameters, $method = 'PUT')
    {
        $data = [
            'status' => isset($parameters['status']) ? $parameters['status'] : Widget::STATUS_DEFAULT
        ];
        $form = $this->formFactory->create(new WidgetForm(), $widget, array('method' => $method));
        $form->submit($data, 'PATCH' !== $method);
        if ($form->isValid()) {
            $widget = $form->getData();

            $this->dm->persist($widget);
            $this->dm->flush($widget);

            return $widget;
        }

        return $this->getFormErrors($form);
    }


    /**
     * Обработка формы настроек
     *
     * @param Widget $widget     Виджет для обработки
     * @param array  $parameters Параметры для обработки
     *
     * @return array|Widget
     *
     */
    private function processSettingsForm(Widget $widget, array $parameters)
    {
        $form = $this->formFactory->create(new WidgetSettingsForm());
        $form->submit($parameters);
        if ($form->isValid()) {
            $settings = $form->getData();

            $widget->setSettings($settings);

            $this->dm->persist($widget);
            $this->dm->flush($widget);

            return $widget;
        }

        return $this->getFormErrors($form);
    }
}