<?php
/*
 *  @package j8ahmed-test-plugin-1
 */

/*
 *  Plugin Name: J8ahmed Test WordPress Plugin
 *  Plugin URI: https://j8ahmed.com/
 *  Description: One of my many test wordpress plugins
 *  Version: 1.0.0
 *  Author: Jamal J8 Ahmed
 *  Author URI: https://j8ahmed.com
 *  Requires at least: 4.5
 *  License: GPLv3 or later
 *  text-domain: j8ahmed-test-plugin-1
 */

/*
  J8ahmed Test WordPress Plugin
  Copyright (C) 2023 j8ahmed.com

  This program is free software: you can redistribute it and/or modify
  it under the terms of the GNU General Public License as published by
  the Free Software Foundation, either version 3 of the License, or
  (at your option) any later version.

  This program is distributed in the hope that it will be useful,
  but WITHOUT ANY WARRANTY; without even the implied warranty of
  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
  GNU General Public License for more details.

  You should have received a copy of the GNU General Public License
  along with this program. If not, see <http://www.gnu.org/licenses/>.

 */

define('WP_DEBUG', true);

if (! class_exists("J8ahmedTestPlugin1") ){

    class J8ahmedTestPlugin1 {

        function __construct() {
            add_action("init", [$this, "construct_custom_post_types"]);
        }

        function activate_plugin() {
            $this->construct_custom_post_types();
            flush_rewrite_rules();
        }

        function deactivate_plugin() {
            // Unregister the post type, so the rules are no longer in memory.
            unregister_post_type("j8ahmed_test");
            // Clear the permalinks to remove our post type's rules from the database.
            flush_rewrite_rules();
        }

        public static function uninstall_plugin() {
            // Get all the posts from our custom post type and delete them from the DB
            // Still need to test this after I have made a proper dev environment for this plugin and a build process that allows me to easily uninstall and add this plugin back to my site.
            $tests = get_posts([
                "post_type" => "j8ahmed_test",
                "numberposts" => -1,
            ]);
            foreach( $tests as $test ){
                wp_delete_post($test->ID, true);
            }
        }

        function construct_custom_post_types() {
            register_post_type("j8ahmed_test",
                array(
                    "labels" => array(
                        "name"          => __("Tests", "textdomain"),
                        "singular_name" => __("Test", "textdomain"),
                    ),
                    "public"      => true,
                    "has_archive" => true,
                )
            );
        }

        function enqueue_scripts(){
            wp_enqueue_style("j8_plugin_test_styles", plugins_url("assets/styles/test_style.css", __FILE__));
            wp_enqueue_script("j8_plugin_test_scripts", plugins_url("assets/js/test_script.js", __FILE__));
        }

        function enqueue_admin_scripts(){
            wp_enqueue_style("j8_plugin_admin_test_styles", plugins_url("assets/styles/admin_test_style.css", __FILE__));
            wp_enqueue_script("j8_plugin_admin_test_scripts", plugins_url("assets/js/admin_test_script.js", __FILE__));
        }

        function register_scripts(){
            add_action("wp_enqueue_scripts", [$this, "enqueue_scripts"]);
        }

        function register_admin_scripts(){
            add_action("admin_enqueue_scripts", [$this, "enqueue_admin_scripts"]);
        }
    }

}


if ( class_exists("J8ahmedTestPlugin1") ){
    $j8ahmedTestPlugin1 = new J8ahmedTestPlugin1();

    /* 
     * Register necessary scripts depending on page / needs... 
     * For now just add them. remeber we can add conditionals and page checks to make it more efficient.
     */
    $j8ahmedTestPlugin1->register_scripts();
    $j8ahmedTestPlugin1->register_admin_scripts();

    // activation
    register_activation_hook( __FILE__, [$j8ahmedTestPlugin1, "activate_plugin"]);

    // deactivation
    register_deactivation_hook( __FILE__, [$j8ahmedTestPlugin1, "deactivate_plugin"]);

    // uninstall
    register_uninstall_hook( __FILE__, ["J8ahmedTestPlugin1", "uninstall_plugin"]);
}



