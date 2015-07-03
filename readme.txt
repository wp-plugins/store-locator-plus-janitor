=== Store Locator Plus : Janitor ===
Plugin Name:  Store Locator Plus : Janitor
Contributors: charlestonsw
Donate link: http://www.storelocatorplus.com/product/store-locator-plus-janitor/
Tags: search form, google maps, cleaning, janitor, database, locator
Requires at least: 3.8
Tested up to: 4.2.2
Stable tag: 4.2.01

A free add-on to assist with extra maintenance during major upgrades or porting to new systems for the Store Locator Plus plugin.

== Description ==

A free add-on pack for the [Store Locator Plus](http://www.storelocatorplus.com/) location mapping system plugin.
This add-on assists in cleaning up the Store Locator Plus settings including clearing out all pre-existing settings that may 
interfere with upgrading or installing on a new server after restoring a WordPress backup.   Allows Store Locator Plus add-ons 
to behave as if the plugin activation is happening on a new install.

= Options Clean Up =

Delete all [Store Locator Plus](http://www.storelocatorplus.com/) settings from the WordPress options table while retaining location data.

Current add-on packs are also supported:

* [Contact Extender](http://www.storelocatorplus.com/product/slp4-contact-extender/)
* [Enhanced Map](http://www.storelocatorplus.com/product/slp4-enhanced-map/)
* [Enhanced Results](http://www.storelocatorplus.com/product/slp4-enhanced-results/)
* [Enhanced Search](http://www.storelocatorplus.com/product/slp4-enhanced-search/)
* [Event Location Manager](http://www.storelocatorplus.com/product/event-location-manager/)
* [Pro Pack](http://www.storelocatorplus.com/product/slp4-pro/)
* [Social Media Extender](http://www.storelocatorplus.com/product/slp4-social-media-extender/)
* [Tagalong](http://www.storelocatorplus.com/product/slp4-tagalong/)
* [User Managed Locations](http://www.storelocatorplus.com/product/slp4-user-managed-locations/)


= Description Decoding =

Prior versions of Store Locator Plus may have encoded HTML while storing the data in the location descriptions.
A one-time-use tool will repair the location descriptions, converting the HTML encoded data to standard HTML
notation.   If you are seeing text such as &lt;br/&gt; instead of <br/> in your description data, you need this tool.

= Related Links =

* [Store Locator Plus](http://www.storelocatorplus.com/)
* [Other CSA Plugins](http://profiles.wordpress.org/charlestonsw/)

== Installation ==

= Requirements =

* Store Locator Plus: 4.2.67+
* Wordpress: 3.8+
* PHP: 5.2+

= Install After SLP =

1. Go fetch and install Store Locator Plus version 4.0 or higher.
2. Purchase this plugin from CSA to get the latest .zip file.
3. Go to plugins/add new.
4. Select upload.
5. Upload the zip file.

== Frequently Asked Questions ==

= What are the terms of the license? =

The license is GPL.  You get the code, feel free to modify it as you
wish.  I prefer customers pay me because they like what I do and
want to support my efforts to bring useful software to market.  Learn more
on the [SLP License Terms](http://www.storelocatorplus.com/products/general-eula/).

== Changelog ==

= 4.2.01 =

* Enhancement: Automatically lists any SLP 4.2 plugins that are currently active with the settings management interface.
* Enhancement: Added MultiMap settings to the permanent settings list.

= 4.2.00 =

* Change: Requires SLP 4.2.63.
* Enhancement: Updated to use lighter memory footprint via SLP 4.2 addon framework.

= 4.1.20 =

* Fix: delete option on multisite.

= 4.1.19 =

* Update to work with SLP 4.2.59

= 4.1.18 =

* Mark some upcoming defunct ER settings.

= 4.2.17 =

* Added [Social Media Extender](http://www.storelocatorplus.com/product/slp4-social-media-extender/) settings.
* Mark Defunct settings in [SLPER](http://www.storelocatorplus.com/product/slp4-enhanced-results/).

= 4.2.16 =

* [SLP 4.2.48](http://www.storelocatorplus.com/product/store-locator-plus/) compatible with delete icon update.

= 4.1.15 =

* Added Drop Locations Table feature, which is FAR faster but less thorough than the clear locations feature.

= 4.1.14 =

* Add [Widget Pack](http://www.storelocatorplus.com/product/slp4-widgets/) defaults setting.

= 4.1.13 =

* Add [Widget Pack](http://www.storelocatorplus.com/product/slp4-widgets/) settings
* Add map_center to base plugin settings list.
* Add 'sl_google_map_country', 'sl_instruction_message', 'sl_location_table_view', 'sl_map_height', 'sl_map_height_units', 'sl_map_home_icon', 'sl_map_overview_control' settings.
* Add 'sl_map_radii', 'sl_map_type','sl_map_width','sl_map_width_units','sl_name_label','sl_radius_label','sl_search_label','sl_starting_image','sl_website_label' settings.
* Add 'sl_zoom_level', 'sl_zoom_tweak' settings.

= 4.1.12 =

* Enhancement: Use [SLP](http://www.storelocatorplus.com/product/store-locator-plus/) PHP maximum execution time if running SLP 4.2 otherwise set time to 600 seconds.

= 4.1.11 =

* Change: Change settings locations for add-on updates.

= 4.1.10 =

* Change: Drop legacy use_email_form setting, use new setting in Enhanced Results options.

= 4.1.09 =

* Enhancement: Add [Event Location Manager](http://www.storelocatorplus.com/product/event-location-manager/) settings.

= 4.1.08 =

* Enhancement: Add [Directory Builder](http://www.storelocatorplus.com/product/directory-builder/) settings.

= 4.1.07 =

* Fix: Janitor version reporting.
* Fix: Issue with rendering serialized options that include arrays.
* Enhancement: Add [Store Pages](http://www.storelocatorplus.com/product/slp4-store-pages/) Settings

= 4.1.04 =

* Change: Remove Super Extendo settings.
* Fix: admin page formatting.
* Enhancement: Show the serialized options as separate settings on the UI.
* Enhancement: Provide reset for a single serialized option value.

= 4.1.03 =

* Fix: Make the base plugin check work in multisite installs.
* Enhancement: Extended Data tables re-build tool for users that implemented Super Extendo.

= 4.1.02 =

* Enhancement: Add tool to reset the [Tagalong](http://www.storelocatorplus.com/product/slp4-tagalong/) category helper table.
* Enhancement: Add tool to rebuild [Tagalong](http://www.storelocatorplus.com/product/slp4-tagalong/) category helper table.

= 4.1.01 =

* Enhancement: Add settings for new [User Managed Locations](http://www.storelocatorplus.com/product/slp4-user-managed-locations/) add-on pack.

= 4.1 =

* Add more settings.
* Enhancement: Reset a single SLP option.
* Enhancement: Reset data extensions option.

= 4.0.008 =

* Enhancement: Add a delete all locations option.

= 4.0.007 =

* Enhancement: Add [Tagalong](http://www.storelocatorplus.com/product/slp4-tagalong/) settings to reset list.
* Enhancement: Isolate the admin code to admin sub-class, improves memory performance of SLP on UI pages.
* Enhancement: Improved security with check_admin_referer() on _wpnonce.
* Enhancement: Added tools to re-code descriptions from encoded HTML (&lt;) to proper HTML (<) as required by recent SLP bug fix.

= 4.0.006 =

* Enhancement: Add [Enhanced Map](http://www.storelocatorplus.com/product/slp4-enhanced-map/) options to the reset list.
* Enhancement: Add new [Contact Extender](http://www.storelocatorplus.com/product/slp4-contact-extender/) options to the reset list.
* Fix: Strict warning on initialization of plugin.   Make plugin a singleton.

= 0.05 =

* Enhancement: Add more [Pro Pack](http://www.storelocatorplus.com/product/slp4-pro/) legacy settings to the list.

= 0.04 =

* Enhancement: Remove primary [Enhanced Search](http://www.storelocatorplus.com/product/slp4-enhanced-search/) option settings.
* Enhancement: Add subheading breaks in options view.

= 0.03 =

* Enhancement: Remove primary [Pro Pack](http://www.storelocatorplus.com/product/slp4-pro/) options settings.

= 0.02 =

* Enhancement: Remove ALL [Enhanced Results](http://www.storelocatorplus.com/product/store-locator-plus-enhanced-results) settings.
* Enhancement: Remove ALL [Super Extendo](http://www.storelocatorplus.com/product/slp4-super-extendo/) settings.