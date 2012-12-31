<?php

/**
 * Misc utilities.
 *
 * PHP version 5
 *
 * @category Pbg\Wordpress\Plugin
 * @package  Pbg\Wordpress\Plugin\Minify
 * @author   Piotr Bogdan <ppbogdan@gmail.com>
 * @license  http://opensource.org/licenses/gpl-license.php GNU Public License
 * @link     http://github.com/pbogdan/pbg-minify
 */

namespace Pbg\Wordpress\Plugin\Minify;

/**
 * Misc utilities.
 *
 * Main purpose is to set up plugin activation / deactivation hooks.
 *
 * @category Pbg\Wordpress\Plugin
 * @package  Pbg\Wordpress\Plugin\Minify
 * @author   Piotr Bogdan <ppbogdan@gmail.com>
 * @license  http://opensource.org/licenses/gpl-license.php GNU Public License
 * @link     http://github.com/pbogdan/pbg-minify
 */
class Util
{
    /**
     * Translate an URL to a file-system location within Wordpress context.
     *
     * @param string $url URL
     *
     * @return string
     */
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