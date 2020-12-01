<?php

$addonJson = json_decode(file_get_contents(__DIR__ . '/addon.json'));


if (!defined('MX_CP_CSSJS_NAME')) {
    define('MX_CP_CSSJS_NAME', $addonJson->name);
    define('MX_CP_CSSJS_VERSION', $addonJson->version);
    define('MX_CP_CSSJS_DOCS', '');
    define('MX_CP_CSSJS_DESCRIPTION', $addonJson->description);
    define('MX_CP_CSSJS_AUTHOR', 'Max Lazar');
    define('MX_CP_CSSJS_DEBUG', false);
}

//$config['MX_CP_CSSJS_tab_title'] = MX_CP_CSSJS_NAME;

return [
    'name' => $addonJson->name,
    'description' => $addonJson->description,
    'version' => $addonJson->version,
    'namespace' => $addonJson->namespace,
    'author' => 'Max Lazar',
    'author_url' => 'https://eecms.dev',
    'settings_exist' => true,
    // Advanced settings
    'services' => [],
];
