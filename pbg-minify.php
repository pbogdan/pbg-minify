<?php

/*
Plugin Name: Pbg Minify
Plugin URI: http://github.com/pbogdan/pbg-minify/
Description: Minifies your CSS & JS.
Version: 0.0.1
Author: Piotr Bogdan
Author URI: http://github.com/pbogdan
License: GPL2
*/

/*
    Copyright 2012 Piotr Bogdan  (email: ppbogdan@gmail.com)

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License, version 2, as
    published by the Free Software Foundation.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/

require_once "vendor/minify/CSSmin.php";
require_once "vendor/minify/JSMin.php";

require_once  ABSPATH . '/wp-admin/includes/plugin.php';

if (is_plugin_active("pbg-spl-autoloader/pbg-spl-autoloader.php")) {
    require_once WP_PLUGIN_DIR . "/pbg-spl-autoloader/pbg-spl-autoloader.php";
} else {
    throw new \RuntimeException("pbg-spl-autoloader plugin is required");
}

$cl = new SplClassLoader(
    "Pbg\Wordpress\Plugin\Minify",
    __DIR__ . "/vendor"
);

$cl->register();

use Pbg\Wordpress\Plugin\Minify as Minify;

Minify\Minify::initialise();
