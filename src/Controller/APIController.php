<?php

namespace App\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\Controller\FOSRestController;
use Symfony\Component\HttpFoundation\Request;
use Nelmio\ApiDocBundle\Annotation\Model;
use Swagger\Annotations as SWG;
use App\Entity\Map;

// TODO: add data transformer
class APIController extends FOSRestController
{
    /**
     * @SWG\Response(
     *     response=201,
     *     description="Returns the new map",
     *     @SWG\Schema(
     *         type="array",
     *         @Model(type=Map::class, groups={"full"})
     *     )
     * )
     * @SWG\Response(
     *     response=403,
     *     description="User not logged",
     *     @SWG\Schema(type="string")
     * )
     *
     * @Security("has_roles('ROLE_USER')")
     * @Rest\Post("/api/v1/map")
     *
     * @param Request $request
     */
    public function postMapAction(Request $request) {}

    /**
     * @SWG\Response(
     *     response=204,
     *     description="Returns the updated map",
     *     @SWG\Schema(
     *         type="array",
     *         @Model(type=Map::class, groups={"full"})
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
     *
     * @Security("has_roles('ROLE_USER')")
     * @Rest\Put("/api/v1/map/{map_id}")
     *
     * @param Request $request
     */
    public function putMapAction(Request $request, $map_id) {}

    /**
     * @TODO add Entity Model
     * @SWG\Response(
     *     response=200,
     *     description="Return the list of mappable entity"
     * )
     * @SWG\Response(
     *     response=403,
     *     description="User not logged",
     *     @SWG\Schema(type="string")
     * )
     * @Security("has_roles('ROLE_USER')")
     * @Rest\Get("/api/v1/entities")
     */
    public function getEntitiesAction() {
        // return this->getDoctrine()->getRrepository(Entity::class)->findAll();
    }

    /**
     * @TODO add Entity Model
     * @SWG\Response(
     *     response=200,
     *     description="Return the map info",
     *     @SWG\Schema(
     *         type="array",
     *         @SWG\Items(
     *             type="object",
     *             @SWG\Property(property="map", type="string"),
     *             @SWG\Property(property="price", type="integer")
     *             @SWG\Property(property="entities", type="array")
     *         )
     *     )
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
     *
     * @Security("has_roles('ROLE_USER')")
     * @Rest\Get("/api/v1/map_info/{map_id}")
     */
    public function getInfoForMapAction($map_id) {}
}