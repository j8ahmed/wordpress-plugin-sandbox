<?php
/*
 *  @package j8ahmed-test-plugin-1
 */

/*
 *  Plugin Name: J8ahmed Test WordPress Plugin
 *  Plugin URI: https://j8ahmed.com/
 *  Description: One of my many test wordpress plugins
 *  Version: 1.0.0
 *  Author: Jamal J8 Ahmed
 *  Author URI: https://j8ahmed.com
 *  Requires at least: 4.5
 *  License: GPLv3 or later
 *  text-domain: j8ahmed-test-plugin-1
 */

/*
  J8ahmed Test WordPress Plugin
  Copyright (C) 2023 j8ahmed.com

  This program is free software: you can redistribute it and/or modify
  it under the terms of the GNU General Public License as published by
  the Free Software Foundation, either version 3 of the License, or
  (at your option) any later version.

  This program is distributed in the hope that it will be useful,
  but WITHOUT ANY WARRANTY; without even the implied warranty of
  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
  GNU General Public License for more details.

  You should have received a copy of the GNU General Public License
  along with this program. If not, see <http://www.gnu.org/licenses/>.

 */

define('WP_DEBUG', true);

if (! class_exists("J8ahmedTestPlugin1") ){
class J8ahmedTestPlugin1 {

    function activate_plugin() {
        echo "plugin is activated";
    }

    function deactivate_plugin() {
        echo "<h1>plugin is deactivated</h1>";
    }
}
}

if ( class_exists("TestRunner") ){
    $j8ahmedTestPlugin1 = new J8ahmedTestPlugin1();
}

// activation
register_activation_hook( __FILE__, [$testRunner, "activate_plugin"]);

// deactivation
register_deactivation_hook( __FILE__, [$testRunner, "deactivate_plugin"]);

// uninstall
