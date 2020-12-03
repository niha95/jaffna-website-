<?php
namespace App\Http\Controllers\ControlPanel;

use App\Models\Image;
use Illuminate\Http\Request;

class ImagesController extends ControlPanelController {

    protected $views_path = 'control_panel.images';

    public function showImagesManager()
    {
        return response()->json([
            'content' => view($this->viewPath('_images_manager'))->render(),
        ]);
    }

    public function dropzoneUploader(Request $request)
    {
        $uploaded_image = $request->file('uploaded_image');
        $album_id = $request->get('album_id');

        $image = new Image([
            'album_id' => $album_id,
        ]);

        $options = [];
        if($request->has('height')) $options['height'] = $request->get('height');

        if($request->get('keep_aspect_ratio') == "true") {
            $options['aspectRatio'] = true;
        } elseif (($width = $request->get('width')) != 0 && !empty($width)) {
            $options['aspectRatio'] = false;
            $options['width'] = $width;
        }

        $image->upload($uploaded_image, $options)->save();
        $image->thumb_url = $image->thumbFullLink();
        $image->image_url = $image->imageFullLink();

        return response()->json([
            'status' => 'success',
            'image' => $image->toArray(),
        ]);
    }

    public function edit($id)
    {
        $image = Image::findOrFail($id);

        $response = [
            'content' => view('control_panel.images._edit', ['image' => $image])->render(),
            'title' => trans('actions.edit_entity', ['entity' => trans('labels.image')]),
        ];

        return response()->json($response);
    }

    public function update($id, Request $request)
    {
        $image = Image::findOrFail($id);

        foreach(siteLocales() as $locale){
            $image->{'caption_' . $locale} = $request->get('caption_' . $locale);
        }

        $image->save();

        return response()->json([
            'status' => 'success',
        ]);
    }

    public function destroy(Request $request, $id)
    {
        Image::findOrFail($id)->delete();

        return response()->json([
            'status' => 'success'
        ]);
    }

}