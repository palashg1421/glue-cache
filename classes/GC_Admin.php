<?php
/**
 * Caching class, performs main task of caching
 */

namespace GC;

class GC_Admin {

    public function __construct() {
        add_action( 'admin_enqueue_scripts',    [ $this, 'scriptsForAdmin' ] );
        add_action( 'admin_menu',               [ $this, 'addMenuPages' ] );
        add_action( 'init',                     [ $this, 'actionsOnInit'] );
        add_action( 'admin_bar_menu',           [ $this, 'addLinkToAdminBar' ], 100 );
        add_action( 'admin_notices',            [ $this, 'adminNotices'] );
    }

    /**
     * Function to enqueue admin scripts
     */
    public function scriptsForAdmin() {
        wp_enqueue_style( 'admin-css', GC_Utility::getBaseUrl() . 'assets/css/style.css' );
    }

    /**
     * Function to add menu pages for the pluguin in admin panel
     */
    public function addMenuPages() {

        add_menu_page(
            __( 'Glue Cache', 'gluecache' ),
            __( 'Glue Cache', 'gluecache' ),
            'administrator',
            'glue-cache',
            function(){
                $settings = get_option( 'gc_settings' );
                require GC_Utility::getBaseUri() . '/templates/template-settings.php';
            },
            'dashicons-superhero'
        );
        
    }

    /**
     * function to perform various action on init hook
     */
    public function actionsOnInit() {

        /**
         * prge cache from admin bar
         */
        if( isset( $_GET['do-purge'] ) && ( 1 == $_GET['do-purge'] ) ) {
            $this->purgeAllCache();
            wp_redirect( $_SERVER['HTTP_REFERER'] );
            exit();
        }

        /**
         * Save plugin settings from backedn
         */
        if( isset( $_POST['gc_save_setting'] ) ) {
            unset( $_POST['gc_save_setting'] );
            update_option( 'gc_settings', $_POST );
            $url = admin_url( '?page=glue-cache' );
            GC_Utility::setFlash( 'setting_saved', GC_Utility::getMessage('SETTING_SVD') );
            wp_redirect( $url );
            exit();
        }
    }

    /**
     * Functin to add purge cache link to admin bar
     */
    public function addLinkToAdminBar( $admin_bar ) {
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

    /**
     * function to perform purging of all the cache
     */
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

    /**
     * function to purge cache of specific page
     */
    public function purgePageCache( $url ) {
    }

    /**
     * function to set admin notices
     */
    public function adminNotices() {
        if( GC_Utility::hasFlash( 'setting_saved' ) ) {
            $message = GC_Utility::getFlash( 'setting_saved' );
            echo GC_Utility::getNoticeHtml( $message, 'success' );
        }
    }
    
}