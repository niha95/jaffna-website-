<?php
namespace App\Http\Controllers\ControlPanel;

use App\Models\Misc;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;

class MiscController extends ControlPanelController {

    protected $views_path = 'control_panel.misc';

    public function __construct(Request $request)
    {
        $this->setCurrentPanel('misc');

        parent::__construct($request);

        $this->addBreadcrumb(
            trans('labels.misc'),
            'fa fa-list',
            route('control_panel.misc.edit_general_data')
        );
    }

    public function editGeneralData()
    {
        $this->addBreadcrumb(
            trans('actions.edit_entity', ['entity' => trans('labels.general_data')]),
            'fa fa-pencil'
        );

        $data = Misc::generalData()->firstOrCreate([]);

        return view($this->viewPath('edit_general_data'), [
            'data' => $data,
        ]);
    }

    public function updateGeneralData(Request $request)
    {
        $input = [];

        foreach (siteLocales() as $locale) {
            $input['site_name_' . $locale] = $request->get('site_name_' . $locale);
            $input['site_word_title_' . $locale] = $request->get('site_word_title_' . $locale);
            $input['site_word_content_' . $locale] = $request->get('site_word_content_' . $locale);
            $input['meta_description_' . $locale] = $request->get('meta_description_' . $locale);
            $input['meta_keywords_' . $locale] = $request->get('meta_keywords_' . $locale);
        }
    
        if($request->hasFile('catalog_pdf')) {
            $fileName = $request->file('catalog_pdf')->getClientOriginalName();
            $input['catalog_pdf'] = $fileName;
            Input::file('catalog_pdf')->move(PDF_PATH , $fileName);
        }

        Misc::first()->update($input);

        flashMessage(trans('messages.entity_edited', ['entity' => trans('labels.misc')]));
        return redirect()->back();
    }

    public function editSocialLinks()
    {
        $this->addBreadcrumb(
            trans('actions.edit_entity', ['entity' => trans('labels.social_links')]),
            'fa fa-pencil'
        );

        $data = Misc::socialLinks()->firstOrCreate([]);

        return view($this->viewPath('edit_social_links'), [
            'data' => $data,
        ]);
    }

    public function updateSocialLinks(Request $request)
    {
        $input = $request->only('facebook', 'twitter', 'youtube',
            'google_plus', 'instagram', 'linked_in');

        Misc::first()->update($input);

        flashMessage(trans('messages.entity_edited', ['entity' => trans('labels.misc')]));
        return redirect()->back();
    }

    public function editContactData()
    {
        $this->addBreadcrumb(
            trans('actions.edit_entity', ['entity' => trans('labels.contact_data')]),
            'fa fa-pencil'
        );

        $data = Misc::contactData()->firstOrCreate([]);

        $translations = [
            'phone_numbers' => trans('labels.phone_numbers'),
            'emails' => trans('labels.emails'),
        ];

        return view($this->viewPath('edit_contact_data'), [
            'data' => $data,
            'translations' => json_encode($translations),
        ]);
    }

    public function updateContactData(Request $request)
    {
        $input = $request->only('emails', 'postal_code', 'google_map', 'google_map_link');

        foreach (siteLocales() as $locale) {
            $input['address_' . $locale] = $request->get('address_' . $locale);
            $input['other_contact_data_' . $locale] = $request->get('other_contact_data_' . $locale);
        }

        $input['phone_numbers'] = $request->get('phone_numbers');
        $input['emails'] = $request->get('emails');

        Misc::first()->update($input);

        flashMessage(trans('messages.entity_edited', ['entity' => trans('labels.misc')]));
        return redirect()->back();
    }

    public function editClosingMessage()
    {
        $this->addBreadcrumb(
            trans('actions.edit_entity', ['entity' => trans('labels.closing_message')]),
            'fa fa-pencil'
        );

        $data = Misc::closingMessage()->firstOrCreate([]);

        return view($this->viewPath('edit_closing_message'), [
            'data' => $data,
        ]);
    }

    public function updateClosingMessage(Request $request)
    {
        $input = $request->only('closing_status');

        foreach (siteLocales() as $locale) {
            $input['closing_message_' . $locale] = $request->get('closing_message_' . $locale);
        }

        Misc::first()->update($input);

        flashMessage(trans('messages.entity_edited', ['entity' => trans('labels.misc')]));
        return redirect()->back();
    }
}