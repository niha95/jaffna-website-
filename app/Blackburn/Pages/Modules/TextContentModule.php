<?php
namespace App\Blackburn\Pages\Modules;

class TextContentModule extends AbstractModule{

    public function renderView($locale)
    {
        $data = $this->getLocalizedData();
        $image = ($this->page->featuredImage) ? $this->page->featuredImage : '';
        
       
        
        return view($this->getRenderingViewFile(), [
            'page_title'=> $this->page->title_locale,
            'm_title'   => $data->title,
            'm_content' => $data->content,
            'm_image'   => $image,
        ])->render();
    }

    public function sanitizeData()
    {
        if(!$this->editing) return [];

        $clean = [];

        foreach($this->data['localized'] as $i) {
            $clean['m__title_' . $i['locale']] = $i['title'];
            $clean['m__content_' . $i['locale']] = $i['content'];
        }

        return $clean;
    }
}