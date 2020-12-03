<?php
namespace App\Http\Controllers\ControlPanel;

use App\Http\Requests\ControlPanel\SlideRequest;
use App\Models\Activity;
use App\Models\Slide;
use Illuminate\Http\Request;

class SlidesController extends ControlPanelController {

    protected $views_path = 'control_panel.slides';

    public function __construct(Request $request)
    {
        $this->setCurrentPanel('slides');

        parent::__construct($request);

        $this->addBreadcrumb(
            trans('labels.slides'),
            'fa fa-list',
            route('control_panel.slides.index')
        );
    }

    public function index()
    {
        $this->addBreadcrumbButton(
            trans('actions.add'),
            route('control_panel.slides.create'),
            'fa fa-plus'
        );

        $slides = Slide::with('image')->orderBy('order', 'asc')
            ->paginate($this->perPage);

        return view($this->viewPath('index'), [
            'slides' => $slides,
        ]);
    }

    public function create()
    {
        $this->addBreadcrumb(
            trans('actions.create_entity', ['entity' => trans('labels.slide')]),
            'fa fa-plus'
        );

        return view($this->viewPath('create'));
    }

    public function store(SlideRequest $request)
    {
        $input = $this->getInput($request);

        $slide = new Slide($input);
        if ($input['image']) $slide->addImage($input['image']);

        if ($slide->save()) {
            Activity::log([
                'message' => 'created_entity',
                'entity' => 'labels.slide'
            ], route('control_panel.slides.edit', [$slide->id], false));

            flashMessage(trans('messages.entity_created', ['entity' => trans('labels.slide')]));

            return $request->exists('save_and_continue')
                ? redirect()->route('control_panel.slides.edit', [$slide->id])
                : redirect()->route('control_panel.slides.index');
        }

        flashMessage(trans('messages.request_failed'), 'danger');
        return redirect()->back();
    }

    public function edit($id)
    {
        $this->addBreadcrumb(
            trans('actions.edit_entity', ['entity' => trans('labels.slide')]),
            'fa fa-pencil'
        );

        $slide = Slide::findOrFail($id)->load('image');

        return view($this->viewPath('edit'), [
            'slide' => $slide,
        ]);
    }

    public function update($id, SlideRequest $request)
    {
        $slide = Slide::findOrFail($id);

        $input = $this->getInput($request);

        $slide->fill($input);
        if ($input['image']) $slide->addImage($input['image']);

        if ($slide->save()) {
            Activity::log([
                'message' => 'edited_entity',
                'entity' => 'labels.slide'
            ], route('control_panel.slides.edit', [$slide->id], false));

            flashMessage(trans('messages.entity_edited', ['entity' => trans('labels.slide')]));

            return $request->exists('save_and_continue')
                ? redirect()->route('control_panel.slides.edit', [$slide->id])
                : redirect()->route('control_panel.slides.index');
        }

        flashMessage(trans('messages.request_failed'));
        return redirect()->back();
    }

    public function destroy($id)
    {
        $slide = Slide::findOrFail($id);

        if($slide->delete()) {
            Activity::log([
                'message' => 'deleted_entity',
                'entity' => 'labels.slide'
            ]);

            return response()->json([
                'status' => 'success',
                'message' => trans('messages.entity_deleted', ['entity' => trans('labels.slide')])
            ]);
        }

        return response()->json([
            'status' => 'failed',
            'message' => trans('messages.request_failed')
        ]);
    }

    public function moveUp($id)
    {
        $item = Slide::findOrFail($id);

        if($item->moveUp()){
            flashMessage(trans('messages.entity_edited', ['entity' => trans('labels.slide')]));
            return redirect()->back();
        }

        flashMessage(trans('messages.request_failed'), 'danger');
        return redirect()->back();
    }

    public function moveDown($id)
    {
        $item = Slide::findOrFail($id);

        if($item->moveDown()){
            flashMessage(trans('messages.entity_edited', ['entity' => trans('labels.slide')]));
            return redirect()->back();
        }

        flashMessage(trans('messages.request_failed'), 'danger');
        return redirect()->back();
    }

    protected function getInput($request = null)
    {
        if(is_null($request)) $request = request();

        foreach (config('site.locales') as $locale) {
            $input['title_' . $locale] = $request->get('title_' . $locale);
            $input['content_' . $locale] = $request->get('content_' . $locale);
            $input['status_' . $locale] = $request->get('status_' . $locale);
        }

        $input['link'] = $request->get('link');
        $input['image'] = $request->file('image');

        return $input;
    }
}