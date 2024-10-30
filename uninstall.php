<?php
if (!defined('WP_UNINSTALL_PLUGIN')) {
    die;
}
$plugin_settings = get_option('iaposter_options');
$delete_set_explicitly =  false !== $plugin_settings
    && isset( $plugin_settings['delete_all_data_on_uninstall'] )
    && true == $plugin_settings['delete_all_data_on_uninstall'];

if ( !$plugin_settings || $delete_set_explicitly  ) {
    global $wpdb;
    //Remove options
    $options_to_remove = array(
        'iaposter_version',
        'iaposter_options',
        'iaposter_token',
        'iaposter_license',
    );
    foreach($options_to_remove as $option_name){
        delete_option( $option_name );
    }
    
    //Remove tables
    $tables_to_remove = array(
        'iaposter_logs',
        'iaposter_queue'
    );
    foreach($tables_to_remove as $table_name) {
        $prefixed_table_name = $wpdb->prefix . $table_name;
        $wpdb->query( "DROP TABLE IF EXISTS $prefixed_table_name;" );
    }
    //Clean up meta
    delete_post_meta_by_key( 'iaposter_meta' );
}