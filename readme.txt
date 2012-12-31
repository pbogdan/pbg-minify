=== Plugin Name ===
Tags: js, css, minify
Requires at least: 3.1
Tested up to: 3.4
Stable tag: 0.0.1
License: GPLv2
License URI: http://www.gnu.org/licenses/gpl-2.0.html

A plugin to automatically combine and compress your CSS and Javascript
assets, to reduce the number of requests and their size.

== Description ==

This plugin will detect all of the style-sheets and Javascript files
loaded by your theme, plugins and Wordpress itself, combine them all
together, perform minification, and update the HTML markup to use
those.

Eventually, it will also provide a way to be dynamically disabled so
it doesn't interfere with development environment.

This plugin is currently not suitable to be used in production. All of
the functionality is subject to change.

It probably doesn't handle a lot of corner cases (remotely hosted
(outside of Wordpress installation) files being one of them). There
are also some plugins that don't include their assets in a way
compatible with plugin.

Currently, there are no tests whatsoever.

== Pre-requisites ==

- PHP JSON extension
- currently selected theme's directory needs to be write-able by the
  web server

== Installation & usage ==

Don't.
