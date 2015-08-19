<?php
/**
 * Plugin Name: Store Locator Plus : Janitor
 * Plugin URI: http://www.storelocatorplus.com/products/store-locator-plus-janitor/
 * Description: A free add-on to assist in clean up of settings for the Store Locator Plus plugin.
 * Author: Store Locator Plus
 * Author URI: http://www.storelocatorplus.com/
 * Requires at least: 3.8
 * Tested up to : 4.3.00
 * Version: 4.3
 *
 * Text Domain: csa-slp-janitor
 * Domain Path: /languages/
 *
 */

if ( ! defined( 'ABSPATH' ) ) { exit; }
include_once( ABSPATH . 'wp-admin/includes/plugin.php' );
if ( !function_exists('is_plugin_active') ||  !is_plugin_active( 'store-locator-le/store-locator-le.php')) {
    return;
}

// If we have not been here before, let's get started...
//
if ( ! class_exists( 'SLPJanitor' ) ) {

	require_once( WP_PLUGIN_DIR . '/store-locator-le/include/base_class.addon.php');

	/**
 	 * Janitor
	 *
	 * @property    SLPJanitor      $addon
	 * @property    SLPJanitor      $instance   static for singleton model
	 *
	 * @package StoreLocatorPlus\Janitor
	 * @author Lance Cleveland <lance@charlestonsw.com>
	 * @copyright 2013 - 2015 Charleston Software Associates, LLC
	 */
    class SLPJanitor extends SLP_BaseClass_Addon {
	    public $addon;
	    public static $instance;

        /**
         * Invoke the plugin as singleton.
         *
         * @static
         */
        public static function init() {
            static $instance = false;
            if ( !$instance ) {
                load_plugin_textdomain( 'csa-slp-janitor', false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' );
                $instance = new SLPJanitor(
	                array(
		                'version'           => '4.3.00'                             ,
		                'min_slp_version'   => '4.3.00'                             ,
		                'name'              => __('Janitor' , 'csa-slp-janitor')    ,
		                'option_name'       => 'csl-slplus-JANITOR-options'         ,
		                'file'              => __FILE__                             ,

		                'admin_class_name'  => 'SLPJanitor_Admin'
	                )
                );
            }
            return $instance;
        }

        /**
         * Add the tabs/main menu items.
         *
         * @param mixed[] $menu_items
         * @return mixed[]
         */
        function filter_AddMenuItems( $menu_items ) {
	        $this->admin_menu_entries = array (array(
                    'label'     => $this->name,
                    'slug'      => $this->addon->short_slug,
                    'class'     => $this->admin,
                    'function'  => 'render_AdminPage'
            ));
	        return parent::filter_AddMenuItems( $menu_items );
        }
    }

    add_action( 'init' ,array('SLPJanitor','init' ) );
}
// Dad. Explorer. Rum Lover. Code Geek. Not necessarily in that order.
