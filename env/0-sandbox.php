<?php
/**
 * These are stubs for closed source code, or things that only apply to local environments.
 */

defined( 'WPINC' ) || die();

require_once WPMU_PLUGIN_DIR . '/wporg-mu-plugins/mu-plugins/loader.php';

/**
 * Configure Frontend Uploader.
 *
 * Set the filters to avoid needing to configure anything in UI.
 */
add_filter(
	'pre_option_frontend_uploader_settings',
	function ( $value ) {
		return array(
			'enable_akismet_protection' => 'off',
			'enable_recaptcha_protection' => 'off',
			'recaptcha_site_key' => '',
			'recaptcha_secret_key' => '',
			'notify_admin' => 'off',
			'admin_notification_text' => 'Someone uploaded a new photo, please moderate at: https://wordpress.org/photos/wp-admin/edit.php?post_type=photo',
			'notification_email' => '',
			'show_author' => 'off',
			'enabled_post_types' => array(
				'photo' => 'photo',
			),
			'wysiwyg_enabled' => 'off',
			'enabled_files' => array(
				'jpg|jpeg|jpe' => 'jpg|jpeg|jpe',
			),
			'auto_approve_user_files' => 'off',
			'auto_approve_any_files' => 'off',
			'obfuscate_file_name' => 'on',
			'suppress_default_fields' => 'off',
		);
	}
);
