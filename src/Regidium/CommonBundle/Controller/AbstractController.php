<?php

namespace Regidium\CommonBundle\Controller;

use FOS\RestBundle\Controller\FOSRestController;

use Nelmio\ApiDocBundle\Annotation\ApiDoc;

/**
 * Abstract controller
 *
 * @package Regidium\CommonBundle\Controller
 * @author Alexey Volkov <alexey.wild88@gmail.com>
 *
 *
 */
abstract class AbstractController extends FOSRestController
{
    /**
     * HTTP OPTIONS method.
     *
     * @ApiDoc(
     *   resource = false,
     *   statusCodes = {
     *     200 = "Always returned"
     *   }
     * )
     *
     * @return bool
     */
    public function optionsAction()
    {
        return true;
    }
}
