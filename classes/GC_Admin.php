<?php
/**
 * Caching class, performs main task of caching
 */
class GC_Admin {

    public function __construct() {
        add_action( 'admin_menu',   [ $this, 'addMenuPages' ] );
        add_action( 'init',         [ $this, 'onChangeConfiguration'] );
    }

    public function addMenuPages() {

        add_menu_page(
            __('Glue Cache', 'gluecache'),
            __('Glue Cache', 'gluecache'),
            'administrator',
            'glue-cache',
            function(){
                $settings = get_option( 'gc_settings' );
                require plugin_dir_path(__DIR__) . '/templates/template-settings.php';
            },
            'dashicons-superhero'
        );
        
    }

    public function onChangeConfiguration() {

        if( isset( $_POST['saveGlueSettings'] ) ) {
            if( $_REQUEST['_wpnonce'] && wp_verify_nonce( $_REQUEST['_wpnonce'], 'gluecache_nonce' ) ) {
            
                $data = [];
                $data['gc_do_console'] = isset( $_POST['gc_do_console'] ) ? $_POST['gc_do_console'] : 0;
                update_option( 'gc_settings', $data );
                wp_redirect( admin_url() . 'admin.php?page=glue-cache&update=1' );
                // exit();
            } else {

                die('nonce error');

            }
        }
        if( isset( $_POST['purgeGlueCache'] ) ) {
        }
    }
    
}
new GC_Admin();