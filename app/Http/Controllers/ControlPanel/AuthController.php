<?php
namespace App\Http\Controllers\ControlPanel;

use Illuminate\Http\Request;

class AuthController extends ControlPanelController {

    protected $redirectAfterLogout = 'site.home';

    public function showLogin()
    {
        if (auth()->check()) {
            flashMessage(trans('messages.already_logged_in'), 'info');

            return redirect()->route('control_panel.home');
        }

        return view('control_panel.auth.login');
    }

    public function doLogin(Request $request)
    {
        $credentials = [
            'email' => $request->get('email'),
            'password' => $request->get('password'),
            'status' => 'active',
        ];

        $remember_me = $request->has('remember_me')
            ? true
            : false;

        if (auth()->attempt($credentials, $remember_me)) {
            flashMessage(trans('messages.logged_in_successfully'));

            return redirect()->intended(route('control_panel.home', [app()->getLocale()]));
        }

        flashMessage(trans('messages.email_and_password_do_not_match'), 'danger');

        return redirect()->back()->withInput([
            'email' => $credentials['email'],
            'remember_me' => $remember_me
        ]);
    }

    public function doLogout()
    {
        auth()->logout();

        flashMessage(trans('messages.logged_out'), 'success');
        return redirect()->route($this->redirectAfterLogout);
    }

}