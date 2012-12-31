<?php

/**
 * Entry point for the Minify Wordpress plugin.
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
 * Serves as an entry point for the plugin.
 *
 * Main purpose is to set up plugin activation / deactivation hooks.
 *
 * @category Pbg\Wordpress\Plugin
 * @package  Pbg\Wordpress\Plugin\Minify
 * @author   Piotr Bogdan <ppbogdan@gmail.com>
 * @license  http://opensource.org/licenses/gpl-license.php GNU Public License
 * @link     http://github.com/pbogdan/pbg-minify
 */
class Minify
{
    /**
     * Called once on plugin activation. Does nothing currently.
     *
     * @return void
     */
    public static function activate()
    {
    }

    /**
     * Called once on plugin deactivation. Does nothing currently.
     *
     * @return void
     */
    public static function deactivate()
    {
    }

    /**
     * Wires up plugin activation / deactivation hooks.
     *
     * @return void
     */
    public static function initialise()
    {
        // make sure we have created database tables
        \register_activation_hook(
            "pbg-minify/pbg-minify.php",
            array(
                'Pbg\Wordpress\Plugin\Minify\Minify',
                "activate"
            )
        );

        \register_deactivation_hook(
            "pbg-minify/pbg-minify.php",
            array(
                'Pbg\Wordpress\Plugin\Minify\Minify',
                "deactivate"
            )
        );
    }
}
