<?php
namespace App\Blackburn\Pages\Modules;

use App\Models\News;

class NewsModule extends AbstractModule {

    static protected $news = null;

    public function renderView($locale)
    {
        $news = $this->getNews();

        $data = [
            'news' => $news
        ];

        return view($this->getRenderingViewFile(), $data)->render();
    }

    public function sanitizeData()
    {
        return [];
    }

    public function addTranslations()
    {
        $this->translations['news_translated'] = labelTranslated('pages.modules.news');
    }

    public function getNews()
    {
        if(static::$news != null) return static::$news;

        static::$news = News::active()->latest()->get();

        return static::$news;
    }
}