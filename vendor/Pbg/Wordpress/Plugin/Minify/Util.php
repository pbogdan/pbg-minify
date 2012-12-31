<?php

namespace Pbg\Wordpress\Plugin\Minify;

class Util
{
    public static function urlToPath($url)
    {
        if (strpos($url, \content_url()) !== false) {
            $filePath = str_replace(\content_url(), WP_CONTENT_DIR, $url);
        } else if (strpos($url, \includes_url()) !== false) {
            $filePath = str_replace(\includes_url(), ABSPATH . WPINC, $url);
        } else if (substr($url, 0, 1) === "/") {
            $filePath = ABSPATH . substr($url, 1);
        } else {
            throw new \InvalidArgumentException("Unrecognised URL {$url}");
        }

        return $filePath;
    }
}