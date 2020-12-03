<?php
namespace App\Blackburn\Pages\Modules;

class CallToActionModule extends AbstractModule {

    public function renderView($locale)
    {
        $l = $this->getLocalizedData($locale);

        $classes = @implode(' ', @json_decode($this->data->classes));

        $data = [
            'withAnimation' => $this->data->animated,
            'fullWidth' => $this->data->full_width,
            'withActionArrow' => $this->data->action_arrow,
            'classes' => $classes,
            'm_button_link' => $l->button_link,
            'm_button_label' => $l->button_label,
            'm_content' => $l->content,
        ];

        return view($this->getRenderingViewFile(), $data)->render();
    }

    public function sanitizeData()
    {
        $data = [];

        $data['classNames'] = $this->getClassNames();
        $data['selectedClasses'] = [];
        $data['m__action_arrow'] = 0;
        $data['m__animated'] = 0;
        $data['m__full_width'] = 0;

        if (!$this->editing) return $data;

        foreach ($this->data['localized'] as $i) {
            $data['m__button_label_' . $i['locale']] = $i['button_label'];
            $data['m__button_link_' . $i['locale']] = $i['button_link'];
            $data['m__content_' . $i['locale']] = $i['content'];
        }

        $data['selectedClasses'] = @json_decode($this->data['classes']);

        $data['m__action_arrow'] = $this->data['action_arrow'];
        $data['m__animated'] = $this->data['animated'];
        $data['m__full_width'] = $this->data['full_width'];

        return $data;
    }

    public function addTranslations()
    {
        $this->translations['call_to_action_translated'] = labelTranslated('pages.modules.call_to_action');
    }

    public function getClassNames()
    {
        $classNames = [
            'with-borders', 'with-full-borders', 'featured', 'featured-primary', 'featured-secondary',
            'featured-tertiary', 'featured-quaternary', 'call-to-action-primary',
            'call-to-action-secondary', 'call-to-action-tertiary', 'call-to-action-quaternary',
            'call-to-action-default', 'button-centered', 'appear-animation'
        ];

        return array_combine($classNames, $classNames);
    }

}