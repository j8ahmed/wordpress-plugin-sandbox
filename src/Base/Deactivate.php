<?php
/*
 *  @package j8ahmed-test-plugin-1
 */

namespace J8ahmed\TestPlugin1\Base;

class Deactivate {

    public static function init() {
        // WP register deactivation function
        register_deactivation_hook(PLUGIN_FILE, [self::class, "deactivate"]);
    }

    public static function deactivate() {
        // Unregister the WP post type, so the rules are no longer in memory.
        unregister_post_type("j8ahmed_test");
        // Clear the WP permalinks to remove our post type's rules from the database.
        flush_rewrite_rules();
    }
}

