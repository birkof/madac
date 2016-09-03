<?php

namespace AppBundle\Controller;

use AppBundle\Service\MeasurementService;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class DefaultController extends Controller
{
    /**
     * @Route("/", name="homepage")
     */
    public function indexAction(Request $request)
    {
        return $this->render('AppBundle:Madac:index.html.twig');
    }

    /**
     * @Route("/measurement", name="save_measurement")
     * @Method({"POST"})
     * @param Request $request
     * @return JsonResponse
     */
    public function saveMeasurementAction(Request $request)
    {
        /** @var MeasurementService $measurementService */
        $measurementService = $this->get(MeasurementService::ID);

        try {
            $measurementService->saveMeasurement(json_decode($request->getContent(), true));
        } catch (\InvalidArgumentException $ex) {
            return new JsonResponse(
                [
                    'isError'   =>  true,
                    'message'   =>  $ex->getMessage()
                ],
                Response::HTTP_BAD_REQUEST
            );
        }

        return new JsonResponse(
            [
                'isError'   =>  false
            ],
            Response::HTTP_OK
        );
    }
}
