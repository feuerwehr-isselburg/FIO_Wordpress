<?php
global $abb_phpunit;
$abb_phpunit = true;

define( 'WP_PATH', dirname( dirname( dirname( dirname( dirname( __FILE__ ) ) ) ) ) );

require_once WP_PATH . '/wp-tests-includes/functions.php';
function mk_manually_load_environment() {
	// Add your theme.
	switch_theme( 'Jupiter' );
	// Update array with plugins to include ...
	$plugins_to_active = array(
		'js_composer_theme/js-composer.php'
	);
	update_option( 'active_plugins', $plugins_to_active );
}

tests_add_filter( 'muplugins_loaded', 'mk_manually_load_environment' );
require  WP_PATH . '/wp-tests-includes/bootstrap.php';
