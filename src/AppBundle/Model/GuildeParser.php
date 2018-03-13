<?php

namespace AppBundle\Model;

/**
 * Created by PhpStorm.
 * User: robinparisot
 * Date: 11/03/2018
 * Time: 16:14
 */
trait GuildeParser
{
    public $urlGuilde;

    public $parserLoadGuilde;

    public $guildeLevel;

    public $guildeName;

    public $guildeNbMembers;
    public $guildeLvlMoy;

    public $guildeAllianceInfos;

    public $guildeServer;

    public $guildeCreatedAt;

    public $guildeImg;

    public $guildeAllianceImg;


    public function initGuildeParser($guildeName)
    {
        $this->guildeName       = $guildeName;
        $this->urlGuilde        = $this->url . 'pages-guildes/' . $this->guildeName . '/';
        $this->parserLoadGuilde = $this->parser->load($this->urlGuilde);
        $this->setGuildeLevel();
        $this->setGuildeName();
        $this->setGuildeImg();
        $this->setGuildeNbMembers();
        $this->setGuildeAllianceImg();
        $this->setGuildeAllianceInfos();
        $this->setGuildeServer();
        $this->setGuildeCreatedAt();
    }

    public function getGuildeInfos()
    {
        $value = [
            'name'      => $this->getGuildeName(),
            'img'       => $this->getGuildeImg(),
            'level'     => $this->getGuildeLevel(),
            'nbMembers' => $this->getGuildeNbMembers(),
            'lvlMoy' => $this->getGuildeLvlMoy(),
            'server'    => $this->getGuildeServer(),
            'createdAt' => $this->getGuildeCreatedAt(),
            'alliance'  => $this->getGuildeAllianceInfos(),
        ];
        return $value;
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
    public function getGuildeAllianceInfos()
    {
        return $this->guildeAllianceInfos;
    }

    /**
     * @param mixed $guildeAlliance
     */
    public function setGuildeAllianceInfos()
    {
        $allianceTag          = 'div.ak-character-alliances a';
        $allianceNbGuildeTag  = 'div.ak-character-alliances span.ak-infos-guildlevel';
        $allianceNbMembresTag = 'div.ak-character-alliances span.ak-infos-guildmembers';

        $allianceName              = $this->parserLoadGuilde->find($allianceTag, 0)->text;
        $allianceNbGuildes         = intval(explode(' ', $this->parserLoadGuilde->find($allianceNbGuildeTag, 0)->text)[0]);
        $allianceNbMembres         = intval(explode(' ', $this->parserLoadGuilde->find($allianceNbMembresTag, 0)->text)[0]);
        $value                     = [
            'name'      => $allianceName,
            'img'       => $this->getGuildeAllianceImg(),
            'nbGuilde'  => $allianceNbGuildes,
            'nbMembers' => $allianceNbMembres,
        ];
        $this->guildeAllianceInfos = $value;
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
    public function getGuildeLvlMoy()
    {
        return $this->guildeLvlMoy;
    }

    /**
     * @param mixed $guildeLvlMoy
     */
    public function setGuildeLvlMoy($guildeLvlMoy)
    {
        $this->guildeLvlMoy = $guildeLvlMoy;
    }

}