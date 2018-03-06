<?php

namespace App\Controller;

use App\Entity\UserCompany;
use Doctrine\ORM\EntityManager;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use App\Helper\APIControllerHelper;
use App\Form\CompanyFormType;
use App\Form\JoinCompanyType;
use App\Entity\Company;
use App\Entity\User;

class CompanyController extends APIControllerHelper
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
        $em = $this->getDoctrine()->getManager();

        /** @var $user User */
        $user = $this->getUser();

        $data = [];
        $form = $this->createForm(JoinCompanyType::class, $data, [
            'companies' => $this->getDoctrine()->getRepository('App:Company')->findAll(Company::class)
        ]);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $company = $form->get('company')->getData();

            $userCompany = new UserCompany();
            // if no user accepted in the company, set default to true
            $userCompany->setAccepted(true);

            $em->persist($userCompany);
            $em->flush();

            $userCompany->setCompany($company);
            $userCompany->setUser($user);
            $em->flush();

            return $this->redirectToRoute('app_home_home');
        }

        return $this->render('api/join_company.html.twig', [
            'form' => $form->createView()
        ]);
    }
}