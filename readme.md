# CRUD

Laravel CRUD Generator

Controller
Tests
Model
Requests
Views
Languages
VueJS
Route
Menu
Factory
Seed

## Installation

Via Composer

``` bash
$ composer require codersstudio/crud
```

## Usage
``` bash
$ php artisan crud:install
```

``` bash
$ php artisan crud:generate table_name [--route=admin] [--force=0] [--import=0] [--export=0]
```

CodersStudio\CRUD\Traits\Multitenantable - you can use it on model 

## Import/Export enable

Commands:
``` bash
$ ./artisan crud:generate table_name --import=1 --export=1
$ npm run dev/prod
```

Blade files:
``` bash
@include('vendor.codersstudio.crud.import', ['route' => route('{{table_name}}.import')])
@include('vendor.codersstudio.crud.export', ['route' => route('{{table_name}}.export')])
```

Controller 

``` bash
use CodersStudio\CRUD\Traits\{Importable, Exportable};
use App\Exports\PostsExport;
use App\Imports\PostsImport;

class PostController extends Controller
{
    use Importable, Exportable;

    public function getExportObject()
    {
        return new PostsExport();
    }

    public function getImportObject()
    {
        return new PostsImport();
    }
    
    ...
}  
```

## Contributing

Please see [contributing.md](contributing.md) for details and a todolist.


## License

license. Please see the [license file](license.md) for more information.
