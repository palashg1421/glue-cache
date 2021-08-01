<?php
/**
 * Caching class, performs main task of caching
 */
class GC_Admin {

    public function __construct() {
        add_action( 'admin_enqueue_scripts',    [ $this, 'scriptsForAdmin' ] );
        add_action( 'admin_menu',               [ $this, 'addMenuPages' ] );
        add_action( 'init',                     [ $this, 'onSettingSave'] );
        add_action( 'admin_bar_menu',           [ $this, 'addLinkToAdminBar' ], 100);
    }

    public function scriptsForAdmin() {
        wp_enqueue_style( 'admin-css', GC_Utility::getBaseUrl() . 'assets/css/style.css' );
    }

    public function addMenuPages() {

        add_menu_page(
            __('Glue Cache', 'gluecache'),
            __('Glue Cache', 'gluecache'),
            'administrator',
            'glue-cache',
            function(){
                $settings = get_option( 'gc_settings' );
                require GC_Utility::getBaseUri() . '/templates/template-settings.php';
            },
            'dashicons-superhero'
        );
        
    }

    public function onSettingSave() {

        if( isset( $_POST['saveGlueSettings'] ) ) {
            if( $_REQUEST['_wpnonce'] && wp_verify_nonce( $_REQUEST['_wpnonce'], 'gluecache_nonce' ) ) {
            
                $data = [];
                $data['gc_do_console'] = isset( $_POST['gc_do_console'] ) ? $_POST['gc_do_console'] : 0;
                update_option( 'gc_settings', $data );
                wp_redirect( admin_url() . 'admin.php?page=glue-cache&update=1' );
                exit();
            } else {
                die('nonce error');
            }
        }
        if( isset( $_POST['purgeGlueCache'] ) ) {
            $this->purgeAllCache();
            wp_redirect( admin_url() . 'admin.php?page=glue-cache&purge=1' );
            exit();
        }
        if( isset( $_GET['do-purge'] ) && ( 1 == $_GET['do-purge'] ) ) {
            $this->purgeAllCache();
            wp_redirect( $_SERVER['HTTP_REFERER'] );
            exit();
        }
    }

    public function addLinkToAdminBar( $admin_bar ) {
        if( !is_admin() ) {
            $admin_bar->add_menu( [
                'id'    => 'glue-cache',
                'title' => "<span class='glue-cache-ab-icon-red'></span>" . __( 'Glue Cache', 'gluecache' ),
                'href'  => ''
            ] );
            $admin_bar->add_menu( [
                'id'        => 'purge-all-cache',
                'parent'    => 'glue-cache',
                'title'     => __( 'Purge All Cache', 'gluecache' ),
                'href'      => admin_url() . '?page=glue-cache&do-purge=1'
            ] );
        }
    }

    public function purgeAllCache() {
        $dir = ABSPATH . 'wp-content/gccache/';
        $list = scandir( $dir );
        foreach( $list as $item ) {
            if( !( $item == '.' || $item == '..' ) ) {
                $path = $dir . $item;
                unlink ( $path );
            }
        }
    }

    public function purgePageCache( $url ) {
    }
    
}
new GC_Admin();