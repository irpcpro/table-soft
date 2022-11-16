<h1>Table Soft</h1>
<h4>BugLoos TEST</h4>
<ul>
    <li>Author: <span>IRPCPRO</span></li>
    <li>Email: <span>designer.pcpro@yahoo.com</span></li>
</ul>
<p>A package for managing table data</p>
<hr>


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
