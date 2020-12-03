<?php

return [
    'can_access_control_panel' => ['super_user', 'admin', 'editor'],

    'roles' => [
        'super_user' => [
            'permissions' => [],
            'inherit_from' => ['admin']
        ],

        'admin' => [
            'permissions' => [
                'user.create', 'user.edit', 'user.delete',
                'page.delete', 'slide.delete',
                'album.delete', 'album_category.delete',
                'menu.delete', 'misc.edit'
            ],
            'inherit_from' => ['editor']
        ],

        'editor' => [
            'permissions' => [
                'slide.edit', 'slide.create', 'menu.edit', 'menu.create',
                'pages.edit', 'pages.create', 'album_category.edit', 'album_category.create',
                'album.create', 'album.edit'
            ]
        ],
    ],
];