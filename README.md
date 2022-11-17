<h1>Table Soft</h1>
<h4>BugLoos TEST</h4>
<ul>
    <li>Author: <span>IRPCPRO</span></li>
    <li>Email: <span>designer.pcpro@yahoo.com</span></li>
</ul>
<p>A package for managing table data</p>
<h2>Installing</h2>

----
Register the TableSoft service provider by adding it to the providers in `config/app.php` file.
```php
'providers' => [
    ...
    ...
    \Irpcpro\TableSoft\ServiceProviders\TableSoftServiceProvider::class,
]
```

If you want you can alias the TableSoft facade by adding it to the aliases in `config/app.php` file.
```php
'aliases' => Facade::defaultAliases()->merge([
    ...
    ...
    'TableSoft' => \Irpcpro\TableSoft\Facade\TableSoftFacade::class,
])->toArray(),
```

#Configurations

----
* Pass Collection or Builder data into the facade.
```php
use TableSoft;

class HomeController extends Controller {
    public function index(){
        // get data from collection
        $data = collect([ [..], [..], [..] ]);
        
        // Or ..
        
        // get data from models
        $data = App\Models\Product::query();
       
        // finally pass the data to TableSoft
        $table = TableSoft::data($data);
    }
}
```

<h3>create columns</h3>

---

for adding column to table:
```php
$table->column('Title') // default key name = Title
```
<h3>for get data from specific key name:</h3>

---
```php
$table->column('Title', 'columnTitle')
$table->column('Title', 'columnTitle:string') // default type column is string
```
can use these type of data:
<ul>
    <li>int</li>
    <li>string</li>
    <li>float</li>
    <li>date</li>
    <li>bool</li>
</ul>

<h3>for sorting data:</h3>

---
```php
$table->column('Title', 'columnTitle:string', 'sort') // default ASC
$table->column('Title', 'columnTitle:string', 'sort:asc')
```
can use these type of sorting data:
<ul>
    <li>asc</li>
    <li>desc</li>
</ul>

<h3>callback function for value</h3>

---
```php
$table->column('Price', 'price:int', 'sort', function($value){
    return $value . '$';
});
```
also use without sorting data
```php
$table->column('Price', 'price:int', function($value){
    return $value . '$';
});
```
the second (fieldName:type) parameter must be set

<h3>set searchable:</h3>

---
```php
$table->column('Price', 'price:int', function($value){
    return $value . '$';
})->searchable();
```
or set after define column:
```php
$table = $table->column('Price', 'price:int', function($value){
    return $value . '$';
});
$table->searchable();
```

<h3>set width for column:</h3>

---
```php
$table->setWidth(20);
$table->setWidth(20, 'px');
```
set measure in second parameter
<ul>
    <li>px</li>
    <li>%</li>
</ul>

<h3>set row counter automatically:</h3>

---
```php
$table->rowCounter('row', 'row-name:string', function($val){
    return $value;
});
```
<h5>Important: the field name should start with `sort-`</h5>

<h3>set paginate for list:</h3>

---
```php
$table->paginate(10);
```
if set 0 it will return all data. (without limitation)

<h3>set caching data:</h3>

---
```php
$table->setCaching('id-name-table');
```
`id-name-table` should be a specific and unique string for this table.

<h3>get data from service:</h3>

---
```php
$data = Http::get('https://...../products');
$data = collect($data->json());
```

<h3>for more:</h3>

---
```php
// get data
$data = Product::query();

// set table
$table = TableSoft::data($data);
$table = $table->column('Title', 'title:string', 'sort')->searchable();
$table = $table->column('Image', 'thumbnail:string', function($value){
    return "<img src='$value'/>";
});
$table = $table->column('Description', 'description:string', 'sort:asc')->searchable();
$table = $table->column('Price', 'price:int', 'sort', function($value){
    return $value . '$';
})->setWidth(50, 'px')->searchable();
$table = $table->rowCounter('row')->setWidth(20,'px');
$table = $table->setCaching('table-product4');
$table = $table->paginate(10);

// get table
$data = $table->get();
```

* the response have several controller for manage your table:

```php
array:5 [▼
  "head" => Illuminate\Support\Collection {#334 ▶}
  "body" => Illuminate\Pagination\LengthAwarePaginator {#339 ▶}
  "sort_fields" => Illuminate\Support\Collection {#316 ▶}
  "query_params" => array:3 [▶]
  "exists" => true
]
```

the data of `head` and `body` have same data structure:
```php
{
    +title: "Description"
    +name: "description"
    +type: "string"
    +sort: "sort"
    +sortBy: "asc"
    +value: "Description"
    +width: null
    +widthMeasure: null
    +searchable: true
}
```

<h3>here's a sample for show table in blade:</h3>

---
```php
<table class="table table-bordered">
    <thead>
        <tr>
            @foreach($data['head'] as $head)
                <th width="{{$head->width ? $head->width.$head->widthMeasure : ''}}">{{$head}}</th>
            @endforeach
        </tr>
    </thead>
    <tbody>
        @foreach($data['body'] as $body)
            <tr>
                @foreach($body as $item)
                    <td width="{{$item->width ? $item->width.$item->widthMeasure : ''}}">{!! $item !!}</td>
                @endforeach
            </tr>
        @endforeach
    </tbody>
</table>
```
