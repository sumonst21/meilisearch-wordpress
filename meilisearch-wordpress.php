<?php
    /*
    Plugin Name: Meilisearch Wordpress
    Plugin URI: https://wordpress.meilisearch.dev
    description: The best search experience in wordpress with Meilisearch
    Version: 0.1.1
    Author: Meilisearch
    Author URI: https://meilisearch.com
    License: MIT
    Text Domain: meilisearch-wordpress
    */

    require_once __DIR__ . '/vendor/autoload.php';
    require_once __DIR__ . '/src/search_widget.php';
    require_once __DIR__ . '/src/admin/meilisearch_admin.php';
    require_once __DIR__ . '/src/admin/utils.php';

    function meilisearch_scripts() {
        wp_register_style( 'meilisearch_widget', plugin_dir_url( __FILE__ ).'src/css/meilisearch_widget.css' );
        wp_enqueue_style('meilisearch_widget');
    }

    add_action('wp_insert_post', 'index_post_after_update', 1000, 3);
    add_action('rest_after_insert_post', 'index_post_after_meta_update', 1000, 2);
    add_action('wp_trash_post', 'delete_post_from_index');
    add_action( 'wp_enqueue_scripts', 'meilisearch_scripts' );
    register_activation_hook( __FILE__, 'meilisearch_wordpress_activate' );

    if ( is_admin() )
        $meilisearch = new MeiliSearch();

        $meilisearch_options = get_option( 'meilisearch_option_name' );
        $meilisearch_url = $meilisearch_options['meilisearch_url_0'];
        $meilisearch_search_url = $meilisearch_options['meilisearch_search_url_4'];
        $meilisearch_public_key = $meilisearch_options['meilisearch_public_key_2'];
        $meilisearch_index_name = $meilisearch_options['meilisearch_index_name'];

        // Tell the admin to configure the plugin before using it
        if (!isset($meilisearch_options['meilisearch_url_0']) || !isset($meilisearch_options['meilisearch_private_key_1'])){
            add_action('admin_notices', 'meilisearch_admin_notice');
        }

        function meilisearch_admin_notice(){
            ?>
            <div class="notice notice-error is-dismissible">
                <p><?php _e( 'Meilisearch is not configured. Please configure it in the Meilisearch settings page.', 'meilisearch-wordpress' ); ?></p>
            </div>
            <?php
        }



?>
