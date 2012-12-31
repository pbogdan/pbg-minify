<?php

/*
Plugin Name: pbg-minify
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

ini_set("include_path", ini_get("include_path") . ":" . __DIR__ . "/vendor/");

require_once "autoload.php";

use Pbg\Wordpress\Plugin\Minify as Minify;

Minify\Minify::initialise();
Minify\AssetManager::activate();
