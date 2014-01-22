<?php

namespace Regidium\ClientBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Form\FormTypeInterface;

use FOS\RestBundle\Controller\Annotations;
use FOS\RestBundle\Util\Codes;
use FOS\RestBundle\Request\ParamFetcherInterface;

use Regidium\CommonBundle\Controller\AbstractController;

use Regidium\ClientBundle\Form\ClientForm;
use Regidium\ClientBundle\Document\Client;

use Nelmio\ApiDocBundle\Annotation\ApiDoc;

/**
 * Client controller
 *
 * @todo Update response for HTML format
 *
 * @package Regidium\ClientBundle\Controller
 * @author Alexey Volkov <alexey.wild88@gmail.com>
 *
 * @Annotations\RouteResource("Client")
 */
class ClientController extends AbstractController
{
    /**
     * List all clients.
     *
     * @ApiDoc(
     *   resource = false,
     *   statusCodes = {
     *     200 = "Returned when successful"
     *   }
     * )
     *
     * @Annotations\QueryParam(name="offset", requirements="\d+", nullable=true, description="Offset from which to start listing clients.")
     * @Annotations\QueryParam(name="limit", requirements="\d+", default="5", description="How many clients to return.")
     *
     * @Annotations\View(
     *   templateVar="clients",
     *   statusCode=200
     * )
     *
     * @param Request               $request      the request object
     * @param ParamFetcherInterface $paramFetcher param fetcher service
     *
     * @return array
     */
    public function cgetAction(Request $request, ParamFetcherInterface $paramFetcher)
    {
        $return = $this->get('regidium.client.handler')->all();

        return $this->view($return);
    }

    /**
     * Get single client.
     *
     * @ApiDoc(
     *   resource = false,
     *   description = "Gets a clients for a given uid",
     *   output = "Regidium\ClientBundle\Document\Client",
     *   statusCodes = {
     *     200 = "Returned when successful",
     *     404 = "Returned when the client is not found"
     *   }
     * )
     *
     *
     * @Annotations\View(templateVar="client")
     *
     * @param int     $uid      the client uid
     *
     * @return array
     *
     * @throws NotFoundHttpException when client not exist
     */
    public function getAction($uid)
    {
        $client = $this->getOr404(['uid' => $uid]);

        return $client;
    }

    /**
     * Presents the form to use to create a new client.
     *
     * @ApiDoc(
     *   resource = false,
     *   statusCodes = {
     *     200 = "Returned when successful"
     *   }
     * )
     *
     * @Annotations\View(
     *  templateVar = "form"
     * )
     *
     * @return FormTypeInterface
     */
    public function newAction()
    {
        return $this->createForm(new ClientForm());
    }

    /**
     * Create a client from the submitted data.
     *
     * @ApiDoc(
     *   resource = false,
     *   description = "Creates a new client from the submitted data.",
     *   input = "Regidium\ClientBundle\Form\ClientForm",
     *   statusCodes = {
     *     200 = "Returned when successful",
     *     400 = "Returned when the form has errors"
     *   }
     * )
     *
     * @Annotations\View(
     *  template = "RegidiumClientBundle:Client:newClient.html.twig",
     *  statusCode = Codes::HTTP_BAD_REQUEST,
     *  templateVar = "form"
     * )
     *
     * @param Request $request the request object
     *
     * @return FormTypeInterface|View
     */
    public function postAction(Request $request)
    {
        $result = $this->get('regidium.client.handler')->post(
            $request->request->all()
        );

        if (!$result instanceof Client) {
            return $this->view(['errors' => $result]);
        }

        $routeOptions = array(
            'uid' => $result->getUid(),
            '_format' => $request->get('_format')
        );

        return $this->routeRedirectView('api_1_get_client', $routeOptions, Codes::HTTP_CREATED);

    }

