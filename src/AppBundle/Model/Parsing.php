<?php

namespace AppBundle\Model;

use PHPHtmlParser\Dom;

class Parsing
{

    public $url = 'https://www.dofus.com/fr/mmorpg/communaute/annuaires/';
    public $parser;

    function __construct($guildeId)
    {
        $this->parser = new Dom;
//        $this->initGuildeParser($guildeId);
//        $this->initGuildeParserMembers();
    }
}