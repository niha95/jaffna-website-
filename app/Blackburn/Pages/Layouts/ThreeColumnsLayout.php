<?php
namespace App\Blackburn\Pages\Layouts;

class ThreeColumnsLayout extends AbstractLayout {

    public static function availablePositions()
    {
        return [
            ['slug' => 'top_left', 'name' => trans('pages.positions.top_left')],
            ['slug' => 'bottom_left', 'name' => trans('pages.positions.bottom_left')],
            ['slug' => 'right', 'name' => trans('pages.positions.right')],
        ];
    }
}