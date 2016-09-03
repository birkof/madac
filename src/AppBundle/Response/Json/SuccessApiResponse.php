<?php
namespace AppBundle\Response\Json;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class SuccessApiResponse extends JsonResponse
{
    public function __construct($data = [], $status = Response::HTTP_OK, $headers = array(), $json = false)
    {
        $data =
        [
            'isError'   =>  false,
            'message'   =>  $data
        ];
        parent::__construct($data, $status, $headers, $json);
    }
}
