<?php

declare(strict_types=1);

return [
    'providers' => [
        'Yard\ConfigExpander\BranchViewer\BranchViewerServiceProvider' => [
            'enabled' => true
        ],
        'Yard\ConfigExpander\ACF\ACFServiceProvider' => [
            'enabled' => true
        ],
        'Yard\ConfigExpander\Protection\ProtectionServiceProvider' => [
            'enabled' => true
        ]
    ]
];
