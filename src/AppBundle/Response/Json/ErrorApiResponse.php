<?php
namespace AppBundle\Response\Json;


use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class ErrorApiResponse extends JsonResponse
{
    public function __construct($data = [], $status = Response::HTTP_BAD_REQUEST, $headers = array(), $json = false)
    {
        $data =
            [
                'isError'   =>  true,
                'message'   =>  $data
            ];
        parent::__construct($data, $status, $headers, $json);
    }
}
