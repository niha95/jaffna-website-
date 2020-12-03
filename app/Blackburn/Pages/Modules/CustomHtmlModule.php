<?php
namespace App\Blackburn\Pages\Modules;

class CustomHtmlModule extends AbstractModule{

    protected function sanitizeData()
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