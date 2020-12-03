<?php
namespace App\Http\Controllers\ControlPanel;

use App\Blackburn\Filters\AlbumFilters;
use App\Models\AlbumCategory;
use App\Models\Image;
use Zipper;
use App\Models\Album;
use Illuminate\Http\Request;

class AlbumsController extends ControlPanelController {

    protected $views_path = 'control_panel.albums';

    public function __construct(Request $request)
    {
        $this->setCurrentPanel('albums');

        $this->composeForm();

        parent::__construct($request);

        $this->addBreadcrumb(
            trans('labels.albums'),
            'fa fa-list',
            route('control_panel.albums.index')
        );
    }

    public function index(AlbumFilters $filters)
    {
        
        //dd( defaultLocale());
        $this->addBreadcrumbButton(
            trans('actions.add'),
            route('control_panel.albums.create'),
            'fa fa-plus'
        );

        $albums = Album::with('category', 'imagesCountRelation', 'latestImages')
            ->filter($filters)
            ->latest()
            ->paginate($this->perPage);

        $albums->appends($filters->queryString());

       return view($this->viewPath('index'), [
            'albums' => $albums,
            'filters_as_string' => $filters->toString(),
            'categories' => AlbumCategory::listCategories(),
            'orders' => Album::availableOrders(),
        ]);
    }

    public function create()
    {
        $this->addBreadcrumb(
            trans('actions.create_entity', ['entity' => trans('labels.album')]),
            'fa fa-plus'
        );

        return view($this->viewPath('create'));
    }

    public function store(Request $request)
    {
        $input = $this->getInput();

        $this->validate($request, Album::getRules());

        $album = new Album($input);

        if ($album->save()) {
            flashMessage(trans('messages.entity_created', ['entity' => trans('labels.album')]));

            return $request->exists('save_and_continue')
                ? redirect()->route('control_panel.albums.edit', [$album->id])
                : redirect()->route('control_panel.albums.index');
        }

        flashMessage(trans('messages.request_failed'));
        return redirect()->back();
    }

    public function edit($id)
    {
        $this->addBreadcrumb(
            trans('actions.edit_entity', ['entity' => trans('labels.album')]),
            'fa fa-pencil'
        );

        $album = Album::findOrFail($id)->load('images');

        return view($this->viewPath('edit'), [
            'album' => $album,
        ]);
    }

    public function update($id, Request $request)
    {
        $album = Album::findOrFail($id);

        $input = $this->getInput();

        $this->validate($request, Album::getRules());

        $album->fill($input);

        if ($album->save()) {
            flashMessage(trans('messages.entity_edited', ['entity' => trans('labels.album')]));

            return $request->exists('save_and_continue')
                ? redirect()->route('control_panel.albums.edit', [$album->id])
                : redirect()->route('control_panel.albums.index');
        }

        flashMessage(trans('messages.request_failed'));
        return redirect()->back();
    }

    public function destroy($id)
    {
        $album = Album::findOrFail($id);

        if($album->delete()) {
            return response()->json([
                'status' => 'success',
                'message' => trans('messages.entity_deleted', ['entity' => trans('labels.album')])
            ]);
        }

        return response()->json([
            'status' => 'failed',
            'message' => trans('messages.request_failed')
        ]);
    }

    public function downloadAllImages($id)
    {
        $album = Album::findOrFail($id);

        $filename = time() . '_' . $album->name . '.zip';

        $zipper = Zipper::make(ZIP_PATH . $filename);

        $album->images->map(function ($image) use ($zipper) {
            $zipper->add(IMAGE_PATH . $image->image_filename);
        });

        $zipper->close();

        $file_path = ZIP_PATH . $filename;

        while (!file_exists($file_path)) sleep(1);
        return response()->download($file_path);
    }

    protected function composeForm()
    {
        view()->composer($this->viewPath('_form'), function($view){
            $categories = AlbumCategory::listCategories();

            $view->with([
                'categories' => $categories,
            ]);
        });
    }

    protected function getInput()
    {
        $input['album_category_id'] = request('album_category_id');

        foreach (config('site.locales') as $locale) {
            $input['name_' . $locale] = request()->get('name_' . $locale);
            $input['description_' . $locale] = request()->get('description_' . $locale);
            $input['status_' . $locale] = request()->get('status_' . $locale);
        }

        return $input;
    }
}