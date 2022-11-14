<?php

namespace Irpcpro\TableSoft\Features;

class DefineHeaderColumn
{

    /**
     * @var string
     * */
    public $type;
    /**
     * @var string
     * */
    public $title;
    /**
     * @var int
     * */
    public $colSpan;

    public function __construct(DefineColumn $data)
    {
        $this->type = $data->fieldType;
        $this->title = $data->title;
        $this->colSpan = $data->colSpan ?? null;
    }

    /**
     * @return string
     * */
    public function __toString(): string
    {
        return $this->title;
    }

}
