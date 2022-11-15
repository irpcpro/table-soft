<?php

namespace Irpcpro\TableSoft\Includes\Columns;

abstract class ColumnFields
{
    /**
     * @var string
     * */
    public $title;
    /**
     * @var string
     * */
    public $name;
    /**
     * @var string
     * */
    public $type;
    /**
     * @var string
     * */
    public $sort;
    /**
     * @var string
     * */
    public $sortBy;
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
    /**
     * @var bool
     * */
    public $searchable;

    /**
     * return value of column when it echos
     * @return string
     * */
    abstract function __toString();

}
