<?php

class localizer {
    
    /**
     * holds the countries list
     * @var array
     */
    private $countries = array(
        "af" => "Afghanistan",
        "ax" => "Åland Islands",
        "al" => "Albania",
        "dz" => "Algeria",
        "as" => "American Samoa",
        "ad" => "Andorra",
        "ao" => "Angola",
        "ai" => "Anguilla",
        "aq" => "Antarctica",
        "ag" => "Antigua and Barbuda",
        "ar" => "Argentina",
        "am" => "Armenia",
        "aw" => "Aruba",
        "au" => "Australia",
        "at" => "Austria",
        "az" => "Azerbaijan",
        "bs" => "Bahamas",
        "bh" => "Bahrain",
        "bd" => "Bangladesh",
        "bb" => "Barbados",
        "by" => "Belarus",
        "be" => "Belgium",
        "bz" => "Belize",
        "bj" => "Benin",
        "bm" => "Bermuda",
        "bt" => "Bhutan",
        "bo" => "Bolivia",
        "ba" => "Bosnia and Herzegovina",
        "bw" => "Botswana",
        "bv" => "Bouvet Island",
        "br" => "Brazil",
        "io" => "British Indian Ocean Territory",
        "bn" => "Brunei Darussalam",
        "bg" => "Bulgaria",
        "bf" => "Burkina Faso",
        "bi" => "Burundi",
        "kh" => "Cambodia",
        "cm" => "Cameroon",
        "ca" => "Canada",
        "cv" => "Cape Verde",
        "ky" => "Cayman Islands",
        "cf" => "Central African Republic",
        "td" => "Chad",
        "cl" => "Chile",
        "cn" => "China",
        "cx" => "Christmas Island",
        "cc" => "Cocos (Keeling) Islands",
        "co" => "Colombia",
        "km" => "Comoros",
        "cg" => "Congo",
        "cd" => "Congo, The Democratic Republic of The",
        "ck" => "Cook Islands",
        "cr" => "Costa Rica",
        "ci" => "Cote D'ivoire",
        "hr" => "Croatia",
        "cu" => "Cuba",
        "cy" => "Cyprus",
        "cz" => "Czech Republic",
        "dk" => "Denmark",
        "dj" => "Djibouti",
        "dm" => "Dominica",
        "do" => "Dominican Republic",
        "ec" => "Ecuador",
        "eg" => "Egypt",
        "sv" => "El Salvador",
        "gq" => "Equatorial Guinea",
        "er" => "Eritrea",
        "ee" => "Estonia",
        "et" => "Ethiopia",
        "fk" => "Falkland Islands (Malvinas)",
        "fo" => "Faroe Islands",
        "fj" => "Fiji",
        "fi" => "Finland",
        "fr" => "France",
        "gf" => "French Guiana",
        "pf" => "French Polynesia",
        "tf" => "French Southern Territories",
        "ga" => "Gabon",
        "gm" => "Gambia",
        "ge" => "Georgia",
        "de" => "Germany",
        "gh" => "Ghana",
        "gi" => "Gibraltar",
        "gr" => "Greece",
        "gl" => "Greenland",
        "gd" => "Grenada",
        "gp" => "Guadeloupe",
        "gu" => "Guam",
        "gt" => "Guatemala",
        "gg" => "Guernsey",
        "gn" => "Guinea",
        "gw" => "Guinea-bissau",
        "gy" => "Guyana",
        "ht" => "Haiti",
        "hm" => "Heard Island and Mcdonald Islands",
        "va" => "Holy See (Vatican City State)",
        "hn" => "Honduras",
        "hk" => "Hong Kong",
        "hu" => "Hungary",
        "is" => "Iceland",
        "in" => "India",
        "id" => "Indonesia",
        "ir" => "Iran, Islamic Republic of",
        "iq" => "Iraq",
        "ie" => "Ireland",
        "im" => "Isle of Man",
        "il" => "Israel",
        "it" => "Italy",
        "jm" => "Jamaica",
        "jp" => "Japan",
        "je" => "Jersey",
        "jo" => "Jordan",
        "kz" => "Kazakhstan",
        "ke" => "Kenya",
        "ki" => "Kiribati",
        "kp" => "Korea, Democratic People's Republic of",
        "kr" => "Korea, Republic of",
        "kw" => "Kuwait",
        "kg" => "Kyrgyzstan",
        "la" => "Lao People's Democratic Republic",
        "lv" => "Latvia",
        "lb" => "Lebanon",
        "ls" => "Lesotho",
        "lr" => "Liberia",
        "ly" => "Libyan Arab Jamahiriya",
        "li" => "Liechtenstein",
        "lt" => "Lithuania",
        "lu" => "Luxembourg",
        "mo" => "Macao",
        "mk" => "Macedonia, The Former Yugoslav Republic of",
        "mg" => "Madagascar",
        "mw" => "Malawi",
        "my" => "Malaysia",
        "mv" => "Maldives",
        "ml" => "Mali",
        "mt" => "Malta",
        "mh" => "Marshall Islands",
        "mq" => "Martinique",
        "mr" => "Mauritania",
        "mu" => "Mauritius",
        "yt" => "Mayotte",
        "mx" => "Mexico",
        "fm" => "Micronesia, Federated States of",
        "md" => "Moldova, Republic of",
        "mc" => "Monaco",
        "mn" => "Mongolia",
        "me" => "Montenegro",
        "ms" => "Montserrat",
        "ma" => "Morocco",
        "mz" => "Mozambique",
        "mm" => "Myanmar",
        "na" => "Namibia",
        "nr" => "Nauru",
        "np" => "Nepal",
        "nl" => "Netherlands",
        "an" => "Netherlands Antilles",
        "nc" => "New Caledonia",
        "nz" => "New Zealand",
        "ni" => "Nicaragua",
        "ne" => "Niger",
        "ng" => "Nigeria",
        "nu" => "Niue",
        "nf" => "Norfolk Island",
        "mp" => "Northern Mariana Islands",
        "no" => "Norway",
        "om" => "Oman",
        "pk" => "Pakistan",
        "pw" => "Palau",
        "ps" => "Palestinian Territory, Occupied",
        "pa" => "Panama",
        "pg" => "Papua New Guinea",
        "py" => "Paraguay",
        "pe" => "Peru",
        "ph" => "Philippines",
        "pn" => "Pitcairn",
        "pl" => "Poland",
        "pt" => "Portugal",
        "pr" => "Puerto Rico",
        "qa" => "Qatar",
        "re" => "Reunion",
        "ro" => "Romania",
        "ru" => "Russian Federation",
        "rw" => "Rwanda",
        "sh" => "Saint Helena",
        "kn" => "Saint Kitts and Nevis",
        "lc" => "Saint Lucia",
        "pm" => "Saint Pierre and Miquelon",
        "vc" => "Saint Vincent and The Grenadines",
        "ws" => "Samoa",
        "sm" => "San Marino",
        "st" => "Sao Tome and Principe",
        "sa" => "Saudi Arabia",
        "sn" => "Senegal",
        "rs" => "Serbia",
        "sc" => "Seychelles",
        "sl" => "Sierra Leone",
        "sg" => "Singapore",
        "sk" => "Slovakia",
        "si" => "Slovenia",
        "sb" => "Solomon Islands",
        "so" => "Somalia",
        "za" => "South Africa",
        "gs" => "South Georgia and The South Sandwich Islands",
        "es" => "Spain",
        "lk" => "Sri Lanka",
        "sd" => "Sudan",
        "sr" => "Suriname",
        "sj" => "Svalbard and Jan Mayen",
        "sz" => "Swaziland",
        "se" => "Sweden",
        "ch" => "Switzerland",
        "sy" => "Syrian Arab Republic",
        "tw" => "Taiwan, Province of China",
        "tj" => "Tajikistan",
        "tz" => "Tanzania, United Republic of",
        "th" => "Thailand",
        "tl" => "Timor-leste",
        "tg" => "Togo",
        "tk" => "Tokelau",
        "to" => "Tonga",
        "tt" => "Trinidad and Tobago",
        "tn" => "Tunisia",
        "tr" => "Turkey",
        "tm" => "Turkmenistan",
        "tc" => "Turks and Caicos Islands",
        "tv" => "Tuvalu",
        "ug" => "Uganda",
        "ua" => "Ukraine",
        "ae" => "United Arab Emirates",
        "gb" => "United Kingdom",
        "us" => "United States",
        "um" => "United States Minor Outlying Islands",
        "uy" => "Uruguay",
        "uz" => "Uzbekistan",
        "vu" => "Vanuatu",
        "ve" => "Venezuela",
        "vn" => "Viet Nam",
        "vg" => "Virgin Islands, British",
        "vi" => "Virgin Islands, U.S.",
        "wf" => "Wallis and Futuna",
        "eh" => "Western Sahara",
        "ye" => "Yemen",
        "zm" => "Zambia",
        "zw" => "Zimbabwe"
    );
    
