<?php
/*
Plugin Name: Product Source Column for WooCommerce
Plugin URI: http:/github.com/yoscibacco/product-source
Description: Adds a "Source" column to the WooCommerce products list to display the product's source.
Version: 0.1
Author: Can Bacco
Author URI: http:/github.com/yoscibacco/
Update URI: http:/github.com/yoscibacco/product-source
*/

// Add the source column to the products page
add_filter('manage_edit-product_columns', 'custom_add_source_column', 9999);

function custom_add_source_column($columns) {
    $columns['_remote_source'] = 'Kaynak'; // Column header
    return $columns;
}

// Fill the custom column content
add_action('manage_product_posts_custom_column', 'custom_display_source_column_content', 10, 2);

function custom_display_source_column_content($column, $product_id) {
    if ($column == '_remote_source') {
        $source = get_post_meta($product_id, '_remote_source', true); // Get the 'source' custom field of the product
        echo $source ? $source : 'Belirtilmemiş'; // Display the 'source' value if it exists, otherwise display "Belirtilmemiş"
    }
}

/*GITHUB SETTINGS*/

add_filter( 'update_plugins_github.com', 'self_update', 10, 4 );

/**
 * Check for updates to this plugin
 *
 * @param array  $update   Array of update data.
 * @param array  $plugin_data Array of plugin data.
 * @param string $plugin_file Path to plugin file.
 * @param string $locales    Locale code.
 *
 * @return array|bool Array of update data or false if no update available.
 */
function self_update( $update, array $plugin_data, string $plugin_file, $locales ) {

	// only check this plugin
	if ( 'product-source/product-source.php' !== $plugin_file ) {
		return $update;
	}

	// already completed update check elsewhere
	if ( ! empty( $update ) ) {
		return $update;
	}

	// let's go get the latest version number from GitHub
	$response = wp_remote_get(
		'https://api.github.com/repos/yoscibacco/product-source/releases/latest',
		array(
			'user-agent' => 'yoscibacco',
		)
	);

	if ( is_wp_error( $response ) ) {
		return;
	} else {
		$output = json_decode( wp_remote_retrieve_body( $response ), true );
	}

	$new_version_number  = $output['tag_name'];
	$is_update_available = version_compare( $plugin_data['Version'], $new_version_number, '<' );

	if ( ! $is_update_available ) {
		return false;
	}

	$new_url     = $output['html_url'];
	$new_package = $output['assets'][0]['browser_download_url'];

	error_log('$plugin_data: ' . print_r( $plugin_data, true ));
	error_log('$new_version_number: ' . $new_version_number );
	error_log('$new_url: ' . $new_url );
	error_log('$new_package: ' . $new_package );

	return array(
		'slug'    => $plugin_data['TextDomain'],
		'version' => $new_version_number,
		'url'     => $new_url,
		'package' => $new_package,
	);
}
?>