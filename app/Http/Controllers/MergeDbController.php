<?php
namespace App\Http\Controllers;

use Carbon\Carbon;
use DB;
use File;

class MergeDbController extends Controller {

    public function merge(){
        $albums = DB::connection('vadecom_ci')->table('gallery_cats')->get();

        $data = [];

        $now = Carbon::now();

        foreach($albums as $album) {
            $record = [
                'id' => $album->id,
                'name_ar' => $album->title,
                'name_en' => $album->title,
                'album_category_id' => 1,
                'status_ar' => 'active',
                'status_en' => 'active',
                'created_at' => $now,
                'updated_at' => $now,
            ];

            $data[] = $record;
        }

        DB::table('albums')->insert($data);
    }

    public function mergeImages()
    {
        $images = DB::connection('vadecom_ci')->table('gallery')->get();

        $data = [];

        foreach($images as $image) {
            if(empty($image->image) || empty($image->thumb)) continue;

            $imageFilename = rand(1111, 9999) . '_' . basename($image->image);
            $thumbFilename = rand(1111, 9999) . '_' . basename($image->thumb);

            if(file_exists(base_path('upload_2016-11-09/' . $image->image)) && file_exists(base_path('upload_2016-11-09/' . $image->thumb))) {
                $path = base_path('upload_2016-11-09/' . $image->image);
                $target = base_path('uploads/images/' . $imageFilename);

                File::copy($path, $target);

                $path = base_path('upload_2016-11-09/' . $image->thumb);
                $target = base_path('uploads/thumbs/' . $thumbFilename);

                File::copy($path, $target);
            }

            $album_id = null;
            if($image->sub_catid) {
                $album_id = $image->sub_catid;
            } elseif ($image->catid) {
                $album_id = $image->catid;
            }

            $now = Carbon::now();

            $record = [
                'id' => $image->id,
                'album_id' => $album_id,
                'original_filename' => $imageFilename,
                'image_filename' => $imageFilename,
                'thumb_filename' => $thumbFilename,
                'created_at' => $now,
                'updated_at' => $now,
            ];

            $data[] = $record;
        }

        DB::table('images')->insert($data);
    }

}