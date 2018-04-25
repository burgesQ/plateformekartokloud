<?php

namespace App\Controller;


use App\Entity\DailyKartoVm;
use App\Entity\KartoVm;
use App\Entity\KartoVmMap;
use App\Entity\Map;
use App\Entity\User;
use App\Helper\APIControllerHelper;
use App\Service\KartoMapCreator;
use Doctrine\ORM\EntityManagerInterface;
use FOS\RestBundle\Controller\Annotations as Rest;
use Nelmio\ApiDocBundle\Annotation\Model;
use Swagger\Annotations as SWG;
use Symfony\Component\Finder\Exception\AccessDeniedException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

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
     *     response=401,
     *     description="User need to join a company.",
     *     @SWG\Schema(type="string")
     * )
     * @SWG\Response(
     *     response=403,
     *     description="User not logged",
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
    public function postMapAction(Request $request, KartoMapCreator $kartoMapCreator, EntityManagerInterface $em)
    {
        /** @var User $user */
        $user = $this->checkUser();

        if ($user->getCompany() == null) {
            return $this->createApiResponse("You need to join a company my man", Response::HTTP_UNAUTHORIZED);
        }

        // de-serialize json
        $kvms = [];
        foreach ($request->request as $oneKVm) {
            $kvms[] = $kartoMapCreator->createKartoVmMap($oneKVm);
        }

        // create map entity
        $map = new Map();
        $map->setCompany($user->getCompany()->getCompany());
        foreach ($kvms as $oneKVm) {
            if ($oneKVm) {
                $map->addKartoVm($oneKVm);
            } else {
                return $this->createApiResponse("Invalid data in the json array", Response::HTTP_UNPROCESSABLE_ENTITY);
            }
        }

        $em->persist($map);
        $em->flush();

        return $this->createApiResponse($map, Response::HTTP_OK);
    }

    /**
     * @return User|null
     */
    private function checkUser() : ?User
    {
        if ($this->getUser()) {
            return $this->getUser();
        } else {
            throw new AccessDeniedException("You need to be logged my man.");
        }
    }

    /**
     * @SWG\Response(
     *     response=204,
     *     description="Returns the array of kvm"
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
     *
     * @Rest\Put("/api/v1/kvm/{map_id}")
     *
     * @param int                    $map_id
     * @param EntityManagerInterface $em
     *
     * @return Response
     */
    public function getKVMforMapAction($map_id, EntityManagerInterface $em)
    {
        return $this->createApiResponse($em->getRepository(KartoVmMap::class)->findBy(["map_id" => $map_id])
            , Response::HTTP_OK);
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
     * @param Request                $request
     * @param int                    $map_id
     * @param EntityManagerInterface $em
     *
     * @return Response
     */
    public function putMapAction(Request $request, int $map_id, EntityManagerInterface $em)
    {
        $this->checkUser();

        if (!($map = $em->getRepository(Map::class)->findOneBy(["id" => $map_id]))) {
            return $this->createApiResponse("No such map entity", Response::HTTP_NOT_FOUND);
        }

        // update pos kvm
        $arrayId = [];
        foreach ($request->request as $oneKVm) {
            if (!($kvm = $em->getRepository(KartoVmMap::class)->findOneBy(["id" => $oneKVm["karto_vm_id"]]))) {
                return $this->createApiResponse("No such karto vm map entity", Response::HTTP_NOT_FOUND);
            }

            $kvm->setXPos($oneKVm["x_pos"]);
            $kvm->setYPos($oneKVm["y_pos"]);
            $arrayId[] = $kvm->getId();
        }

        // remove kvm
        /** @var KartoVmMap $oneKVm */
        foreach ($map->getKartoVms() as $oneKVm) {
            if (!in_array($oneKVm->getId(), $arrayId)) {
                $map->removeKartoVm($oneKVm);
                $em->remove($oneKVm);
            }
        }

        $em->flush();

        return $this->createApiResponse(null, Response::HTTP_NO_CONTENT);
    }

    /**
     * @SWG\Response(
     *     response=200,
     *     description="Return a karto map",
     *     @Model(type=Map::class)
     * )
     * @SWG\Response(
     *     response=403,
     *     description="User not logged",
     *     @SWG\Schema(type="string")
     * )
     *
     * @Rest\Get("/api/v1/map/{map_id}")
     * @param int $map_id
     *
     * @return Response
     */
    public function getMapByIdAction(int $map_id)
    {
        $this->checkUser();

        return $this->createApiResponse(
            $this->getDoctrine()
                 ->getRepository(Map::class)
                 ->findOneBy(["id" => $map_id])
        );
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
    public function getKartoVmAction()
    {
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
    public function getDailyKartoVmAction()
    {
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
     *     description="Return a list of map for the company of the logged user.",
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
    public function getMapByCompanyAction()
    {
        $this->checkUser();

        if ($this->getUser()->getCompany()->getCompany() == null) {
            return $this->createApiResponse(
                "User not linked to a company.",
                Response::HTTP_NOT_FOUND
            );
        }

        return $this->createApiResponse(
            $this->getDoctrine()
                 ->getRepository(Map::class)
                 ->findBy(["company" => $this->getUser()->getCompany()->getId()])
        );
    }

//    /**
//     * @TODO add Entity Model
//     * @SWG\Response(
//     *     response=200,
//     *     description="Return the map info"
//     * )
//     * @SWG\Response(
//     *     response=403,
//     *     description="User not logged",
//     *     @SWG\Schema(type="string")
//     * )
//     * @SWG\Response(
//     *     response=404,
//     *     description="Invalid map id or not in corresponding company",
//     *     @SWG\Schema(type="string")
//     * )
//     * @Rest\Get("/api/v1/map_info/{map_id}")
//     *
//     * @param string $map_id
//     *
//     * @return Response
//     */
//    public function getInfoForMapAction(string $map_id) {
//        $this->checkUser();
//
//        return $this->createApiResponse(
//            $this->getDoctrine()
//                 ->getRepository(Map::class)
//                 ->findBy(["id" => $map_id])
//        );
//    }
}