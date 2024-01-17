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
        define("PLUGIN_FILE", $plugin_file);
        define("PLUGIN_DIR", dirname($plugin_file));
        define("PLUGIN", plugin_basename($plugin_file));
    }

}


// use Inc\Activate;
// use Inc\Deactivate;
// use Inc\TestJ8ahmed;
// 
// if (! class_exists("J8ahmedTestPlugin1") ){
// 
//     class J8ahmedTestPlugin1 {
// 
//         const PATH = __DIR__;
//         protected $plugin;
// 
//         function __construct() {
//             $this->plugin = plugin_basename(__FILE__);
//         }
// 
//         public static function uninstall_plugin() {
//             // Get all the posts from our custom post type and delete them from the DB
//             // Still need to test this after I have made a proper dev environment for this plugin and a build process that allows me to easily uninstall and add this plugin back to my site.
//             $tests = get_posts([
//                 "post_type" => "j8ahmed_test",
//                 "numberposts" => -1,
//             ]);
//             foreach( $tests as $test ){
//                 wp_delete_post($test->ID, true);
//             }
//         }
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
//     // uninstall
//     register_uninstall_hook( __FILE__, ["J8ahmedTestPlugin1", "uninstall_plugin"]);
// }
// 
// 
// 
