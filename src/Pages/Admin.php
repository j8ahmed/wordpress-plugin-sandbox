<?php
/*
 *  @package j8ahmed-test-plugin-1
 */

namespace J8ahmed\TestPlugin1\Pages;

class Admin {
    private const CLASS_PATH = __NAMESPACE__ . "\\Admin";

    public static function init() {
        // define constants 
        define("ADMIN_PAGE",  "j8ahmed_test_plugin_1");

        add_action("admin_menu", [self::CLASS_PATH, "add_admin_pages"]);
        add_filter("plugin_action_links_" . PLUGIN, [self::CLASS_PATH, "add_plugin_links"]);
        add_action("init", [self::CLASS_PATH, "construct_custom_post_types"]);

        // Register styles & scripts
        self::register_scripts();

        // Register regular scripts???
    }

    public static function add_admin_pages() {
        add_menu_page(
            "J8ahmed Test Plugin",
            "J8ahmed",
            "manage_options",
            ADMIN_PAGE,
            [self::CLASS_PATH, "load_admin_page"],
            "dashicons-palmtree",
            null
        );
    }

    public static function load_admin_page(){
        require_once join(DIRECTORY_SEPARATOR, [PLUGIN_DIR, "templates", "test-page.php"]);
    }

    public static function add_plugin_links($links){
        $links[] = "<a href='". admin_url("admin.php?page=" . ADMIN_PAGE) ."'>Settings</a>";
        return $links;
    }

    public static function construct_custom_post_types() {
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

    private static function register_scripts(){
        add_action("admin_enqueue_scripts", [self::CLASS_PATH, "enqueue_scripts"]);
    }

    public static function enqueue_scripts(){
        wp_enqueue_style("j8_plugin_admin_test_styles", plugins_url("assets/styles/admin_test_style.css", PLUGIN_FILE));
        wp_enqueue_script("j8_plugin_admin_test_scripts", plugins_url("assets/js/admin_test_script.js", PLUGIN_FILE));
    }

}
