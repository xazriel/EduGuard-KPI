<?php

return [
    'show_warnings'        => false,
    'orientation'          => 'portrait',
    'defines'              => [],
    'default_font'         => 'DejaVu Sans',
    'dpi'                  => 96,
    'enable_php'           => false,
    'enable_remote'        => true,
    'font_dir'             => storage_path('fonts/'),
    'font_cache'           => storage_path('fonts/'),
    'chroot'               => realpath(base_path()),
    'log_output_file'      => null,
    'temp_dir'             => sys_get_temp_dir(),
    'options'              => [
        'defaultMediaType'     => 'screen',
        'defaultPaperSize'     => 'a4',
        'defaultFont'          => 'DejaVu Sans',
        'dpi'                  => 96,
        'fontHeightRatio'      => 1.1,
        'isHtml5ParserEnabled' => true,
        'isRemoteEnabled'      => false,
    ],
];
