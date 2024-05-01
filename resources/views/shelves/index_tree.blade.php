@extends('layouts.tri')

@section('body')
    @include('shelves.parts.list', ['shelves' => $shelves, 'view' => $view, 'listOptions' => $listOptions])
@stop



@section('left')
        <div id="recents" class="mb-xl">
            @include('entities.list_tree', ['entities' => $left_space, 'style' => 'compact'])
        </div>
   

    <!-- <div id="popular" class="mb-xl">
        <h5>{{ trans('She') }}</h5>
        @if(count($popular) > 0)
            @include('entities.list', ['entities' => $popular, 'style' => 'compact'])
        @else
            <p class="text-muted pb-l mb-none">{{ trans('entities.shelves_popular_empty') }}</p>
        @endif
    </div>

    <div id="new" class="mb-xl">
        <h5>{{ trans('entities.shelves_new') }}</h5>
        @if(count($new) > 0)
            @include('entities.list', ['entities' => $new, 'style' => 'compact'])
        @else
            <p class="text-muted pb-l mb-none">{{ trans('entities.shelves_new_empty') }}</p>
        @endif
    </div> -->
@stop

@section('right')

    <div class="actions mb-xl">
        <h5>{{ trans('common.actions') }}</h5>
        <div class="icon-list text-link">
            @if(userCan('bookshelf-create-all'))
                <a href="{{ url("/create-shelf") }}" data-shortcut="new" class="icon-list-item">
                    <span>@icon('add')</span>
                    <span>{{ trans('entities.shelves_new_action') }}</span>
                </a>
            @endif

            @include('entities.view-toggle', ['view' => $view, 'type' => 'bookshelves'])

            <a href="{{ url('/tags') }}" class="icon-list-item">
                <span>@icon('tag')</span>
                <span>{{ trans('entities.tags_view_tags') }}</span>
            </a>
        </div>
    </div>

@stop