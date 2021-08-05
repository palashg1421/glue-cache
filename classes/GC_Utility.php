<?php
/**
 * Utility class of the plugin for helper functions
 */

namespace GC;

class GC_Utility {

    /**
     * Function to print any kind of data in pre format
     */
    public static function printer( $data ) {
        echo '<pre>';
        if( $data ) print_r( $data );
        else var_dump( $data );
        echo '</pre>';
    }

    /**
     * Function to get base uri of the plugin
     */
    public static function getBaseUri() {
        return plugin_dir_path( __DIR__ );
    }

    /**
     * Function to get base url of the plugin
     */
    public static function getBaseUrl() {
        return plugin_dir_url( __DIR__ );
    }
}
