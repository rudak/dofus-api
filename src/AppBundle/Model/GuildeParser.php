<?php

namespace AppBundle\Model;

/**
 * Created by PhpStorm.
 * User: robinparisot
 * Date: 11/03/2018
 * Time: 16:14
 */
class GuildeParser extends Parsing
{
    private $guildeId;

    private $urlGuilde;

    private $urlGuildeMembers;

    private $parserLoadGuilde;

    private $guildeName;

    private $guildeLevel;

    private $guildeNbMembers;

    private $guildeServer;

    private $guildeCreatedAt;

    private $guildeImg;

    private $guildeMembers;

    private $guildeMembersPagination;

    private $guildeAllianceName;

    private $guildeAllianceNbGuilde;

    private $guildeAllianceNbMembers;

    private $guildeAllianceImg;

    public function __construct($guildeId)
    {
        parent::__construct($guildeId);
        $this->guildeId         = $guildeId;
        $this->urlGuilde        = $this->url . 'pages-guildes/' . $this->guildeId . '/';
        $this->parserLoadGuilde = $this->parser->load($this->urlGuilde);
    }

    public function setInfos()
    {
        $this->setGuildeName();
        $this->setGuildeNbMembers();
        $this->setGuildeLevel();
        $this->setGuildeImg();
        $this->setGuildeServer();
    }

    public function setInfosExtends()
    {
        $this->setGuildeAllianceImg();
        $this->setGuildeAllianceName();
        $this->setGuildeAllianceNbMembers();
        $this->setGuildeAllianceNbGuilde();
        $this->setGuildeCreatedAt();
    }

    public function setMembers()
    {
        $this->setGuildeNbMembers();
        $this->urlGuildeMembers = $this->urlGuilde . 'membres';
        $this->setGuildeMembersPagination();
        $this->setGuildeMembers();

    }

    public function getGuildeMembersPagination()
    {
        return $this->guildeMembersPagination;
    }

    public function setGuildeMembersPagination()
    {
        $this->parserLoadGuilde        = $this->parser->load($this->urlGuildeMembers);
        $nbMembersPerPage              = 25;
        $this->guildeMembersPagination = ceil($this->getGuildeNbMembers() / $nbMembersPerPage);
    }

    public function getGuildeMembers()
    {
        return $this->guildeMembers;
    }

    public function setGuildeMembers()
    {
        $parser = $this->parser;
        $url    = $this->urlGuildeMembers;
        $moy    = 0;

        $membres = [];
        for ($i = 1; $i <= $this->guildeMembersPagination; $i++) {
            $parser->load($url . '?page=' . $i);
            $tableTag = '.ak-guilds table tr';
            $table    = $parser->find($tableTag);
            unset($table[0]);
            foreach ($table as $row) {
                $membres[] = [
                    'name'  => $row->find('td a', 0)->text,
                    'class' => $row->find('td a', 1)->text,
                    'lvl'   => intval($row->find('td', 2)->text),
                    'rang'  => $row->find('td', 3)->text,
                ];
                $moy       = $moy + intval($row->find('td', 2)->text);
            }
        }
        $this->guildeMembers = $membres;
    }

    /**
     * @return mixed
     */
    public function getGuildeLevel()
    {
        return $this->guildeLevel;
    }

    /**
     * @param mixed $guildeLevel
     */
    public function setGuildeLevel()
    {
        $levelTag          = 'span.ak-directories-level';
        $level             = $this->parserLoadGuilde->find($levelTag, 0)->text;
        $this->guildeLevel = intval(explode(' ', $level)[2]);
    }

    /**
     * @return mixed
     */
    public function getGuildeNbMembers()
    {
        return $this->guildeNbMembers;
    }

    /**
     * @param mixed $guildeNbMembers
     */
    public function setGuildeNbMembers()
    {

        $nbMembersTag          = 'span.ak-directories-breed';
        $nbMembers             = $this->parserLoadGuilde->find($nbMembersTag, 0)->text;
        $this->guildeNbMembers = intval(explode(' ', $nbMembers)[1]);
    }

    /**
     * @return mixed
     */
    public function getGuildeName()
    {
        return $this->guildeName;
    }

    /**
     * @param mixed $guildeName
     */
    public function setGuildeName()
    {
        $nameTag          = 'h1.ak-return-link';
        $name             = trim($this->parserLoadGuilde->find($nameTag, 0)->text);
        $this->guildeName = $name;
    }

