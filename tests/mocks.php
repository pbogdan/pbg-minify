<?php

use Pbg\Wordpress\Plugin\Minify as Minify;

define("WP_CONTENT_DIR", "/home/piotr/wordpress/wp-content");

define("ABSPATH", "/home/piotr/wordpress/");
define("WPINC", "wp-includes");

function site_url()
{
    return "http://example.com";
}

function content_url()
{
    return "http://example.com/wp-content";
}

function includes_url()
{
    return "http://example.com/wp-includes";
}

class TestableStyle extends Minify\Style
{
    public function minify()
    {
        return "minified content";
    }

    protected function fetchRemote()
    {
        return "remote content";
    }

    protected function readFile()
    {
        return "file content";
    }
}