<?php

namespace Irpcpro\TableSoft\Facade;

use Illuminate\Support\Facades\Facade;

class TableSoftFacade extends Facade {
    protected static function getFacadeAccessor()
    {
        return 'TableSoft';
    }
}
