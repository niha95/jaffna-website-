<?php
namespace App\Http\Controllers\ControlPanel;

use App\Models\News;
use Illuminate\Http\Request;

class NewsController extends ControlPanelController {

    protected $views_path = 'control_panel.news';

    protected $currentPanel = 'news';

    public function __construct(Request $request)
    {
        $this->setCurrentPanel('news');

        parent::__construct($request);

        $this->addBreadcrumb(
            trans('labels.news'),
            'fa fa-list',
            route('control_panel.news.index')
        );
    }

    public function index()
    {
        $this->addBreadcrumbButton(
            trans('actions.add'),
            route('control_panel.news.create'),
            'fa fa-plus'
        );

        $news = News::paginate($this->perPage);

        return view($this->viewPath('index'), [
            'news' => $news,
        ]);
    }

    public function create()
    {
        $this->addBreadcrumb(
            trans('actions.create_entity', ['entity' => trans('labels.news')]),
            'fa fa-plus'
        );

        return view($this->viewPath('create'));
    }

    public function store(Request $request)
    {
        $input = $this->getInput();

        $this->validate($request, News::getRules());

        $news = new News($input);

        if ($news->save()) {
            flashMessage(trans('messages.entity_created', ['entity' => trans('labels.news')]));

            return $request->exists('save_and_continue')
                ? redirect()->route('control_panel.news.edit', [$news->id])
                : redirect()->route('control_panel.news.index');
        }

        flashMessage(trans('messages.request_failed'));
        return redirect()->back();
    }

    public function edit($id)
    {
        $this->addBreadcrumb(
            trans('actions.edit_entity', ['entity' => trans('labels.news')]),
            'fa fa-pencil'
        );

        $news = News::findOrFail($id);

        return view($this->viewPath('edit'), [
            'news' => $news,
        ]);
    }

    public function update($id, Request $request)
    {
        $news = News::findOrFail($id);

        $input = $this->getInput();

        $this->validate($request, News::getRules());

        $news->fill($input);

        if ($news->save()) {
            flashMessage(trans('messages.entity_edited', ['entity' => trans('labels.news')]));

            return $request->exists('save_and_continue')
                ? redirect()->route('control_panel.news.edit', [$news->id])
                : redirect()->route('control_panel.news.index');
        }

        flashMessage(trans('messages.request_failed'));
        return redirect()->back();
    }

    public function destroy($id)
    {
        $news = News::findOrFail($id);

        if ($news->delete()) {
            return response()->json([
                'status' => 'success',
                'message' => trans('messages.entity_deleted', ['entity' => trans('labels.news')])
            ]);
        }

        return response()->json([
            'status' => 'failed',
            'message' => trans('messages.request_failed')
        ]);
    }

    private function getInput()
    {
        $input = [];

        foreach (siteLocales() as $locale) {
            $input['title_' . $locale] = request('title_' . $locale);
            $input['link_' . $locale] = request('link_' . $locale);
            $input['status_' . $locale] = request('status_' . $locale);
        }

        return $input;
    }

}