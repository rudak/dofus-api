<?php
/**
 * Created by PhpStorm.
 * User: robinparisot
 * Date: 12/03/2018
 * Time: 13:34
 */

namespace AppBundle\Model;

trait GuildeParserMembers
{
    private $urlMembers;

    private $pager;

    private $members;

    private $parserLoadMembers;

    function initGuildeParserMembers()
    {
        $this->setUrlMembers();
        $this->parserLoadMembers = $this->parser->load($this->urlMembers);
        $this->setPager();
        $this->setMembers();
    }

    public function getMembers()
    {
        return $this->members;
    }

    public function setMembers()
    {
        $parser = $this->parser;
        $url    = $this->urlMembers;
        $moy = 0;

        $membres = [];
        for ($i = 1; $i <= $this->getPager(); $i++) {
            $parser->load($url . '?page=' . $i);
            $tableTag = '.ak-guilds table tr';
            $table    = $parser->find($tableTag);

            $first = true;

            foreach ($table as $row) {
                if ($first) {
                    $first = false;
                    continue;
                }
                $membres[] = [
                    'name'  => $row->find('td a', 0)->text,
                    'class' => $row->find('td a', 1)->text,
                    'lvl'   => intval($row->find('td', 2)->text),
                    'rang'  => $row->find('td', 3)->text,
                ];
                $moy = $moy + intval($row->find('td', 2)->text);
            }
        }
        $moy = round($moy/$this->guildeNbMembers);
        $this->setGuildeLvlMoy($moy);
//        $membres = json_encode($membres);
        $this->members = $membres;
    }

    public function getPager()
    {
        return $this->pager;
    }

    public function setPager()
    {
        $nbMembersPerPage = 25;
        $this->pager = ceil($this->getGuildeNbMembers()/$nbMembersPerPage);
    }

    /**
     * @return string
     */
    public function getUrlMembers()
    {
        return $this->urlMembers;
    }

    /**
     * @param string $urlMembers
     */
    public function setUrlMembers()
    {
        $this->urlMembers = $this->urlGuilde . 'membres';
    }

}