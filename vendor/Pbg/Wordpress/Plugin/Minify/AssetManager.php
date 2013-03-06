<?php

/**
 * Asset manager.
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
 * Asset manager.
 *
 * Asset manager role is to take responsibility for CSS and JS handling
 * mechanism within Wordpress. It supports & uses Wordpress' internal API's
 * for queuing CSS & JS but hijacks how the results are inserted into the
 * markup.
 *
 * @category Pbg\Wordpress\Plugin
 * @package  Pbg\Wordpress\Plugin\Minify
 * @author   Piotr Bogdan <ppbogdan@gmail.com>
 * @license  http://opensource.org/licenses/gpl-license.php GNU Public License
 * @link     http://github.com/pbogdan/pbg-minify
 */
class AssetManager
{
    /**
     * Overtakes Wordpress' "asset pipeline".
     *
     * @return void
     */
    public static function activate()
    {
        // bail if dev env
        add_action(
            "wp_loaded",
            function() {
                $wp_head_hooks = array(
                    1 => array("wp_enqueue_scripts"),
                    8 => array("wp_print_styles"),
                    9 => array("wp_print_head_scripts")
                );

                foreach ($wp_head_hooks as $priority => $hook) {
                    remove_action("wp_head", $hook, $priority);
                }

                $wp_footer_hooks = array(
                    20 => array("wp_print_footer_scripts")
                );

                foreach ($wp_footer_hooks as $priority => $hook) {
                    remove_action("wp_footer", $hook, $priority);
                }

                add_action(
                    "wp_head",
                    function() {
                        AssetManager::loadAssets("css");
                    },
                    8
                );

                add_action(
                    "wp_footer",
                    function() {
                        AssetManager::loadAssets("js");
                    },
                    20
                );
            }
        );
    }

    /**
     * Hand off markup creation to specific asset group.
     *
     * @param string $assetGroup Asset group's name
     *
     * @return string
     */
    public static function loadAssets($assetGroup)
    {
        echo AssetGroup::factory($assetGroup)
            ->refresh()
            ->getHtml();
    }
}
