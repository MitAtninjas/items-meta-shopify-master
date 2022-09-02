<?php

return [
    'roles' => [
        'admin' => 'Administrator',
        'customer' => 'Customer'
    ],
    'user_status' => [
        'status' => [
            'active' => 'Active',
            'inactive' => 'Inactive' 
        ],
        'default_checked' => 'active'
    ],
    "default_shipping_methods" => [
        'options' => [
            "bpost_standard" => "Belgium Post - Standard",
            "bpost_hvo" => "Belgium Post - HVO",
            "bpost_service_points" => "Belgium Post - Service Points",
            "dhl_de_standard" => "DHL DE - Standard",
            "dhl_de_service_points" => "DHL DE - Service Points",
            "dhl_de_service_packstations" => "DHL DE - Service Packstations",
            "postnl_nl_servicepoints"=>"PostNL - Service Points",
            "postnl_nl_standard"=>"PostNL - Standard",
            "dhl_nl_servicepoints"=>"DHL NL - Service Points",
            "dhl_nl_standard"=>"DHL NL - Standard"
        ],
        "standard" => [
            "bpost_standard",
            "bpost_hvo",
            "dhl_de_standard",
            "postnl_nl_standard",
            "dhl_nl_standard"
        ],
        "service_points" => [
            "bpost_service_points",
            "dhl_de_service_points",
            "dhl_de_service_packstations",
            "postnl_nl_servicepoints",
            "dhl_nl_servicepoints",
        ]
    ],
    "shipping_countries" => [
        'AX' => 'Aland Islands',
        'AL' => 'Albania',
        'AD' => 'Andorra',
        'AT' => 'Austria',
        'BY' => 'Belarus',
        'BE' => 'Belgium',
        'BA' => 'Bosnia and Herzegovina',
        'BG' => 'Bulgaria',
        'HR' => 'Croatia',
        'CZ' => 'Czech Republic',
        'DK' => 'Denmark',
        'EE' => 'Estonia',
        'FO' => 'Faroe Islands',
        'FI' => 'Finland',
        'FR' => 'France',
        'DE' => 'Germany',
        'GI' => 'Gibraltar',
        'GR' => 'Greece',
        'GG' => 'Guernsey',
        'VA' => 'Holy See (Vatican City State)',
        'HU' => 'Hungary',
        'IS' => 'Iceland',
        'IE' => 'Ireland',
        'IM' => 'Isle of Man',
        'IT' => 'Italy',
        'JE' => 'Jersey',
        'XK' => 'Kosovo',
        'LV' => 'Latvia',
        'LI' => 'Liechtenstein',
        'LT' => 'Lithuania',
        'LU' => 'Luxembourg',
        'MK' => 'Macedonia, the Former Yugoslav Republic of',
        'MT' => 'Malta',
        'MD' => 'Moldova, Republic of',
        'MC' => 'Monaco',
        'ME' => 'Montenegro',
        'NL' => 'Netherlands',
        'NO' => 'Norway',
        'PL' => 'Poland',
        'PT' => 'Portugal',
        'RO' => 'Romania',
        'SM' => 'San Marino',
        'RS' => 'Serbia',
        'CS' => 'Serbia and Montenegro',
        'SK' => 'Slovakia',
        'SI' => 'Slovenia',
        'ES' => 'Spain',
        'SJ' => 'Svalbard and Jan Mayen',
        'SE' => 'Sweden',
        'CH' => 'Switzerland',
        'UA' => 'Ukraine',
        'GB' => 'United Kingdom'
    ],
    "store_type" => [
        "basic" => "Basic",
        "advanced" => "Advanced",
        "plus" => "Plus"
    ],
    "use_sandbox"=>[
        '1'=>"Yes",
        "0"=>"No"
    ],
    "active_ants_environment" => [
        "test" => "Test",
        "dev" => "Development",
        "production" => "Production"
    ],
    "active_ants_environment_url" => [
        "test" => "https://shopapitest.activeants.nl",
        "dev" => "http://shopapitest.local.dev",
        "production" => "https://shopapi.activeants.nl",
        "default" => "https://shopapitest.activeants.nl"
    ],
    "standard_method_title" => "Standard",
    "standard_method_cost" => 15.00,
    "date_settings" => '{"section_title":"Select Preferred Delivery Date","button_text":"Submit","success_message":"Success ! Delivery date is updated","error_message":"Error ! Please Try again later"}',
    "packstation_settings" => '{"section_title":"Enter Packstation Registration No","button_text":"Submit","success_message":"Success ! Registration No is updated","error_message":"Error ! Please Try again later"}'
];
