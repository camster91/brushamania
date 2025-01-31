<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Default Request Method
    |--------------------------------------------------------------------------
    |
    | If not provided, will use curl as default.
    | Supported: "guzzle", "curl", if you want to use your own request method,
    | please read document.
    |
    */
    'request_method' => 'curl',
    /*
    |--------------------------------------------------------------------------
    | Enable/Disable Service
    |--------------------------------------------------------------------------
    | Type: bool
    |
    | This option is used to disable/enable the service
    |
    | Supported: true, false
    |
    */
    'is_service_enabled' => true,
    /*
    |--------------------------------------------------------------------------
    | Host Name
    |--------------------------------------------------------------------------
    | Type: string
    | Default will be empty, assign value only if you want domain check with Google response
    | Google reCAPTCHA host name, https://www.google.com/recaptcha/admin
    |
    */
    'host_name' => '35.183.201.110',
    /*
    |--------------------------------------------------------------------------
    | Secret Key
    |--------------------------------------------------------------------------
    | Type: string
    | Google reCAPTCHA credentials, https://www.google.com/recaptcha/admin
    |
    */
    'secret_key' => '6LfQMKwUAAAAAH9YeTgx_7JyP-x_56tL_m5FaGaf',
    /*
    |--------------------------------------------------------------------------
    | Site Key
    |--------------------------------------------------------------------------
    | Type: string
    | Google reCAPTCHA credentials, https://www.google.com/recaptcha/admin
    |
    */
    'site_key' => '6LcyOrYqAAAAACLkY9tYPLxrWqh2Qg-gQE9JKfLO',

    /*
    |--------------------------------------------------------------------------
    | Badge Style
    |--------------------------------------------------------------------------
    | Type: boolean
    | Support:
    |  -  true: the badge will be shown inline with the form, also you can customise your style
    |  -  false: the badge will be shown in the bottom right side
    |
    */
    'inline' => false,

    /*
    |--------------------------------------------------------------------------
    | Background Badge Style
    |--------------------------------------------------------------------------
    | Type: boolean
    | Support:
    |  -  true: the background badge will be displayed at the bottom right of page
    |  -  false: the background badge will be invisible
    |
    */
    'background_badge_display' => true,
    /*
    |--------------------------------------------------------------------------
    | Background Badge Style
    |--------------------------------------------------------------------------
    | Type: boolean
    | Support:
    |  -  true: the script will run on every page if you put init() on the global page
    |  -  false: the script will only be running if there is action defined
    |
    */
    'background_mode' => false,

    /*
    |--------------------------------------------------------------------------
    | Score Comparision
    |--------------------------------------------------------------------------
    | Type: bool
    | If you enable it, the package will do score comparision from your setting
    */
    'is_score_enabled' => true,
    /*
    |--------------------------------------------------------------------------
    | Setting
    |--------------------------------------------------------------------------
    | Type: array
    | Define your score threshold, define your action
    | action: Google reCAPTCHA required parameter
    | threshold: score threshold
    | score_comparision: true/false, if this is true, the system will do score comparsion against your threshold for the action
    */
    'setting' => [
        [
            'action' => 'school_registration',
            'threshold' => 0.2,
            'score_comparision' => true,
        ],
        [
            'action' => 'dentist_registration',
            'threshold' => 0.2,
            'score_comparision' => true,
        ],
        [
            'action' => 'rotarian_registration',
            'threshold' => 0.2,
            'score_comparision' => true,
        ],
        [
            'action' => 'parent_registration',
            'threshold' => 0.2,
            'score_comparision' => true,
        ],
        [
            'action' => 'politician_registration',
            'threshold' => 0.2,
            'score_comparision' => true,
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Setting
    |--------------------------------------------------------------------------
    | Type: array
    | Define a list of ip that you want to skip
    */
    'skip_ips' => [

    ],
    /*
    |--------------------------------------------------------------------------
    | Options
    |--------------------------------------------------------------------------
    | Custom option field for your request setting, which will be used for RequestClientInterface
    |
    */
    'options' => [

    ],
    /*
    |--------------------------------------------------------------------------
    | API JS Url
    |--------------------------------------------------------------------------
    | Type: string
    | Google reCAPTCHA API JS URL
    */
    'api_js_url' => 'https://www.google.com/recaptcha/api.js',
    /*
    |--------------------------------------------------------------------------
    | Site Verify Url
    |--------------------------------------------------------------------------
    | Type: string
    | Google reCAPTCHA API
    */
    'site_verify_url' => 'https://www.google.com/recaptcha/api/siteverify',

    /*
    |--------------------------------------------------------------------------
    | Language
    |--------------------------------------------------------------------------
    | Type: string
    | https://developers.google.com/recaptcha/docs/language
    */
    'language' => 'en',
];
