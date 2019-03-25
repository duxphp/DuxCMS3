<?php
return [
    'dux.upload_driver' =>
        [
            'local' =>
                [
                    'driver' => 'local',
                ],
            'qiniu' =>
                [
                    'access_key' => 'L11jq7V4BZNSmKguEewI72YTUY7fe1Qi62vLQ0Vt',
                    'secret_key' => 'BlczKHfqf9jERj29CH-epEEmIk7e0gWsQwjyKHMl',
                    'bucket' => 'linlingou',
                    'domain' => 'http://lib.a.cuhuibao.com',
                    'url' => 'up-z1.qiniup.com',
                    'driver' => 'qiniu',
                ],
            'oss' =>
                [
                    'access_id' => '',
                    'secret_key' => '',
                    'bucket' => '',
                    'domain' => '',
                    'url' => '',
                    'driver' => 'oss',
                ],
        ],
];
