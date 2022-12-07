<?php
/**
 * Caching class, performs main task of caching.
 */

namespace GC;

class GC_Caching {

    public function __construct() {
        add_action( 'setup_theme',              [ $this, 'start_capture' ] );
        add_action( 'wp_print_footer_scripts',  [ $this, 'generate_cache' ], 100 );
        add_action( 'template_redirect',        [ $this, 'serve_cached_pages' ] );
    }

    /**
     * Start recording the pages
     */
    public function start_capture() {
        if( !$this->page_exception() ) {
            ob_start();
        }
    }

    /**
     * Generated the cache from recorded content
     */
    public function generate_cache() {
        if( !$this->page_exception() ) {
            $path = ABSPATH . 'wp-content/gccache/';
            
            /**
             * Check and create cache folder if not exists
             */
            if( !file_exists( $path ) ) {
                mkdir( $path, 0777, true );
            }
    
            /**
             * Create cache for the current page if not exist
             */
            $current_page       = $this->get_page_name();
            $page_in_cache      = "cached-$current_page";
            $cache_page_path    = $path . $page_in_cache;
        
            if( !file_exists( $cache_page_path ) ) {
                $cached_content = ob_get_contents();
                $cached_content .= "</body></html>";
                ob_flush();
    
                /**
                 * Compressing the page before caching
                 */
                $content = $this->minification( $cached_content );
    
                $cache_file_handler = fopen( $cache_page_path, "w" );
                fwrite( $cache_file_handler, $content );
                fclose( $cache_file_handler );
            }
        }
    }

    /**
     * Check cache and serve the cached page if available
     */
    public function serve_cached_pages() {
        if( !$this->page_exception() ) {
            $path               = ABSPATH . 'wp-content/gccache/';
            $accessed_page      = "cached-" . $this->get_page_name();
            $cache_page_path    = $path . $accessed_page;
            if( file_exists( $cache_page_path ) ) {
                
                /**
                 * Show message on console if enable from settings
                 */
                $settings = get_option( 'gc_settings' );
                if( isset( $settings['show_console_msg'] ) && 1 == $settings['show_console_msg'] ) {
                    echo "<script>console.info('%c🗲Glue Cache Working:', 'color: #004085; background-color: #D1ECF1; border-radius: 4px; padding: 4px;',  'Served from cache...');</script>";
                }
    
                echo file_get_contents( $cache_page_path );
                exit();
            }
        }
    }

    /**
     * Get name of currently accessed page by the global users
     */
    public function get_page_name() {
        $scheme	= $_SERVER['REQUEST_SCHEME'];
        $host	= $_SERVER['HTTP_HOST'];
        $uri	= $_SERVER['REQUEST_URI'];
        $url    = $scheme . '://' . $host . $uri;
        return base64_encode( $url );
    }

    /**
     * Minifi HTML before caching
     */
    public function minification( $content ) {
        $minifiedContent = preg_replace( '/>(\s)+/', '>', $content );
        $minifiedContent = preg_replace( '/{(\s)*/', '{', $minifiedContent );
        $minifiedContent = preg_replace( '/}(\s)*/', '}', $minifiedContent );
        $minifiedContent = preg_replace( '/;(\s)*/', ';', $minifiedContent );
        $minifiedContent = preg_replace( '/;(\s)*/', ';', $minifiedContent );
        $minifiedContent = preg_replace( '/(",)(\s)*/', '",', $minifiedContent );
        $minifiedContent = preg_replace( '/(\s)+}/', '}', $minifiedContent );
        $minifiedContent = preg_replace( '/<!--[^\\[<>].*?(?<!!)-->/', '', $minifiedContent );
        return $minifiedContent;
    }

    /**
     * Manages the page not to be cached
     */
    public function page_exception() {
        global $pagenow;

        if( current_user_can( 'manage_options' ) ) {
            return true;
        }

        if( is_admin() || is_network_admin() ) {
            return true;
        }
        if( 'wp-login.php' === $pagenow ) {
            return true;
        }

        $targetPages    = ['cart', 'checkout'];
        $uriSegements   = explode( '/', $_SERVER['REQUEST_URI'] );
        $uriSegements   = array_filter( $uriSegements );
        $slug           = end( $uriSegements );
        if( in_array( $slug, $targetPages) ) {
            return true;
        }
    }

}