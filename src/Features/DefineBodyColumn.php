<?php

namespace Irpcpro\TableSoft\Features;

class DefineBodyColumn
{
    /**
     * @var string
     * */
    public $type;
    /**
     * @var string
     * */
    public $name;
    /**
     * @var string
     * */
    public $value;
    /**
     * @var int
     * */
    public $width;
    /**
     * @var string
     * */
    public $widthMeasure;

    public function __construct($value, DefineColumn|null $data = null)
    {
        $this->type = $data->fieldType ?? null;
        $this->name = $data->fieldName ?? null;
        $this->value = $value ?? null;
        $this->width = $data->width ?? null;
        $this->widthMeasure = $data->widthMeasure ?? null;
    }

    /**
     * @return string
     * */
    public function __toString(): string
    {
        return $this->value;
    }
}
