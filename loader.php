<?php
/*
Plugin Name: CouponsInDemand
Description: This plugin enables coupon listing and coupon configuration settings.
Version: 2.2
Author: CouponsInDemand
*/
include dirname(__FILE__) . '/coupon.php';
include dirname(__FILE__) . '/configuration.php';
register_activation_hook( __FILE__, 'configuration_activate' );
//if any operation needs to take place when de-activating the plugin, enable the below line and code in the corresponding function which is in configuration.php file
//register_deactivation_hook(__FILE__, 'configuration_deactivate' );
?>