<?php
if(version_compare(PHP_VERSION, '7.2.0', '>=')) {
    error_reporting(E_ALL ^ E_NOTICE ^ E_WARNING);
}
Route::pattern('path', '([A-z\d-\/_.]+)?');



/* General Routes */
Route::get('image/{image_filename}', ['as' => 'images.show_image', 'uses' => 'ImagesController@showImage']);
Route::get('thumb/{thumb_filename}', ['as' => 'images.show_thumb', 'uses' => 'ImagesController@showThumb']);
/* End of General Routes */

Route::group(['prefix' => 'images-manager'], function(){

    Route::get('fetch-categories', [
        'as' => 'control_panel.images_manager.fetch_categories',
        'uses' => 'ControlPanel\ImagesManagerController@fetchCategories'
    ]);

    Route::get('fetch-uncategorized-images', [
        'as' => 'control_panel.images_manager.fetch_uncategorized_images',
        'uses' => 'ControlPanel\ImagesManagerController@fetchUncategorizedImages'
    ]);

    Route::get('fetch-albums', [
        'as' => 'control_panel.images_manager.fetch_albums',
        'uses' => 'ControlPanel\ImagesManagerController@fetchAlbums'
    ]);

    Route::get('fetch-images', [
        'as' => 'control_panel.images_manager.fetch_images',
        'uses' => 'ControlPanel\ImagesManagerController@fetchImages'
    ]);

    Route::post('store-category', [
        'as' => 'control_panel.images_manager.store_category',
        'uses' => 'ControlPanel\ImagesManagerController@storeCategory'
    ]);

    Route::post('store-album', [
        'as' => 'control_panel.images_manager.store_album',
        'uses' => 'ControlPanel\ImagesManagerController@storeAlbum'
    ]);

    Route::patch('update-image', [
        'as' => 'control_panel.images_manager.update_image',
        'uses' => 'ControlPanel\ImagesManagerController@updateImage'
    ]);

    Route::post('delete-image', [
        'as' => 'control_panel.images_manager.delete_image',
        'uses' => 'ControlPanel\ImagesManagerController@deleteImage'
    ]);

});

