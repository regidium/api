<?php

namespace Regidium\WidgetBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use FOS\RestBundle\Controller\Annotations;
use FOS\RestBundle\View\View;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;

use Regidium\CommonBundle\Controller\AbstractController;
use Regidium\CommonBundle\Document\Widget;
use Regidium\CommonBundle\Document\Trigger;
use Regidium\WidgetBundle\Form\TriggerForm;

/**
 * Widget Trigger controller
 *
 * @package Regidium\WidgetBundle\Controller
 * @author Alexey Volkov <alexey.wild88@gmail.com>
 *
 * @Annotations\RouteResource("Trigger")
 */
class WidgetTriggerController extends AbstractController
{
    /**
     * Получаение списка триггеров виджета.
     *
     * @ApiDoc(
     *   resource = false,
     *   description = "Получаение списка триггеров виджета.",
     *   statusCodes = {
     *     200 = "Возвращает при успешном выполнении"
     *   }
     * )
     *
     * @param Request $uid UID виджета
     *
     * @Annotations\QueryParam(name="offset", requirements="\d+", nullable=true, description="Смещение списка.")
     * @Annotations\QueryParam(name="limit", requirements="\d+", default="5", description="Кочиество элементов в списке.")
     *
     * @return View
     */
    public function cgetAction($uid)
    {
        $widget = $this->get('regidium.widget.handler')->one(['uid' => $uid]);

        if (!$widget instanceof Widget) {
            return $this->sendError('Widget not found!');
        }

        $return = [];
        $triggers = $widget->getTriggers();
        foreach($triggers as $trigger) {
            $return[] = $trigger->toArray();
        }

        return $this->sendArray($return);
    }

    /**
     * Создаем или редактируем триггер.
     *
     * @ApiDoc(
     *   resource = false,
     *   description = "Создаем или редактируем триггер.",
     *   statusCodes = {
     *     200 = "Возвращает при успешном выолнении",
     *   }
     * )
     *
     * @param Request $request     Request объект
     * @param string  $uid         UID виджета
     * @param string  $trigger_uid UID триггера
     *
     * @return View
     */
    public function putAction(Request $request, $uid, $trigger_uid = null)
    {
        $widget = $this->get('regidium.widget.handler')->one(['uid' => $uid]);
        if (!$widget instanceof Widget) {
            return $this->sendError('Widget not found!');
        }

        $data = $request->request->get('trigger');

        $trigger = null;
        if (!$trigger_uid == 'new') {
            $repository = $this->get('doctrine.odm.mongodb.document_manager')->getRepository('Regidium\CommonBundle\Document\Trigger');
            //$trigger = $this->get('regidium.trigger.handler')->one(['uid' => $trigger_uid]);
            $trigger = $repository->findOneBy(['uid' => $trigger_uid]);
        } else {
            unset($data['uid']);
        }

        $data['widget_uid'] = $uid;

        if (!$trigger) {
//            $trigger = $this->get('regidium.trigger.handler')->post(
//                $data
//            );
            $trigger = $this->processForm(new Trigger(), $data, 'POST');
        } else {
//            $trigger = $this->get('regidium.trigger.handler')->put(
//                $trigger,
//                $data
//            );
            $trigger = $this->processForm($trigger, $data, 'PUT');
        }

        if (!$trigger instanceof Trigger) {
            return $this->sendError($trigger);
        }

        return  $this->send($trigger->toArray());
    }

    /**
     * Удаление существующего триггера.
     *
     * @ApiDoc(
     *   resource = false,
     *   description = "Удаление существующего триггера.",
     *   statusCodes = {
     *     200 = "Возвращает при успешном выполнении"
     *   }
     * )
     * @param string $uid         UID виджета
     * @param string $trigger_uid UID триггера
     *
     * @return View
     *
     */
    public function deleteAction($uid, $trigger_uid)
    {
        $widget = $this->get('regidium.widget.handler')->one(['uid' => $uid]);
        if (!$widget instanceof Widget) {
            return $this->sendError('Widget not found!');
        }

        $result = $this->get('regidium.trigger.handler')->delete([ 'uid' => $trigger_uid ]);

        if ($result === 404) {
            return $this->sendError('Trigger not found!');
        } elseif ($result === 500) {
            return $this->sendError('Server error!');
        }

        return $this->sendSuccess();
    }


    /**
     * Обработка формы.
     *
     * @param Trigger $trigger Триггер для обработки
     * @param array   $data    Данные для обработки
     * @param string  $method  HTTP метод
     *
     * @return string|array|Widget
     *
     */
    public function processForm(Trigger $trigger, array $data, $method = 'PUT')
    {
        $form = $this->get('form.factory')->create(new TriggerForm(), $trigger, ['method' => $method]);
        $form->submit($data, 'PATCH' !== $method);
        if ($form->isValid()) {
            $trigger = $form->getData();

            $widget_uid = $form->get('widget_uid')->getData();
            $widget = $this->get('doctrine.odm.mongodb.document_manager')->getRepository('Regidium\CommonBundle\Document\Widget')->findOneBy(['uid' => $widget_uid]);
            $trigger->setWidget($widget);

            $this->get('doctrine.odm.mongodb.document_manager')->persist($trigger);
            $this->get('doctrine.odm.mongodb.document_manager')->flush($trigger);

            return $trigger;
        }

        $return = [];
        $errors = $form->getErrors();
        foreach ($errors as $error) {
            $return[] = $error->getMessage();
        }

        return $return;
    }
}
