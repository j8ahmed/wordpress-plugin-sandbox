<?php
/*
 *  @package j8ahmed-test-plugin-1
 */

if (! class_exists("J8ahmedActivatePlugin") ){

    class J8ahmedActivatePlugin {

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

}
