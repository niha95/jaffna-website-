<?php
namespace App\Blackburn\Pages\Modules;

class YoutubeVideoModule extends AbstractModule {

    public function renderView($locale)
    {
        foreach ($this->data->localized as $i) {
            if ($i->locale != $locale) continue;

            return view($this->getRenderingViewFile(), [
                'title' => $i->title,
                'link' => $this->generateEmbedLink()
            ])->render();
        }
    }

    protected function generateEmbedLink()
    {
        $search = '#(.*?)(?:href="https?://)?(?:www\.)?(?:youtu\.be/|youtube\.com(?:/embed/|/v/|/watch?.*?v=))([\w\-]{10,12}).*#x';
        $replace = 'http://www.youtube.com/embed/$2';
        return preg_replace($search, $replace, $this->data->link);
    }

    protected function sanitizeData()
    {
        if (!$this->editing) return [];

        $clean = [];

        foreach ($this->data['localized'] as $i) {
            $clean['m__title_' . $i['locale']] = $i['title'];
        }

        $clean['m__link'] = $this->data['link'];

        return $clean;
    }

}