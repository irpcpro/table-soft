<?php

namespace Irpcpro\TableSoft;

use Dotenv\Repository\Adapter\ArrayAdapter;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;
use Irpcpro\TableSoft\Features\DefineColumn;
use Irpcpro\TableSoft\Features\GetData;

class TableSoft {

    private $data;
    private $isDataModelBuilder = false;
    private $columns = [];
    private $paginate = 0;

    /**
     * @param Collection|Builder $data
     * @return TableSoft
     * */
    public function data(Collection|Builder $data): TableSoft
    {
        // get type of $data. is Model-Collection or Collection
        if($data instanceof Builder)
            $this->isDataModelBuilder = true;

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

    /**
     * @param int $number number of item per page. if 0 paginate never set
     * @return TableSoft
     * */
    public function paginate(int $number): TableSoft
    {
        $this->paginate = $number;
        return $this;
    }

    public function get()
    {
        $build_data = new GetData($this->data, $this->columns, $this->isDataModelBuilder);

        if($this->paginate)
            $build_data = $build_data->paginate($this->paginate);

        $build_data = $build_data->build();
        return $build_data;
    }

}