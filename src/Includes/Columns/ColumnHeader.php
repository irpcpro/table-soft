<?php

namespace Irpcpro\TableSoft\Includes\Columns;

class ColumnHeader extends ColumnFields
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

    public function __construct(DefineColumn $data)
    {
        $this->title = $data->title;
        $this->name = $data->name;
        $this->type = $data->type;
        $this->sort = $data->sort;
        $this->sortBy = $data->sortBy;
        $this->value = ($data->value)($data->title ?? '');
        $this->width = $data->width ?? null;
        $this->widthMeasure = $data->widthMeasure;
        $this->searchable = $data->searchable;
    }

    /**
     * @return string
     * */
    public function __toString(): string
    {
        return $this->title;
    }

}
