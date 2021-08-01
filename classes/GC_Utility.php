<?php
/**
 * Utility class of the plugin for helper functions
 */
class GC_Utility {

    public static function printer( $data ) {
        echo '<pre>';
        if( $data ) print_r( $data );
        else var_dump( $data );
        echo '</pre>';
    }

    public static function getBaseUri() {
        return plugin_dir_path( __DIR__ );
    }

    public static function getBaseUrl() {
        return plugin_dir_url( __DIR__ );
    }
}
