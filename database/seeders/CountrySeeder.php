<?php
  
namespace Database\Seeders;
  
use Illuminate\Database\Seeder;
use App\Models\CountryList;
  
class CountrySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        CountryList::truncate();
  
        $countries = [
            ['name' => 'Afghanistan', 'country_code' => 'AF'],
            ['name' => 'Åland Islands', 'country_code' => 'AX'],
            ['name' => 'Albania', 'country_code' => 'AL'],
            ['name' => 'Algeria', 'country_code' => 'DZ'],
            ['name' => 'American Samoa', 'country_code' => 'AS'],
            ['name' => 'Andorra', 'country_code' => 'AD'],
            ['name' => 'Angola', 'country_code' => 'AO'],
            ['name' => 'Anguilla', 'country_code' => 'AI'],
            ['name' => 'Antarctica', 'country_code' => 'AQ'],
            ['name' => 'Antigua and Barbuda', 'country_code' => 'AG'],
            ['name' => 'Argentina', 'country_code' => 'AR'],
            ['name' => 'Armenia', 'country_code' => 'AM'],
            ['name' => 'Aruba', 'country_code' => 'AW'],
            ['name' => 'Australia', 'country_code' => 'AU'],
            ['name' => 'Austria', 'country_code' => 'AT'],
            ['name' => 'Azerbaijan', 'country_code' => 'AZ'],
            ['name' => 'Bahamas', 'country_code' => 'BS'],
            ['name' => 'Bahrain', 'country_code' => 'BH'],
            ['name' => 'Bangladesh', 'country_code' => 'BD'],
            ['name' => 'Barbados', 'country_code' => 'BB'],
            ['name' => 'Belarus', 'country_code' => 'BY'],
            ['name' => 'Belgium', 'country_code' => 'BE'],
            ['name' => 'Belize', 'country_code' => 'BZ'],
            ['name' => 'Benin', 'country_code' => 'BJ'],
            ['name' => 'Bermuda', 'country_code' => 'BM'],
            ['name' => 'Bhutan', 'country_code' => 'BT'],
            ['name' => 'Bolivia, Plurinational State of', 'country_code' => 'BO'],
            ['name' => 'Bonaire, Sint Eustatius and Saba', 'country_code' => 'BQ'],
            ['name' => 'Bosnia and Herzegovina', 'country_code' => 'BA'],
            ['name' => 'Botswana', 'country_code' => 'BW'],
            ['name' => 'Bouvet Island', 'country_code' => 'BV'],
            ['name' => 'Brazil', 'country_code' => 'BR'],
            ['name' => 'British Indian Ocean Territory', 'country_code' => 'IO'],
            ['name' => 'Brunei Darussalam', 'country_code' => 'BN'],
            ['name' => 'Bulgaria', 'country_code' => 'BG'],
            ['name' => 'Burkina Faso', 'country_code' => 'BF'],
            ['name' => 'Burundi', 'country_code' => 'BI'],
            ['name' => 'Cambodia', 'country_code' => 'KH'],
            ['name' => 'Cameroon', 'country_code' => 'CM'],
            ['name' => 'Canada', 'country_code' => 'CA'],
            ['name' => 'Cape Verde', 'country_code' => 'CV'],
            ['name' => 'Cayman Islands', 'country_code' => 'KY'],
            ['name' => 'Central African Republic', 'country_code' => 'CF'],
            ['name' => 'Chad', 'country_code' => 'TD'],
            ['name' => 'Chile', 'country_code' => 'CL'],
            ['name' => 'China', 'country_code' => 'CN'],
            ['name' => 'Christmas Island', 'country_code' => 'CX'],
            ['name' => 'Cocos (Keeling) Islands', 'country_code' => 'CC'],
            ['name' => 'Colombia', 'country_code' => 'CO'],
            ['name' => 'Comoros', 'country_code' => 'KM'],
            ['name' => 'Congo', 'country_code' => 'CG'],
            ['name' => 'Congo, the Democratic Republic of the', 'country_code' => 'CD'],
            ['name' => 'Cook Islands', 'country_code' => 'CK'],
            ['name' => 'Costa Rica', 'country_code' => 'CR'],
            ['name' => 'Côte d\'Ivoire', 'country_code' => 'CI'],
            ['name' => 'Croatia', 'country_code' => 'HR'],
            ['name' => 'Cuba', 'country_code' => 'CU'],
            ['name' => 'Curaçao', 'country_code' => 'CW'],
            ['name' => 'Cyprus', 'country_code' => 'CY'],
            ['name' => 'Czech Republic', 'country_code' => 'CZ'],
            ['name' => 'Denmark', 'country_code' => 'DK'],
            ['name' => 'Djibouti', 'country_code' => 'DJ'],
            ['name' => 'Dominica', 'country_code' => 'DM'],
            ['name' => 'Dominican Republic', 'country_code' => 'DO'],
            ['name' => 'Ecuador', 'country_code' => 'EC'],
            ['name' => 'Egypt', 'country_code' => 'EG'],
            ['name' => 'El Salvador', 'country_code' => 'SV'],
            ['name' => 'Equatorial Guinea', 'country_code' => 'GQ'],
            ['name' => 'Eritrea', 'country_code' => 'ER'],
            ['name' => 'Estonia', 'country_code' => 'EE'],
            ['name' => 'Ethiopia', 'country_code' => 'ET'],
            ['name' => 'Falkland Islands (Malvinas)', 'country_code' => 'FK'],
            ['name' => 'Faroe Islands', 'country_code' => 'FO'],
            ['name' => 'Fiji', 'country_code' => 'FJ'],
            ['name' => 'Finland', 'country_code' => 'FI'],
            ['name' => 'France', 'country_code' => 'FR'],
            ['name' => 'French Guiana', 'country_code' => 'GF'],
            ['name' => 'French Polynesia', 'country_code' => 'PF'],
            ['name' => 'French Southern Territories', 'country_code' => 'TF'],
            ['name' => 'Gabon', 'country_code' => 'GA'],
            ['name' => 'Gambia', 'country_code' => 'GM'],
            ['name' => 'Georgia', 'country_code' => 'GE'],
            ['name' => 'Germany', 'country_code' => 'DE'],
            ['name' => 'Ghana', 'country_code' => 'GH'],
            ['name' => 'Gibraltar', 'country_code' => 'GI'],
            ['name' => 'Greece', 'country_code' => 'GR'],
            ['name' => 'Greenland', 'country_code' => 'GL'],
            ['name' => 'Grenada', 'country_code' => 'GD'],
            ['name' => 'Guadeloupe', 'country_code' => 'GP'],
            ['name' => 'Guam', 'country_code' => 'GU'],
            ['name' => 'Guatemala', 'country_code' => 'GT'],
            ['name' => 'Guernsey', 'country_code' => 'GG'],
            ['name' => 'Guinea', 'country_code' => 'GN'],
            ['name' => 'Guinea-Bissau', 'country_code' => 'GW'],
            ['name' => 'Guyana', 'country_code' => 'GY'],
            ['name' => 'Haiti', 'country_code' => 'HT'],
            ['name' => 'Heard Island and McDonald Mcdonald Islands', 'country_code' => 'HM'],
            ['name' => 'Holy See (Vatican City State)', 'country_code' => 'VA'],
            ['name' => 'Honduras', 'country_code' => 'HN'],
            ['name' => 'Hong Kong', 'country_code' => 'HK'],
            ['name' => 'Hungary', 'country_code' => 'HU'],
            ['name' => 'Iceland', 'country_code' => 'IS'],
            ['name' => 'India', 'country_code' => 'IN'],
            ['name' => 'Indonesia', 'country_code' => 'ID'],
            ['name' => 'Iran, Islamic Republic of', 'country_code' => 'IR'],
            ['name' => 'Iraq', 'country_code' => 'IQ'],
            ['name' => 'Ireland', 'country_code' => 'IE'],
            ['name' => 'Isle of Man', 'country_code' => 'IM'],
            ['name' => 'Israel', 'country_code' => 'IL'],
            ['name' => 'Italy', 'country_code' => 'IT'],
            ['name' => 'Jamaica', 'country_code' => 'JM'],
            ['name' => 'Japan', 'country_code' => 'JP'],
            ['name' => 'Jersey', 'country_code' => 'JE'],
            ['name' => 'Jordan', 'country_code' => 'JO'],
            ['name' => 'Kazakhstan', 'country_code' => 'KZ'],
            ['name' => 'Kenya', 'country_code' => 'KE'],
            ['name' => 'Kiribati', 'country_code' => 'KI'],
            ['name' => 'Korea, Democratic People\'s Republic of', 'country_code' => 'KP'],
            ['name' => 'Korea, Republic of', 'country_code' => 'KR'],
            ['name' => 'Kuwait', 'country_code' => 'KW'],
            ['name' => 'Kyrgyzstan', 'country_code' => 'KG'],
            ['name' => 'Lao People\'s Democratic Republic', 'country_code' => 'LA'],
            ['name' => 'Latvia', 'country_code' => 'LV'],
            ['name' => 'Lebanon', 'country_code' => 'LB'],
            ['name' => 'Lesotho', 'country_code' => 'LS'],
            ['name' => 'Liberia', 'country_code' => 'LR'],
            ['name' => 'Libya', 'country_code' => 'LY'],
            ['name' => 'Liechtenstein', 'country_code' => 'LI'],
            ['name' => 'Lithuania', 'country_code' => 'LT'],
            ['name' => 'Luxembourg', 'country_code' => 'LU'],
            ['name' => 'Macao', 'country_code' => 'MO'],
            ['name' => 'Macedonia, the Former Yugoslav Republic of', 'country_code' => 'MK'],
            ['name' => 'Madagascar', 'country_code' => 'MG'],
            ['name' => 'Malawi', 'country_code' => 'MW'],
            ['name' => 'Malaysia', 'country_code' => 'MY'],
            ['name' => 'Maldives', 'country_code' => 'MV'],
            ['name' => 'Mali', 'country_code' => 'ML'],
            ['name' => 'Malta', 'country_code' => 'MT'],
            ['name' => 'Marshall Islands', 'country_code' => 'MH'],
            ['name' => 'Martinique', 'country_code' => 'MQ'],
            ['name' => 'Mauritania', 'country_code' => 'MR'],
            ['name' => 'Mauritius', 'country_code' => 'MU'],
            ['name' => 'Mayotte', 'country_code' => 'YT'],
            ['name' => 'Mexico', 'country_code' => 'MX'],
            ['name' => 'Micronesia, Federated States of', 'country_code' => 'FM'],
            ['name' => 'Moldova, Republic of', 'country_code' => 'MD'],
            ['name' => 'Monaco', 'country_code' => 'MC'],
            ['name' => 'Mongolia', 'country_code' => 'MN'],
            ['name' => 'Montenegro', 'country_code' => 'ME'],
            ['name' => 'Montserrat', 'country_code' => 'MS'],
            ['name' => 'Morocco', 'country_code' => 'MA'],
            ['name' => 'Mozambique', 'country_code' => 'MZ'],
            ['name' => 'Myanmar', 'country_code' => 'MM'],
            ['name' => 'Namibia', 'country_code' => 'NA'],
            ['name' => 'Nauru', 'country_code' => 'NR'],
            ['name' => 'Nepal', 'country_code' => 'NP'],
            ['name' => 'Netherlands', 'country_code' => 'NL'],
            ['name' => 'New Caledonia', 'country_code' => 'NC'],
            ['name' => 'New Zealand', 'country_code' => 'NZ'],
            ['name' => 'Nicaragua', 'country_code' => 'NI'],
            ['name' => 'Niger', 'country_code' => 'NE'],
            ['name' => 'Nigeria', 'country_code' => 'NG'],
            ['name' => 'Niue', 'country_code' => 'NU'],
            ['name' => 'Norfolk Island', 'country_code' => 'NF'],
            ['name' => 'Northern Mariana Islands', 'country_code' => 'MP'],
            ['name' => 'Norway', 'country_code' => 'NO'],
            ['name' => 'Oman', 'country_code' => 'OM'],
            ['name' => 'Pakistan', 'country_code' => 'PK'],
            ['name' => 'Palau', 'country_code' => 'PW'],
            ['name' => 'Palestine, State of', 'country_code' => 'PS'],
            ['name' => 'Panama', 'country_code' => 'PA'],
            ['name' => 'Papua New Guinea', 'country_code' => 'PG'],
            ['name' => 'Paraguay', 'country_code' => 'PY'],
            ['name' => 'Peru', 'country_code' => 'PE'],
            ['name' => 'Philippines', 'country_code' => 'PH'],
            ['name' => 'Pitcairn', 'country_code' => 'PN'],
            ['name' => 'Poland', 'country_code' => 'PL'],
            ['name' => 'Portugal', 'country_code' => 'PT'],
            ['name' => 'Puerto Rico', 'country_code' => 'PR'],
            ['name' => 'Qatar', 'country_code' => 'QA'],
            ['name' => 'Réunion', 'country_code' => 'RE'],
            ['name' => 'Romania', 'country_code' => 'RO'],
            ['name' => 'Russian Federation', 'country_code' => 'RU'],
            ['name' => 'Rwanda', 'country_code' => 'RW'],
            ['name' => 'Saint Barthélemy', 'country_code' => 'BL'],
            ['name' => 'Saint Helena, Ascension and Tristan da Cunha', 'country_code' => 'SH'],
            ['name' => 'Saint Kitts and Nevis', 'country_code' => 'KN'],
            ['name' => 'Saint Lucia', 'country_code' => 'LC'],
            ['name' => 'Saint Martin (French part)', 'country_code' => 'MF'],
            ['name' => 'Saint Pierre and Miquelon', 'country_code' => 'PM'],
            ['name' => 'Saint Vincent and the Grenadines', 'country_code' => 'VC'],
            ['name' => 'Samoa', 'country_code' => 'WS'],
            ['name' => 'San Marino', 'country_code' => 'SM'],
            ['name' => 'Sao Tome and Principe', 'country_code' => 'ST'],
            ['name' => 'Saudi Arabia', 'country_code' => 'SA'],
            ['name' => 'Senegal', 'country_code' => 'SN'],
            ['name' => 'Serbia', 'country_code' => 'RS'],
            ['name' => 'Seychelles', 'country_code' => 'SC'],
            ['name' => 'Sierra Leone', 'country_code' => 'SL'],
            ['name' => 'Singapore', 'country_code' => 'SG'],
            ['name' => 'Sint Maarten (Dutch part)', 'country_code' => 'SX'],
            ['name' => 'Slovakia', 'country_code' => 'SK'],
            ['name' => 'Slovenia', 'country_code' => 'SI'],
            ['name' => 'Solomon Islands', 'country_code' => 'SB'],
            ['name' => 'Somalia', 'country_code' => 'SO'],
            ['name' => 'South Africa', 'country_code' => 'ZA'],
            ['name' => 'South Georgia and the South Sandwich Islands', 'country_code' => 'GS'],
            ['name' => 'South Sudan', 'country_code' => 'SS'],
            ['name' => 'Spain', 'country_code' => 'ES'],
            ['name' => 'Sri Lanka', 'country_code' => 'LK'],
            ['name' => 'Sudan', 'country_code' => 'SD'],
            ['name' => 'Suriname', 'country_code' => 'SR'],
            ['name' => 'Svalbard and Jan Mayen', 'country_code' => 'SJ'],
            ['name' => 'Swaziland', 'country_code' => 'SZ'],
            ['name' => 'Sweden', 'country_code' => 'SE'],
            ['name' => 'Switzerland', 'country_code' => 'CH'],
            ['name' => 'Syrian Arab Republic', 'country_code' => 'SY'],
            ['name' => 'Taiwan', 'country_code' => 'TW'],
            ['name' => 'Tajikistan', 'country_code' => 'TJ'],
            ['name' => 'Tanzania, United Republic of', 'country_code' => 'TZ'],
            ['name' => 'Thailand', 'country_code' => 'TH'],
            ['name' => 'Timor-Leste', 'country_code' => 'TL'],
            ['name' => 'Togo', 'country_code' => 'TG'],
            ['name' => 'Tokelau', 'country_code' => 'TK'],
            ['name' => 'Tonga', 'country_code' => 'TO'],
            ['name' => 'Trinidad and Tobago', 'country_code' => 'TT'],
            ['name' => 'Tunisia', 'country_code' => 'TN'],
            ['name' => 'Turkey', 'country_code' => 'TR'],
            ['name' => 'Turkmenistan', 'country_code' => 'TM'],
            ['name' => 'Turks and Caicos Islands', 'country_code' => 'TC'],
            ['name' => 'Tuvalu', 'country_code' => 'TV'],
            ['name' => 'Uganda', 'country_code' => 'UG'],
            ['name' => 'Ukraine', 'country_code' => 'UA'],
            ['name' => 'United Arab Emirates', 'country_code' => 'AE'],
            ['name' => 'United Kingdom', 'country_code' => 'GB'],
            ['name' => 'United States', 'country_code' => 'US'],
            ['name' => 'United States Minor Outlying Islands', 'country_code' => 'UM'],
            ['name' => 'Uruguay', 'country_code' => 'UY'],
            ['name' => 'Uzbekistan', 'country_code' => 'UZ'],
            ['name' => 'Vanuatu', 'country_code' => 'VU'],
            ['name' => 'Venezuela, Bolivarian Republic of', 'country_code' => 'VE'],
            ['name' => 'Viet Nam', 'country_code' => 'VN'],
            ['name' => 'Virgin Islands, British', 'country_code' => 'VG'],
            ['name' => 'Virgin Islands, U.S.', 'country_code' => 'VI'],
            ['name' => 'Wallis and Futuna', 'country_code' => 'WF'],
            ['name' => 'Western Sahara', 'country_code' => 'EH'],
            ['name' => 'Yemen', 'country_code' => 'YE'],
            ['name' => 'Zambia', 'country_code' => 'ZM'],
            ['name' => 'Zimbabwe', 'country_code' => 'ZW'],
        ];
          
        foreach ($countries as $key => $value) {
            CountryList::create($value);
        }
    }
}