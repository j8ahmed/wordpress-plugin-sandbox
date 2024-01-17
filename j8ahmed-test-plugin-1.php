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

// Check for root naming conflict
if (class_exists('J8ahmed\TestPlugin1\Init')){
    // We have a naming conflict with another WP plugin, kill the plugin
    die("Plugin with shared names already exists");
}

if ( file_exists(__DIR__ . "/vendor/autoload.php") ){
    require_once(__DIR__ . "/vendor/autoload.php");
}

if (class_exists('J8ahmed\TestPlugin1\Init')){
    // Initialize Plugin
    J8ahmed\TestPlugin1\Init::init(__FILE__);


    // add activation and deactivation hooks
    // register_activation_hook(PLUGIN_FILE, [self::class, "activate"]);
    // register_deactivation_hook(PLUGIN_FILE, [self::class, "deactivate"]);
}


