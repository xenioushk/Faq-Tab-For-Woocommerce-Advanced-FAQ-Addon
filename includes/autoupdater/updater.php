<?php

$updaterBase = 'https://projects.bluewindlab.net/wpplugin/zipped/plugins/';
$pluginRemoteUpdater = $updaterBase . 'baf/notifier_ftfwc.php';
new WpAutoUpdater(FAQTFW_ADDON_CURRENT_VERSION, $pluginRemoteUpdater, FAQTFW_ADDON_UPDATER_SLUG);
