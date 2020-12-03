<?php
namespace App\Blackburn\Pages\Modules;

abstract class AbstractModule {

    protected $data;

    protected $editing = false;

    protected $index = null;

    protected $page = null;

    protected $translations = [];

    protected $urls = [];

    protected $hasViewFile = true;

    public function __construct($data = '', $index = null, $page = null)
    {
        $this->data = $data;
        if (!empty($data)) $this->editing = true;

        $this->index = $index;

        $this->page = $page;

        $this->addCommonTranslations();
        $this->addCommonUrls();

        if (method_exists($this, 'addTranslations')) $this->addTranslations();
        if (method_exists($this, 'addUrls')) $this->addUrls();
    }

    public function render($locale = null)
    {
        if (is_null($locale)) $locale = app()->getLocale();

        if ($this->hasViewFile) return $this->renderView($locale);

        return $this->renderInline($locale);
    }

    public function renderView($locale)
    {
        return $this->renderInline($locale);
    }

    public function renderInline($locale)
    {
        $content = '';
        $className = 'm_' . static::slug();

        foreach ($this->data->localized as $i) {
            if ($i->locale == $locale) {
                $content = $i->content;
                break;
            }
        }

        return "<div class='{$className}'>{$content}</div>";
    }

    public function getRenderingViewFile()
    {
        if (property_exists(get_called_class(), 'renderView')) return $this->renderView;

        return 'pages.modules._' . static::slug() . '_rendering';
    }

    public function getEditingViewFile()
    {
        if (property_exists(get_called_class(), 'editingView')) return $this->editingView;

        return 'pages.modules._' . static::slug() . '_editing';
    }

    public function getEditingView()
    {
        $data = $this->sanitizeData();

        return view($this->getEditingViewFile(), [
            'data' => $data,
            'editing_mode' => $this->editing,
            'module_index' => $this->index,
            'translations' => json_encode($this->translations),
            'urls' => json_encode($this->urls),
        ])->render();
    }

    public function getLocalizedData($locale = null)
    {
        if (is_null($locale)) $locale = app()->getLocale();


        foreach ($this->data->localized as $i) {
            if($i->locale == $locale) return $i;
        }

        return null;
    }

    public function getTitle()
    {
        if ($this->editing) {
            return trans('actions.edit_entity', ['entity' => static::name()]);
        }

        return trans('actions.add_entity', ['entity' => static::name()]);
    }

    public function addCommonTranslations()
    {
        $this->translations = [];
    }

    public function addCommonUrls()
    {
        $this->urls = [];
    }

    abstract protected function sanitizeData();


    public static function slug($delimiter = '_')
    {
        if (property_exists(get_called_class(), 'slug')) return static::slug;

        $shortName = (new \ReflectionClass(get_called_class()))->getShortName();

        return str_replace($delimiter . 'module', '', snake_case($shortName, $delimiter));
    }

    public static function name()
    {
        return trans('pages.modules.' . static::slug());
    }

    public static function editingUrl()
    {
        return route('control_panel.pages.modules', [static::slug('-')]);
    }
}