    /**
     * Update existing client from the submitted data or create a new client at a specific location.
     *
     * @ApiDoc(
     *   resource = false,
     *   input = "Regidium\ClientBundle\Form\ClientForm",
     *   statusCodes = {
     *     201 = "Returned when the client is created",
     *     204 = "Returned when successful",
     *     400 = "Returned when the form has errors"
     *   }
     * )
     *
     * @Annotations\View(
     *  template = "RegidiumClientBundle:Client:editClient.html.twig",
     *  templateVar = "form"
     * )
     *
     *
     * @param Request $request the request object
     * @param int     $uid      the client uid
     *
     * @return FormTypeInterface|View
     *
     * @throws NotFoundHttpException when client not exist
     */
    public function putAction(Request $request, $uid)
    {
        try {
            if (!($client = $this->get('regidium.client.handler')->one(['uid' => $uid]))) {
                $statusCode = Codes::HTTP_CREATED;
                $post = [
                    'email' => $request->request->get('email', null),
                    'fullname' => $request->request->get('fullname', null),
                    'password' => $request->request->get('password', null),
                    'status' => $request->request->get('status', 2)
                ];
                $client = $this->get('regidium.client.handler')->post(
                    $post
                );
            } else {
                $statusCode = Codes::HTTP_OK;
                $password = $request->request->get('password', null);
                if ($client->getPassword() != null && $password == null) {
                    $password = $client->getPassword();
                }
                $put = [
                    'email' => $request->request->get('email', null),
                    'fullname' => $request->request->get('fullname', null),
                    'password' => $password,
                    'status' => $request->request->get('status', 2)
                ];
                $client = $this->get('regidium.client.handler')->put(
                    $client,
                    $put
                );
            }

            if (!$client instanceof Client) {
                return  $this->view(['errors' => $client]);
            }

            $routeOptions = array(
                'uid' => $client->getUid(),
                '_format' => $request->get('_format')
            );

            return $this->routeRedirectView('api_1_get_client', $routeOptions, $statusCode);

        } catch (InvalidFormException $exception) {

            return $exception->getForm();
        }
    }

    /**
     * Update existing client from the submitted data or create a new client at a specific location.
     *
     *
     * @ApiDoc(
     *   resource = false,
     *   input = "Regidium\ClientBundle\Form\Type\ClientType",
     *   statusCodes = {
     *     204 = "Returned when successful",
     *     400 = "Returned when the form has errors"
     *   }
     * )
     *
     * @Annotations\View(
     *  template = "RegidiumClientBundle:Client:editClient.html.twig",
     *  templateVar = "form"
     * )
     *
     * @todo Remove
     *
     * @deprecated Will be removed
     *
     * @param Request $request the request object
     * @param int  $uid  client uid
     *
     * @return FormTypeInterface|View
     *
     * @throws NotFoundHttpException when client not exist
     */
    public function patchAction(Request $request, $uid)
    {
        try {
            $client = $this->get('regidium.client.handler')->patch(
                $this->getOr404(['uid' => $uid]),
                $request->request->all()
            );

            $routeOptions = array(
                'uid' => $client->getUid(),
                '_format' => $request->get('_format')
            );

            return $this->routeRedirectView('api_1_get_client', $routeOptions, Codes::HTTP_NO_CONTENT);

        } catch (InvalidFormException $exception) {

            return $exception->getForm();
        }
    }

    /**
     * Remove existing client.
     *
     *
     * @ApiDoc(
     *   resource = false,
     *   statusCodes = {
     *     200 = "Always returned",
     *   }
     * )
     *
     * @param int   $uid  client uid
     *
     * @return View
     *
     */
    public function deleteAction($uid)
    {
        $result = $this->get('regidium.client.handler')->delete([ 'uid' => $uid ]);

        if ($result === 404) {
            return $this->view(['errors' => ['Client not found!']]);
        } elseif ($result === 500) {
            return $this->view(['errors' => ['Server error!']]);
        }

        return $this->view(['success' => true]);
    }

    /**
     * Fetch a client or throw an 404 Exception.
     *
     * @param array $criteria
     *
     * @return Client
     *
     * @throws NotFoundHttpException
     */
    protected function getOr404(array $criteria)
    {
        if (!($client = $this->get('regidium.client.handler')->one($criteria))) {
            throw new NotFoundHttpException(sprintf('The resource was not found.'));
        }

        return $client;
    }
}
