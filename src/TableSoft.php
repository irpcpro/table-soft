<?php

namespace Irpcpro\TableSoft;

use Exception;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Irpcpro\TableSoft\Features\DefineColumn;
use Irpcpro\TableSoft\Features\DefineHeaderColumn;
use Irpcpro\TableSoft\Features\GetData;
use JetBrains\PhpStorm\ArrayShape;

class TableSoft {

    /**
     * @var array
     * */
    private $data;
    /**
     * @var bool
     * */
    private $isDataModelBuilder = false;
    /**
     * @var DefineColumn[]
     * */
    private array $columns = [];
    /**
     * @var int
     * */
    private int $paginate = 0;
    /**
     * @var bool
     * */
    private bool $rowCounter = false;


    private int $lastColumnIndex = 0;


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
     * @param bool $addFirst to add column in the beginning of columns array
     * @return TableSoft
     */
    public function column(string $title, string $field = null, string|object $sort = null, object $function = null, bool $addFirst = false): TableSoft
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

        // make column data
        $columnData = new DefineColumn($title, $field, $this->lastColumnIndex++, $sort, $function);

        // add column to array
        if($addFirst)
            array_unshift($this->columns, $columnData);
        else
            $this->columns[] = $columnData;

        // return class
        return $this;
    }

    /**
     * @param int $size
     * @param string $measure [%,px,..]
     * @return TableSoft
     * */
    public function setWidth(int $size, string $measure = '%'): TableSoft
    {
        if(!empty($this->columns)){
            if($this->columns[0]->index == $this->lastColumnIndex - 1)
                $this->columns[0]->setWidthColumn($size, $measure);
            else
                end($this->columns)->setWidthColumn($size, $measure);
        }

        return $this;
    }

    /**
     * @return $this
     */
    public function searchable(): TableSoft
    {
        if(!empty($this->columns))
            end($this->columns)->setSearchableColumn();

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

    /**
     * set counter row to table
     * @return TableSoft
     * */
    public function rowCounter(string $title = 'Row', string $field = null, string|object $sort = null, object $function = null): TableSoft
    {
        // just add row counter once
        if($this->rowCounter == false){
            $this->rowCounter = true;

            // add column
            $this->column($title, $field, $sort, $function, true);
        }
        return $this;
    }

    #[ArrayShape([
        'head' => [DefineHeaderColumn::class],
        'body' => Collection::class | LengthAwarePaginator::class,
        'exists' => 'bool'
    ])] public function get(): array
    {
        $build_data = new GetData($this->data, $this->columns, $this->isDataModelBuilder);
        $build_data = $build_data->build($this->paginate);
        return $build_data;
    }

}
