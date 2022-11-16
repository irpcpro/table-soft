<?php

namespace Irpcpro\TableSoft;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Illuminate\View\View;
use Irpcpro\TableSoft\Includes\Columns\DefineColumn;
use Irpcpro\TableSoft\Includes\Columns\ColumnHeader;
use Irpcpro\TableSoft\Includes\GetData;
use JetBrains\PhpStorm\ArrayShape;

class TableSoft
{

    /**
     * @var Collection|Builder
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
     * @var string
     * */
    private string $paginateMethodRender;
    /**
     * @var bool
     * */
    private bool $rowCounter = false;
    /**
     * @var int
     * */
    private int $lastColumnIndex = 0;

    public function __construct()
    {
        $this->paginateMethodRender = 'pagination::bootstrap-4';
    }

    /**
     * @param Collection|Builder $data
     * @return TableSoft
     * */
    public function data(Collection|Builder $data): TableSoft
    {
        // get type of $data. is Model-Collection or Collection
        if ($data instanceof Builder)
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
        // if field name is null, copy from title
        if ($field == null)
            $field = $title;

        // check field
        $type = 'string';
        $explodeField = explode(':', $field);
        if (isset($explodeField[1])) {
            if (!in_array($explodeField[1], ['int', 'string', 'float', 'date', 'bool']))
                return $this; // don't add this column
            $type = $explodeField[1];
        }
        $field = preg_replace('/[^A-Za-z0-9\-]/', '', mb_strtolower($explodeField[0])) . ':' . $type;

        // return this field. Input Error
        if (is_object($sort) && $function != [])
            return $this; // don't add this column

        // check if variable callback function is instead of sort
        if (is_object($sort)) {
            $function = $sort;
            $sort = null;
        }

        // check type of sorting if it isn't null
        if ($sort != null && gettype($sort) == 'string') {
            if (str_starts_with($sort, 'sort')) {
                $explodeSort = explode(':', $sort);
                if (isset($explodeSort[1])) {
                    if (!in_array($explodeSort[1], ['asc', 'desc']))
                        return $this; // don't add this column
                } else {
                    if ($sort == 'sort') {
                        $sort .= ':asc';
                    } else {
                        return $this; // don't add this column
                    }
                }
            } else {
                return $this; // don't add this column
            }
        }

        // add default closure if $function is null
        if ($function == null)
            $function = function ($value) {
                return $value;
            };

        // make column data
        $columnData = new DefineColumn($title, $field, $this->lastColumnIndex++, $sort, $function);

        // add column to array
        if ($addFirst)
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
        if (!empty($this->columns)) {
            if ($this->columns[0]->index == $this->lastColumnIndex - 1)
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
        if (!empty($this->columns)) {
            if ($this->columns[0]->index == $this->lastColumnIndex - 1)
                $this->columns[0]->setSearchableColumn();
            else
                end($this->columns)->setSearchableColumn();
        }
        return $this;
    }

    /**
     * @param int $number number of item per page. if 0 paginate never set
     * @param string $renderMethod
     * @return TableSoft
     * */
    public function paginate(int $number, string $renderMethod = 'pagination::bootstrap-4'): TableSoft
    {
        $this->paginate = $number;
        $this->paginateMethodRender = $renderMethod;
        return $this;
    }

    /**
     * set counter row to table
     * @return TableSoft
     * */
    public function rowCounter(string $title = 'Row', string $field = null, string|object $sort = null, object $function = null): TableSoft
    {
        // just add row counter once
        if ($this->rowCounter == false) {
            $this->rowCounter = true;

            // add column
            $this->column($title, $field, $sort, $function, true);
        }
        return $this;
    }

    #[ArrayShape([
        'head' => [ColumnHeader::class],
        'body' => Collection::class | LengthAwarePaginator::class,
        'exists' => 'bool',
        'sort' => Collection::class,
        'pagination' => ['string' | View::class]
    ])] public function get(): array
    {
        $build_data = new GetData($this->data, $this->columns, $this->isDataModelBuilder);
        $build_data = $build_data->setPaginateMethodRender($this->paginateMethodRender);
        $build_data = $build_data->build($this->paginate);
        return $build_data;
    }

}
