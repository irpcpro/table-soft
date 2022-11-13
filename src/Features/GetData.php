<?php

namespace Irpcpro\TableSoft\Features;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;
use PhpParser\Node\Expr\Array_;

class GetData
{
    public $data;
    public $columns;
    public $isDataModelBuilder;

    /**
     * @param Collection|Builder $data
     * @param DefineColumn[] $columns
     * @param bool $isDataModelBuilder
     * */
    public function __construct(Collection|Builder $data, array $columns, bool $isDataModelBuilder)
    {
        $this->data = $data;
        $this->columns = collect($columns);
        $this->isDataModelBuilder = $isDataModelBuilder;
        return $this;
    }

    public function paginate($number): GetData
    {
        if($this->isDataModelBuilder)
            $this->data = $this->data->paginate($number);
        else
            $this->data = $this->data->paginateList($number);

        return $this;
    }

    public function build()
    {

//        dd($this->columns->pluck('fieldName'));
        $getFieldNames = $this->columns->pluck('fieldName')->toArray();
        $out = $this->data->map(function($data) use ($getFieldNames) {
            $out = [];
            foreach ($getFieldNames as $item){
                if($data->$item)
                    $out[$item] = $data->$item;
            }
            return $out;
        });

//        dd($out);

        return ':)';
    }

}
