<?php
/*
 *  @package j8ahmed-test-plugin-1
 */

namespace J8ahmed\TestPlugin1\Base;

class Activate {

    public static function init() {
        // activation
        register_activation_hook(PLUGIN_FILE, [self::class, "activate"]);
        echo "testing log file output";
    }

    public static function activate() {
        self::construct_custom_post_types();
        flush_rewrite_rules();
    }

    private static function construct_custom_post_types() {
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
}

