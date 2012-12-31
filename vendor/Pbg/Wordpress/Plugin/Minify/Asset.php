<?php

/**
 * Base abstraction for an asset, where an asset can be a CSS or JS file.
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
 * Provides basic operations, shared across all individual asset
 * implementations.
 *
 * @category Pbg\Wordpress\Plugin
 * @package  Pbg\Wordpress\Plugin\Minify
 * @author   Piotr Bogdan <ppbogdan@gmail.com>
 * @license  http://opensource.org/licenses/gpl-license.php GNU Public License
 * @link     http://github.com/pbogdan/pbg-minify
 */
abstract class Asset
{
    protected $minifier;
    protected $wpAsset;

    /**
     * Constructor.
     *
     * @param array $wpAsset Single element from WP_Dependencies queue
     *
     * @return Asset
     */
    public function __construct(array $wpAsset)
    {
        $this->wpAsset = $wpAsset;
    }

    /**
     * Minify the asset. Return minified representation as a string.
     *
     * @return string
     */
    public function minify()
    {
        if ($this->minifier === null) {
            throw new \RuntimeException("No minifier instance set!");
        }

        return $this->minifier->minify($this->getContent());
    }

    /**
     * Translates asset's URL into file system location.
     *
     * @return string
     */
    public function getFilePath()
    {
        if ($this->isRemote()) {
            throw new \InvalidArgumentException(
                "Remotely hosted assets are not supported!"
            );
        }

        $url = $this->wpAsset["src"];

        return Util::urlToPath($url);
    }

    /**
     * Loads up contents of the asset's file into a string.
     *
     * Also takes care of any additional data added via Wordpress'
     * WP_Dependencies class, most usually JS localisation feature.
     *
     * @return string
     */
    protected function getContent()
    {
        $content = $this->readFile();

        if ($this->hasInlineContent()) {
            if (isset($this->wpAsset["extra"]["data"])) {
                $content = $this->wpAsset["extra"]["data"] . $content;
            } else if (isset($this->wpAsset["extra"]["after"])) {
                $content = $content . $this->wpAsset["extra"]["after"];
            }
        }

        return $content;
    }

    /**
     * Checks whether there's, apart from asset's file, any additional, inline
     * content added.
     *
     * @return bool
     */
    protected function hasInlineContent()
    {
        $hasExtra = isset($this->wpAsset["extra"]) &&
            sizeof($this->wpAsset["extra"] > 0);
        $hasData = isset($this->wpAsset["data"]) &&
            sizeof($this->wpAsset["data"] > 0);

        return $hasExtra || $hasData;
    }


    /**
     * Checks whether an asset is included in local installation or not.
     *
     * @return bool
     */
    protected function isRemote()
    {
        return (strpos($this->wpAsset["src"], site_url()) === false &&
                substr($this->wpAsset["src"], 0, 1) !== "/");
    }

    /**
     * Reads asset's file and returns its contents.
     *
     * Throws an exception on failure.
     *
     * @throws RuntimeException
     * @return string
     */
    protected function readFile()
    {
        $content = @file_get_contents($this->getFilePath());

        if (!$content) {
            throw new \RuntimeException("Cannot read file {$this->wpAsset['src']}");
        }

        return $content;
    }
}
