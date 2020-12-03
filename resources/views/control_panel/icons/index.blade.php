@extends('control_panel.layouts.default')

@section('content')
    <div class="panel panel-default">
        <div class="panel-heading">
            <h3 class="panel-title">{{ trans('labels.icons') }}</h3>
        </div>
        <div class="panel-body" id="IconsIndexPage">
            @if(!$items->isEmpty())
                <div class="table-responsive">
                    <table class="table table-hover">
                        <tr>
                            <th>#</th>
                            <th>{{ trans('labels.title') }}</th>
                            <th>{{ trans('labels.icon') }}</th>
                            <th>{{ trans('labels.status') }}</th>
                            <th>{{ trans('labels.link') }}</th>
                            <th></th>
                        </tr>

                        @foreach($items as $i => $item)
                            <tr>
                                <td>{{ $i + 1 }}</td>
                                <td class="text-center">{!! $item->title_translated !!}</td>
                                <td>
                                    @if(!empty($item->iconImg))
                                        <img src="{{ $item->iconImg->thumbFullLink() }}"
                                             alt="{{ $item->title_default }}"
                                             class="img-thumbnail image_tiny"
                                        >
                                    @endif
                                </td>
                                <td>{!! $item->renderStatus() !!}</td>
                                <td>
                                    <a href="{{ $item->fullUrl() }}" title="{{ $item->title_default }}"
                                       target="_blank">{{ $item->link }}</a>
                                </td>
                                <td nowrap>
                                    <a href="{{ route('control_panel.icons.edit', $item->id) }}" class="btn btn-primary">
                                        <i class="fa fa-pencil" aria-hidden="true"></i>
                                    </a>
                                    <a href="javascript: void(0)"
                                       data-url="{{ route('control_panel.icons.destroy', $item->id) }}"
                                       class="btn btn-danger entity_delete_button" onclick="confirmDeleteEntity(this)"
                                    >
                                        <i class="fa fa-trash" aria-hidden="true"></i>
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </table>
                </div>
            @else
                <p>{{ trans('messages.no_entities', ['entities' => trans('labels.icons')]) }}</p>
            @endif
        </div>
    </div>
@stop

@section('js')
    <script>
        $('#IconsIndexPage .entity_delete_button').on('entity_delete', function(){
            location.reload();
        });
    </script>
@append