<?php
/*
 *  @package j8ahmed-test-plugin-1
 */

namespace J8ahmed\TestPlugin1\Base;

class SettingsLinks {

    public static function init() {
        // Add additional links to plugin
        add_filter("plugin_action_links_" . PLUGIN, [self::class, "add_plugin_links"]);
    }

    public static function add_plugin_links($links){
        $links[] = "<a href='". admin_url("admin.php?page=" . ADMIN_PAGE) ."'>Settings</a>";
        return $links;
    }


}

