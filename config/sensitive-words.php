<?php

return [
    // 敏感词检测是否开启，建议只在审核时开启
    'enable' => env('SENSITIVE_WORDS_ENABLE', false),
    'token'  => env('SENSITIVE_WORDS_TOKEN', ''),
];
