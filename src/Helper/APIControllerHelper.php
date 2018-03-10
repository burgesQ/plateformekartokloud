<?php

namespace App\Helper;

use JMS\Serializer\SerializationContext;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use FOS\RestBundle\Controller\FOSRestController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\FormInterface;

class APIControllerHelper extends FOSRestController
{
    /**
     * @param  Request       $request
     * @param  FormInterface $form
     */
    protected function processForm(Request $request, FormInterface $form)
    {
        $data = json_decode($request->getContent(), true);

        if ($data === null)
            throw new BadRequestHttpException();

        $clearMissing = $request->getMethod() != 'PATCH';

        $form->submit($data, $clearMissing);
    }

    /**
     * @param string|array    $data
     * @param int $statusCode
     *
     * @return Response
     */
    protected function createApiResponse($data, $statusCode = Response::HTTP_OK)
    {
        if ("string" == gettype($data))
            $data = [ "message" => $data ];

        $json = $this->serialize($data);

        return new Response($json, $statusCode, [
            'Content-Type' => 'application/json',
        ]);
    }

    /**
     * @param        $data
     * @param string $format
     *
     * @return mixed|string
     */
    protected function serialize($data, $format = 'json') {
        return $this->container
            ->get('jms_serializer')
            ->serialize($data, $format,
                        SerializationContext::create()->enableMaxDepthChecks()
            );
    }
}
