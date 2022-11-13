<?php
namespace Irpcpro\TableSoft\Services;

use Illuminate\Support\ServiceProvider;
use Irpcpro\TableSoft\TableSoft;

class TableSoftServiceProvider extends ServiceProvider {

    public function register()
    {
        $this->app->bind('TableSoft', function(){
            return new TableSoft;
        });
    }

}
