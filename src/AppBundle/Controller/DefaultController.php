<?php

namespace AppBundle\Controller;

use AppBundle\Model\GuildeParser;
use AppBundle\Model\GuildeParserMembers;
use AppBundle\Model\Parsing;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\DomCrawler\Crawler;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class DefaultController extends Controller
{
    public function devAction()
    {
        $response = new Response();

        $value = ['foo' => 'bar'];

        $value = json_encode($value);

        $response->setContent($value);
        return $response;
    }


    public function guildeInfosAction(Request $request)
    {
        $response = new Response();
        $response->headers->set('Content-Type', 'application/json');

        $guildeId = $request->query->get('guildeId');
        $parser   = new GuildeParser($guildeId . '-api');

        $extends = boolval($request->query->get('extends'));

        $parser->setInfos();
        $value = [
            'guildeName'      => $parser->getGuildeName(),
            'guildeNbMembers' => $parser->getGuildeNbMembers(),
            'guildeLevel'     => $parser->getGuildeLevel(),
            'guildeImage'     => $parser->getGuildeImg(),
            'guildeServer'    => $parser->getGuildeServer(),
        ];

        if ($extends) {
            $parser->setInfosExtends();
            $value['extends'] = [
                'guildeCreatedAt'         => $parser->getGuildeCreatedAt(),
                'guildeAllianceName'      => $parser->getGuildeAllianceName(),
                'guildeAllianceNbMembers' => $parser->getGuildeAllianceNbMembers(),
                'guildeAllianceNbGuilde'  => $parser->getGuildeAllianceNbGuilde(),
                'guildeAllianceImg'       => $parser->getGuildeAllianceImg(),
            ];
        }

        $value = json_encode($value);

        $response->setContent($value);
        return $response;
    }

    public function getGuildeMembersAction(Request $request)
    {
        $response = new Response();
        $response->headers->set('Content-Type', 'application/json');

        $guildeId = $request->query->get('guildeId');
        $parser   = new GuildeParser($guildeId . '-api');
        $parser->setMembers();
        $value = ['members'=>$parser->getGuildeMembers()];

        $value = json_encode($value);

        $response->setContent($value);
        return $response;
    }

}
