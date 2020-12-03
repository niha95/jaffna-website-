<?php
namespace App\Http\Controllers\ControlPanel;

use App\Models\Image;

class ApiController extends ControlPanelController {

    public function fetchImages()
    {
        $album_id = request('album_id');

        $images = Image::where('album_id', $album_id)->get();

        $images = $images->map(function ($i) {
            $image = $i->toArray();

            $image['image_url'] = route('images.show_image', $i->image_filename);
            $image['thumb_url'] = route('images.show_thumb', $i->thumb_filename);

            return $image;
        });

        return response()->json($images);
    }

}