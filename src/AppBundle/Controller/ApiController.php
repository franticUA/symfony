<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;

class ApiController extends Controller
{
    const OK_CODE = 200;
    const ERR_CODE = 400;
    const NOT_FOUND = 404;

    public function jsonResponse($data, $message, $code = self::OK_CODE) {
        return JsonResponse::create([
            'message' => $message,
            'data' => $data
        ], $code);
    }
}
