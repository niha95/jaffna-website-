@extends('control_panel.layouts.default')

@section('content')
    <div class="panel panel-default">
        <div class="panel-heading">
            <h3 class="panel-title">{{ trans('labels.categories') }}</h3>
        </div>
        <div class="panel-body">
            @if(!$categories->isEmpty())
                <div class="table-responsive">
                    <table class="table table-hover">
                        <tr>
                            <th>#</th>
                            <th>{{ trans('labels.name') }}</th>
                            <th>{{ trans('labels.parent') }}</th>
                            <th>{{ trans('labels.pages_count') }}</th>
                            <th></th>
                        </tr>

                        @foreach($categories as $i => $category)
                            <tr>
                                <td class="text-center">{{ $i + 1 }}</td>
                                <td class="text-center">{!! $category->name_translated !!}</td>
                                <td class="text-center">
                                    @if($category->parent)
                                        <a href="{{ route('control_panel.pages_categories.edit', [$category->parent->slug]) }}">
                                            {!! @$category->parent->name_translated !!}

                                        </a>
                                    @endif
                                </td>
                                <td class="text-center">{{ $category->pages_count }}</td>
                                <td nowrap class="text-center">
                                    <a href="{{ $category->fullUrl() }}" class="btn btn-primary" target="_blank">
                                        <i class="fa fa-eye" aria-hidden="true"></i>
                                    </a>
                                    <a href="{{ route('control_panel.pages_categories.edit', $category->id) }}" class="btn btn-primary">
                                        <i class="fa fa-pencil" aria-hidden="true"></i>
                                    </a>
                                    <a href="javascript: void(0)"
                                       data-url="{{ route('control_panel.pages_categories.destroy', $category->id) }}"
                                       class="btn btn-danger" onclick="confirmDeleteEntity(this)"
                                    >
                                        <i class="fa fa-trash" aria-hidden="true"></i>
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </table>
                </div>

                {{ $categories->links() }}
            @else
                <p>{{ trans('messages.no_entities', ['entities' => trans('labels.categories')]) }}</p>
            @endif
        </div>
    </div>
@stop