<?php

namespace AppBundle\Utils;

use Symfony\Component\CssSelector\CssSelectorConverter;
use Symfony\Component\DomCrawler\Crawler;

class Parser implements ParserInterface
{

    protected $crawler;

    protected $converter;

    public function __construct($url)
    {
        $this->crawler   = new Crawler(file_get_contents($url));
        $this->converter = new CssSelectorConverter();
    }

    protected function getTextOfNode($cssNode)
    {
        return trim($this->crawler->filterXPath($this->converter->toXPath($cssNode))->text());
    }


    protected function getIntFromNode($cssNode)
    {
        return intval(preg_replace('/[^0-9]/', '', trim($this->crawler->filterXPath($this->converter->toXPath($cssNode))->text())));
    }

}