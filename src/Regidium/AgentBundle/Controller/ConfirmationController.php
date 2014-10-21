<?php
/**
 * @author Russell Kvashnin <russell.kvashnin@gmail.com>
 */

namespace Regidium\AgentBundle\Controller;

use Regidium\CommonBundle\Controller\AbstractController;
use FOS\RestBundle\Controller\Annotations;
use FOS\RestBundle\View\View;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;

/**
 * Agent controller
 *
 * @todo Security
 *
 * @package Regidium\AgentBundle\Controller
 * @author Alexey Volkov <alexey.wild88@gmail.com>
 *
 * @Annotations\RouteResource("Confirmation")
 */
class ConfirmationController extends AbstractController
{
    /**
     * Подтверждение регистрации агента
     *
     * @ApiDoc(
     *   resource = true,
     *   description = "Подтверждение регистрации агента",
     *   output = "Regidium\CommonBundle\Document\Confirmation",
     *   statusCodes = {
     *     200 = "Возвращает при успешном выполнении"
     *   }
     * )
     *
     * @param string $secretCode секретный код
     *
     * @return View
     *
     */
    public function getAction($secretCode)
    {
        return $this->sendArray($this->get('regidium.confirmation.handler')->confirm($secretCode));
    }

} 