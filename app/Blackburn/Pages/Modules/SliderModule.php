<?php
namespace App\Blackburn\Pages\Modules;

use App\Models\Album;
use Illuminate\Support\Collection;

class SliderModule extends AbstractModule {

    public function renderView($locale)
    {
        $album = Album::whereId($this->data->albumId)->first();
        if(is_null($album)) return;

        $l = $this->getLocalizedData($locale);

        $slides = new Collection();
        foreach ($album->images as $image) {
            $slides->push((object) [
                'caption' => $image->caption_locale,
                'image_full_url' => $image->imageFullLink(),
                'thumb_full_url' => $image->thumbFullLink(),
            ]);
        }

        return view($this->getRenderingViewFile(), [
            'title' => $l->title,
            'slides' => $slides,
            'uid' => rand(1111, 9999)
        ])->render();
    }

    public function sanitizeData()
    {
        if (!$this->editing) return [];

        $album = Album::findOrFail($this->data['albumId']);

        $clean = [
            'album_id' => $album->id,
            'album_name' => $album->name_translated_piped,
        ];

        foreach($this->data['localized'] as $i) {
            $clean[$i['locale']] = [
                'title' => $i['title'],
            ];
        }

        return $clean;
    }

    public function addTranslations()
    {
        $this->translations['slider_translated'] = labelTranslated('pages.modules.slider');
    }
}