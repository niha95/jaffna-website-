<?php
namespace App\Blackburn\Pages\Modules;

use Illuminate\Support\Collection;

class PackagesModule extends AbstractModule {

    public function renderView($locale)
    {
        $data = [
            'uid' => rand(1000, 9999),
            'emart_id' => $this->data->emart_id,
            'thanks_page' => $this->data->thanks_page
        ];

        foreach($this->data->localized as $l) {
            if($l->locale != app()->getLocale()) continue;

            $data['caption'] = $l->caption;
            $data['title'] = $l->title;
        }

        $packages = new Collection();
        foreach($this->data->packages as $package) {
            foreach($package->localized as $l) {
                if($l->locale != app()->getLocale()) continue;

                $p = new \stdClass();
                $p->title = $l->title;

                $p->description = explode("\n", $l->description);

                $p->current_price = $l->current_price;
                $p->original_price = $l->original_price;
                $p->link = '#';

                $packages->push($p);
            }
        }
        $data['packages'] = $packages;

        return view($this->getRenderingViewFile(), $data)->render();
    }

    public function sanitizeData()
    {
        $data = [
            'm__emart_id' => @$this->data['emart_id'],
            'm__thanks_page' => @$this->data['thanks_page'],
        ];

        if($this->editing) {
            foreach($this->data['localized'] as $l) {
                $data['m__title_' . $l['locale']] = $l['title'];
                $data['m__caption_' . $l['locale']] = $l['caption'];
            }
        }

        $data['packages'] = $this->editing ? $this->data['packages'] : [];

        return $data;
    }
}