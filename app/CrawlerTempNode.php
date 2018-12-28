<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CrawlerTempNode extends Model
{
    protected $fillable = ['pk','state','ip','port','continent_code','continent_name','country_code2','country_code3','country_name','country_capital','state_prov','district','city','zipcode','latitude','longitude','is_eu','calling_code','country_tld','isp','connection_type','organization','geoname_id'];
}