Route::group(['prefix' => 'vc-admin', 'namespace' => 'ControlPanel', 'middleware' => 'set_control_panel_locale'], function () {

    Route::group(['prefix' => request()->segment(2)], function () {

        Route::get('login', ['as' => 'control_panel.show_login', 'uses' => 'AuthController@showLogin']);
        Route::post('login', ['as' => 'control_panel.do_login', 'uses' => 'AuthController@doLogin']);

        Route::get('forget-password', ['as' => 'control_panel.show_remind', 'uses' => 'RemindersController@showRemind']);
        Route::post('forget-password', ['as' => 'control_panel.send_remind', 'uses' => 'RemindersController@sendRemind']);

        Route::get('reset-password/{token}', ['as' => 'control_panel.show_reset', 'uses' => 'RemindersController@showReset']);
        Route::post('reset-password/{token}', ['as' => 'control_panel.do_reset', 'uses' => 'RemindersController@doReset']);

        Route::get('logout', ['as' => 'control_panel.do_logout', 'uses' => 'AuthController@doLogout']);

        Route::group(['middleware' => 'control_panel_auth'], function () {
            Route::get('/search', ['as' => 'control_panel.search', 'uses' => 'ControlPanelController@search']);

            //Api
            Route::get('api/fetch-images', ['as' => 'control_panel.api.fetch_images', 'uses' => 'ApiController@fetchImages']);

            //Users
            Route::get('users', ['as' => 'control_panel.users.index', 'uses' => 'UsersController@index']);
            Route::get('users/create', ['as' => 'control_panel.users.create', 'uses' => 'UsersController@create']);
            Route::post('users', ['as' => 'control_panel.users.store', 'uses' => 'UsersController@store']);
            Route::get('users/{username}/edit', ['as' => 'control_panel.users.edit', 'uses' => 'UsersController@edit']);
            Route::patch('users/{username}', ['as' => 'control_panel.users.update', 'uses' => 'UsersController@update']);
            Route::delete('users/{username}', ['as' => 'control_panel.users.destroy', 'uses' => 'UsersController@destroy']);

            //Misc
            Route::get('misc/edit-general-data', [
                'as' => 'control_panel.misc.edit_general_data',
                'uses' => 'MiscController@editGeneralData'
            ]);
            Route::patch('misc/edit-general-data', [
                'as' => 'control_panel.misc.update_general_data',
                'uses' => 'MiscController@updateGeneralData'
            ]);

            Route::get('misc/edit-social-links',[
                'as' => 'control_panel.misc.edit_social_links',
                'uses' => 'MiscController@editSocialLinks'
            ]);
            Route::patch('misc/edit-social-links', [
                'as' => 'control_panel.misc.update_social_links',
                'uses' => 'MiscController@updateSocialLinks'
            ]);

            Route::get('misc/edit-closing-message', [
                'as' => 'control_panel.misc.edit_closing_message',
                'uses' => 'MiscController@editClosingMessage'
            ]);
            Route::patch('misc/edit-closing-message', [
                'as' => 'control_panel.misc.update_closing_message',
                'uses' => 'MiscController@updateClosingMessage'
            ]);

            Route::get('misc/edit-contact-data', [
                'as' => 'control_panel.misc.edit_contact_data',
                'uses' => 'MiscController@editContactData'
            ]);
            Route::patch('misc/edit-contact-data', [
                'as' => 'control_panel.misc.update_contact_data',
                'uses' => 'MiscController@updateContactData'
            ]);

            //Albums Categories
            Route::get('albums-categories', ['as' => 'control_panel.albums_categories.index', 'uses' => 'AlbumsCategoriesController@index']);
            Route::get('albums-categories/create', ['as' => 'control_panel.albums_categories.create', 'uses' => 'AlbumsCategoriesController@create']);
            Route::post('albums-categories', ['as' => 'control_panel.albums_categories.store', 'uses' => 'AlbumsCategoriesController@store']);
            Route::get('albums-categories/{slug}/edit', ['as' => 'control_panel.albums_categories.edit', 'uses' => 'AlbumsCategoriesController@edit']);
            Route::patch('albums-categories/{slug}', ['as' => 'control_panel.albums_categories.update', 'uses' => 'AlbumsCategoriesController@update']);
            Route::delete('albums-categories/{slug}', ['as' => 'control_panel.albums_categories.destroy', 'uses' => 'AlbumsCategoriesController@destroy']);

            //Albums
            Route::get('albums', ['as' => 'control_panel.albums.index', 'uses' => 'AlbumsController@index']);
            Route::get('albums/create', ['as' => 'control_panel.albums.create', 'uses' => 'AlbumsController@create']);
            Route::post('albums', ['as' => 'control_panel.albums.store', 'uses' => 'AlbumsController@store']);
            Route::get('albums/{slug}/edit', ['as' => 'control_panel.albums.edit', 'uses' => 'AlbumsController@edit']);
            Route::patch('albums/{slug}', ['as' => 'control_panel.albums.update', 'uses' => 'AlbumsController@update']);
            Route::delete('albums/{slug}', ['as' => 'control_panel.albums.destroy', 'uses' => 'AlbumsController@destroy']);
            Route::get('albums/{slug}', ['as' => 'control_panel.albums.show', 'uses' => 'AlbumsController@show']);
            Route::post('albums/{slug}/add-images', ['as' => 'control_panel.albums.add_images', 'uses' => 'AlbumsController@addImages']);
            Route::post('albums/{slug}/delete-image', ['as' => 'control_panel.albums.delete_image', 'uses' => 'AlbumsController@deleteImage']);
            Route::get('albums/{id}/download-all-images', ['as' => 'control_panel.albums.download_all_images', 'uses' => 'AlbumsController@downloadAllImages']);

            //Pages
            Route::get('pages/api/layouts', ['as' => 'control_panel.pages.api.layouts', 'uses' => 'PagesController@layouts']);
            Route::get('pages/api/editing-view', ['as' => 'control_panel.pages.api.editing_form', 'uses' => 'PagesController@editingForm']);

            Route::get('pages', ['as' => 'control_panel.pages.index', 'uses' => 'PagesController@index']);
            Route::get('pages/create', ['as' => 'control_panel.pages.create', 'uses' => 'PagesController@create']);
            Route::post('pages', ['as' => 'control_panel.pages.store', 'uses' => 'PagesController@store']);
            Route::get('pages/{id}/edit', ['as' => 'control_panel.pages.edit', 'uses' => 'PagesController@edit']);
            Route::patch('pages/{id}', ['as' => 'control_panel.pages.update', 'uses' => 'PagesController@update']);
            Route::get('pages/{id}/delete', ['as' => 'control_panel.pages.delete', 'uses' => 'PagesController@delete']);
            Route::delete('pages/{id}', ['as' => 'control_panel.pages.destroy', 'uses' => 'PagesController@destroy']);
            Route::post('pages/modules/edit-module', ['as' => 'control_panel.pages.edit_module', 'uses' => 'PagesController@editModule']);
            Route::get('pages/modules/{module}', ['as' => 'control_panel.pages.modules', 'uses' => 'PagesController@module']);
            Route::get('pages/get-categories-path', ['as' => 'control_panel.pages.get_categories_path', 'uses' => 'PagesController@getCategoriesPath']);
            
            
            Route::post('pages/delete_image',  ['as' => 'control_panel.pages.delete_image', 'uses' => 'PagesController@delete_image']);
            
            
            

            //Pages Categories
            Route::get('pages-categories', ['as' => 'control_panel.pages_categories.index', 'uses' => 'PagesCategoriesController@index']);
            Route::get('pages-categories/create', ['as' => 'control_panel.pages_categories.create', 'uses' => 'PagesCategoriesController@create']);
            Route::post('pages-categories', ['as' => 'control_panel.pages_categories.store', 'uses' => 'PagesCategoriesController@store']);
            Route::get('pages-categories/{slug}/edit', ['as' => 'control_panel.pages_categories.edit', 'uses' => 'PagesCategoriesController@edit']);
            Route::patch('pages-categories/{slug}', ['as' => 'control_panel.pages_categories.update', 'uses' => 'PagesCategoriesController@update']);
            Route::delete('pages-categories/{slug}', ['as' => 'control_panel.pages_categories.destroy', 'uses' => 'PagesCategoriesController@destroy']);

            //Products
            Route::get('products', ['as' => 'control_panel.products.index', 'uses' => 'ProductsController@index']);
            Route::get('products/create', ['as' => 'control_panel.products.create', 'uses' => 'ProductsController@create']);
            Route::post('products', ['as' => 'control_panel.products.store', 'uses' => 'ProductsController@store']);
            Route::get('products/{id}/edit', ['as' => 'control_panel.products.edit', 'uses' => 'ProductsController@edit']);
            Route::patch('products/{id}', ['as' => 'control_panel.products.update', 'uses' => 'ProductsController@update']);
            Route::get('products/{id}/delete', ['as' => 'control_panel.products.delete', 'uses' => 'ProductsController@delete']);
            Route::delete('products/{id}', ['as' => 'control_panel.products.destroy', 'uses' => 'ProductsController@destroy']);
            Route::get('products/get-categories-path', ['as' => 'control_panel.products.get_categories_path', 'uses' => 'ProductsController@getCategoriesPath']);
            Route::post('products/dropzone-uploader', ['as' => 'control_panel.products.dropzone_uploader', 'uses' => 'ProductsController@dropzoneUploader']);

            //Packages
            Route::get('packages', ['as' => 'control_panel.packages.index', 'uses' => 'ProductsController@packages']);
            Route::get('packages/create', ['as' => 'control_panel.packages.create', 'uses' => 'ProductsController@createPackage']);
            //Tours
            Route::get('tours', ['as' => 'control_panel.tours.index', 'uses' => 'ProductsController@tours']);
            Route::get('tours/create', ['as' => 'control_panel.tours.create', 'uses' => 'ProductsController@createTour']);
            //Nile Cruise
            Route::get('nile-cruises', ['as' => 'control_panel.nile-cruises.index', 'uses' => 'ProductsController@nileCruises']);
            Route::get('nile-cruises/create', ['as' => 'control_panel.nile-cruises.create', 'uses' => 'ProductsController@createNileCruise']);
            //Hotels
            Route::get('hotels', ['as' => 'control_panel.hotels.index', 'uses' => 'ProductsController@hotels']);
            Route::get('hotels/create', ['as' => 'control_panel.hotels.create', 'uses' => 'ProductsController@createHotel']);


            //products Categories
            Route::get('products-categories', ['as' => 'control_panel.products_categories.index', 'uses' => 'ProductsCategoriesController@index']);
            Route::get('products-categories/create', ['as' => 'control_panel.products_categories.create', 'uses' => 'ProductsCategoriesController@create']);
            Route::post('products-categories', ['as' => 'control_panel.products_categories.store', 'uses' => 'ProductsCategoriesController@store']);
            Route::get('products-categories/{slug}/edit', ['as' => 'control_panel.products_categories.edit', 'uses' => 'ProductsCategoriesController@edit']);
            Route::patch('products-categories/{slug}', ['as' => 'control_panel.products_categories.update', 'uses' => 'ProductsCategoriesController@update']);
            Route::delete('products-categories/{slug}', ['as' => 'control_panel.products_categories.destroy', 'uses' => 'ProductsCategoriesController@destroy']);

            //Slides
            Route::post('slides/{id}/move-up', ['as' => 'control_panel.slides.move_up', 'uses' => 'SlidesController@moveUp']);
            Route::post('slides/{id}/move-down', ['as' => 'control_panel.slides.move_down', 'uses' => 'SlidesController@moveDown']);
            Route::get('slides', ['as' => 'control_panel.slides.index', 'uses' => 'SlidesController@index']);
            Route::get('slides/create', ['as' => 'control_panel.slides.create', 'uses' => 'SlidesController@create']);
            Route::post('slides', ['as' => 'control_panel.slides.store', 'uses' => 'SlidesController@store']);
            Route::get('slides/{id}/edit', ['as' => 'control_panel.slides.edit', 'uses' => 'SlidesController@edit']);
            Route::patch('slides/{id}', ['as' => 'control_panel.slides.update', 'uses' => 'SlidesController@update']);
            Route::get('slides/{id}/delete', ['as' => 'control_panel.slides.delete', 'uses' => 'SlidesController@delete']);
            Route::delete('slides/{id}', ['as' => 'control_panel.slides.destroy', 'uses' => 'SlidesController@destroy']);

            //Menus
            Route::post('menus/{id}/move-up', ['as' => 'control_panel.menus.move_up', 'uses' => 'MenusController@moveUp']);
            Route::post('menus/{id}/move-down', ['as' => 'control_panel.menus.move_down', 'uses' => 'MenusController@moveDown']);
            Route::get('menus/list-link-type', ['as' => 'control_panel.menus.list_link_types', 'uses' => 'MenusController@listLinkTypes']);
            Route::get('menus/list-links', ['as' => 'control_panel.menus.list_links', 'uses' => 'MenusController@listLinks']);
            Route::get('menus', ['as' => 'control_panel.menus.index', 'uses' => 'MenusController@index']);
            Route::get('menus/create', ['as' => 'control_panel.menus.create', 'uses' => 'MenusController@create']);
            Route::post('menus', ['as' => 'control_panel.menus.store', 'uses' => 'MenusController@store']);
            Route::get('menus/{id}/edit', ['as' => 'control_panel.menus.edit', 'uses' => 'MenusController@edit']);
            Route::patch('menus/{id}', ['as' => 'control_panel.menus.update', 'uses' => 'MenusController@update']);
            Route::get('menus/{id}/delete', ['as' => 'control_panel.menus.delete', 'uses' => 'MenusController@delete']);
            Route::delete('menus/{id}', ['as' => 'control_panel.menus.destroy', 'uses' => 'MenusController@destroy']);
            Route::get('menus/{id}', ['as' => 'control_panel.menus.show', 'uses' => 'MenusController@show']);

            //Icons
            Route::group(['prefix' => 'icons'], function () {
                Route::post('{id}/move-up', ['as' => 'control_panel.icons.move_up', 'uses' => 'IconsController@moveUp']);
                Route::post('{id}/move-down', ['as' => 'control_panel.icons.move_down', 'uses' => 'IconsController@moveDown']);
                Route::get('list-link-type', ['as' => 'control_panel.icons.list_link_types', 'uses' => 'IconsController@listLinkTypes']);
                Route::get('list-links', ['as' => 'control_panel.icons.list_links', 'uses' => 'IconsController@listLinks']);
                Route::get('/', ['as' => 'control_panel.icons.index', 'uses' => 'IconsController@index']);
                Route::get('create', ['as' => 'control_panel.icons.create', 'uses' => 'IconsController@create']);
                Route::post('/', ['as' => 'control_panel.icons.store', 'uses' => 'IconsController@store']);
                Route::get('{id}/edit', ['as' => 'control_panel.icons.edit', 'uses' => 'IconsController@edit']);
                Route::patch('{id}', ['as' => 'control_panel.icons.update', 'uses' => 'IconsController@update']);
                Route::get('{id}/delete', ['as' => 'control_panel.icons.delete', 'uses' => 'IconsController@delete']);
                Route::delete('{id}', ['as' => 'control_panel.icons.destroy', 'uses' => 'IconsController@destroy']);
                Route::get('{id}', ['as' => 'control_panel.icons.show', 'uses' => 'IconsController@show']);
            });

            //Visitors Messages
            Route::get('visitors-messages', [
                'as' => 'control_panel.visitors_messages.index',
                'uses' => 'VisitorsMessagesController@index'
            ]);
            Route::get('visitors-messages/{id}', [
                'as' => 'control_panel.visitors_messages.show',
                'uses' => 'VisitorsMessagesController@show'
            ]);
            Route::delete('visitors-messages/{id}', [
                'as' => 'control_panel.visitors_messages.destroy',
                'uses' => 'VisitorsMessagesController@destroy'
            ]);

            //Contacts
            Route::get('contacts', [
                'as' => 'control_panel.contacts.index',
                'uses' => 'ContactsController@index'
            ]);
            Route::delete('contacts/{id}', [
                'as' => 'control_panel.contacts.destroy',
                'uses' => 'ContactsController@destroy'
            ]);
            
            //flight Reservations
            Route::get('flight-reservations', [
                'as' => 'control_panel.flight_reservations.index',
                'uses' => 'FlightReservationController@index'
            ]);
            Route::get('flight-reservations/{id}', [
                'as' => 'control_panel.flight_reservations.show',
                'uses' => 'FlightReservationController@show'
            ]);
            Route::delete('flight-reservations/{id}', [
                'as' => 'control_panel.flight_reservations.destroy',
                'uses' => 'FlightReservationController@destroy'
            ]);


            //Link Selector
            Route::get('link-selector/list-types', [
                'as' => 'control_panel.link_selector.list_types',
                'uses' => 'LinkSelectorController@listTypes'
            ]);

            //Images Manager
            Route::post('images/dropzone-uploader', ['as' => 'control_panel.images.dropzone_uploader', 'uses' => 'ImagesController@dropzoneUploader']);
            Route::get('images/{id}/edit', ['as' => 'control_panel.images.edit', 'uses' => 'ImagesController@edit']);
            Route::patch('images/{id}', ['as' => 'control_panel.images.update', 'uses' => 'ImagesController@update']);
            Route::delete('images/{id}/delete', ['as' => 'control_panel.images.destroy', 'uses' => 'ImagesController@destroy']);
            //\\
            Route::delete('productalbum/{id}/delete', ['as' => 'control_panel.productalbum.destroy', 'uses' => 'ProductAlbumController@destroy']);
            //Logs
            Route::get('logs', ['as' => 'control_panel.logs.index', 'uses' => 'SiteLogsController@index']);
            Route::get('logs/{id}/delete', ['as' => 'control_panel.logs.delete', 'uses' => 'SiteLogsController@delete']);
            Route::delete('logs/{id}', ['as' => 'control_panel.logs.destroy', 'uses' => 'SiteLogsController@destroy']);

            //Unauthorized
            Route::get('unauthorized', ['as' => 'control_panel.unauthorized', 'uses' => 'AuthController@unauthorized']);

            //ImagesBrowser
            Route::get('images-browser/albums-and-sub-categories', [
                'as' => 'control_panel.images_browser.albums',
                'uses' => 'ImagesBrowserController@fetchAlbums'
            ]);
            Route::get('images-browser/album-images', [
                'as' => 'control_panel.images_browser.album_images',
                'uses' => 'ImagesBrowserController@fetchAlbumImages'
            ]);
            Route::post('images-browser/upload-images', [
                'as' => 'control_panel.images_browser.upload_images',
                'uses' => 'ImagesBrowserController@uploadImages'
            ]);

            //Ckeditor image upload
            Route::post('ckeditor/upload-image', ['as' => 'control_panel.ckeditor.upload_image', 'uses' => 'CKEditorController@ckeditorUploadImage']);

            //Images Manager
            Route::get('images-manager/categories', ['as' => 'control_panel.images_manager.categories', 'uses' => 'ImagesManagerController@categories']);
            Route::get('images-manager/categories/{id}/albums', ['as' => 'control_panel.images_manager.albums', 'uses' => 'ImagesManagerController@albums']);
            Route::get('images-manager/albums/{id}/images', ['as' => 'control_panel.images_manager.images', 'uses' => 'ImagesManagerController@images']);
            Route::get('link-selector/fetch-categories-and-pages', ['as' => 'control_panel.link_selector.fetch_categories_and_pages', 'uses' => 'LinkSelectorController@fetchCategoriesAndPages']);
        });
    });

    Route::get('{locale?}', ['as' => 'control_panel.home', 'uses' => 'HomeController@home']);
        
});
    
     Route::group(['prefix' => 'cart'], function () {
                Route::get('add/{product}', ['as' => 'site.cart.add', 'uses' => 'CartController@add']);
                Route::post('remove/{rowId}', ['as' => 'site.cart.remove', 'uses' => 'CartController@remove']);
                Route::post('clear', ['as' => 'site.cart.clear', 'uses' => 'CartController@clear']);
                Route::post('request', ['as' => 'site.cart.request', 'uses' => 'CartController@request']);
                Route::get('checkout', ['as' => 'site.cart.checkout', 'uses' => 'CartController@getCheckout']);
                
            });
            
