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

    /**
     * Function to set message in flash
     */
    public static function setFlash( $key, $value ) {
        @session_start();
        $_SESSION[$key] = $value;
    }

    /**
     * Function to check data in flash
     */
    public static function hasFlash( $key ) {
        @session_start();
        return isset( $_SESSION[$key] );
    }

    /**
     * Function to get data from flash
     */
    public static function getFlash( $key ) {
        @session_start();
        $message = $_SESSION[$key];
        unset( $_SESSION[$key] );
        return $message;
    }

    /**
     * Function to get message from store array
     */
    public static function getMessage( $key ) {
        $message = [];
        
        $message['SETTING_SVD'] = __( 'Settings saved successfully', 'gccache' );

        if( isset( $message[$key] ) ) {
            return $message[$key];
        } else {
            return __( 'No specific message found', 'gccache' );
        }
    }

    /**
     * Function to get composed HTML to show notices
     */
    public static function getNoticeHtml( $message, $type, $isDismissable = 0 ) {
        switch( $type ) {
            case 'success':
                $class = 'success';
            break;
            case 'error':
                $class = 'error';
            break;
            case 'warning':
                $class = 'warning';
            break;
            case 'info':
                $class = 'info';
            break;
                $class = 'warning';
        }
        if( $isDismissable ) {
            $html = "<div class='notice notice-$class is-dismissible'> 
                <p><strong>$message</strong></p>
            </div>";
        } else {
            $html = "<div class='notice notice-$class'> 
                <p><strong>$message</strong></p>
            </div>";
        }

        return $html;
    }
}
