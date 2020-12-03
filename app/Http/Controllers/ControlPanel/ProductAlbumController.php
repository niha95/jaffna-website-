<?php
namespace App\Http\Controllers\ControlPanel;

use App\Models\ProductAlbum;
use Illuminate\Http\Request;

class ProductAlbumController extends ControlPanelController {

    

    public function showImagesManager()
    {
        return response()->json([
            'content' => view($this->viewPath('_images_manager'))->render(),
        ]);
    }

     public function dropzoneUploader(Request $request)
    {
        $uploaded_image = $request->file('uploaded_image');
        $product_id = $request->get('product_id');

        $image = new ProductAlbum([
            'product_id' => product_id,
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
        $image->image_url_relative = $image->imageFullLink(true);

        return response()->json([
            'status' => 'success',
            'image' => $image->toArray(),
        ]);
    }

    public function edit($id)
    {
        $image = ProductAlbum::findOrFail($id);

        $response = [
            'content' => view('control_panel.images._edit', ['image' => $image])->render(),
            'title' => trans('actions.edit_entity', ['entity' => trans('labels.image')]),
        ];

        return response()->json($response);
    }

    public function update($id, Request $request)
    {
        $image = ProductAlbum::findOrFail($id);

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
        ProductAlbum::findOrFail($id)->delete();

        return response()->json([
            'status' => 'success'
        ]);
    }

}