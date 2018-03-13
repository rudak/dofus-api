<?php

namespace AppBundle\Controller;

use AppBundle\Model\Parsing;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class DefaultController extends Controller
{
    public function indexAction(Request $request)
    {
        $guildeName = '421800212-glory-owl-dja';
//        $guildeName = '148200211-generation-sang';
        $parser = new Parsing($guildeName);
        return $this->render(':default:index.html.twig');


    }

    public function getGuildeMembersAction($guildeId)
    {
        $parser = new Parsing($guildeId.'-api');
        $value = json_encode($parser->getMembers());
        $response = new Response();
        $response->setContent($value);
        $response->headers->set('Content-Type', 'application/json');
        return $response;
    }

    public function getGuildeInfosAction($guildeId)
    {
        $parser = new Parsing($guildeId.'-api');
        $value = [
            'infosGlobales' => $parser->getGuildeInfos(),
            'membres'=> $parser->getMembers()
        ];
        $value = json_encode($value);
        $response = new Response();
        $response->setContent($value);
        $response->headers->set('Content-Type', 'application/json');
        return $response;
    }
}
