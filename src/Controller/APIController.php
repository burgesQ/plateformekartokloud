<?php

namespace App\Controller;

use App\Entity\Company;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use FOS\RestBundle\Controller\Annotations as Rest;
use Symfony\Component\HttpFoundation\Request;
use Nelmio\ApiDocBundle\Annotation\Model;
use App\Helper\APIControllerHelper;
use Swagger\Annotations as SWG;
use App\Entity\KartoVm;
use App\Entity\Map;
use Symfony\Component\HttpFoundation\Response;

class APIController extends APIControllerHelper
{
    /**
     * @SWG\Response(
     *     response=201,
     *     description="Returns the new map",
     *     @SWG\Schema(
     *         @Model(type=Map::class)
     *     )
     * )
     * @SWG\Response(
     *     response=403,
     *     description="User not logged",
     *     @SWG\Schema(type="string")
     * )
     * @SWG\Response(
     *     response=501,
     *     description="Not implemented",
     *     @SWG\Schema(type="string")
     * )
     *
     * @Security("has_roles('ROLE_USER')")
     * @Rest\Post("/api/v1/map")
     *
     * @param Request $request
     * @TODO
     * @return Response
     */
    public function postMapAction(Request $request) {
        return $this->createApiResponse(null, Response::HTTP_NOT_IMPLEMENTED);
    }

    /**
     * @SWG\Response(
     *     response=204,
     *     description="Returns the updated map",
     *     @SWG\Schema(
     *         @Model(type=Map::class)
     *     )
     * )
     * @SWG\Response(
     *     response=403,
     *     description="User not logged",
     *     @SWG\Schema(type="string")
     * )
     * @SWG\Response(
     *     response=404,
     *     description="Invalid map id",
     *     @SWG\Schema(type="string")
     * )
     * @SWG\Response(
     *     response=501,
     *     description="Not implemented",
     *     @SWG\Schema(type="string")
     * )
     *
     * @Security("has_roles('ROLE_USER')")
     * @Rest\Put("/api/v1/map/{map_id}")
     *
     * @param Request $request
     * @TODO
     * @return Response
     */
    public function putMapAction(Request $request, $map_id) {
        return $this->createApiResponse(null, Response::HTTP_NOT_IMPLEMENTED);
    }

    /**
     * @SWG\Response(
     *     response=200,
     *     description="Return the list of karto vm",
     *     @Model(type=KartoVm::class)
     * )
     * @SWG\Response(
     *     response=403,
     *     description="User not logged",
     *     @SWG\Schema(type="string")
     * )
     * @Security("has_roles('ROLE_USER')")
     * @Rest\Get("/api/v1/karto_vm")
     */
    public function getKartoVmAction() {
        return $this->createApiResponse(
            $this->getDoctrine()
                 ->getRepository(KartoVm::class)
                 ->findAll()
        );
    }

    /**
     * @SWG\Response(
     *     response=200,
     *     description="Return the list of map peer company",
     *     @Model(type=KartoVm::class)
     * )
     * @SWG\Response(
     *     response=403,
     *     description="User not logged",
     *     @SWG\Schema(type="string")
     * )
     * @SWG\Response(
     *     response=404,
     *     description="User haven't the right / no such company",
     *     @SWG\Schema(type="string")
     * )
     *
     * @Security("has_roles('ROLE_USER')")
     * @Rest\Get("/api/v1/comapny/{company_id}/maps")
     *
     * @param int $company_id
     *
     * @return Response
     */
    public function getMapByCompanyAction(int $company_id) {

        if (!($this->getDoctrine()->getRepository(Company::class)->findOneBy(["id" => $company_id]))
            || $this->getUser()->getCompany()->getCompany()->getId() !== $company_id)
        return $this->createApiResponse(
            "No such company.",
            Response::HTTP_NOT_FOUND
        );

        return $this->createApiResponse(
            $this->getDoctrine()
                 ->getRepository(Map::class)
                 ->findAll()
        );
    }

    /**
     * @TODO add Entity Model
     * @SWG\Response(
     *     response=200,
     *     description="Return the map info"
     * )
     * @SWG\Response(
     *     response=403,
     *     description="User not logged",
     *     @SWG\Schema(type="string")
     * )
     * @SWG\Response(
     *     response=404,
     *     description="Invalid map id or not in corresponding company",
     *     @SWG\Schema(type="string")
     * )
     * @TODO
     * @Security("has_roles('ROLE_USER')")
     * @Rest\Get("/api/v1/map_info/{map_id}")
     *
     * @param string $map_id
     *
     * @return Response
     */
    public function getInfoForMapAction(string $map_id) {
        return $this->createApiResponse(null, Response::HTTP_NOT_IMPLEMENTED);
    }
}