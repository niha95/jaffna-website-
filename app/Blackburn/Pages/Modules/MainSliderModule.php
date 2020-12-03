<?php
namespace App\Blackburn\Pages\Modules;

use App\Models\Slide;

class MainSliderModule extends AbstractModule {

    protected static $slides = null;

    public function renderView($locale)
    {
        if (is_null(static::$slides)) {
            $this->loadSlides();
        }

        return view($this->getRenderingViewFile(), [
            'slides' => static::$slides,
            'uid' => rand(1000, 9999)
        ])->render();
    }

    public function loadSlides()
    {
        static::$slides = Slide::with('image')->active(app()->getLocale())->get();
    }


    public function sanitizeData()
    {
        return [];
    }

    public function addTranslations()
    {
        $this->translations['main_slider_translated'] = labelTranslated('pages.modules.main_slider');
    }
}