    private $timezones = array(
        "-12.0" => "(GMT -12:00) Eniwetok, Kwajalein",
        "-11.0" => "(GMT -11:00) Midway Island, Samoa",
        "-10.0" => "(GMT -10:00) Hawaii",
        "-9.0" => "(GMT -9:00) Alaska",
        "-8.0" => "(GMT -8:00) Pacific Time (US &amp; Canada)",
        "-7.0" => "(GMT -7:00) Mountain Time (US &amp; Canada)",
        "-6.0" => "(GMT -6:00) Central Time (US &amp; Canada), Mexico City",
        "-5.0" => "(GMT -5:00) Eastern Time (US &amp; Canada), Bogota, Lima",
        "-4.0" => "(GMT -4:00) Atlantic Time (Canada), Caracas, La Paz",
        "-3.5" => "(GMT -3:30) Newfoundland",
        "-3.0" => "(GMT -3:00) Brazil, Buenos Aires, Georgetown",
        "-2.0" => "(GMT -2:00) Mid-Atlantic",
        "-1.0" => "(GMT -1:00 hour) Azores, Cape Verde Islands",
        "0.0" => "(GMT) Western Europe Time, London, Lisbon, Casablanca",
        "1.0" => "(GMT +1:00 hour) Brussels, Copenhagen, Madrid, Paris",
        "2.0" => "(GMT +2:00) Kaliningrad, South Africa",
        "3.0" => "(GMT +3:00) Baghdad, Riyadh, Moscow, St. Petersburg",
        "3.5" => "(GMT +3:30) Tehran",
        "4.0" => "(GMT +4:00) Abu Dhabi, Muscat, Baku, Tbilisi",
        "4.5" => "(GMT +4:30) Kabul",
        "5.0" => "(GMT +5:00) Ekaterinburg, Islamabad, Karachi, Tashkent",
        "5.5" => "(GMT +5:30) Bombay, Calcutta, Madras, New Delhi",
        "5.75" => "(GMT +5:45) Kathmandu",
        "6.0" => "(GMT +6:00) Almaty, Dhaka, Colombo",
        "7.0" => "(GMT +7:00) Bangkok, Hanoi, Jakarta",
        "8.0" => "(GMT +8:00) Beijing, Perth, Singapore, Hong Kong",
        "9.0" => "(GMT +9:00) Tokyo, Seoul, Osaka, Sapporo, Yakutsk",
        "9.5" => "(GMT +9:30) Adelaide, Darwin",
        "10.0" => "(GMT +10:00) Eastern Australia, Guam, Vladivostok",
        "11.0" => "(GMT +11:00) Magadan, Solomon Islands, New Caledonia",
        "12.0" => "(GMT +12:00) Auckland, Wellington, Fiji, Kamchatka",
    );
    
    /*
     * constructor
     */
    public function __construct(){
        //cosntructor
    }
    
    /**
     * generates the countries combobox elements
     * 
     * @param string $selected
     * @param string $disabled
     * @return string
     */
    public function generateCountriesComboBoxElements($selected = 'x', $disabled = 'x'){
        $markup = '';
        
        foreach($this->countries as $id => $name){
            $markup .= '<option value="'.$id.'" label="'.$name.'"'.(($id == $selected)?' selected="selected"':'').(($id == $disabled)?' disabled="disabled"':'').'>'.$name.'</option>';
        }
        
        return $markup;
    }
    
    /**
     * generates the timezones combobox elements
     * 
     * @param string $selected
     * @param string $disabled
     * @return string
     */
    public function generateTimezonesComboBoxElements($selected = 'x', $disabled = 'x'){
        $markup = '';
        
        foreach($this->timezones as $id => $name){
            $markup .= '<option value="'.$id.'" label="'.$name.'"'.(($id == $selected)?' selected="selected"':'').(($id == $disabled)?' disabled="disabled"':'').'>'.$name.'</option>';
        }
        
        return $markup;
    }

}