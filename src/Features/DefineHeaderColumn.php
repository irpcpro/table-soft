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
    public $width;
    /**
     * @var string
     * */
    public $widthMeasure;

    public function __construct(DefineColumn $data)
    {
        $this->type = $data->fieldType;
        $this->title = $data->title;
        $this->width = $data->width ?? null;
        $this->widthMeasure = $data->widthMeasure;
    }

    /**
     * @return string
     * */
    public function __toString(): string
    {
        return $this->title;
    }

}
