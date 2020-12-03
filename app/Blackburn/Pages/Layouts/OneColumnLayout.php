<?php
namespace App\Blackburn\Pages\Layouts;

class OneColumnLayout extends AbstractLayout {

    public static function availablePositions()
    {
        return [
            ['slug' => 'middle', 'name' => trans('pages.positions.middle')],
        ];
    }
}