<?php
    $settings = get_option( 'gc_settings' );
    if( isset( $settings['purge_on_deletion'] ) && ( $settings['purge_on_deletion'] == 1 ) ) {
        $dir = ABSPATH . 'wp-content/gccache/';
        $list = scandir( $dir );
        foreach( $list as $item ) {
            if( !( $item == '.' || $item == '..' ) ) {
                $path = $dir . $item;
                unlink ( $path );
            }
        }
        rmdir( $dir );
    }