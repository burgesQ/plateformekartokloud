<?php

namespace App\Helper;

use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\FormInterface;

class APIControllerHelper extends Controller
{
    /**
     * @param  Request       $request
     * @param  FormInterface $form
     */
    protected function processForm(Request $request, FormInterface $form)
    {
        $data = json_decode($request->getContent(), true);
        if ($data === null) {
            throw new BadRequestHttpException();
        }

        $clearMissing = $request->getMethod() != 'PATCH';

        $form->submit($data, $clearMissing);
    }
}
