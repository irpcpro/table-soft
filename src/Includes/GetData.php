<?php

namespace Irpcpro\TableSoft\Includes;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;
use Irpcpro\TableSoft\Includes\Columns\ColumnBody;
use Irpcpro\TableSoft\Includes\Columns\DefineColumn;
use Irpcpro\TableSoft\Includes\Columns\ColumnHeader;
use Irpcpro\TableSoft\Includes\QueryParams\QueryParams;
use JetBrains\PhpStorm\ArrayShape;

class GetData
{
    /**
     * @var Collection|Builder
     * */
    public $data;
    public $columns;
    public $isDataModelBuilder;
    private $tableDataHead;
    private $tableDataBody;
    private $paginate;
    private QueryParams $queryParam;
    private $paginateMethodRender;


    /**
     * @param Collection|Builder $data
     * @param DefineColumn[] $columns
     * @param bool $isDataModelBuilder
     * */
    public function __construct(Collection|Builder $data, array $columns, bool $isDataModelBuilder)
    {
        // set data
        $this->data = $data;
        $this->columns = collect($columns);
        $this->isDataModelBuilder = $isDataModelBuilder;

        // set query param settings
        $this->setQueryParamsSettings();

        // return this
        return $this;
    }

    /**
     * get query param setting
     * @return void
     * */
    private function setQueryParamsSettings(): void
    {
        $this->queryParam = new QueryParams();
    }

    private function paginate($data)
    {
        if ($this->paginate) {
            if ($this->isDataModelBuilder)
                $data = collect($data);

            if (count($this->columns))
                $data = $data->paginateList($this->paginate);
        }

        return $data;
    }

    private function makePaginateButtons(){
        if(count($this->tableDataBody) && $this->tableDataBody && method_exists($this->tableDataBody,'total')){
            return $this->tableDataBody->appends(request()->except('page'))->render($this->paginateMethodRender);
        }
    }

    /**
     * @return array
     * */
    private function getFieldNames(): array
    {
        return $this->columns->pluck('name')->toArray();
    }

    /**
     * @return Collection
     * */
    private function getColumnsGroupByFieldName(): Collection
    {
        return $this->columns->groupBy('name');
    }

    /**
     * @return Collection
     * */
    private function makeTableHead(): Collection
    {
        $headColumns = collect([]);

        if (count($this->columns))
            foreach ($this->columns as $item)
                $headColumns->push(new ColumnHeader($item));
        else
            return collect([]);

        return $headColumns;
    }

    /**
     * @return int
     * */
    private function generatePageNumber(): int
    {
        $out = 1;
        if($this->queryParam->currentPage != 0)
            $out = (($this->paginate?? 0) * (($this->queryParam->currentPage ?? 1) - 1) + 1);

        return $out;
    }

    /**
     * mapping on data to change fields
     * */
    private function mappingData($data, $getFieldNames, $columnsGroupBy)
    {
        $counterRow = $this->generatePageNumber();

        $mappingFunction = function ($allData) use ($getFieldNames, $columnsGroupBy, &$counterRow) {
            // data filtered
            $dataFiltered = collect([]);

            // get data row item
            foreach ($getFieldNames as $item) {
                // get field
                $columnsetting = $columnsGroupBy->get($item);

                if (!$columnsetting) {
                    $dataFiltered[$item] = new ColumnBody('', null);
                    continue;
                }

                // get first of column setting
                $columnsetting = $columnsetting->first();

                if (property_exists($allData, $item) || ($this->isDataModelBuilder && $allData->$item)) {
                    // return edited array
                    $dataFiltered[$item] = new ColumnBody(($columnsetting->value)($allData->$item), $columnsetting);
                } else {
                    // handle for row counter
                    if (str_starts_with($columnsetting->name, 'row')) {
                        $dataFiltered[$item] = new ColumnBody($counterRow++, $columnsetting);
                    } else {
                        $dataFiltered[$item] = new ColumnBody('', null);
                    }
                }
            }

            // remake array
            return $dataFiltered;
        };

        if($this->paginate)
            $out = $data->through($mappingFunction);
        else
            $out = $data->map($mappingFunction);

        return $out;
    }


    /**
     * filter data with searching text
     * */
    private function searchingData($data)
    {
        // if search text is empty, return data
        if ($this->queryParam->searchText == null)
            return $data;

        // get any fields that has searchable property
        $searchable_fields = $this->columns->where('searchable', true);

        // if any field isn't searchable, return data
        if ($searchable_fields->count() == 0)
            return $data;

        // filter for search character
        $data = $data->filter(function ($item) use ($searchable_fields) {
            foreach ($searchable_fields as $field) {
                if (!($field->name && $item->{$field->name}))
                    continue;

                if (stripos($item->{$field->name}, $this->queryParam->searchText) !== false)
                    return true;
            }
            return false;
        });

        // return searched data
        return $data;
    }


    /**
     * @return Collection|LengthAwarePaginator
     * */
    private function makeTableBody(): Collection|LengthAwarePaginator
    {
        // get field names
        $getFieldNames = $this->getFieldNames();
        $columnsGroupBy = $this->getColumnsGroupByFieldName();

        // if it's builder, get the data
        if ($this->isDataModelBuilder)
            $this->data = $this->data->get();

        // return empty when didn't set any columns
        if (count($this->columns) == 0)
            return collect([]);

        // get all data
        $out = $this->data;

        // searching on data if exists any field searchable
        $out = $this->searchingData($out);


        // set paginate to list
        $out = $this->paginate($out);



        // mapping on data to change
        $out = $this->mappingData($out, $getFieldNames, $columnsGroupBy);

        // return data
        return $out;
    }

    /**
     * @param string $paginateMethodRender
     * @return GetData
     */
    public function setPaginateMethodRender(string $paginateMethodRender): GetData
    {
        $this->paginateMethodRender = $paginateMethodRender;
        return $this;
    }

    /**
     * @param int $paginate
     * @return array
     */
    #[ArrayShape([
        'head' => [ColumnHeader::class],
        'body' => (Collection::class | LengthAwarePaginator::class),
        'exists' => 'bool'
    ])] public function build(int $paginate = 0): array
    {
        $this->paginate = $paginate;
        // get head table data
        $this->tableDataHead = $this->makeTableHead();
        // get body table data
        $this->tableDataBody = $this->makeTableBody();

        // return list of data
        return [
            'head' => $this->tableDataHead,
            'body' => $this->tableDataBody,
            'exists' => (bool)count($this->tableDataBody),
            'pagination' => $this->makePaginateButtons()
        ];
    }

}