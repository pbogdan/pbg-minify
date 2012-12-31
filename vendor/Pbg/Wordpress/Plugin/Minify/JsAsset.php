<?php

/**
 * Javascript asset implementation.
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
 * Javascript asset implementation.
 *
 * Main purpose is to set up plugin activation / deactivation hooks.
 *
 * @category Pbg\Wordpress\Plugin
 * @package  Pbg\Wordpress\Plugin\Minify
 * @author   Piotr Bogdan <ppbogdan@gmail.com>
 * @license  http://opensource.org/licenses/gpl-license.php GNU Public License
 * @link     http://github.com/pbogdan/pbg-minify
 */
class JsAsset extends Asset
{
    /**
     * Constructor.
     *
     * Instantiates JS minifier as a property.
     *
     * @param array $wpAsset Single element from WP_Dependencies queue
     *
     * @return JsAsset
     */
    public function __construct(array $wpAsset)
    {
        parent::__construct($wpAsset);
        $this->minifier = new JsMinifier();
    }
}
