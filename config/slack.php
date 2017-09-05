<?php

return [
    'channels' => [
        'signups' => env('SLACK_SIGNUPS_CHANNEL', '#signups'),
    ],
    'url' => env('SLACK_WEBHOOK',''),
];