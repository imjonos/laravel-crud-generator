# CRUD

[![Latest Version on Packagist][ico-version]][link-packagist]
[![Total Downloads][ico-downloads]][link-downloads]
[![Build Status][ico-travis]][link-travis]
[![StyleCI][ico-styleci]][link-styleci]

This is where your description should go. Take a look at [contributing.md](contributing.md) to see a to do list.

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

##Search

Поиск
Массив $searchable
Поиск по связям
Пример:
Мы работаем с моделью User 

Первый параметр — название метода для связи.
Через точку можно перечислить несколько методов — posts.tags  и т. д.
Искать будем по последней связи, перечисленной через точку, в нашем примере поиск будет осуществляться по tags.
После знака : перечисляем через запятую поля из таблицы, по которой будет осуществляться поиск (title, body).
После знака | указываем тип поиска:
%like%
%like
like%
=
!=
>
<
>=
<=
in

Если ищем по дате, окончание request параметра должно быть на _from или _to (posts_created_at_from, posts_created_at_to)

Каждую новую цепочку связей перечисляем в новом элементе массива.

Для поиска по полю из текущей таблицы перечисляем просто название полей в массиве:
[
	„id“,
	„name“,
]

Структура базы
users
	id
	name

posts
	id
	title
	body
	user_id

tags
	id
	name

post_tag
	post_id
	tag_id

$searchable = [
	„id“,
	„name“,
	„posts:title,body|%like“,
	„posts.tags:name|=“
]

Название request параметра должно быть результатом конкатенации названий методов связи и поля из таблицы: posts_tags_name
posts_body
id
name


## Change log

Please see the [changelog](changelog.md) for more information on what has changed recently.

## Testing

``` bash
$ composer test
```

## Contributing

Please see [contributing.md](contributing.md) for details and a todolist.

## Security

If you discover any security related issues, please email author email instead of using the issue tracker.

## Credits

- [author name][link-author]
- [All Contributors][link-contributors]

## License

license. Please see the [license file](license.md) for more information.

[ico-version]: https://img.shields.io/packagist/v/codersstudio/crud.svg?style=flat-square
[ico-downloads]: https://img.shields.io/packagist/dt/codersstudio/crud.svg?style=flat-square
[ico-travis]: https://img.shields.io/travis/codersstudio/crud/master.svg?style=flat-square
[ico-styleci]: https://styleci.io/repos/12345678/shield

[link-packagist]: https://packagist.org/packages/codersstudio/crud
[link-downloads]: https://packagist.org/packages/codersstudio/crud
[link-travis]: https://travis-ci.org/codersstudio/crud
[link-styleci]: https://styleci.io/repos/12345678
[link-author]: https://github.com/codersstudio
[link-contributors]: ../../contributors

