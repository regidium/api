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
 */
abstract class AbstractController extends FOSRestController
{
    /**
     * Return array
     *
     * @param mixed   $data
     * @param integer $status_code
     * @param array   $headers
     *
     * @return View
     */
    protected function send($data = null, $status_code = null, array $headers = [])
    {
        return $this->view($data, $status_code, $headers);
    }

    /**
     * Return error
     *
     * @param array|string $error       Text error
     * @param int          $status_code Status code
     * @param array        $headers     Response headers
     *
     * @return View
    */
    protected function sendError($error, $status_code = null, array $headers = [])
    {
        $errors = [];
        if (!is_array($error)) {
            $errors[] = $error;
        } else {
            $errors = $error;
        }

        return $this->send(['errors' => $errors], $status_code, $headers);
    }

    /**
     * Return success
     *
     * @param mixed $data        Return data
     * @param int   $status_code Status code
     * @param array $headers     Response headers
     *
     * @return View
     */
    protected function sendSuccess($data = true, $status_code = null, array $headers = [])
    {
        return $this->send(['success' => $data], $status_code, $headers);
    }

    /**
     * Return array
     *
     * @param mixed $data        Return data
     * @param int   $status_code Status code
     * @param array $headers     Response headers
     *
     * @return View
     */
    protected function sendArray($data = true, $status_code = null, array $headers = [])
    {
        if (!is_array($data)) {
            $return[] = $data;
        } else {
            $return = $data;
        }

        return $this->send($return, $status_code, $headers);
    }
}
