<?php

namespace App\Controller;

use App\Entity\Company;
use App\Entity\User;
use App\Form\CompanyFormType;
use App\Form\JoinCompanyType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Helper\ControllerHelper;

class CompanyController extends ControllerHelper
{
    /**
     * @Route("/company/create")
     *
     * @param Request $request
     *
     * @return Response
     */
    public function createCompanyAction(Request $request)
    {
        $company = new Company();
        $form = $this->createForm(CompanyFormType::class, $company);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->persist($company);
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('app_home_home');
        }

        return $this->render('api/create_company.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("company/join")
     *
     * @param Request $request
     *
     * @return Response
     */
    public function joinCompanyAction(Request $request)
    {
        $companies = $this->getDoctrine()->getRepository('App:Company')->findAll(Company::class);

        /**
         * @var $user User
         */
        $user = $this->getUser();

        $form = $this->createForm(JoinCompanyType::class, $user, [
            'companies' => $companies
        ]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->persist($this->getUser());
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('app_home_home');
        }
        return $this->render('api/join_company.html.twig', [
            'form' => $form->createView()
        ]);
    }
}