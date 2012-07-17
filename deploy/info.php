<?php

/////////////////////////////////////////////////////////////////////////////
// General information
/////////////////////////////////////////////////////////////////////////////

$app['basename'] = 'wireless';
$app['version'] = '1.2.3';
$app['release'] = '1';
$app['vendor'] = 'ClearFoundation';
$app['packager'] = 'ClearFoundation';
$app['license'] = 'GPLv3';
$app['license_core'] = 'LGPLv3';
$app['description'] = lang('wireless_app_description');

/////////////////////////////////////////////////////////////////////////////
// App name and categories
/////////////////////////////////////////////////////////////////////////////

$app['name'] = lang('wireless_app_name');
$app['category'] = lang('base_category_network');
$app['subcategory'] = lang('base_subcategory_settings');

/////////////////////////////////////////////////////////////////////////////
// Controllers
/////////////////////////////////////////////////////////////////////////////

$app['controllers']['wireless']['title'] = $app['name'];
$app['controllers']['settings']['title'] = lang('base_settings');
$app['controllers']['policy']['title'] = lang('base_app_policy');

/////////////////////////////////////////////////////////////////////////////
// Packaging
/////////////////////////////////////////////////////////////////////////////

$app['requires'] = array(
    'app-accounts',
    'app-incoming-firewall',
    'app-groups',
    'app-users',
    'app-network',
);

$app['core_requires'] = array(
    'app-network-core',
    'app-incoming-firewall-core',
    'app-radius-core',
    'hostapd',
    'openssl',
);

$app['core_directory_manifest'] = array(
    '/var/clearos/wireless' => array(),
    '/var/clearos/wireless/backup' => array(),
);

$app['core_file_manifest'] = array(
    'hostapd.php'=> array('target' => '/var/clearos/base/daemon/hostapd.php'),
);
