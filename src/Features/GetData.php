<?php

namespace Irpcpro\TableSoft\Features;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use JetBrains\PhpStorm\ArrayShape;

class GetData
{
    public $data;
    public $columns;
    public $isDataModelBuilder;

    private $tableDataHeader;
    private $tableDataBody;

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

    /**
     * @param int $number
     * */
    private function paginate(int $number): void
    {
        if ($this->isDataModelBuilder)
            $this->tableDataBody = collect($this->tableDataBody);

        $this->tableDataBody = $this->tableDataBody->paginateList($number);

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
    private function makeTableHeader(): Collection
    {
        $headColumns = collect([]);

        foreach($this->columns as $item)
            $headColumns->push(new DefineHeaderColumn($item));

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

        // map on data
        return $this->data->map(function ($data) use ($getFieldNames, $columnsGroupBy) {
            // data filtered
            $dataFiltered = collect([]);

            // get data row item
            foreach ($getFieldNames as $item) {
                // get field
                $columnsetting = $columnsGroupBy->get($item);
                if($columnsetting && $data->$item){
                    // get first of column setting
                    $columnsetting = $columnsetting->first();
                    $value = $data->$item;

                    // return edited array
                    $dataFiltered[$item] = ($columnsetting->value)($value);
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
        'header' => [DefineHeaderColumn::class],
        'body' => (Collection::class | LengthAwarePaginator::class)
    ])] public function build(int $paginate = 0): array
    {
        $this->tableDataHeader = $this->makeTableHeader();
        $this->tableDataBody = $this->makeTableBody();

        if($paginate)
            $this->paginate($paginate);

        return [
            'header' => $this->tableDataHeader,
            'body' => $this->tableDataBody
        ];
    }

}
