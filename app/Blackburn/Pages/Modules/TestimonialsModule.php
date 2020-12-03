<?php
namespace App\Blackburn\Pages\Modules;

use App\Models\Testimonial;
use Illuminate\Support\Collection;

class TestimonialsModule extends AbstractModule {

    public function renderView($locale)
    {
        $testimonials = Testimonial::with('clientImage')->whereIn('id', $this->data)->get();

        return view($this->getRenderingViewFile(), [
            'm_testimonials' => $testimonials
        ])->render();
    }

    public function sanitizeData()
    {
        $testimonials = new Collection();
        Testimonial::get()->map(function ($item) use (&$testimonials) {
            $data = [
                'id' => $item->id,
                'client_name' => $item->client_name,
                'client_caption' => $item->client_caption,
            ];

            if(@in_array($item->id, $this->data['testimonials'])) {
                $data['selected'] = true;
            } else {
                $data['selected'] = false;
            }

            if ($item->clientImage) {
                $data['client_image'] = $item->clientImage->image_filename;
            } else {
                $data['client_image'] = null;
            }

            $testimonials->push($data);
        });

        $data['testimonials'] = $testimonials->toArray();

        if($this->editing){
            foreach($this->data['localized'] as $l) {
                $data['m__title_' . $l['locale']] = $l['title'];
            }
        }

        return $data;
    }

    public function addTranslations()
    {
        $this->translations['testimonials'] = trans('labels.testimonials');
    }
}