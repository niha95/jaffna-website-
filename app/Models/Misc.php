<?php

namespace App\Models;

use App\Blackburn\Traits\LocalizedFieldsTrait;
use Illuminate\Database\Eloquent\Model;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class Misc extends BaseModel {

    use LocalizedFieldsTrait;


    protected $fillable = [];

    protected $table = 'misc';


    protected function setFillableFields()
    {
        $this->fillable = ['phone_numbers', 'emails', 'postal_code', 'google_map',
            'facebook', 'twitter', 'youtube', 'google_plus', 'instagram', 'linked_in','snapchat',
            'catalog_pdf', 'closing_status','google_map_link'];

        foreach (config('site.locales') as $locale) {
            $this->fillable[] = 'site_name_' . $locale;
            $this->fillable[] = 'site_word_title_' . $locale;
            $this->fillable[] = 'site_word_content_' . $locale;
            $this->fillable[] = 'meta_keywords_' . $locale;
            $this->fillable[] = 'meta_description_' . $locale;
            $this->fillable[] = 'address_' . $locale;
            $this->fillable[] = 'other_contact_data_' . $locale;
            $this->fillable[] = 'closing_message_' . $locale;
        }
    }

    public function scopeGeneralData($query)
    {
        $query->addSelect('id');

        foreach (\Config::get('site.locales') as $locale) {
            $query->addSelect('site_name_' . $locale);
            $query->addSelect('site_word_title_' . $locale);
            $query->addSelect('site_word_content_' . $locale);
            $query->addSelect('meta_description_' . $locale);
            $query->addSelect('meta_keywords_' . $locale);
        }

        return $query;
    }

    public function scopeSocialLinks($query)
    {
        return $query->addSelect('id', 'facebook', 'twitter',
            'youtube', 'instagram', 'google_plus', 'linked_in', 'snapchat');
    }

    public function scopeClosingMessage($query)
    {
        $query->addSelect('id');
        $query->addSelect('closing_status');

        foreach (\Config::get('site.locales') as $locale) {
            $query->addSelect('closing_message_' . $locale);
        }

        return $query;
    }

    public function scopeContactData($query)
    {
        $query->addSelect(['id', 'phone_numbers', 'emails',
            'postal_code', 'google_map','google_map_link']);

        foreach (\Config::get('site.locales') as $locale) {
            $query->addSelect('address_' . $locale);
            $query->addSelect('other_contact_data_' . $locale);
        }

        return $query;
    }

    public function getPhoneNumbersListAttribute()
    {
        $list = [];

        $phone_numbers = @json_decode($this->phone_numbers);

        if (is_array($phone_numbers)) {
            foreach ($phone_numbers as $phone_number) {
                $list[] = $phone_number;
            }
        }

        return $list;
    }

    public function getEmailsListAttribute()
    {
        $list = [];

        $emails = @json_decode($this->emails);

        if (is_array($emails)) {
            foreach ($emails as $email) {
                $list[] = $email;
            }
        }

        return $list;
    }

    public static function siteIsClosed()
    {
        $misc = static::first();

        return is_null($misc) || $misc->closing_status == 'closed';
    }
}
