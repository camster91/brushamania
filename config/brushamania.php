<?php

return [
    'emails' => [
        'development' => explode(',', env('DEV_CC_EMAILS')),
        'production' => explode(',', env('PROD_CC_EMAILS')),
    ]
];
