<?php
/*
 *  @package j8ahmed-test-plugin-1
 */

namespace J8ahmed\TestPlugin1\Test;

class Settings {

    public static function init(){
        // define constants 
        // define("ADMIN_PAGE",  "j8ahmed_test_plugin_1");

        //add_action("admin_menu", [self::class, "add_admin_pages"]);
        //add_action("init", [self::class, "construct_custom_post_types"]);
        add_action('admin_init', [self::class, "add_settings"]);
    }

    public static function add_settings(){
        // register a new setting for "reading" page
        register_setting('reading', 'wporg_setting_name');

        // register a new section in the "reading" page
        add_settings_section(
            'wporg_settings_section',
            'WPOrg Settings Section',
            [self::class, "settings_section_cb"],
            'j8ahmed_test_plugin_1'
        );

        // register a new field in the "wporg_settings_section" section, inside the "reading" page
        add_settings_field(
            'wporg_settings_field',
            'WPOrg Setting',
            [self::class, "settings_field_cb"],
            'j8ahmed_test_plugin_1',
            'wporg_settings_section'
        );
    }

    public static function settings_section_cb(){
        echo '<p>WPOrg Section Introduction.</p>';
    }

    public static function settings_field_cb(){
        // get the value of the setting we've registered with register_setting()
        $setting = get_option('wporg_setting_name');
        // output the field
        ?>
            <input type="text" name="wporg_setting_name" value="<?php echo isset( $setting ) ? esc_attr( $setting ) : ''; ?>">
        <?php
    }

}
