<?php
namespace App\Http\Controllers;

class ImagesController extends Controller {

    public function showImage($image_filename) {
        $image = \ImageIntervention::make(IMAGE_PATH . $image_filename);

        return $this->createResponse($image);
    }

    public function showThumb($thumb_filename) {
        $thumb = \ImageIntervention::make(THUMB_PATH . $thumb_filename);

        return $this->createResponse($thumb);
    }

    private function createResponse($file) {

        $response = \Response::make($file->encode($file->mime));

        // set content-type
        $response->header('Content-Type', $file->mime);
        $response->header('Pragma', 'public');
        $response->header('Cache-Control', 'max-age=86400');
        $response->header('Expires', gmdate('D, d M Y H:i:s \G\M\T', time() + 86400));

        // output
        return $response;
    }

    public function ckeditorUploadImage() {

    }

}