<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\CountryList;

class Store extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $guarded = [
        'id',
    ];
    
    public function getCountryList()
    {
        $country_list = [];
        if($this->is_county_enable == 1 && !empty($this->country_id)){
            $country_list =json_decode(CountryList::whereIn('id', $this->country_id)->get());
        }
        return $country_list;
    }

    public function getSpecificCountryCodeList()
    {
        $country_list = [];
        if($this->is_county_enable == 1 && !empty($this->country_id)){
            $country_list =CountryList::whereIn('id', $this->country_id)->pluck('country_code')->toArray();
        }
        return $country_list;
    }

    public function getCountryIdAttribute($value)
    {
        if (!empty($value)) {
            return explode(',', $value);
        } else {
            return '';
        }
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }
}
