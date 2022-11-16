<?php

namespace Irpcpro\TableSoft\Includes\QueryParams;

use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use JetBrains\PhpStorm\ArrayShape;

class QueryParams
{
    /**
     * @var Request
     * */
    private Request $request;
    /**
     * @var string|null
     * */
    public string|null $searchText;
    /**
     * @var int
     * */
    public int $currentPage = 0;
    /**
     * @var Collection
     * */
    public Collection $sort;

    public function __construct()
    {
        // default parameters
        $this->sort = collect([]);
        $this->request = request();

        // set properties
        $this->setSearchText();
        $this->setCurrentPage();
        $this->setSort();
    }

    // search text setter by request param
    private function setSearchText(): void
    {
        $this->searchText = clean_text($this->request->input('q'));
    }

    // current page setter by request param
    private function setCurrentPage(): void
    {
        $this->currentPage = clean_text(intval($this->request->input('page', 0)));
    }

    // get all sort query param and add to array property
    private function setSort(): void
    {
        $allParams = collect($this->request->all());
        if($allParams->count()){
            $allParams = $allParams->filter(function($value, $key){
                // check key should start with (sort-)
                $ifExistsSort = str_starts_with($key,'sort-');
                if($ifExistsSort === false)
                    return false;

                // check the second parameter, field name should exist
                $getFieldName = explode('-', $key);
                if(!isset($getFieldName[1]) || ($getFieldName[1] == ''))
                    return false;

                // check value of sorting
                if(!in_array($value, ['asc','desc','none']))
                    return false;

                // return parameters as sort
                return true;
            });
            if($allParams->count()){
                $this->sort = $allParams->map(function($value, $key){
                    $getField = explode('-', $key);
                    return [
                        'key' => clean_text($key),
                        'value' => clean_text($value),
                        'field' => $getField[1] ?? '',
                    ];
                });
            }
        }
    }

    /**
     * public access params
     * @return array
     * */
    #[ArrayShape([
        'searchText' => "null|string",
        'currentPage' => "int",
        'sort' => Collection::class
    ])] public function getAllParams(): array
    {
        return [
            'searchText' => $this->searchText,
            'currentPage' => $this->currentPage,
            'sort' => $this->sort
        ];
    }
}
