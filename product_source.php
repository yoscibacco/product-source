<?php
/*
Plugin Name: Product Source Column for WooCommerce
Plugin URI: http:/github.com/yoscibacco/product-source
Description: Adds a "Source" column to the WooCommerce products list to display the product's source.
Version: 1.1
Author: Can Bacco
Author URI: http:/github.com/yoscibacco/
Update URI: http:/github.com/yoscibacco/product-source
GitHub Plugin URI: yoscibacco/product-source
GitHub Branch: main
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