<?php

namespace Irpcpro\TableSoft\ServiceProviders;

use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Illuminate\Support\ServiceProvider;
use Irpcpro\TableSoft\TableSoft;

class TableSoftServiceProvider extends ServiceProvider
{

    public function register()
    {
        // add facade to app
        $this->app->bind('TableSoft', function () {
            return new TableSoft;
        });
    }

    public function boot()
    {
        // add paginate to illuminate support collection
        Collection::macro('paginateList', function ($perPage, $total = null, $page = null, $pageName = 'page') {
            $page = $page ?: LengthAwarePaginator::resolveCurrentPage($pageName);
            $results = $this->forPage($page, $perPage);
            $total = $total ?: $this->count();
            $options = [
                'path' => LengthAwarePaginator::resolveCurrentPath(),
                'pageName' => $pageName,
            ];
            return new LengthAwarePaginator($results, $total, $perPage, $page, $options);
        });

        // add package routes to system
        $this->loadRoutesFrom(__DIR__ . '/../Testing/Routes/routes.php');

        // add tag view
        $this->loadViewsFrom(__DIR__ . '/../Testing/Views', 'tableSoft');

        // publish file to project
        $this->publishes([
            __DIR__ . '/../Testing/Public' => public_path('/'),
            __DIR__ . '/../Testing/Migrations' => database_path('/migrations/'),
        ], 'Irpcpro-Publish');
    }

}
