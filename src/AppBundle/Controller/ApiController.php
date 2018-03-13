<?php

namespace AppBundle\Controller;

use AppBundle\Utils\GuildeParser;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;

class ApiController extends Controller
{
    public function infosAction($id)
    {
        $guildeParser = new GuildeParser($id);
        return new JsonResponse($guildeParser->getGuildeInfos());
    }
}
