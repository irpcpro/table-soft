<?php

namespace Irpcpro\TableSoft\Includes\QueryParams;

use Illuminate\Http\Request;

class QueryParams
{
    /**
     * @var Request
     * */
    private $request;
    /**
     * @var string|null
     * */
    public string|null $searchText;
    /**
     * @var int
     * */
    public int $currentPage = 0;

    public function __construct()
    {
        $this->request = request();
        $this->setSearchText();
        $this->setCurrentPage();
    }

    // search text setter by request param
    private function setSearchText(): void
    {
        $this->searchText = $this->request->input('q');
    }

    // current page setter by request param
    private function setCurrentPage(): void
    {
        $this->currentPage = intval($this->request->input('page', 0));
    }

}
