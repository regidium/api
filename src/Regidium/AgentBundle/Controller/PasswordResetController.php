<?php
/**
 * @author Russell Kvashnin <russell.kvashnin@gmail.com>
 */

namespace Regidium\AgentBundle\Controller;

use Regidium\CommonBundle\Controller\AbstractController;
use FOS\RestBundle\Controller\Annotations;
use FOS\RestBundle\View\View;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use Symfony\Component\HttpFoundation\Request;

/**
 * Agent controller
 *
 * @todo Security
 *
 * @package Regidium\AgentBundle\Controller
 * @author Alexey Volkov <alexey.wild88@gmail.com>
 *
 * @Annotations\RouteResource("PasswordReset")
 */
class PasswordResetController extends AbstractController
{
    /**
     * Сброс пароля агента
     *
     * @ApiDoc(
     *   resource = true,
     *   description = "Сброс пароля агента",
     *   output = "Regidium\CommonBundle\Document\ResetPasswordRequest",
     *   statusCodes = {
     *     200 = "Возвращает при успешном выполнении"
     *   }
     * )
     *
     * @param Request $request
     *
     * @return View
     */
    public function postAction(Request $request)
    {
        $secretCode = $request->request->get('token');
        $password = $request->request->get('password');

        return $this->sendArray(
            $this->get('regidium.reset_password.handler')->renew($secretCode, $password)
        );
    }

} 