<?php

namespace Regidium\CommonBundle\Controller;

use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\View\View;

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
     * Return array
     *
     * @param mixed   $data
     * @param integer $statusCode
     * @param array   $headers
     *
     * @return View
     */
    protected function send($data = null, $statusCode = null, array $headers = array())
    {
        return $this->view($data, $statusCode, $headers);
    }

    /**
     * Return error
     *
     * @param array|string $errors     Text error
     * @param int          $statusCode Status code
     * @param array        $headers    Response headers
     *
     * @return View
    */
    protected function sendError($error, $statusCode = null, array $headers = array())
    {
        $errors = array();
        if (!is_array($error)) {
            $errors[] = $error;
        } else {
            $errors = $error;
        }

        return $this->send(['errors' => $errors], $statusCode, $headers);
    }

    /**
     * Return success
     *
     * @param mixed $data       Return data
     * @param int   $statusCode Status code
     * @param array $headers    Response headers
     *
     * @return View
     */
    protected function sendSuccess($data = true, $statusCode = null, array $headers = array())
    {
        return $this->send(['success' => $data], $statusCode, $headers);
    }

    /**
     * Return array
     *
     * @param mixed $data       Return data
     * @param int   $statusCode Status code
     * @param array $headers    Response headers
     *
     * @return View
     */
    protected function sendArray($data = true, $statusCode = null, array $headers = array())
    {
        if (!is_array($data)) {
            $data[] = $data;
        }

        return $this->send($data, $statusCode, $headers);
    }
}
