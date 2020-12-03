<?php namespace App\Blackburn\Composers;

use Illuminate\Support\ServiceProvider;

class ComposerServiceProvider extends ServiceProvider {

    public function register()
    {
        $this->composeControlPanel();

        $this->composeSite();
    }

    /*
     * Control Panel Composers
     */
    private function composeControlPanel()
    {
        $this->composeControlPanelSideMenu();
        $this->composeControlPanelImagesBrowser();
    }

    private function composeControlPanelSideMenu()
    {
        $this->app->view->composer('control_panel.layouts.partials._side_menu',
            'App\Blackburn\Composers\ControlPanel\SideMenuComposer');
    }

    private function composeControlPanelImagesBrowser()
    {
        $this->app->view->composer('control_panel.partials._images_browser',
            'App\Blackburn\Composers\ControlPanel\ImagesBrowserComposer');
    }


    /*
     * Site Composers
     */
    private function composeSite()
    {

    }

}