<?php
/**
 * Plugin Name: Store Locator Plus : Janitor
 * Plugin URI: http://www.storelocatorplus.com/products/store-locator-plus-janitor/
 * Description: A free add-on to assist in clean up of settings for the Store Locator Plus plugin.
 * Version: 4.1.11
 * Author: Charleston Software Associates
 * Author URI: http://charlestonsw.com/
 * Requires at least: 3.4
 * Tested up to : 4.1
 *
 * Text Domain: csa-slp-janitor
 * Domain Path: /languages/
 *
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

// No SLP? Get out...
//
include_once( ABSPATH . 'wp-admin/includes/plugin.php' );
if ( !function_exists('is_plugin_active') ||  !is_plugin_active( 'store-locator-le/store-locator-le.php')) {
    return;
}

// If we have not been here before, let's get started...
//
if ( ! class_exists( 'SLPJanitor' ) ) {

    /**
    * Janitor
    *
    * @package StoreLocatorPlus\Janitor
    * @author Lance Cleveland <lance@charlestonsw.com>
    * @copyright 2013-2015 Charleston Software Associates, LLC
    */
    class SLPJanitor {

        //-------------------------------------
        // Constants
        //-------------------------------------
    
        //-------------------------------------
        // Constants
        //-------------------------------------

        /**
         * @const string VERSION the current plugin version.
         */
        const VERSION = '4.1.11';

        /**
         * @const string MIN_SLP_VERSION the minimum SLP version required for this version of the plugin.
         */
        const MIN_SLP_VERSION = '4.0';

        /**
         * Our options are saved in this option name in the WordPress options table.
         */
        const OPTION_NAME = 'csl-slplus-JANITOR-options';

        /**
         * Our plugin slug.
         */
        const PLUGIN_SLUG = 'slp-janitor';    

        /**
         * Our admin page slug.
         */
        const ADMIN_PAGE_SLUG = 'slp_janitor';

        //-------------------------------------
        // Properties
        //-------------------------------------

        /**
         * WordPress data about this plugin.
         *
         * @var mixed[] $metadata
         */
        public $metadata;
        
        public $options = array(
            'installed_version' => '',
        );        
        
        /**
         * The base plugin.
         *
         * @var \SLPLus $plugin
         */
        public  $slplus = null;

        /**
         * Slug for this plugin.
         *
         * @var string $slug
         */
        public $slug;

        //-------------------------------------
        // Methods
        //-------------------------------------

        /**
         * Invoke the plugin as singleton.
         *
         * @static
         */
        public static function init() {
            static $instance = false;
            if ( !$instance ) {
                load_plugin_textdomain( 'csa-slp-janitor', false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' );
                $instance = new SLPJanitor();
            }
            return $instance;
        }


        /**
         * Constructor.
         */
        function __construct() {
            $this->name = __('Janitor','csa-slp-janitor');
            $this->dir  = plugin_dir_path( __FILE__ );
            $this->slug = plugin_basename( __FILE__ );
            $this->url  = plugins_url( '' , __FILE__ );

            add_action('slp_init_complete'             ,array($this,'slp_init'              )       );
            add_action('slp_admin_menu_starting'       ,array($this,'admin_menu'            )       );
            add_filter('slp_menu_items'                ,array($this,'filter_AddMenuItems'   ),100   );
        }


        //-------------------------------------------------------------
        // METHODS :: STANDARD SLP ADD ON ADMIN INIT
        //
        // All admin related hooks and filters should go in admin init.
        //
        // This saves a ton of overhead on stacking admin-only calls
        // that will never get called unless you are on the admin
        // interface.
        //-------------------------------------------------------------

        /**
         * If we are in admin mode, run our admin updates.
         */
        function admin_init() {
            $this->createobject_Admin();
            $this->metadata = get_plugin_data(__FILE__, false, false);
            
            // Activate/Update Processing
            if ( version_compare( $this->options['installed_version'] , SLPJanitor::VERSION , '<' ) ) {
                $this->options['installed_version'] = SLPJanitor::VERSION;
                update_option(SLPJanitor::OPTION_NAME,$this->options);
            }              
        }

        /**
         * WordPress admin_menu hook.
         *
         * Do not put any hooks/filters here other than the admin init hook.
         */
        function admin_menu(){
            if (!$this->setPlugin()) { return ''; }
            add_action('admin_init' ,array($this,'admin_init'));
        }


        /**
         * Create and attach the admin processing object.
         */
        function createobject_Admin() {
            if (!isset($this->Admin)) {
                require_once($this->dir.'include/class.admin.php');
                $this->Admin =
                    new SLPJanitor_Admin(
                        array(
                            'addon'     => $this,
                            'slplus'    => $this->slplus,
                        )
                    );
            }
        }

        /**
         * Create the Admin Tab.
         *
         * It is hooked here to ensure the AdminUI object is instantiated first.
         */
        function createpage_AdminTab() {
            $this->createobject_Admin();
            $this->Admin->render_AdminPage();
        }        

        /**
         * Add the tabs/main menu items.
         *
         * @param mixed[] $menuItems
         * @return mixed[]
         */
        function filter_AddMenuItems($menuItems) {
            return array_merge(
                        $menuItems,
                        array(
                            array(
                                'label'     => $this->name,
                                'slug'      => SLPJanitor::ADMIN_PAGE_SLUG,
                                'class'     => $this,
                                'function'  => 'createpage_AdminTab'
                            ),
                        )
                    );
        }

        /**
         * Do this after SLP initiliazes.
         *
         * @return null
         */
        function slp_init() {
            if (!$this->setPlugin()) { return; }
            $this->slplus->register_addon(plugin_basename(__FILE__), $this);
        }

        /**
         * Set the plugin property to point to the primary plugin object.
         *
         * Returns false if we can't get to the main plugin object.
         *
         * @global wpCSL_plugin__slplus $slplus_plugin
         * @return boolean true if plugin property is valid
         */
        function setPlugin() {
            if (!isset($this->slplus) || ($this->slplus == null)) {
                global $slplus_plugin;
                $this->slplus = $slplus_plugin;
            }
            return (isset($this->slplus) && ($this->slplus != null));
        }
    }

    add_action('init' ,array('SLPJanitor','init'               ));
}
// Dad. Explorer. Rum Lover. Code Geek. Not necessarily in that order.
