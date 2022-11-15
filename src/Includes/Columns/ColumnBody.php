<?php

namespace Irpcpro\TableSoft\Includes\Columns;

class ColumnBody extends ColumnFields
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
     * set all data to properties
     * */
    public function __construct($value, DefineColumn|null $data = null)
    {
        $this->title = $data->title ?? null;
        $this->name = $data->name ?? null;
        $this->type = $data->type ?? null;
        $this->sort = $data->sort ?? null;
        $this->sortBy = $data->sortBy ?? null;
        $this->value = $value ?? null;
        $this->width = $data->width ?? null;
        $this->widthMeasure = $data->widthMeasure ?? null;
        $this->searchable = $data->searchable ?? false;
    }

    /**
     * @return string
     * */
    public function __toString(): string
    {
        return $this->value;
    }
}
