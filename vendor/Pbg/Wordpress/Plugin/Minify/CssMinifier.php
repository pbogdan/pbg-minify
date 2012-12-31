<?php

/**
 * CSS minifier implementation.
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
 * CSS minifier implementation.
 *
 * Main purpose is to set up plugin activation / deactivation hooks.
 *
 * @category Pbg\Wordpress\Plugin
 * @package  Pbg\Wordpress\Plugin\Minify
 * @author   Piotr Bogdan <ppbogdan@gmail.com>
 * @license  http://opensource.org/licenses/gpl-license.php GNU Public License
 * @link     http://github.com/pbogdan/pbg-minify
 */
class CssMinifier implements Minifier
{
    /**
     * Take a string representation of CSS file and return minified
     * content.
     *
     * @param string $content Non-minified content
     *
     * @return string
     */
    public function minify($content)
    {
        $minifier = new \CSSMin();

        return $minifier->run($content);
    }
}
