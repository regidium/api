<?php

namespace Regidium\MailBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use FOS\RestBundle\Controller\Annotations;
use FOS\RestBundle\View\View;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;

use Regidium\CommonBundle\Controller\AbstractController;
use Regidium\CommonBundle\Document\Agent;
use Regidium\CommonBundle\Document\Mail;

/**
 * Mail controller
 *
 * @todo Security
 *
 * @package Regidium\MailBundle\Controller
 * @author Alexey Volkov <alexey.wild88@gmail.com>
 *
 * @Annotations\RouteResource("Mail")
 */
class MailController extends AbstractController
{
    /**
     * Создание нового предложения от агента.
     *
     * @ApiDoc(
     *   resource = true,
     *   description = "Создание нового предложения от агента.",
     *   statusCodes = {
     *     200 = "Возвращает при успешном выполнении"
     *   }
     * )
     *
     * @param Request $request   Request объект
     * @param Request $agent_uid Agent UID
     *
     * @return View
     */
    public function postIssueAction(Request $request, $agent_uid)
    {
        $agent = $this->get('regidium.agent.handler')->one(['uid' => $agent_uid]);

        if (!$agent instanceof Agent) {
            return $this->sendError('Agent not found!');
        }

        $data = $request->request->all();
        $data['agent_email'] = $agent->getEmail();

        $mail = $this->get('regidium.mail.handler')->post([
           'receivers' => ['alexey.wild88@gmail.com', 'robot@regidium.com'],
           'title' => 'New Issue',
           'template' => 'RegidiumMailBundle:Agent/System:new_issue.html.twig',
           'data' => $data
        ]);

        if (!$mail instanceof Mail) {
            return $this->sendError($mail);
        }

        return $this->sendSuccess();
    }
}
