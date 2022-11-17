<?php

namespace Irpcpro\TableSoft\Testing\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TableSetting extends Model
{
    use HasFactory;

    protected $table = 'tablesettings';

    protected $fillable = [
        'name',
        'fields',
        'caching',
        'paginate',
    ];

    public function getFieldsAttribute($value)
    {
        return json_decode($value);
    }

    public function setFieldsAttribute($value)
    {
        $this->attributes['fields'] = json_encode($value);
    }

}
