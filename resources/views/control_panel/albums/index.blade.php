@extends('control_panel.layouts.default')

@section('content')
    @include('control_panel.albums._filters')

    <div class="panel panel-default">
        <div class="panel-heading">
            <h3 class="panel-title">{{ trans('labels.slides') }}</h3>
        </div>
        <div class="panel-body">
            @if(!$albums->isEmpty())
                <div class="table-responsive">
                    <table class="table table-hover">
                        <tr>
                            <th>#</th>
                            <th>{{ trans('labels.name') }}</th>
                            <th>{{ trans('labels.category') }}</th>
                            <th>{{ trans('labels.images_count') }}</th>
                            <th>{{ trans('labels.latest_images') }}</th>
                            <th>{{ trans('labels.status') }}</th>
                            <th></th>
                        </tr>

                        @foreach($albums as $i => $album)
                            <tr>
                                <td>{{ $i + 1 }}</td>
                                <td class="text-center">{!! $album->name_translated !!}</td>
                               
                                <td>{{ $album->images_count }}</td>
                                <td>
                                    @foreach($album->latestImages as $image)
                                        <img src="{{ $image->thumbFullLink() }}"
                                             alt="{{ $image->original_filename }}"
                                             class="img-thumbnail image_tiny">
                                    @endforeach
                                </td>
                                <td>{!! $album->renderStatus() !!}</td>
                                <td nowrap>
                                    <a href="{{ route('control_panel.albums.edit', $album->id) }}" class="btn btn-primary">
                                        <i class="fa fa-pencil" aria-hidden="true"></i>
                                    </a>
                                    <a href="javascript: void(0)" data-url="{{ route('control_panel.albums.destroy', $album->id) }}"
                                       class="btn btn-danger" onclick="confirmDeleteEntity(this)"
                                    >
                                        <i class="fa fa-trash" aria-hidden="true"></i>
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </table>
                </div>

                {{ $albums->links() }}
            @else
                <p>{{ trans('messages.no_entities', ['entities' => trans('labels.albums')]) }}</p>
            @endif
        </div>
    </div>
@stop