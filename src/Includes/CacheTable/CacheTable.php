<?php

namespace Irpcpro\TableSoft\Includes\CacheTable;

use Irpcpro\TableSoft\Includes\QueryParams\QueryParams;

class CacheTable
{

    /**
     * @var QueryParams
     * */
    private QueryParams $queryParameters;
    /**
     * @var string
     * */
    private string $tableCacheID;
    /**
     * @var string
     * */
    private string $cacheKey;
    /**
     * caching data for 30 minutes
     * @var int seconds
     * */
    private const CACHING_TIME = 60 * 1;


    public function __construct(string $tableCacheID, QueryParams $queryParameters)
    {
        $this->tableCacheID = $tableCacheID;
        $this->queryParameters = $queryParameters;
        $this->setCacheKey();
        return $this;
    }

    /**
     * @return string
     * */
    private function getSearchText(): string
    {
        return (empty($this->queryParameters->searchText))? '-' : $this->queryParameters->searchText;
    }

    /**
     * @return string
     * */
    private function getCurrentPage(): string
    {
        return (string)($this->queryParameters->currentPage ?? 0);
    }

    /**
     * @return string
     * */
    private function getSorts(): string
    {
        // if sorting parameters are empty
        if($this->queryParameters->sort->count() == 0)
            return '-';

        $collectText = '';
        $i = 0;
        // create sorting string map
        foreach ($this->queryParameters->sort as $item) {
            $collectText .= ($i++ == 0? '' : '+') . $item['field'] . '=' . $item['value'];
        }

        return $collectText;
    }

    /**
     * @return void
     */
    private function setCacheKey(): void
    {
        $getSorts = $this->getSorts();
        $getSearchText = $this->getSearchText();
        $getCurrentPage = $this->getCurrentPage();
        $cacheKey = [
            'cahe-id:' . $this->tableCacheID,
            'sorts:' . $getSorts,
            'search-text:' . $getSearchText,
            'current-page:' . $getCurrentPage
        ];
        $this->cacheKey = join('&',$cacheKey);
    }

    public function get()
    {
        return cache()->get($this->cacheKey);
    }

    public function save($data)
    {
        cache([$this->cacheKey => $data], $this::CACHING_TIME);
    }
}
