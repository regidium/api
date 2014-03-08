<?php

namespace Regidium\WidgetBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use FOS\RestBundle\Controller\Annotations;
use FOS\RestBundle\View\View;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;

use Regidium\CommonBundle\Controller\AbstractController;
use Regidium\CommonBundle\Document\Widget;
use Regidium\CommonBundle\Document\Trigger;

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
     *     200 = "Возвращает при успешном редактировании",
     *     204 = "Возвращает при успешном создании"
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

        $trigger = null;
        if ($trigger_uid) {
            $trigger = $this->get('regidium.trigger.handler')->one(['uid' => $trigger_uid]);
        }

        $data = $request->request->all();
        $data['widget_uid'] = $uid;

        if (!$trigger) {
            $trigger = $this->get('regidium.trigger.handler')->post(
                $data
            );
        } else {
            $trigger = $this->get('regidium.trigger.handler')->put(
                $trigger,
                $data
            );
        }

        if (!$trigger instanceof Trigger) {
            return $this->sendError($trigger);
        }

        return  $this->send($trigger->toArray());
    }
}
