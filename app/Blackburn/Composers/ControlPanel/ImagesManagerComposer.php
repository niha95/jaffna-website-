<?php namespace App\Blackburn\Composers\ControlPanel;

class ImagesManagerComposer {

    public function compose($view)
    {
        $main_categories_list = \AlbumCategory::lists('name', 'id');
        $uncategorized_albums = \Album::with('someImages')->where('album_category_id', null)->get();

        return $view->with([
            'main_categories_list' => $main_categories_list,
            '$uncategorized_albums' => $uncategorized_albums,
        ]);

    }
}