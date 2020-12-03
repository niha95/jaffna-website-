<?php
namespace App\Blackburn\Traits;

use Symfony\Component\HttpFoundation\File\UploadedFile;

trait ImageUploadTrait {

    protected $image;
    protected $upload_options = [
        'aspectRatio' => true,
        'upsize' => true,
    ];

    public function upload(UploadedFile $uploaded_image, $options = [])
    {
        $this->image['uploaded_file'] = $uploaded_image;
        $this->image['image_file'] = \ImageIntervention::make($uploaded_image);
        $this->upload_options = array_merge($this->upload_options, $options);

        $this->processImage()->generateWatermark()->saveImageFile()->generateThumb();

        return $this;
    }

    public function processImage()
    {
        $method = isset($this->upload_options['method']) ? $this->upload_options['method'] : 'resize';
        $width = isset($this->upload_options['width']) ? $this->upload_options['width'] : 1024;
        $height = isset($this->upload_options['height']) ? $this->upload_options['height'] : null;

        $this->image['image_file']->{$method}($width, $height, function ($constraint) {

            if (isset($this->upload_options['aspectRatio']) && $this->upload_options['aspectRatio'] != false)
                $constraint->aspectRatio();

            if (isset($this->upload_options['upsize']) && $this->upload_options['aspectRatio'] != false)
                $constraint->upsize();

        });

        return $this;
    }

    public function generateThumb()
    {
        $width = isset($this->upload_options['thumb_width']) ? $this->upload_options['thumb_width'] : 400;
        $height = isset($this->upload_options['thumb_height']) ? $this->upload_options['thumb_height'] : 400;

        $this->image['thumb_file'] = $this->image['image_file'];

        $this->image['thumb_file']->fit($width, $height);

        $this->image['thumb_file']->save(THUMB_PATH . $this->getNewThumbFilename());

        return $this;
    }

    public function generateWatermark()
    {
        if (!isset($this->upload_options['watermark'])) return $this;

        if(isset($this->upload_options['watermark']['url'])) {
            $insert = $this->upload_options['watermark']['url'];
        } elseif(is_file(public_path('assets/site/images/logo.png'))) {
            $insert = public_path('assets/site/images/logo.png');
        } else {
            return $this;
        }

        $position = isset($this->upload_options['watermark']['position'])
            ? $this->upload_options['watermark']['position']
            : 'top-left';

        $x = isset($this->upload_options['watermark']['x']) ? $this->upload_options['watermark']['x'] : 5;

        $y = isset($this->upload_options['watermark']['y']) ? $this->upload_options['watermark']['y'] : 5;

        $this->image['image_file']->insert($insert, $position, $x, $y);

        return $this;
    }

    public function saveImageFile()
    {
        $this->setOriginalFilename();

        $this->image['image_file']->save(IMAGE_PATH . $this->getNewImageFilename());

        return $this;
    }

    protected function setOriginalFilename()
    {
        $this->original_filename = $this->image['uploaded_file']->getClientOriginalName();
    }

    protected function getNewImageFilename()
    {
        $this->image['image_filename']
            = $this->image_filename
            = sha1(time() . pathinfo($this->image['uploaded_file']->getClientOriginalName(), PATHINFO_FILENAME))
            . '.'
            . pathinfo($this->image['uploaded_file']->getClientOriginalName(), PATHINFO_EXTENSION);

        return $this->image_filename;
    }

    protected function getNewThumbFilename()
    {
        $this->image['thumb_filename']
            = $this->thumb_filename
            = 'thumb_' . $this->image['image_filename'];

        return $this->thumb_filename;
    }
}