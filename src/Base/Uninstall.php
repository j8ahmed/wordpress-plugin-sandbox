<?php
/*
 *  @package j8ahmed-test-plugin-1
 */

namespace J8ahmed\TestPlugin1\Base;

class Uninstall {

    public static function init() {
        // WP register uninstall function
        register_uninstall_hook( PLUGIN_FILE, [self::class, "uninstall_plugin"]);
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

}

