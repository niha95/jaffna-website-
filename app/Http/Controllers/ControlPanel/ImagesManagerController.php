<?php
namespace App\Http\Controllers\ControlPanel;

use App\Models\Album;
use App\Models\AlbumCategory;
use App\Models\Image;

class ImagesManagerController extends ControlPanelController {

    public function fetchCategories()
    {
        $categories = AlbumCategory::orderBy('name_' . defaultLocale())->get();

        $categories = $categories->map(function ($item) {
            return [
                'id' => $item->id,
                'name' => $item->name_translated_piped
            ];
        });

        return response()->json([
            'content' => $categories->toArray(),
        ]);
    }

    public function fetchUncategorizedImages()
    {
        $images = Image::where('album_id', 0)->orWhere('album_id', null)->get();

        $images = $images->map(function ($i) {
            $data = [
                'id' => $i->id,
                'album_id' => 0,
                'image_url_relative' => route('images.show_image', [$i->image_filename], false),
                'image_url' => route('images.show_image', [$i->image_filename]),
                'thumb_url' => route('images.show_thumb', [$i->thumb_filename]),
            ];

            foreach (siteLocales() as $locale) {
                $data['caption_' . $locale] = $i->{'caption_' . $locale};
            }

            return $data;
        });

        return response()->json([
            'content' => $images->toArray(),
        ]);
    }

    public function fetchAlbums()
    {
        $category_id = request()->get('category_id');

        $albums = Album::with('latestImages')->where('album_category_id', $category_id)->get();

        $albums = $albums->map(function ($i) {
            return [
                'id' => $i->id,
                'name' => $i->name_translated_piped,
                'latestImages' => $i->latestImages->take(3)->map(function ($j) {
                    return [
                        'id' => $j->id,
                        'caption' => $j->caption,
                        'image_url_relative' => route('images.show_image', [$j->image_filename], false),
                        'image_url' => route('images.show_image', [$j->image_filename]),
                        'thumb_url' => route('images.show_thumb', [$j->thumb_filename]),
                    ];
                })
            ];
        });

        return response()->json([
            'content' => $albums->toArray(),
        ]);
    }

    public function fetchImages()
    {
        $album_id = request()->get('album_id');

        $images = Image::where('album_id', $album_id)->get();

        $images = $images->map(function ($i) {
            $data = [
                'id' => $i->id,
                'album_id' => $i->album_id,
                'image_url_relative' => route('images.show_image', [$i->image_filename], false),
                'image_url' => route('images.show_image', [$i->image_filename]),
                'thumb_url' => route('images.show_thumb', [$i->thumb_filename]),
            ];

            foreach (siteLocales() as $locale) {
                $data['caption_' . $locale] = $i->{'caption_' . $locale};
            }

            return $data;
        });

        return response()->json([
            'content' => $images->toArray(),
        ]);
    }

    public function storeCategory()
    {
        $input = [];

        foreach (siteLocales() as $locale) {
            $input['name_' . $locale] = request('name_' . $locale);
        }

        $category = new AlbumCategory($input);

        $category->save();

        return response()->json([
            'id' => $category->id,
            'name' => $category->name_translated_piped
        ]);
    }

    public function storeAlbum()
    {
        $input = [
            'album_category_id' => request('album_category_id')
        ];

        foreach (siteLocales() as $locale) {
            $input['name_' . $locale] = request('name_' . $locale);
            $input['description_' . $locale] = request('description_' . $locale);
        }

        $album = new Album($input);

        $album->save();

        return response()->json([
            'id' => $album->id,
            'name' => $album->name_translated_piped,
            'latest_images' => []
        ]);
    }

    public function updateImage()
    {
        $image = Image::findOrFail(request()->get('id'));

        foreach (siteLocales() as $locale) {
            $image->{'caption_' . $locale} = request()->get('caption_' . $locale);
        }

        $image->save();
    }

    public function deleteImage()
    {
        $image = Image::findOrFail(request()->get('id'));

        $image->delete();
    }
}