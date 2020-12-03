<?php
namespace App\Blackburn\Pages\Modules;

class MovingTextModule extends AbstractModule {

    public function renderView($locale)
    {
        $l = $this->getLocalizedData($locale);

        $pieces = explode('#t#', $l->title);

        $data = [
            'beforeText' => $pieces[0],
            'afterText' => $pieces[1],
            'caption' => $l->caption,
            'words' => explode("\n", $l->words),
            'tag' => $this->data->size,
            'color' => $this->data->color,
        ];

        return view($this->getRenderingViewFile(), $data)->render();
    }

    public function sanitizeData()
    {
        $data = [];

        $data['m__color'] = 'inverted-primary';
        $data['m__size'] = 'xlarge';

        if (!$this->editing) return $data;

        foreach ($this->data['localized'] as $i) {
            $data['m__title_' . $i['locale']] = $i['title'];
            $data['m__caption_' . $i['locale']] = $i['caption'];
            $data['m__words_' . $i['locale']] = $i['words'];
        }

        $data['m__color'] = $this->data['color'];
        $data['m__size'] = $this->data['size'];

        return $data;
    }

    public function addTranslations()
    {
        $this->translations['moving_text_translated'] = labelTranslated('pages.modules.moving_text');
    }
}