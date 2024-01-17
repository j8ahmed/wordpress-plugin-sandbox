<?php
/*
 *  @package j8ahmed-test-plugin-1
 */

namespace J8ahmed\TestPlugin1;

final class Init {

    /*
     * Store all dependency classes
     * @return array of plugin dependency classes
     */
    public static function get_deps() {
        return [
            Base\Activate::class,
            Base\Deactivate::class,
            Base\Uninstall::class,
            Pages\Admin::class,
        ];
    }

    /*
     * Initialize all dependencies requiring initialization
     * @return null
     */
    public static function init($plugin_file) {
        self::define_constants($plugin_file);

        foreach( self::get_deps() as $dependency){
            if (method_exists($dependency, "init")){
                $dependency::init();
            }
        }
    }

    private static function define_constants($plugin_file) {
        define("PLUGIN_FILE", $plugin_file);                // Full path to core plugin file
        define("PLUGIN_DIR", dirname($plugin_file));        // Full path to plugin directory
        define("PLUGIN", plugin_basename($plugin_file));    // <plugin-directory>/<core-plugin-file>
    }

}


// if (! class_exists("J8ahmedTestPlugin1") ){
// 
//     class J8ahmedTestPlugin1 {
// 
//         function enqueue_scripts(){
//             wp_enqueue_style("j8_plugin_test_styles", plugins_url("assets/styles/test_style.css", __FILE__));
//             wp_enqueue_script("j8_plugin_test_scripts", plugins_url("assets/js/test_script.js", __FILE__));
//         }
// 
//         function register_scripts(){
//             add_action("wp_enqueue_scripts", [$this, "enqueue_scripts"]);
//         }
// 
//     }
// 
// }
// 
// 
// if ( class_exists("J8ahmedTestPlugin1") ){
//     $j8ahmedTestPlugin1 = new J8ahmedTestPlugin1();
// 
//     /* 
//      * Register necessary scripts depending on page / needs... 
//      * For now just add them. remeber we can add conditionals and page checks to make it more efficient.
//      */
//     $j8ahmedTestPlugin1->register_scripts();
// 
// 
// }
