<?php
namespace Irpcpro\TableSoft\Services;

use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Illuminate\Support\ServiceProvider;
use Irpcpro\TableSoft\TableSoft;

class TableSoftServiceProvider extends ServiceProvider {

    public function register()
    {
        // add facade
        $this->app->bind('TableSoft', function(){
            return new TableSoft;
        });
    }

    public function boot()
    {
        // add paginate to illuminate support collection
        Collection::macro('paginate', function($perPage, $total = null, $page = null, $pageName = 'page') {
            $page = $page ?: LengthAwarePaginator::resolveCurrentPage($pageName);
            return new LengthAwarePaginator(
                $this->forPage($page, $perPage),
                $total ?: $this->count(),
                $perPage,
                $page,
                [
                    'path' => LengthAwarePaginator::resolveCurrentPath(),
                    'pageName' => $pageName,
                ]
            );
        });
    }

}