    /**
     * @return mixed
     */
    public function getGuildeServer()
    {
        return $this->guildeServer;
    }

    /**
     * @param mixed $guildeServer
     */
    public function setGuildeServer()
    {
        $serverTag          = 'span.ak-directories-server-name';
        $server             = $this->parserLoadGuilde->find($serverTag, 0)->text;
        $this->guildeServer = $server;
    }

    /**
     * @return mixed
     */
    public function getGuildeCreatedAt()
    {
        return $this->guildeCreatedAt;
    }

    /**
     * @param mixed $guildeCreatedAt
     */
    public function setGuildeCreatedAt()
    {
        $createdAtTag          = 'span.ak-directories-creation-date';
        $createdAt             = explode(' ', trim($this->parserLoadGuilde->find($createdAtTag, 0)->text));
        $createdAt             = end($createdAt);
        $createdAt             = \DateTime::createFromFormat('d/m/Y', $createdAt);
        $this->guildeCreatedAt = $createdAt;
    }

    /**
     * @return mixed
     */
    public function getGuildeImg()
    {
        return $this->guildeImg;
    }

    /**
     * @param mixed $guildeImg
     */
    public function setGuildeImg()
    {
        $guildeImgTag = 'div.ak-directories-header  div.ak-emblem';
        $guildeImg    = $this->parser->find($guildeImgTag)->getAttribute('style');
//        Récupération de la 1er partie du style, l'url de l'image
        $guildeImg = explode(' ', $guildeImg)[0];
        preg_match("/url\((.*)\)/", $guildeImg, $guildeImg);
        $guildeImg       = $guildeImg[1];
        $this->guildeImg = $guildeImg;
    }

    /**
     * @return mixed
     */
    public function getGuildeAllianceImg()
    {
        return $this->guildeAllianceImg;
    }

    /**
     * @param mixed $guildeAllianceImg
     */
    public function setGuildeAllianceImg()
    {
        $guildeAllianceImgTag = 'div.ak-character-alliances div.ak-emblem';
        $guildeAllianceImg    = $this->parser->find($guildeAllianceImgTag)->getAttribute('style');
//        Récupération de la 1er partie du style, l'url de l'image
        $guildeAllianceImg = explode(' ', $guildeAllianceImg)[0];
        preg_match("/url\((.*)\)/", $guildeAllianceImg, $guildeAllianceImg);
        $guildeAllianceImg       = $guildeAllianceImg[1];
        $this->guildeAllianceImg = $guildeAllianceImg;
    }

    /**
     * @return mixed
     */
    public function getGuildeAllianceName()
    {
        return $this->guildeAllianceName;
    }

    /**
     * @param mixed $guildeAllianceName
     */
    public function setGuildeAllianceName()
    {
        $allianceTag              = 'div.ak-character-alliances a';
        $allianceName             = $this->parserLoadGuilde->find($allianceTag, 0)->text;
        $value                    = $allianceName;
        $this->guildeAllianceName = $value;
    }

    /**
     * @return mixed
     */
    public function getGuildeAllianceNbGuilde()
    {
        return $this->guildeAllianceNbGuilde;
    }

    /**
     * @param mixed $guildeAllianceNbGuilde
     */
    public function setGuildeAllianceNbGuilde()
    {
        $allianceNbGuildeTag          = 'div.ak-character-alliances span.ak-infos-guildlevel';
        $allianceNbGuildes            = intval(explode(' ', $this->parserLoadGuilde->find($allianceNbGuildeTag, 0)->text)[0]);
        $value                        = $allianceNbGuildes;
        $this->guildeAllianceNbGuilde = $value;
    }

    /**
     * @return mixed
     */
    public function getGuildeAllianceNbMembers()
    {
        return $this->guildeAllianceNbMembers;
    }

    /**
     * @param mixed $guildeAllianceNbMembers
     */
    public function setGuildeAllianceNbMembers()
    {
        $allianceNbMembresTag = 'div.ak-character-alliances span.ak-infos-guildmembers';

        $allianceNbMembres             = intval(explode(' ', $this->parserLoadGuilde->find($allianceNbMembresTag, 0)->text)[0]);
        $value                         = $allianceNbMembres;
        $this->guildeAllianceNbMembers = $value;
    }

}