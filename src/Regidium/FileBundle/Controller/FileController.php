<?php

namespace Regidium\FileBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

use FOS\RestBundle\Controller\Annotations;

use Regidium\CommonBundle\Controller\AbstractController;

use Nelmio\ApiDocBundle\Annotation\ApiDoc;

/**
 * File controller
 *
 * @todo Update response for HTML format
 *
 * @package Regidium\FileBundle\Controller
 * @author Alexey Volkov <alexey.wild88@gmail.com>
 *
 * @Annotations\RouteResource("File")
 */
class FileController extends AbstractController
{
    /**
     * Get single file.
     *
     * @ApiDoc(
     *   resource = false,
     *   description = "Get file for a given uid",
     *   output = "Regidium\FileBundle\Document\File",
     *   statusCodes = {
     *     200 = "Returned when successful",
     *     404 = "Returned when file is not found"
     *   }
     * )
     *
     *
     * @param int     $uid      file uid
     *
     * @return array
     *
     * @throws NotFoundHttpException when file not exist
     */
    public function getAction($uid)
    {
        return $this->getOr404(['uid' => $uid]);
    }


    /**
     * Upload file from the submitted data.
     *
     * @ApiDoc(
     *   resource = true,
     *   description = "Upload a new file from the submitted data.",
     *   statusCodes = {
     *     200 = "Returned when successful",
     *     400 = "Returned when the form has errors"
     *   }
     * )
     *
     * @param Request $request the request object
     *
     * @return View
     */
    public function postAction(Request $request)
    {
        $result = $this->get('regidium.file.handler')->post(
            $request->files
        );

        if (!$result instanceof File) {
            return $this->view(['errors' => $result]);
        }

        $routeOptions = array(
            'uid' => $result->getUid(),
            '_format' => $request->get('_format')
        );

        return $this->routeRedirectView('api_1_get_file', $routeOptions, Codes::HTTP_CREATED);

    }

    /**
     * Remove existing file.
     *
     *
     * @ApiDoc(
     *   resource = false,
     *   statusCodes = {
     *     200 = "Always returned",
     *   }
     * )
     *
     * @param int   $uid  File uid
     *
     * @return View
     *
     */
    public function deleteAction($uid)
    {
        $result = $this->get('regidium.file.handler')->delete([ 'uid' => $uid ]);

        if ($result === 404) {
            return $this->view(['errors' => ['File not found!']]);
        } elseif ($result === 500) {
            return $this->view(['errors' => ['Server error!']]);
        }

        return $this->view(['success' => true]);
    }

    /**
     * Fetch a file or throw an 404 Exception.
     *
     * @param array $criteria
     *
     * @return File
     *
     * @throws NotFoundHttpException
     */
    protected function getOr404(array $criteria)
    {
        if (!($file = $this->get('regidium.file.handler')->one($criteria))) {
            throw new NotFoundHttpException(sprintf('The file was not found.'));
        }

        return $file;
    }
}
