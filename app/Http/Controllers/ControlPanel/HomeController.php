<?php
namespace App\Http\Controllers\ControlPanel;

class HomeController extends ControlPanelController {

    public function home(){
        return redirect()->route('control_panel.misc.edit_general_data');
    }

}