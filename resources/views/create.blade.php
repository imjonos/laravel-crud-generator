@extends('nos.crud::layouts.app')
@section('page-style')
    {{-- Page Css files --}}
@endsection
@section('title', trans(($path ?? '').'crud.'.Str::replace('-', '_',$componentName).'.title'))
@section('content')
    <{{ $componentName }}-create inline-template>
    <div>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item">
                    <a href="{{ route(Str::replace('-', '_', (isset($viewPath))?$viewPath:Str::plural($componentName)).'.index') }}">
                        {{ trans(($path ?? '').'crud.'.Str::replace('-', '_', $componentName).'.title') }}
                    </a>
                </li>
                <li class="breadcrumb-item active" aria-current="page">@lang('crud.buttons.create')</li>
            </ol>
        </nav>
        <div class="card">
            <div class="card-header">
                @lang('crud.buttons.create')
            </div>
            <div class="card-body">
                <div class="row justify-content-center">
                    <div class="col-md-8">
                        {{ $inputs }}
                        <div class="text-right mt-1">
                            <button class="btn btn-primary" @click="store">
                                <i v-if="loading" class="fas fa-pulse fa-spinner"></i>
                                @lang('crud.buttons.save')
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    </{{ $componentName }}-create>
@endsection
