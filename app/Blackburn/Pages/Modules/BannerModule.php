<?php
namespace App\Blackburn\Pages\Modules;

class BannerModule extends AbstractModule {

    public function renderView($locale)
    {
        $banner = $this->getLocalizedData($locale);

        return view($this->getRenderingViewFile(), [
            'banner' => $banner
        ])->render();
    }

    public function sanitizeData()
    {
        if (!$this->editing) return [];

        $clean = [];

        foreach($this->data['localized'] as $i) {
            $clean[$i['locale']] = [
                'title' => $i['title'],
                'content' => $i['content'],
                'link' => $i['link']
            ];
        }

        return $clean;
    }

    public function addTranslations()
    {
        $this->translations['banner_translated'] = labelTranslated('pages.modules.banner');
    }

}