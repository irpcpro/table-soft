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
    public $colSpan;

    public function __construct($value, DefineColumn $data)
    {
        $this->type = $data->fieldType;
        $this->name = $data->fieldName;
        $this->value = $value;
        $this->colSpan = $data->colSpan ?? null;
    }

    /**
     * @return string
     * */
    public function __toString(): string
    {
        return $this->value;
    }
}
