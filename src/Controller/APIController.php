<?php

namespace App\Controller;


use Symfony\Component\Finder\Exception\AccessDeniedException;
use FOS\RestBundle\Controller\Annotations as Rest;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Nelmio\ApiDocBundle\Annotation\Model;
use Doctrine\ORM\EntityManagerInterface;
use App\Helper\APIControllerHelper;
use App\Service\KartoMapCreator;
use Swagger\Annotations as SWG;
use App\Entity\DailyKartoVm;
use App\Entity\KartoVm;
use App\Entity\Map;

class APIController extends APIControllerHelper
{
    private function checkUser() {
        if ($this->getUser())
            return true;
        else
            throw new AccessDeniedException("You need to be logged my man.");
    }

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
     * @Rest\Post("/api/v1/map")
     *
     * @param Request                $request
     * @param KartoMapCreator        $kartoMapCreator
     * @param EntityManagerInterface $em
     *
     * @return Response
     */
    public function postMapAction(Request $request, KartoMapCreator $kartoMapCreator, EntityManagerInterface $em) {
        $this->checkUser();

        $kvms = array();
        foreach ($request->request as $oneKVm)
            $kvms[] = $kartoMapCreator->createKartoVmMap($oneKVm);

        $map = new Map();
        foreach ($kvms as $oneKVm)
            if ($oneKVm)
                $map->addKartoVm($oneKVm);
            else
                return $this->createApiResponse("Invalide data in the json array", Response::HTTP_UNPROCESSABLE_ENTITY);

        $em->persist($map);
        $em->flush();

        return $this->createApiResponse($map, Response::HTTP_OK);
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
     * @Rest\Put("/api/v1/map/{map_id}")
     *
     * @param Request $request
     * @TODO
     * @return Response
     */
    public function putMapAction(Request $request, $map_id) {
        $this->checkUser();
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
     *
     * @Rest\Get("/api/v1/karto_vm")
     */
    public function getKartoVmAction() {
        $this->checkUser();
        return $this->createApiResponse(
            $this->getDoctrine()
                 ->getRepository(KartoVm::class)
                 ->findAll()
        );
    }

    /**
     * @SWG\Response(
     *     response=200,
     *     description="Return the list of karto vm",
     *     @Model(type=DailyKartoVm::class)
     * )
     * @SWG\Response(
     *     response=403,
     *     description="User not logged",
     *     @SWG\Schema(type="string")
     * )
     *
     * @Rest\Get("/api/v1/daily_karto_vm")
     */
    public function getDailyKartoVmAction() {
        $this->checkUser();
        return $this->createApiResponse(
            $this->getDoctrine()
                 ->getRepository(DailyKartoVm::class)
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
     * @Rest\Get("/api/v1/company/maps")
     *
     * @return Response
     */
    public function getMapByCompanyAction() {
        $this->checkUser();

        if ($this->getUser()->getCompany()->getCompany() == null)
            return $this->createApiResponse(
                "User not linked to a company.",
                Response::HTTP_NOT_FOUND
            );

        return $this->createApiResponse(
            $this->getDoctrine()
                 ->getRepository(Map::class)
                 ->findBy(["company" => $this->getUser()->getCompany()->getId()])
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
     * @Rest\Get("/api/v1/map_info/{map_id}")
     *
     * @param string $map_id
     *
     * @return Response
     */
    public function getInfoForMapAction(string $map_id) {
        $this->checkUser();

        return $this->createApiResponse(
            $this->getDoctrine()
                 ->getRepository(Map::class)
                 ->findBy(["id" => $map_id])
        );
    }
}