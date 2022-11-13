<?php

namespace Irpcpro\TableSoft;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Irpcpro\TableSoft\Features\DefineColumn;
use mysql_xdevapi\Table;
use function PHPUnit\Framework\isNull;

class TableSoft {

    private $data;
    private $isDataModelCollection = false;
    private $columns = [];

    /**
     * @param Collection|Model $data
     * @return TableSoft
     * */
    public function data(Collection|Model $data): TableSoft
    {
        // get type of $data. is Model-Collection or Collection
        if($data instanceof Model)
            $this->isDataModelCollection = true;

        // get data
        $this->data = $data;
        return $this;
    }

    /**
     * @param string $title the title of the column you can see
     * @param string|null $field fieldName:type(int,string,float,date,bool)
     * @param string|object|null $sort sort:sortType(asc,desc) - default asc
     * @param object|null $function the function returns value to overwrite
     * @return TableSoft
     */
    public function column(string $title, string $field = null, string|object $sort = null, object $function = null): TableSoft
    {
        if($field == null)
            $field = mb_strtolower($title);

        if(strpos($field, ':') == false)
            $field .= ':string';

        // return this field. Input Error
        if(is_object($sort) && $function != [])
            return $this;

        if(is_object($sort)){
            $function = $sort;
            $sort = null;
        }elseif($sort != null && strpos($sort,':') == false){
            $sort .= ':asc';
        }

        if($function == null)
            $function = function($value){return $value;};

        $this->columns[] = new DefineColumn($title, $field, $sort, $function);
        return $this;
    }

}
