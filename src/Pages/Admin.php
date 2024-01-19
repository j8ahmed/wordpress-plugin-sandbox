<?php
/*
 *  @package j8ahmed-test-plugin-1
 */

namespace J8ahmed\TestPlugin1\Pages;

class Admin {

    public static function init() {
        // define constants 
        define("ADMIN_PAGE",  "j8ahmed_test_plugin_1");

        add_action("admin_menu", [self::class, "add_admin_pages"]);
        add_action("init", [self::class, "construct_custom_post_types"]);

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
            [self::class, "load_admin_page"],
            "dashicons-palmtree",
            null
        );

        add_submenu_page(
            ADMIN_PAGE,
            "J8ahmed Test Plugin",
            "Settings",
            "manage_options",
            ADMIN_PAGE,
            [self::class, "load_admin_page"],
            null
        );

        add_submenu_page(
            ADMIN_PAGE,
            "J8ahmed Test Plugin",
            "Sub 2",
            "manage_options",
            ADMIN_PAGE . "_sub_2",
            [self::class, "load_admin_page"],
            null
        );
    }

    public static function load_admin_page(){
        require_once join(DIRECTORY_SEPARATOR, [PLUGIN_DIR, "templates", "test-page.php"]);
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
        add_action("admin_enqueue_scripts", [self::class, "enqueue_scripts"]);
    }

    public static function enqueue_scripts(){
        wp_enqueue_style("j8_plugin_admin_test_styles", plugins_url("assets/styles/admin_test_style.css", PLUGIN_FILE));
        wp_enqueue_script("j8_plugin_admin_test_scripts", plugins_url("assets/js/admin_test_script.js", PLUGIN_FILE));
    }

}
