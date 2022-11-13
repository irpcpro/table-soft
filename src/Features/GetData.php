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
        $this->columns = $columns;
        $this->isDataModelBuilder = $isDataModelBuilder;
        return $this;
    }

    public function paginate($number): GetData
    {
        $this->data = $this->data->paginate($number);
        return $this;
    }

    public function build()
    {
        dd($this->data);

        return ':)';
    }

}
