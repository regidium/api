<?php

namespace Regidium\CoreBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Controller\Annotations;
use FOS\RestBundle\Request\ParamFetcherInterface;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;


/**
 * Core controller
 *
 * @package Regidium\CoreBundle\Controller
 * @author Alexey Volkov <alexey.wild88@gmail.com>
 */
class CoreController extends FOSRestController
{
    /**
     * @ApiDoc(
     *  resource=false,
     *  description="This index GET method",
     * )
     *
     * @Annotations\QueryParam(name="name", requirements="\d+", nullable=true, description="Test name.")
     */
    public function getAction(Request $request, ParamFetcherInterface $paramFetcher)
    {
        $name = $paramFetcher->get('name');
        return "Hello {$name}";
    }
}
