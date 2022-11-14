<?php

namespace Irpcpro\TableSoft\Features;

class DefineColumn extends ColumnFields
{
    public $title;
    public $fieldName;
    public $fieldType;
    public $sort;
    public $sortBy;
    public $value;
    public $colSpan;

    public function __construct($title, $field, $sort = null, $function = null)
    {
        $this->title = $title;
        $this->fieldName = explode(':', $field)[0];
        $this->fieldType = explode(':', $field)[1];
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
    public function setColSpan($size)
    {
        $this->colSpan = $size;
    }

}