Route::group(['middleware' => 'set_site_locale'], function(){
    Route::get('closed', ['as' => 'site.closed', 'uses' => 'Site\PagesController@closed']);

    Route::group(['namespace' => 'Site', 'middleware' => ['site_closed']], function () {

        Route::group(['prefix' => getSiteLocaleSegment()], function(){
            Route::get('/', ['as' => 'site.home', 'uses' => 'PagesController@showHome']);

            Route::get('products/{slug}.html', ['as' => 'site.products.show', 'uses' => 'ProductsController@show']);
            Route::get('products/{slug}', ['as' => 'site.products', 'uses' => 'PagesController@showProductsCategory']);
            Route::get('products/{category}', ['as' => 'site.productscategory', 'uses' => 'PagesController@showProductsCategory']);
            Route::get('products/{category}/{sub_category}', ['as' => 'site.products_sub_category', 'uses' => 'PagesController@showProductsSubCategory']);
            Route::get('contact-us', ['as' => 'site.contact_us', 'uses' => 'PagesController@showContactUs']);
            Route::post('contact-us', ['as' => 'site.send_contact_message', 'uses' => 'ContactController@sendContactMessage']);
            
            Route::get('store.html', ['as' => 'site.store', 'uses' => 'PagesController@showstore']);
             Route::get('brunch', ['as' => 'site.brunch', 'uses' => 'PagesController@showBrunch']);
           // Route::post('store', ['as' => 'site.send_contact_message', 'uses' => 'ContactController@sendContactMessage']);
           
             Route::post('flight.html', ['as' => 'site.flight_reservation', 'uses' => 'ReservationController@flightReservation']);
             
            Route::get('{path}.html', ['as' => 'site.page', 'uses' => 'PagesController@showPage']);
            Route::get('{path}', ['as' => 'site.category', 'uses' => 'PagesController@showCategory']);
        });

        Route::get('{locale}', ['as' => 'site.home', 'uses' => 'PagesController@showHome']);
    });
});