@extends('nos.crud::layouts.app')
@section('title', trans(($path ?? '').'crud.'.Str::replace('-', '_', $componentName).'.title'))
@section('content')
    <{{ $componentName }}-index
    :selected="{{collect($selected)->toJson() }}"
    inline-template
    >
    <div>
        <div class="card">
            <div class="card-body">
                @include(($path ?? '').'admin.'.Str::replace('-', '_', (isset($viewPath))?$viewPath:Str::plural($componentName)).'.filters')
                <div class="d-flex justify-content-end flex-nowrap mt-1">
                    <div>
                        <button class="btn btn-warning" @click="clearFilters">
                            <i class="fas fa-recycle"></i>
                            @lang('crud.buttons.clear')
                        </button>
                        <button class="btn btn-primary" @click="getData(1)">
                            <i class="fas fa-search"></i>
                            @lang('crud.buttons.search')
                        </button>
                    </div>
                </div>
            </div>
        </div>
        <div class="card" :class="{ loading: loading }">
            <div class="card-body">
                <div class="row justify-content-between align-items-end">
                    <div class="col-auto">
                        <div class="text-right mb-3 text-muted">
                            @lang('crud.labels.found'): @{{ _.size(data.data) }} @lang('crud.labels.from') @{{
                            data.total }}
                        </div>
                    </div>
                    <div class="col-auto">
                        <div class="text-right mb-1 text-muted">
                            @yield('buttons')
                            @if(!isset($showCreateButton) || $showCreateButton === true)
                                <a class="btn btn-primary"
                                   href="{{ route(Str::replace('-', '_', (isset($viewPath))?$viewPath:Str::plural($componentName)).'.create') }}">
                                    @lang('crud.buttons.create')
                                </a>
                            @endif
                            @if(isset($showImportButton) && $showImportButton)
                                @include('nos.crud::import', ['route' => route(Str::replace('-', '_', Str::plural($componentName)).'.import')])
                            @endif

                        </div>
                    </div>
                </div>
                @include(($path ?? "").'admin.'.Str::replace('-', '_', (isset($viewPath))?$viewPath:Str::plural($componentName)).'.table')
            </div>
        </div>
    </div>
    </{{ $componentName }}-index>
@endsection
