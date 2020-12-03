<?php
namespace App\Blackburn\Pages\Layouts;

abstract class AbstractLayout {

    protected $positions;

    protected $page;

    public function __construct($positions = [], $page = null)
    {
        $this->positions = $positions;
        $this->page = $page;
    }

    public function getPositions()
    {
        return $this->positions;
    }

    public function setPositions($positions)
    {
        $this->positions = $positions;
    }

    public function getPosition($slug)
    {
        foreach ($this->positions as $position)
            if ($position->slug == $slug) return $position;
    }

    public function render($locale = null)
    {
        if (is_null($locale)) $locale = app()->getLocale();

        $positions = [];

        foreach ($this->positions as $p) {
            $positions[$p->slug] = [];

            foreach ($p->modules as $m) {
                $positions[$p->slug][] = $this->renderModule($m, $locale);
            }
        }

        return view($this->getViewFile(), $positions)->render();
    }

    protected function renderModule($m, $locale)
    {
        $class = config('pages.modules.' . $m->type);

        $module = new $class($m->data, null, $this->page);

        return $module->render($locale);
    }

    protected function getViewFile()
    {
        if (isset($this->viewFile)) return $this->viewFile;

        return 'pages.layouts._' . static::slug();
    }

    public static function slug($delimiter = '_')
    {
        if (property_exists(get_called_class(), 'slug')) return static::slug;

        $shortName = (new \ReflectionClass(get_called_class()))->getShortName();

        return str_replace($delimiter . 'layout', '', snake_case($shortName, $delimiter));
    }

    public static function meta()
    {
        return [
            'name' => static::name(),
            'positions' => static::availablePositions()
        ];
    }

    public static function name()
    {
        return trans('pages.layouts.' . static::slug());
    }

    public static function thumbnail()
    {
        return view('pages._thumbnail', [
            'thumbnail' => asset('assets/control-panel/images/layouts/' . static::slug('-') . '.jpg')
        ])->render();
    }

    protected static function availablePositions()
    {
        return [];
    }
}