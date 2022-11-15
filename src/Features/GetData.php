<?php

namespace Irpcpro\TableSoft\Features;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Irpcpro\TableSoft\TableSoft;
use JetBrains\PhpStorm\ArrayShape;

class GetData
{
    public $data;
    public $columns;
    public $isDataModelBuilder;
    private $tableDataHead;
    private $tableDataBody;
    private $rowCounter;
    private $paginate;

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

    private function paginate(): void
    {
        if($this->paginate){
            if ($this->isDataModelBuilder)
                $this->tableDataBody = collect($this->tableDataBody);

            if(count($this->columns))
                $this->tableDataBody = $this->tableDataBody->paginateList($this->paginate);
        }
    }

    /**
     * set counter row to table
     * @return void
     * */
    public function rowCounterColumn(): void
    {
        $this->rowCounter = true;
    }

    /**
     * @return array
     * */
    private function getFieldNames(): array
    {
        return $this->columns->pluck('fieldName')->toArray();
    }

    /**
     * @return Collection
     * */
    private function getColumnsGroupByFieldName(): Collection
    {
        return $this->columns->groupBy('fieldName');
    }

    /**
     * @return Collection
     * */
    private function makeTableHead(): Collection
    {
        $headColumns = collect([]);

        if(count($this->columns))
            foreach($this->columns as $item)
                $headColumns->push(new DefineHeaderColumn($item));
        else
            return collect([]);

        return $headColumns;
    }

    /**
     * @return Collection
     * */
    private function makeTableBody(): Collection
    {
        // get field names
        $getFieldNames = $this->getFieldNames();
        $columnsGroupBy = $this->getColumnsGroupByFieldName();

        // if it's builder, get the data
        if($this->isDataModelBuilder)
            $this->data = $this->data->get();

        // return empty when didn't set any columns
        if(count($this->columns) == 0)
            return collect([]);

        $counterRow = 1;
        // map on data
        return $this->data->map(function ($data) use ($getFieldNames, $columnsGroupBy, &$counterRow) {
            // data filtered
            $dataFiltered = collect([]);

            // get data row item
            foreach ($getFieldNames as $item) {
                // get field
                $columnsetting = $columnsGroupBy->get($item);

                if(!$columnsetting){
                    $dataFiltered[$item] = new DefineBodyColumn('', null);
                    continue;
                }

                // get first of column setting
                $columnsetting = $columnsetting->first();
                $value = $data->$item;

                if($value){
                    // return edited array
                    $dataFiltered[$item] = new DefineBodyColumn(($columnsetting->value)($value), $columnsetting);
                }else{
                    // handle for row counter
                    if(str_starts_with($columnsetting->fieldName, 'row')){
                        $dataFiltered[$item] = new DefineBodyColumn($counterRow++, $columnsetting);
                    }else{
                        $dataFiltered[$item] = new DefineBodyColumn('', null);
                    }
                }
            }

            // remake array
            return $dataFiltered;
        });
    }

    /**
     * @param int $paginate
     * @return array
     */
    #[ArrayShape([
        'head' => [DefineHeaderColumn::class],
        'body' => (Collection::class | LengthAwarePaginator::class),
        'exists' => 'bool'
    ])] public function build(int $paginate = 0): array
    {
        $this->paginate = $paginate;
        // get head table data
        $this->tableDataHead = $this->makeTableHead();
        // get body table data
        $this->tableDataBody = $this->makeTableBody();
        // set paginate to list
        $this->paginate();
        // return list of data
        return [
            'head' => $this->tableDataHead,
            'body' => $this->tableDataBody,
            'exists' => (bool) count($this->tableDataBody)
        ];
    }

}
