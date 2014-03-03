<?php

namespace Regidium\WidgetBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use FOS\RestBundle\Controller\Annotations;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;

use Regidium\CommonBundle\Controller\AbstractController;
use Regidium\CommonBundle\Document\Widget;

/**
 * Widget setting controller
 *
 * @todo Update response for HTML format
 *
 * @package Regidium\WidgetBundle\Controller
 * @author Alexey Volkov <alexey.wild88@gmail.com>
 *
 * @Annotations\RouteResource("Settings")
 */
class WidgetSettingsController extends AbstractController
{
    /**
     * Change widget settings.
     *
     * @ApiDoc(
     *   resource = false,
     *   statusCodes = {
     *     204 = "Returned when successful",
     *     400 = "Returned when has errors"
     *   }
     * )
     *
     * @param Request $request
     * @param string  $uid Widget UID
     *
     * @return View
     */
    public function putAction(Request $request, $uid)
    {
        $widget = $this->get('regidium.widget.handler')->one(['uid' => $uid]);
        if (!$widget instanceof Widget) {
            return $this->sendError('Widget not found!');
        }

        $settings = $widget->getSettings();
        $new_settings = $request->request->all();

        foreach($new_settings as $key => $new_setting) {
            $settings[$key] = $new_setting;
        }

        $this->get('regidium.widget.handler')->putSettings($widget, $settings);

        $return = [
            'uid' => $widget->getUid(),
            'settings' => $widget->getSettings()
        ];

        return $this->view($return);
    }
}
