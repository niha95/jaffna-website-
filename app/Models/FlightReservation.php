<?php

namespace App\Models;

use App\Blackburn\Traits\QueryFiltersTrait;
use App\Blackburn\Traits\StatusAttributeTrait;

class FlightReservation extends BaseModel {

    use StatusAttributeTrait;
    use QueryFiltersTrait;

    protected $table = 'flight_reservations';

    protected $fillable = [];

    protected $status_localized = false;


    protected function setFillableFields()
    {
        $this->fillable = ['Product_name', 'Price', 'Quantity',
            'date', 'hours', 'Place','name','phone_number','email'];
    }
    
    public function getProductNamesListAttribute()
    {
        $list = [];

        $Product_name = @json_decode($this->Product_name);

        if (is_array($Product_name)) {
            foreach ($Product_name as $Product_name) {
                $list[] = $Product_name;
            }
        }

        return $list;
    }

}
