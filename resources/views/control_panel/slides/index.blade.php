@extends('control_panel.layouts.default')

@section('content')
    <div class="panel panel-default">
        <div class="panel-heading">
            <h3 class="panel-title">{{ trans('labels.slides') }}</h3>
        </div>
        <div class="panel-body">
            @if(!$slides->isEmpty())
                <div class="table-responsive">
                    <table class="table table-hover">
                        <tr>
                            <th>#</th>
                            <th>{{ trans('labels.title') }}</th>
                            <th>{{ trans('labels.image') }}</th>
                            <th>{{ trans('labels.status') }}</th>
                            <th>{{ trans('labels.link') }}</th>
                            <th></th>
                            <th></th>
                        </tr>

                        @foreach($slides as $i => $slide)
                            <tr>
                                <td>{{ $i + 1 }}</td>
                                <td class="text-center">{!! $slide->title_translated !!}</td>
                                <td>
                                    @if($slide->image)
                                        <img src="{{ $slide->image->thumbFullLink() }}"
                                             alt="{{ $slide->title_translated_piped }}"
                                             class="img-thumbnail image_tiny"
                                        >
                                    @endif
                                </td>
                                <td>{!! $slide->renderStatus() !!}</td>
                                <td>
                                    <a href="{{ $slide->fullLink() }}"
                                       title="{{ $slide->title_default }}"
                                       target="_blank"
                                    >
                                        {{ $slide->link }}
                                    </a>
                                </td>
                                <td nowrap>
                                    <a href="{{ route('control_panel.slides.edit', $slide->id) }}" class="btn btn-primary">
                                        <i class="fa fa-pencil" aria-hidden="true"></i>
                                    </a>
                                    <a href="javascript: void(0)" data-url="{{ route('control_panel.slides.destroy', $slide->id) }}"
                                       class="btn btn-danger" onclick="confirmDeleteEntity(this)"
                                    >
                                        <i class="fa fa-trash" aria-hidden="true"></i>
                                    </a>
                                </td>
                                <td nowrap>
                                    {!! $slide->reorderButtons($slides->count()) !!}
                                </td>
                            </tr>
                        @endforeach
                    </table>
                </div>

                {{ $slides->links() }}
            @else
                <p>{{ trans('messages.no_entities', ['entities' => trans('labels.slides')]) }}</p>
            @endif
        </div>
    </div>
@stop