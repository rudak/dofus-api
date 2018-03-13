<?php

namespace AppBundle\Model;

class GuildeModel
{
    public $id;

    public $name;

    public $nb_members;

    public $level;

    /**
     * GuildeModel constructor.
     * @param string $id
     */
    public function __construct($guildeId)
    {
        $this->id = $guildeId;
    }


}