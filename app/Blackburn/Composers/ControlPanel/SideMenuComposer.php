<?php namespace App\Blackburn\Composers\ControlPanel;

use App\Models\FlightReservation;
use \Illuminate\Support\Collection;

class SideMenuComposer {

    public function compose($view)
    {
        $panels = Collection::make($this->sideMenu());

        $current_option = \Config::get('control_panel.current_option');

        $current_panel = \Config::get('control_panel.current_panel');

        foreach ($panels as $key => $value) {
            if (key_exists($current_option, $value['options'])) {
                $current_panel = $key;
                break;
            }
        }

        $view->with('panels', $panels)
            ->with('current_option', $current_option)
            ->with('current_panel', $current_panel);
    }

    public function sideMenu()
    {
        return [

            //Users
            'users' => [
                'title' => trans('labels.users'),
                'options' => [
                    'control_panel.users.create' => [
                        'label' => trans('actions.add_entity', ['entity' => trans('labels.user')]),
                        'link' => route('control_panel.users.create'),
                        'icon' => 'fa fa-plus'
                    ],
                    'control_panel.users.index' => [
                        'label' => trans('actions.list_entities', ['entities' => trans('labels.users')]),
                        'link' => route('control_panel.users.index'),
                        'icon' => 'fa fa-list',
                    ],
                ],
                'icon' => 'fa fa-user',
            ],

            //Misc
            'misc' => [
                'title' => trans('labels.misc'),
                'options' => [
                    'control_panel.misc.edit_general_data' => [
                        'label' => trans('labels.general_data'),
                        'link' => route('control_panel.misc.edit_general_data'),
                        'icon' => 'fa fa-pencil'
                    ],
                    'control_panel.misc.edit_social_links' => [
                        'label' => trans('labels.social_links'),
                        'link' => route('control_panel.misc.edit_social_links'),
                        'icon' => 'fa fa-pencil',
                    ],
                    'control_panel.misc.edit_closing_message' => [
                        'label' => trans('labels.closing_message'),
                        'link' => route('control_panel.misc.edit_closing_message'),
                        'icon' => 'fa fa-pencil',
                    ],
                    'control_panel.misc.edit_contact_data' => [
                        'label' => trans('labels.contact_data'),
                        'link' => route('control_panel.misc.edit_contact_data'),
                        'icon' => 'fa fa-pencil',
                    ],
                ],
                'icon' => 'fa fa-cog',
            ],

            //Albums
            'albums' => [
                'title' => trans('labels.albums'),
                'options' => [
                    'control_panel.albums_categories.create' => [
                        'label' => trans('actions.add_entity', ['entity' => trans('labels.category')]),
                        'link' => route('control_panel.albums_categories.create'),
                        'icon' => 'fa fa-plus'
                    ],
                    'control_panel.albums_categories.index' => [
                        'label' => trans('actions.list_entities', ['entities' => trans('labels.categories')]),
                        'link' => route('control_panel.albums_categories.index'),
                        'icon' => 'fa fa-list',
                    ],
                    'control_panel.albums.create' => [
                        'label' => trans('actions.add_entity', ['entity' => trans('labels.album')]),
                        'link' => route('control_panel.albums.create'),
                        'icon' => 'fa fa-plus'
                    ],
                    'control_panel.albums.index' => [
                        'label' => trans('actions.list_entities', ['entities' => trans('labels.albums')]),
                        'link' => route('control_panel.albums.index'),
                        'icon' => 'fa fa-list',
                    ],
                ],
                'icon' => 'fa fa-file-image-o',
            ],

            //Pages
            'pages' => [
                'title' => trans('labels.pages'),
                'options' => [
                    'control_panel.pages_categories.create' => [
                        'label' => trans('actions.add_entity', ['entity' => trans('labels.category')]),
                        'link' => route('control_panel.pages_categories.create'),
                        'icon' => 'fa fa-plus'
                    ],
                    'control_panel.pages_categories.index' => [
                        'label' => trans('actions.list_entities', ['entities' => trans('labels.categories')]),
                        'link' => route('control_panel.pages_categories.index'),
                        'icon' => 'fa fa-list',
                    ],
                    'control_panel.pages.create' => [
                        'label' => trans('actions.add_entity', ['entity' => trans('labels.page')]),
                        'link' => route('control_panel.pages.create'),
                        'icon' => 'fa fa-plus'
                    ],
                    'control_panel.pages.index' => [
                        'label' => trans('actions.list_entities', ['entities' => trans('labels.pages')]),
                        'link' => route('control_panel.pages.index'),
                        'icon' => 'fa fa-list',
                    ],
                ],
                'icon' => 'fa fa-file-text',
            ],

            //Products
            'products' => [
                'title' => trans('labels.products'),
                'options' => [
                    'control_panel.products_categories.create' => [
                        'label' => trans('actions.add_entity', ['entity' => trans('labels.category')]),
                        'link' => route('control_panel.products_categories.create'),
                        'icon' => 'fa fa-plus fa-fw'
                    ],
                    'control_panel.products_categories.index' => [
                        'label' => trans('actions.list_entities', ['entities' => trans('labels.categories')]),
                        'link' => route('control_panel.products_categories.index'),
                        'icon' => 'fa fa-list fa-fw',
                    ],
                    'control_panel.products.create' => [
                        'label' => trans('actions.add_entity', ['entity' => trans('labels.product')]),
                        'link' => route('control_panel.products.create'),
                        'icon' => 'fa fa-plus fa-fw'
                    ],
                    'control_panel.products.index' => [
                        'label' => trans('actions.list_entities', ['entities' => trans('labels.products')]),
                        'link' => route('control_panel.products.index'),
                        'icon' => 'fa fa-list fa-fw',
                    ],
                ],
                'icon' => 'fa fa-shopping-cart fa-fw',
            ],

        

            //Navbar
            'menus' => [
                'title' => trans('labels.main_menu'),
                'options' => [
                    'control_panel.menus.create' => [
                        'label' => trans('actions.add_entity', ['entity' => trans('labels.item')]),
                        'link' => route('control_panel.menus.create'),
                        'icon' => 'fa fa-plus'
                    ],
                    'control_panel.menus.index' => [
                        'label' => trans('actions.list_entities', ['entities' => trans('labels.items')]),
                        'link' => route('control_panel.menus.index'),
                        'icon' => 'fa fa-list',
                    ],
                ],
                'icon' => 'fa fa-bars',
            ],

            //Slides
            'slides' => [
                'title' => trans('labels.slides'),
                'options' => [
                    'control_panel.slides.create' => [
                        'label' => trans('actions.add_entity', ['entity' => trans('labels.slide')]),
                        'link' => route('control_panel.slides.create'),
                        'icon' => 'fa fa-plus'
                    ],
                    'control_panel.slides.index' => [
                        'label' => trans('actions.list_entities', ['entities' => trans('labels.slides')]),
                        'link' => route('control_panel.slides.index'),
                        'icon' => 'fa fa-list',
                    ],
                ],
                'icon' => 'fa fa-file-image-o',
            ],

            //Contacts
            'contacts' => [
                'title' => trans('labels.contacts'),
                'options' => [
                    'control_panel.visitors_messages.index' => [
                        'label' => trans('actions.list_entities', ['entities' => trans('labels.visitors_messages')]),
                        'link' => route('control_panel.visitors_messages.index'),
                        'icon' => 'fa fa-list'
                    ]
                ],
                
                'icon' => 'fa fa-comments-o',
            ],
   
        ];
    }

}