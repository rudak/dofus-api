<?php

namespace AppBundle\Utils;

use AppBundle\Model\GuildeModel;

class GuildeParser extends Parser
{

    const URL = 'https://www.dofus.com/fr/mmorpg/communaute/annuaires/pages-guildes/%d-foo';

    private $guildeId;

    private $guildeModel;

    public function __construct($guildeId)
    {
        parent::__construct(sprintf(self::URL, $guildeId));
        $this->guildeId = intval($guildeId);
    }

    public function getGuildeInfos()
    {
        $this->getGuildeModel()->name       = $this->getTextOfNode('.ak-return-link');
        $this->getGuildeModel()->level      = $this->getIntFromNode('.ak-directories-level');
        $this->getGuildeModel()->nb_members = $this->getIntFromNode('.ak-infos-guildmembers');

        // etc ...

        return get_object_vars($this->getGuildeModel());
    }

    private function getGuildeModel()
    {
        if (null == $this->guildeModel) {
            $this->guildeModel = new GuildeModel($this->guildeId);
        }
        return $this->guildeModel;
    }
}