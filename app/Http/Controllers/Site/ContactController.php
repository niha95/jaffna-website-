<?php
namespace App\Http\Controllers\Site;

use App\Models\CareerRequest;
use App\Models\VisitorMessage;
use Illuminate\Http\Request;

class ContactController extends SiteController {

    public function sendContactMessage(Request $request)
    {
        $this->validate($request, [
            'sender_name' => 'required',
            'sender_email_address' => 'required|email',
            'subject' => 'required',
        ]);

        $input = $request->only('sender_name', 'sender_email_address', 'sender_phone_number', 'subject', 'message');

        $message = new VisitorMessage($input);
        $message->status = 'new';

        if ($message->save()) {
            flashMessage(trans('messages.message_sent_successfully'));

            return redirect()->back();
        }

        flashMessage(trans('messages.request_failed'), 'danger');
        return redirect()->back();
    }
}