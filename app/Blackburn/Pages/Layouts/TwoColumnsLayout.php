<?php
namespace App\Blackburn\Pages\Layouts;

class TwoColumnsLayout extends AbstractLayout {

    public static function availablePositions()
    {
        return [
            ['slug' => 'left', 'name' => trans('pages.positions.left')],
            ['slug' => 'right', 'name' => trans('pages.positions.right')],
        ];
    }
}