<?php

namespace AppBundle\Model;

use PHPHtmlParser\Dom;

/**
 * Created by PhpStorm.
 * User: robinparisot
 * Date: 11/03/2018
 * Time: 22:06
 */
class Parsing
{
    use GuildeParser;
    use GuildeParserMembers;

    public $url = 'https://www.dofus.com/fr/mmorpg/communaute/annuaires/';
    public $parser;

    function __construct($guildeName)
    {
        $this->parser = new Dom;
        $this->initGuildeParser($guildeName);
        $this->initGuildeParserMembers();
    }
}