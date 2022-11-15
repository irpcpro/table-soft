<?php

namespace Irpcpro\TableSoft\Includes\Columns;

class DefineColumn
{
    public $title;
    public $name;
    public $type;
    public $sort;
    public $sortBy;
    public $value;
    public $index;
    public $width;
    public $widthMeasure;
    public $searchable = false;

    public function __construct($title, $field, $index, $sort = null, $function = null)
    {
        $this->title = $title;
        $this->index = $index;
        $this->name = explode(':', $field)[0];
        $this->type = explode(':', $field)[1];
        if($sort != null){
            $this->sort = explode(':', $sort)[0];
            $this->sortBy = explode(':', $sort)[1];
        }
        if($function)
            $this->value = $function;
    }

    /**
     * @param int $size
     * */
    public function setWidthColumn($size, $measure)
    {
        $this->width = $size;
        $this->widthMeasure = $measure;
    }

    /**
     * set variable
     * @return void
     */
    public function setSearchableColumn()
    {
        $this->searchable = true;
    }

}
