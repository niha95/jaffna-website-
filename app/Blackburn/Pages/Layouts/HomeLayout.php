<?php
namespace App\Blackburn\Pages\Layouts;

class HomeLayout extends AbstractLayout {

    public static function availablePositions()
    {
        return [
            ['slug' => 'top', 'name' => trans('pages.positions.top')],
            ['slug' => 'right', 'name' => trans('pages.positions.right')],
            ['slug' => 'left', 'name' => trans('pages.positions.left')],
            ['slug' => 'bottom', 'name' => trans('pages.positions.bottom')],
        ];
    }
}