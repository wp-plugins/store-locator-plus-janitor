<?php
/**
 * Plugin Name: Store Locator Plus : Janitor
 * Plugin URI: http://www.charlestonsw.com/products/store-locator-plus-janitor/
 * Description: A free add-on to assist in clean up of settings for the Store Locator Plus plugin.
 * Version: 0.04
 * Author: Charleston Software Associates
 * Author URI: http://charlestonsw.com/
 * Requires at least: 3.3
 * Test up to : 3.7.1
 *
 * Text Domain: csa-slp-janitor
 * Domain Path: /languages/
 *
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

// No SLP? Get out...
//
if ( !in_array( 'store-locator-le/store-locator-le.php', apply_filters( 'active_plugins', get_option('active_plugins')))) {
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
    * @copyright 2013 Charleston Software Associates, LLC
    */
    class SLPJanitor {

        //-------------------------------------
        // Constants
        //-------------------------------------

        /**
         * Our admin page slug.
         */
        const ADMIN_PAGE_SLUG = 'slp_janitor';

        //-------------------------------------
        // Properties
        //-------------------------------------

        
        /**
         * Reset these even if the add-on packs are inactive.
         * 
         * Also used for inspection.
         * 
         * @var string[] $optionList
         */
        private $optionList =
            array(

                // wpCSL & Base plugin
                '-- Store Locator Plus',
                'csl-slplus-db_version'                                 ,
                'csl-slplus-disable_find_image'                         ,
                'csl-slplus_email_form'                                 ,
                'csl-slplus-force_load_js'                              ,
                'csl-slplus-installed_base_version'                     ,
                'csl-slplus-map_language'                               ,
                'csl-slplus-options'                                    ,
                'csl-slplus-theme'                                      ,
                'csl-slplus-theme_array'                                ,
                'csl-slplus-theme_details'                              ,
                'csl-slplus-theme_lastupdated'                          ,

                // Add On Packs
                //
                // ER: Enhanced Results
                '-- Enhanced Results',
                'csl-slplus_disable_initialdirectory'                   ,
                'csl-slplus-enhanced_results_hide_distance_in_table'    ,
                'csl-slplus-enhanced_results_orderby'                   ,
                'csl-slplus-ER-options'                                 ,
                'csl-slplus_label_directions'                           ,
                'csl-slplus_label_fax'                                  ,
                'csl-slplus_label_hours'                                ,
                'csl-slplus_label_phone'                                ,
                'csl-slplus_maxreturned'                                ,
                'csl-slplus_message_noresultsfound'                     ,
                'csl-slplus_slper'                                      ,
                'csl-slplus_use_email_form'                             ,

                // ES: Enhanced Search
                '-- Enhanced Search',
                'csl-slplus-ES-options'                                 ,
                'csl-slplus-enhanced_search_hide_search_form'           ,
                'csl-slplus_show_search_by_name'                        ,
                'csl-slplus_search_by_state_pd_label'                   ,
                'csl-slplus_slpes'                                      ,
                'slplus_show_state_pd'                                  ,

                // PRO: Pro Pack
                '-- Pro Pack',
                'csl-slplus-PRO-options'                                ,

                // SE: SuperExtendo
                '-- Super Extendo',
                'slplus-extendo-options'                                ,
                );

        /**
         * The base plugin.
         *
         * @var \SLPLus $plugin
         */
        public  $plugin = null;

        /**
         * Slug for this plugin.
         *
         * @var string $slug
         */
        private $slug;

        //-------------------------------------
        // Methods
        //-------------------------------------


        /**
         * Constructor.
         */
        function __construct() {
            $this->slug = plugin_basename(__FILE__);
            $this->name = __('Janitor','csa-slp-janitor');

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
            if (!$this->setPlugin()) { return ''; }

            // Admin skinning and scripts
            //
            add_filter('wpcsl_admin_slugs'          , array($this,'filter_AddOurAdminSlug'  ));
        }

        /**
         * WordPress admin_menu hook.
         *
         * Do not put any hooks/filters here other than the admin init hook.
         */
        function admin_menu(){
            add_action('admin_init' ,array($this,'admin_init'));
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
                                'function'  => 'render_AdminPage'
                            ),
                        )
                    );
        }

        /**
         * Add our admin pages to the valid admin page slugs.
         *
         * @param string[] $slugs admin page slugs
         * @return string[] modified list of admin page slugs
         */
        function filter_AddOurAdminSlug($slugs) {
            return array_merge($slugs,
                    array(
                        SLPJanitor::ADMIN_PAGE_SLUG,
                        SLP_ADMIN_PAGEPRE.SLPJanitor::ADMIN_PAGE_SLUG,
                        )
                    );
        }

        /**
         * Render the admin page
         */
        function render_AdminPage() {

            // If we are running a reset.
            //
            $resetInfo = array();
            if ((isset($_REQUEST['action'  ]) && ($_REQUEST['action']==='update'))){
                $resetInfo = $this->reset_Settings();
            }

            // Setup and render settings page
            //
            $this->Settings = new wpCSL_settings__slplus(
                array(
                        'prefix'            => $this->plugin->prefix,
                        'css_prefix'        => $this->plugin->prefix,
                        'url'               => $this->plugin->url,
                        'name'              => $this->plugin->name . ' - ' . $this->name,
                        'plugin_url'        => $this->plugin->plugin_url,
                        'render_csl_blocks' => false,
                        'form_action'       => admin_url().'admin.php?page='.SLPJanitor::ADMIN_PAGE_SLUG
                    )
             );

            //-------------------------
            // Navbar Section
            //-------------------------
            $this->Settings->add_section(
                array(
                    'name'          => 'Navigation',
                    'div_id'        => 'navbar_wrapper',
                    'description'   => $this->plugin->AdminUI->create_Navbar(),
                    'innerdiv'      => false,
                    'is_topmenu'    => true,
                    'auto'          => false,
                    'headerbar'     => false
                )
            );

            //-------------------------
            // General Settings
            //-------------------------
            $sectName = __('Settings','csa-slp-janitor');
            $this->Settings->add_section(array('name' => $sectName));

            // Settings : Reset
            //
            $groupName = __('Reset','csa-slp-janitor') ;
            $this->Settings->add_ItemToGroup(
                array(
                    'section'       => $sectName                    ,
                    'group'         => $groupName                   ,
                    'label'         => __('About','csa-slp-janitor')     ,
                    'type'          => 'subheader'                  ,
                    'show_label'    => false                        ,
                    'description'   =>
                        __('Clearing settings is a destructive process that cannot be undone. ' ,'csa-slp-janitor') .
                        __('Locations will not be deleted. '                                    ,'csa-slp-janitor') .
                        __('Make sure you have a full backup of your site before proceeding. '  ,'csa-slp-janitor') .
                        __('The plugins that will be reset include: ' ,'csa-slp-janitor')       . '<br/>'           .
                        SLPlus::linkToSLP . '<br/>' .
                        SLPlus::linkToER  . '<br/>' .
                        SLPlus::linkToSE  . '<br/>'
                    )
                );
            $this->Settings->add_ItemToGroup(
                array(
                    'section'       => $sectName                    ,
                    'group'         => $groupName                   ,
                    'type'          => 'submit_button'              ,
                    'show_label'    => false                        ,
                    'value'         => __('Reset SLP Options','csa-slp-janitor')
                    )
                );

            if (count($resetInfo)>0) {
                $this->Settings->add_ItemToGroup(
                    array(
                        'section'       => $sectName                    ,
                        'group'         => $groupName                   ,
                        'label'         => __('Cleared SLP Options:','csa-slp-janitor')     ,
                        'type'          => 'subheader'                  ,
                        'show_label'    => false                        ,
                        'description'   => implode('<br/>',$resetInfo)
                        )
                    );
            }

            // Settings: Inspect
            //
            $groupName = __('Inspect','csa-slp-janitor') ;
            $this->Settings->add_ItemToGroup(
                array(
                    'section'       => $sectName                    ,
                    'group'         => $groupName                   ,
                    'label'         => __('Current Settings','csa-slp-janitor')     ,
                    'type'          => 'subheader'                  ,
                    'show_label'    => false                        ,
                    'description'   =>
                        __('Current settings for SLP related options are noted below. ' ,'csa-slp-janitor')
                    )
                );

            // Show each option we know about and its current value.
            //
            foreach ($this->optionList as $optionName) {
                $extValue = print_r(get_option($optionName),true);

                // Header
                //
                if (substr($optionName,0,2)==='--') {
                    $this->Settings->add_ItemToGroup(
                        array(
                            'section'       => $sectName                    ,
                            'group'         => $groupName                   ,
                            'label'         => substr($optionName,3)        ,
                            'type'          => 'subheader'                  ,
                            'show_label'    => false                        ,
                            'description'   => '',
                            )
                        );

                // Option Value
                //
                } else {
                    $this->Settings->add_ItemToGroup(
                        array(
                            'section'       => $sectName                    ,
                            'group'         => $groupName                   ,
                            'label'         => $optionName                  ,
                            'setting'       => $optionName,
                            'description'   => $extValue,
                            'use_prefix'    => false,
                            'disabled'      => true

                        )
                    );
                }
            }


            //------------------------------------------
            // RENDER
            //------------------------------------------
            $this->Settings->render_settings_page();
        }

        /**
         * Do this after SLP initiliazes.
         *
         * @return null
         */
        function slp_init() {
            if (!$this->setPlugin()) { return; }
            $this->plugin->register_addon(plugin_basename(__FILE__));
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
            if (!isset($this->plugin) || ($this->plugin == null)) {
                global $slplus_plugin;
                $this->plugin = $slplus_plugin;
            }
            return (isset($this->plugin) && ($this->plugin != null));
        }

        /**
         * Reset the SLP settings.
         */
        function reset_Settings() {
            $resetInfo = array();

            //FILTER: slp_janitor_deleteoptions
            $slpOptions = apply_filters('slp_janitor_deleteoptions', $this->optionList);
            foreach ($slpOptions as $optionName) {
                if (delete_site_option($optionName)) {
                    $resetInfo[] = sprintf(__('%s has been deleted.','csa-slp-janitor'),$optionName);
                }
            }
            return $resetInfo;
        }

    }

   global $slplus_plugin;
   $slplus_plugin->Janitor = new SLPJanitor();
}