<?php
if (! class_exists('SLPJanitor_Admin')) {
    require_once(SLPLUS_PLUGINDIR.'/include/base_class.admin.php');

    /**
     * Holds the admin-only code.
     *
     * This allows the main plugin to only include this file in admin mode
     * via the admin_menu call.   Reduces the front-end footprint.
     *
     * @package StoreLocatorPlus\SLPJanitor\Admin
     * @author Lance Cleveland <lance@charlestonsw.com>
     * @copyright 2013 Charleston Software Associates, LLC
     */
    class SLPJanitor_Admin extends SLP_BaseClass_Admin {

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

                // Base plugin
                '-- Store Locator Plus',
                'csl-slplus-db_version'                                 ,
                'csl-slplus-disable_find_image'                         ,
                'csl-slplus_email_form'                                 ,
                'csl-slplus-force_load_js'                              ,
                'csl-slplus_hide_address_entry'                         ,
                'csl-slplus-installed_base_version'                     ,
                'csl-slplus-map_language'                               ,
                'csl-slplus-options'                                    ,
                'csl-slplus-options_nojs'                               ,
                'csl-slplus-theme'                                      ,
                'csl-slplus-theme_array'                                ,
                'csl-slplus-theme_details'                              ,
                'csl-slplus-theme_lastupdated'                          ,
                'sl_admin_locations_per_page'                           ,

                // Add On Packs
                //
                // CEX: Contact Extender
                //
                '-- Contact Extender',
                'slplus-extendo-contacts-options'                       ,

                // EM: Enhanced Map
                //
                '-- Enhanced Map'                                       ,
                'csl-slplus-EM-options'                                 ,


                // ER: Enhanced Results
                //
                '-- Enhanced Results',
                'csl-slplus-ER-options'                                 ,
                'csl-slplus_slper'                                      ,
                'csl-slplus_disable_initialdirectory'                   ,
                'csl-slplus-enhanced_results_hide_distance_in_table'    ,
                'csl-slplus-enhanced_results_orderby'                   ,
                'csl-slplus_label_directions'                           ,
                'csl-slplus_label_fax'                                  ,
                'csl-slplus_label_hours'                                ,
                'csl-slplus_label_phone'                                ,
                'csl-slplus_maxreturned'                                ,
                'csl-slplus_message_noresultsfound'                     ,
                'csl-slplus_use_email_form'                             ,

                // ES: Enhanced Search
                '-- Enhanced Search',
                'csl-slplus-ES-options'                                 ,
                'csl-slplus_slpes'                                      ,
                'csl-slplus-enhanced_search_hide_search_form'           ,
                'csl-slplus_show_search_by_name'                        ,
                'csl-slplus_search_by_state_pd_label'                   ,
                'slplus_show_state_pd'                                  ,

                // PRO: Pro Pack
                '-- Pro Pack',
                'csl-slplus-PRO-options'                                ,
                'csl-slplus-db_version'                                 ,
                'csl-slplus_disable_find_image'                         ,
                'csl-slplus_search_tag_label'                           ,
                'csl-slplus_show_tag_any'                               ,
                'csl-slplus_show_tag_search'                            ,
                'csl-slplus_tag_search_selections'                      ,

                // SE: SuperExtendo
                '-- Super Extendo',
                'slplus-extendo-options'                                ,

                // TAG: Tagalong
                '-- Tagalong',
                'csl-slplus-TAGALONG-options'                           ,

                // UML : User Managed Locations
                '-- User Managed Locations',
                'slplus-user-managed-locations-options'                 ,
                );


        /**
         * The wpCSL Settings interface.
         * 
         * @var \wpCSL_settings__slplus $Settings
         */
        private $Settings;

        //-------------------------------------
        // Methods : Base Override
        //-------------------------------------

        /**
         * Admin specific hooks and filters.
         *
         */
        function add_hooks_and_filters() {

            // Admin skinning and scripts
            //
			add_filter('wpcsl_admin_slugs'          , array($this,'filter_AddOurAdminSlug'  ));
		}

		/**
		 * Add our admin pages to the valid admin page slugs.
		 *
		 * @param string[] $slugs admin page slugs
		 * @return string[] modified list of admin page slugs
		*/
		function filter_AddOurAdminSlug($slugs) {
			return array_merge( $slugs, array( 'slp_janitor', SLP_ADMIN_PAGEPRE.'slp_janitor' ) ); 
		}

        /**
         * Set base class properties so we can have more cross-add-on methods.
         */
        function set_addon_properties() {
            $this->admin_page_slug = SLPJanitor::ADMIN_PAGE_SLUG;
        }

        //-------------------------------------
        // Methods : Custom
        //-------------------------------------

        /**
         *
         */
        function fix_Descriptions() {
            $fix_messages = array();

            $offset = 0;
            $data = $this->slplus->database->get_Record(array('selectall'));
            while ( ( $data['sl_id'] > 0 ) ) {
                $new_sl_description = html_entity_decode($data['sl_description']);
                if ($new_sl_description !== $data['sl_description'] ) {
                    $data['sl_description'] = $new_sl_description;
                    $this->slplus->currentLocation->set_PropertiesViaArray($data);
                    $this->slplus->currentLocation->MakePersistent();
                    $fix_messages[] = sprintf(' Fixed location # %d, %s', $data['sl_id'] , $data['sl_store']);
                }
                $offset++;
                $data = $this->slplus->database->get_Record(array('selectall'),array(),$offset);
            }

            if (count($fix_messages) < 1 ) {
                $fix_messages[] = __('No locations were found with encoded HTML strings in their description.', 'csa-slp-janitor');
            } else {
                array_unshift( $fix_messages, __('The following locations had HTML encoded strings stored in the database:','csa-slp-janitor') );
            }

            return $fix_messages;
        }

        /**
         * Handle the incoming form submit action.
         *
         * @return mixed[] results of actions.
         */
		function process_actions() {
            if ( ! isset( $_REQUEST['action']   ) ) { return array(); }
            if ( ! check_admin_referer( 'csl-slplus-settings-options' ) ) { return array(); }
			
            switch ($_REQUEST['action']) {

                // RESET OPTIONS
                //
                case 'reset_options':
                    return $this->reset_Settings();
                    break;

                case 'fix_descriptions':
                    return $this->fix_Descriptions();
					break;

				case 'clear_locations':
					return $this->clear_Locations();
					break;

				case 'delete_extend_datas':
					return $this->delete_Extend_datas();
					break;

                case 'delete_tagalong_helpers':
                    return $this->delete_Tagalong_helpers();
                    break;

                case 'rebuild_tagalong_helpers':
                    return $this->rebuild_Tagalong_helpers();
                    break;

				default:
					if ( strrpos( $_REQUEST['action'], 'reset_single_' ) == 0) {
						$optionName = substr ( $_REQUEST['action'], 13 );
						return $this->reset_single_Settings( $optionName );
					}
                    break;
            }

            return array();
        }

        /**
         * Render the admin page
         */
        function render_AdminPage() {

            // If we are running a reset.
            //
            $action_results = $this->process_actions();

            // Setup and render settings page
            //
            $this->Settings = new wpCSL_settings__slplus(
                array(
                        'prefix'            => $this->slplus->prefix,
                        'css_prefix'        => $this->slplus->prefix,
                        'url'               => $this->slplus->url,
                        'name'              => $this->slplus->name,
                        'plugin_url'        => $this->slplus->plugin_url,
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
                    'description'   => $this->slplus->AdminUI->create_Navbar(),
                    'innerdiv'      => false,
                    'is_topmenu'    => true,
                    'auto'          => false,
                    'headerbar'     => false
                )
			);

			//-------------------------
			// Location
			// ------------------------
			$sectName = __('Locations','csa-slp-janitor');
			$this->Settings->add_section(array('name' => $sectName));

			// Settings : Clear all
            //
            $groupName = __('Clear All','csa-slp-janitor') ;
            $this->Settings->add_ItemToGroup(
                array(
                    'section'       => $sectName                    ,
                    'group'         => $groupName                   ,
                    'label'         => __('About','csa-slp-janitor')     ,
                    'type'          => 'subheader'                  ,
                    'show_label'    => false                        ,
                    'description'   =>
                        __('Clearing ALL locations is a destructive process that cannot be undone. ' ,'csa-slp-janitor') .
                        __('Make sure you have a full backup of your site before proceeding. '  ,'csa-slp-janitor') 
                    )
                );

            $clear_message = __('Clear ALL of the locations of Store Locator Plus?', 'csa-slp-janitor');
            $this->Settings->add_ItemToGroup(
                array(
                    'section'       => $sectName                    ,
                    'group'         => $groupName                   ,
                    'type'          => 'submit_button'              ,
                    'show_label'    => false                        ,
                    'onClick'       => "AdminUI.doAction('clear_locations' ,'{$clear_message}','wpcsl_container','action');",
                    'value'         => __('Clear SLP Locations','csa-slp-janitor')
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
                        __('Make sure you have a full backup of your site before proceeding. '  ,'csa-slp-janitor') 
                    )
                );

            $reset_message = __('Reset ALL of the options listed below to blank?', 'csa-slp-janitor');
            $this->Settings->add_ItemToGroup(
                array(
                    'section'       => $sectName                    ,
                    'group'         => $groupName                   ,
                    'type'          => 'submit_button'              ,
                    'show_label'    => false                        ,
                    'onClick'       => "AdminUI.doAction('reset_options' ,'{$reset_message}','wpcsl_container','action');",
                    'value'         => __('Reset SLP Options','csa-slp-janitor')
                    )
                );

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
                        __('Current settings for SLP related options are noted below. ' ,'csa-slp-janitor') .
                        __('* denotes standard csl-slplus prefix. ' ,'csa-slp-janitor')
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
					$label = str_replace('csl-slplus','*',$optionName);
					$showValue = htmlspecialchars($extValue);
					$custom =	"<input style=\"width:270px\" type=\"text\" name=\"$optionName\" disabled=\"disabled\" value=\"$showValue\"></input>" . 
								"<a class=\"action_icon delete_icon\" alt=\"delete\" title=\"delete\" onclick=\"AdminUI.doAction('reset_single_$optionName'" . 
								" ,'Reset option $optionName to blank?','wpcsl_container','action');\"></a>";

                    $this->Settings->add_ItemToGroup(
                        array(
                            'section'       => $sectName                    ,
                            'group'         => $groupName                   ,
                            'label'         => $label                       ,
                            'setting'       => $optionName,
                            'description'   => $extValue,
                            'use_prefix'    => false,
							'disabled'      => true,
							'type'			=> 'custom',
							'custom'		=> $custom
                        )
                    );
                }
            }

            // Tools
            //
            $sectName = __('Tools','csa-slp-janitor');
            $this->Settings->add_section(array('name' => $sectName));

            // Settings : Description HTML
            //
            $groupName = __('Descriptions','csa-slp-janitor') ;
            $this->Settings->add_ItemToGroup(
                array(
                    'section'       => $sectName                    ,
                    'group'         => $groupName                   ,
                    'label'         => '' ,
                    'type'          => 'subheader'                  ,
                    'show_label'    => false                        ,
                    'description'   =>
                        __('A bug in older versions of Store Locator Plus was storing HTML in encoded format. '         ,'csa-slp-janitor') .
                        __('Click the button below to convert HTML codes such as &lt; back to the standard < format. '  ,'csa-slp-janitor') .
                        __('This should only need to be done once. '                                                    ,'csa-slp-janitor') .
                        __('The process is not reversible, so make sure you have backed up your data first. '           ,'csa-slp-janitor')
                    )
                );

            // Submit
            //
            $reset_message = __( 'Are you sure you convert all encoded HTML to standard HTML in the location descriptions?', 'csa-slp-janitor');
            $this->Settings->add_ItemToGroup(
                array(
                    'section'       => $sectName                    ,
                    'group'         => $groupName                   ,
                    'type'          => 'submit_button'              ,
                    'show_label'    => false                        ,
                    'onClick'       => "AdminUI.doAction('fix_descriptions' ,'{$reset_message}','wpcsl_container','action');",
                    'value'         => __('Fix Description HTML','csa-slp-janitor')
                    )
                );            

			// Add Delete Extended Data Field Info : text
			//
			$this->Settings->add_ItemToGroup(
                array(
                    'section'       => $sectName                    ,
                    'group'         => $groupName                   ,
                    'label'         => $this->slplus->helper->create_SubheadingLabel('Metadata For Data Extensions'),
                    'type'          => 'subheader'                  ,
                    'show_label'    => false                        ,
                    'description'   =>
                        __('Use this button to clear out all of the metadata records in the slp_extendo_meta table. ', 'csa-slp-janitor') .
						__('The table is used to manage the extended data tables. ', 'csa-slp-janitor') .
						__('This will also clear out all of the extended location data. ', 'csa-slp-janitor')
                    )
				);

			// Add Delete Extended Data Field Info : button
			//
            $reset_message = __( 'Are you sure you want to delete all extended data info?', 'csa-slp-janitor');
            $this->Settings->add_ItemToGroup(
                array(
                    'section'       => $sectName                    ,
                    'group'         => $groupName                   ,
                    'type'          => 'submit_button'              ,
                    'show_label'    => false                        ,
                    'onClick'       => "AdminUI.doAction('delete_extend_datas' ,'{$reset_message}','wpcsl_container','action');",
                    'value'         => __('Delete Extended Data Field Info','csa-slp-janitor')
                    )
				);


			// Delete Tagalong Helper Data : text
            //
            $this->Settings->add_ItemToGroup(
                array(
                    'section'       => $sectName                    ,
                    'group'         => $groupName                   ,
                    'label'         => $this->slplus->helper->create_SubheadingLabel('Tagalong Categories'),
                    'type'          => 'subheader'                  ,
                    'show_label'    => false                        ,
                    'description'   =>
                        __('Use this button to clear out the Tagalong categories table. ', 'csa-slp-janitor') .
						__('The table is a helper table to speed up linking locations to categories. ', 'csa-slp-janitor') 
                    )
				);

			// Delete Tagalong Helper Data : button
            //
            $reset_message = __( 'Are you sure you want to delete the Tagalong category helper table?', 'csa-slp-janitor');
            $this->Settings->add_ItemToGroup(
                array(
                    'section'       => $sectName                    ,
                    'group'         => $groupName                   ,
                    'type'          => 'submit_button'              ,
                    'show_label'    => false                        ,
                    'onClick'       => "AdminUI.doAction('delete_tagalong_helpers' ,'{$reset_message}','wpcsl_container','action');",
                    'value'         => __('Delete Tagalong Category Helper Data','csa-slp-janitor')
                    )
				);

			// Delete Tagalong Helper Data : rebuild button
            //
            $reset_message = __( 'Attempt to re-attach Tagalong categories?', 'csa-slp-janitor');
            $this->Settings->add_ItemToGroup(
                array(
                    'section'       => $sectName                    ,
                    'group'         => $groupName                   ,
                    'type'          => 'submit_button'              ,
                    'show_label'    => false                        ,
                    'onClick'       => "AdminUI.doAction('rebuild_tagalong_helpers' ,'{$reset_message}','wpcsl_container','action');",
                    'value'         => __('Rebuild Tagalong Category Helper Data','csa-slp-janitor')
                    )
				);

            // Results Panel
            //
            if ( count( $action_results ) > 0 ) {
                $sectName  = __('Results'   ,'csa-slp-janitor');
                $groupName = __('Info'      ,'csa-slp-janitor');
                $this->Settings->add_section(array('name' => $sectName));
                $this->Settings->add_ItemToGroup(
                    array(
                        'section'       => $sectName                    ,
                        'group'         => $groupName                   ,
                        'label'         => ''                           ,
                        'type'          => 'subheader'                  ,
                        'show_label'    => false                        ,
                        'description'   => implode('<br/>',$action_results)
                        )
                    );
            }

            //------------------------------------------
            // RENDER
            //------------------------------------------
            $this->Settings->render_settings_page();
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
                    $resetInfo[] = sprintf(__('SLP option %s has been deleted.','csa-slp-janitor'),$optionName);
                }
            }
            return $resetInfo;
		}

		/**
         * Reset the single settings.
         */
		function reset_single_Settings($optionName) {
			//$GLOBALS['DebugMyPlugin']->panels['main']->addMessage($optionName);

			$resetInfo = array();
			$options = array($optionName);

            //FILTER: slp_janitor_deleteoptions
            $slpOptions = apply_filters('slp_janitor_deleteoptions', $options);
            foreach ($slpOptions as $optionName) {
                if (delete_site_option($optionName)) {
                    $resetInfo[] = sprintf(__('SLP option %s has been deleted.','csa-slp-janitor'),$optionName);
                }
            }
            return $resetInfo;
        }

		/**
		 *  Clear ALL the SLP locations.
		 */
		function clear_Locations() {
			$clear_messages = array();

            $count = 0;
            $data = $this->slplus->database->get_Record(array('selectslid'));
            while ( ( $data['sl_id'] > 0 ) ) {
				$this->slplus->currentLocation->set_PropertiesViaDB($data['sl_id']);
                $this->slplus->currentLocation->DeletePermanently();

				$count++;
                $data = $this->slplus->database->get_Record(array('selectslid'));
            }

            if ($count < 1 ) {
                $clear_messages[] = __('No locations were found.', 'csa-slp-janitor');
            } else {
                $clear_messages[] = $count . __(' locations has been deleted.', 'csa-slp-janitor');
            }

            return $clear_messages;
		}

		/**
		 * Clear out all of the metadata records in the slp_extendo_meta table, also clear out all of the extended location data.
		 */
		function delete_Extend_datas() {
			global $wpdb;
			$meta_table_name = $wpdb->prefix . 'slp_extendo_meta';
			$data_table_name = $wpdb->prefix . 'slp_extendo';
			$del_messages = array();

			if($wpdb->get_var("SHOW TABLES LIKE '$meta_table_name'") == $meta_table_name) {
				$wpdb->query( "DELETE FROM $meta_table_name" );
				$del_messages[] = __("Delete records of table $meta_table_name.", 'csa-slp-janitor');
			}

			if($wpdb->get_var("SHOW TABLES LIKE '$data_table_name'") == $data_table_name) {
				$wpdb->query( "DROP TABLE $data_table_name" );
				$del_messages[] = __("Drop table $data_table_name.", 'csa-slp-janitor');
			}

			$slplus_options = get_option(SLPLUS_PREFIX.'-options_nojs',array()); 
			$slplus_options['next_field_id'] = 0; 
			$slplus_options['next_field_ported'] = ''; 
			update_option(SLPLUS_PREFIX.'-options_nojs', $slplus_options);
			$del_messages[] = __("Reset extended data options.", 'csa-slp-janitor');

			return $del_messages;
		}

        /**
         * Delete the tagalong helper data.
         */
        function delete_Tagalong_helpers() {
            $del_messages = array();
            $table_name = $this->slplus->database->db->prefix . 'slp_tagalong';
            $del_messages[] = $this->slplus->database->db->delete( $table_name , array( '1' => '1' ) );
            $del_messages[] = __('Tagalong helper data has been cleared.','csa-slp-janitor');
            return $del_messages;
        }

        /**
         * Rebuild the tagalong helper data.
         */
        function rebuild_Tagalong_helpers() {
            $table_name = $this->slplus->database->db->prefix . 'slp_tagalong';

            $offset = 0;
            $locations_with_categories = 0;
            $categories_assigned = 0;
            while ( ( $location_record = $this->slplus->database->get_Record('selectall',array(),$offset++) ) !== null )  {
                if ( $location_record['sl_linked_postid'] > 0 ) {
                    $post_categories = wp_get_object_terms( $location_record['sl_linked_postid'] , SLPLUS::locationTaxonomy , array ('fields'=>'ids') );
                    $locations_with_categories++;
                    foreach ( $post_categories as $category_id ) {
                        $categories_assigned++;
                        $this->slplus->database->db->insert(
                                $table_name ,
                                array (
                                    'sl_id' => $location_record['sl_id'] ,
                                    'term_id' => $category_id
                                ) ,
                                array (
                                    '%u' ,
                                    '%u'
                                    )
                            );
                    }
                }
            }
            $messages[] = sprintf(
                            __("%s category assignments have been made to %s locations.",'csa-slp-janitor'),
                            $categories_assigned,
                            $locations_with_categories
                            );
            return $messages;
        }
	}
}
