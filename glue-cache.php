<?php
/**
 * Plugin Name: Glue Cache
 * Description: A handy plugin which create server side cache.
 * Version:     1.0
 * Author:      Palash Gupta
 * License:     GPLv2 or later
 * License URI: http://www.gnu.org/licenses/old-licenses/gpl-2.0.html
 * Text Domain: gluecache
 * Domain Path: /languages
 * Requires at least: 5.1
 * Requires PHP: 7.3
 * 
 * This program is free software; you can redistribute it and/or modify it under the terms of the GNU
 * General Public License version 2, as published by the Free Software Foundation. You may NOT assume
 * that you can use any other version of the GPL.
 *
 * This program is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without
 * even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 */

/**
 * Exit if accessed this file directly
 */
if ( !defined( 'ABSPATH' ) )
    die( 'Invalid request.' );

if( file_exists( __DIR__ . './vendor/autoload.php') ):
    require_once __DIR__ . '/vendor/autoload.php';
endif;

/**
 * Plugin activation hook
 */
register_activation_hook( __FILE__, function() {} );

/**
 * Plugin deactivation hook
 */
register_deactivation_hook( __FILE__, function() {} );

/**
 * Include core classes
 */
new GC\GC_Caching;
new GC\GC_Admin;
