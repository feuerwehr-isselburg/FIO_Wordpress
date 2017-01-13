<?php
function mk_adminpanel_textdomain( $which_page ) {
	$template_management_textdomain = array(
		'important_notice'                                                 => __( 'Important Notice', 'mk_framework' ),
		'installing_sample_data_will_delete_all_data_on_this_website'      => __( 'Installing sample data will delete all data on this website', 'mk_framework' ),
		'yes_install'                                                      => __( 'Yes, install ', 'mk_framework' ),
		'install_sample_data'                                              => __( 'Install sample data', 'mk_framework' ),
		'uninstalling_template_will_remove_all_your_contents_and_settings' => __( 'Uninstalling template will remove all your contents and settings.', 'mk_framework' ),
		'yes_uninstall'                                                    => __( 'Yes, uninstall ', 'mk_framework' ),
		'template_uninstalled'                                             => __( 'Template uninstalled', 'mk_framework' ),
		'hooray'                                                           => __( 'Hooray', 'mk_framework' ),
		'template_installed_successfully'                                  => __( 'Template installed successfully!', 'mk_framework' ),
		'something_wierd_happened_please_retry_again'                      => __( 'Something wierd happened , please retry again', 'mk_framework' ),
		'oops'                                                             => __( 'Oops ..', 'mk_framework' ),
		'error_in_network_please_check_your_connection_and_try_again'      => __( 'Error in network , please check your connection and try again', 'mk_framework' ),
		'preview'                                                          => __( 'Preview', 'mk_framework' ),
		'install'                                                          => __( 'Install', 'mk_framework' ),
		'uninstall'                                                        => __( 'Uninstall', 'mk_framework' ),
		'downloading_sample_package_data'                                  => __( 'Downloading sample package data', 'mk_framework' ),
		'install_required_plugins'                                         => __( 'Install required plugins', 'mk_framework' ),
		'install_sample_data'                                              => __( 'Install sample data', 'mk_framework' ),
	);

	$addon_management_textdomain = array(
		'installing_notice'                           => __( 'Installing Notice', 'mk_framework' ),
		'are_you_sure_you_want_to_install' => __( 'Are you sure you want to install <strong>{param}</strong> ?', 'mk_framework' ),

		'oops'                                        => __( 'Oops ..', 'mk_framework' ),
		'something_wierd_happened_please_retry_again' => __( 'Something wierd happened , please retry again', 'mk_framework' ),

		'all_done'                                    => __( 'All Done!', 'mk_framework' ),
		'addon_is_successfully_installed'             => __( '<strong>{param}</strong> Add-on is successfully installed.', 'mk_framework' ),

		'important_notice'                         => __( 'Important Notice', 'mk_framework' ),
		'are_you_sure_you_want_to_remove_addon'       => __( 'Are you sure you want to remove <strong>{param}</strong> Add-on? <br> Note that all any data regarding this add-on will be lost.', 'mk_framework' ),
		'conitune'                                  => __( 'Conitune ', 'mk_framework' ),

		'deactivating_notice' => __( 'Deactivating Notice ', 'mk_framework' ),
		'addon_deactivate_successfully'               => __( 'Add-on deactivated successfully ', 'mk_framework' ),

		'deactivate'                                  => __( 'Deactivate', 'mk_framework' ),
		'activate'                                    => __( 'Activate', 'mk_framework' ),
		'something_went_wrong'                        => __( 'Something went wrong!', 'mk_framework' ),
	);
	$plugin_management_textdomain = array(
		'installing_notice'                           => __( 'Installing Notice', 'mk_framework' ),
		'are_you_sure_you_want_to_install' => __( 'Are you sure you want to install <strong>{param}</strong> ?', 'mk_framework' ),

		'oops'                                        => __( 'Oops ..', 'mk_framework' ),
		'something_wierd_happened_please_retry_again' => __( 'Something wierd happened , please retry again', 'mk_framework' ),

		'all_done'                                    => __( 'All Done!', 'mk_framework' ),
		'plugin_is_successfully_installed'             => __( '<strong>{param}</strong> Plugin is successfully installed.', 'mk_framework' ),

		'important_notice'                         => __( 'Important Notice', 'mk_framework' ),
		'are_you_sure_you_want_to_remove_plugin'       => __( 'Are you sure you want to remove <strong>{param}</strong> Plugin? <br> Note that all any data regarding this plugin will be lost.', 'mk_framework' ),
		'conitune'                                  => __( 'Conitune ', 'mk_framework' ),

		'deactivating_notice' => __( 'Deactivating Notice ', 'mk_framework' ),
		'plugin_deactivate_successfully'               => __( 'Plugin deactivated successfully ', 'mk_framework' ),

		'deactivate'                                  => __( 'Deactivate', 'mk_framework' ),
		'remove'                                  => __( 'Remove', 'mk_framework' ),
		'activate'                                    => __( 'Activate', 'mk_framework' ),
		'something_went_wrong'                        => __( 'Something went wrong!', 'mk_framework' ),
	);
	switch ( $which_page ) {
		case 'template-management':
		return $template_management_textdomain;
		break;
		case 'addon-management':
		return $addon_management_textdomain;
		break;
		case 'plugin-management':
		return $plugin_management_textdomain;
		break;
	}
}
