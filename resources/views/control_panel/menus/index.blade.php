@extends('control_panel.layouts.default')

@section('content')
    <div class="panel panel-default">
        <div class="panel-heading">
            <h3 class="panel-title">{{ trans('labels.menus') }}</h3>
        </div>
        <div class="panel-body" id="MenusIndexPage">
            @if(!$items->isEmpty())
                <div class="table-responsive">
                    <table class="table table-hover">
                        <tr>
                            <th>#</th>
                            <th>{{ trans('labels.title') }}</th>
                            @if(count(config('site.menus_positions')) > 1)
                                <th>{{ trans('labels.position') }}</th>
                            @endif
                            <th>{{ trans('labels.status') }}</th>
                            <th>{{ trans('labels.link') }}</th>
                            <th></th>
                            <th></th>
                        </tr>

                        <?php $parentsCount = $items->count() ?>
                        @foreach($items as $i => $item)
                            <tr>
                                <td>{{ $i + 1 }}</td>
                                <td class="text-center">{!! $item->title_translated !!}</td>
                                @if(count(config('site.menus_positions')) > 1)
                                    <td>{{ $item->getPosition() }}</td>
                                @endif
                                <td>{!! $item->renderStatus() !!}</td>
                                <td>
                                    <a href="{{ $item->fullUrl() }}" title="{{ $item->title_default }}"
                                       target="_blank">{{ $item->link }}</a>
                                </td>
                                <td nowrap>
                                    @if($item->hasSubMenus())
                                        <a href="javascript:void(0)" class="btn btn-primary"
                                           data-modal-title="{{ trans('labels.sub_menus') }}"
                                           data-toggle="modal" data-target="#SubMenusModalFor{{ $item->id }}"
                                        >
                                            <i class="fa fa-list" aria-hidden="true"></i>
                                        </a>
                                    @endif
                                    <a href="{{ route('control_panel.menus.edit', $item->id) }}" class="btn btn-primary">
                                        <i class="fa fa-pencil" aria-hidden="true"></i>
                                    </a>
                                    <a href="javascript: void(0)"
                                       data-url="{{ route('control_panel.menus.destroy', $item->id) }}"
                                       class="btn btn-danger entity_delete_button" onclick="confirmDeleteEntity(this)"
                                    >
                                        <i class="fa fa-trash" aria-hidden="true"></i>
                                    </a>
                                </td>
                                <td>{!! $item->reorderButtons($parentsCount) !!}</td>
                            </tr>
                        @endforeach
                    </table>
                </div>

                @foreach($items as $item)
                    @if($item->hasSubMenus())
                        <div class="modal fade" id="SubMenusModalFor{{ $item->id }}" tabindex="-1">
                            <div class="modal-dialog modal-lg" role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                        <h4 class="modal-title">{{ trans('labels.sub_menus') }}</h4>
                                    </div>
                                    <div class="modal-body">
                                        <div class="sub_menus">
                                            <table class="table table-hover">
                                                <tr>
                                                    <th>#</th>
                                                    <th>{{ trans('labels.title') }}</th>
                                                    <th>{{ trans('labels.status') }}</th>
                                                    <th>{{ trans('labels.link') }}</th>
                                                    <th></th>
                                                    <th></th>
                                                </tr>

                                                <?php $childrenCount = $item->subMenus->count(); ?>
                                                @foreach($item->subMenus as $j => $sub_item)
                                                    <tr>
                                                        <td>{{ $j + 1 }}</td>
                                                        <td class="text-center">{!! $sub_item->title_translated !!}</td>
                                                        <td>{!! $sub_item->renderStatus() !!}</td>
                                                        <td>
                                                            <a href="{{ $sub_item->fullUrl() }}" title="{{ $sub_item->title_default }}"
                                                               target="_blank">{{ $sub_item->link }}</a>
                                                        </td>
                                                        <td nowrap>
                                                            <a href="{{ route('control_panel.menus.edit', $sub_item->id) }}" class="btn btn-primary">
                                                                <i class="fa fa-pencil" aria-hidden="true"></i>
                                                            </a>
                                                            <a href="javascript: void(0)"
                                                               data-url="{{ route('control_panel.menus.destroy', $sub_item->id) }}"
                                                               class="btn btn-danger" onclick="confirmDeleteEntity(this)"
                                                            >
                                                                <i class="fa fa-trash" aria-hidden="true"></i>
                                                            </a>
                                                        </td>
                                                        <td>{!! $sub_item->reorderButtons($childrenCount) !!}</td>
                                                    </tr>
                                                @endforeach
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif
                @endforeach
            @else
                <p>{{ trans('messages.no_entities', ['entities' => trans('labels.items')]) }}</p>
            @endif
        </div>
    </div>
@stop

@section('js')
    <script>
        $('#MenusIndexPage .entity_delete_button').on('entity_delete', function(){
            location.reload();
        });
    </script>
@append