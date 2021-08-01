<?php
/**
 * Caching class, performs main task of caching.
 */

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
        ob_start();
    }

    /**
     * Generated the cache from recorded content
     */
    public function generate_cache() {
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
            $cache_file_handler = fopen( $cache_page_path, "w" );
            fwrite( $cache_file_handler, $cached_content );
            fclose( $cache_file_handler );
        }
    }

    /**
     * Check cache and serve the cached page if available
     */
    public function serve_cached_pages() {
        $path               = ABSPATH . 'wp-content/gccache/';
        $accessed_page      = "cached-" . $this->get_page_name();
        $cache_page_path    = $path . $accessed_page;
    
        if( file_exists( $cache_page_path ) ) {
            $actual_name = base64_decode( $this->get_page_name() );
            
            // this option can be managed by setting
            // #138496#1bb6ce
            echo "<script>console.info('%c Glue Cache: ', 'background-color:#17a9bf; color:#fff; border-radius: 2px;', '\'$actual_name\' served from the cache...');</script>";
            
            echo file_get_contents( $cache_page_path );
            exit();
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
        return base64_encode($url);
    }
}
new GC_Caching();