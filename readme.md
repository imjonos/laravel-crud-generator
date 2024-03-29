# Laravel CRUD Generator

Generate:
Controller,
Tests,
Model,
Requests,
Views,
Languages,
VueJS,
Route,
Menu,
Factory,
Seed

Example: https://blog.toprogram.ru

Code example: https://github.com/imjonos/laravel-blog

![image](https://user-images.githubusercontent.com/5132976/208915773-ee66d11f-cc79-4f80-b58d-dd129f019606.png)

![image](https://user-images.githubusercontent.com/5132976/208915883-2eb49bfb-5e4c-4402-a5f5-cc6af5de8918.png)


## Installation

Via Composer

``` bash
$ composer require imjonos/laravel-crud-generator

```

## Usage

``` bash
$ php artisan crud:install
```

``` bash
$ php artisan crud:generate table_name [--route=admin] [--force=0] [--import=0] [--export=0]
```

Nos\CRUD\Traits\Multitenantable - you can use it on model

## Import/Export enable

Commands:

``` bash
$ ./artisan crud:generate table_name --import=1 --export=1
$ npm run dev/prod
```

Blade files:

``` bash
@include('vendor.nos.crud.import', ['route' => route('{{table_name}}.import')])
@include('vendor.nos.crud.export', ['route' => route('{{table_name}}.export')])
```

Controller

``` bash
use Nos\CRUD\Traits\{Importable, Exportable};
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

## File upload enable

```
Based on spatie laravel-medialibrary

Install: 
composer require spatie/laravel-medialibrary:~7.0.0
php artisan vendor:publish --provider="Spatie\MediaLibrary\MediaLibraryServiceProvider" --tag="migrations"
php artisan migrate

Model:

use Spatie\MediaLibrary\HasMedia\HasMedia;
use Spatie\MediaLibrary\HasMedia\HasMediaTrait;

class ModelExample extends Model implements HasMedia
{
    use Multitenantable, HasMediaTrait;
    protected $appends = ['media_collection'];

    /**
     * Return files
     * @return Array
     */
    public function getMediaCollectionAttribute():array
    {
        $this->getMedia();
        return [
            "name" => "MediaCollection",
            "files" => $this->media,
            "removedFiles" => []
        ];
    }
}    
```

```
View:

@component('nos.crud::fields.files', [
    'required' => 0
])
    @slot('label')
        @lang('crud.labels.files')
    @endslot
    @slot('vModel')
        form.media_collection
    @endslot
    @slot('name')
        PostMediaCollection
    @endslot
    @slot('placeholder')
        @lang('crud.labels.files_placeholder')
    @endslot
@endcomponent 
```

```
Controller:

use Nos\CRUD\Traits\FileUploadable;

public function update(UpdateRequest $request, Post $post)
    {
        $this->upload($request, $post);
        $post->update($request->all());
```

## Contributing

Please see [contributing.md](contributing.md) for details and a todolist.

## License

license. Please see the [license file](license.md) for more information.
