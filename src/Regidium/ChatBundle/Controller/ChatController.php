<?php

namespace Regidium\ChatBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Form\FormTypeInterface;

use FOS\RestBundle\Controller\Annotations;
use FOS\RestBundle\Util\Codes;
use FOS\RestBundle\Request\ParamFetcherInterface;

use Regidium\CommonBundle\Controller\AbstractController;

use Nelmio\ApiDocBundle\Annotation\ApiDoc;

/**
 * Chat controller
 *
 * @todo Update response for HTML format
 * @todo Вынести всю работу с моделями в handlers
 *
 * @package Regidium\UserBundle\Controller
 * @author Alexey Volkov <alexey.wild88@gmail.com>
 *
 * @Annotations\RouteResource("Chat")
 */
class ChatController extends AbstractController
{
}
