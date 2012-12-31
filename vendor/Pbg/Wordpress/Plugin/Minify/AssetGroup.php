<?php

/**
 * An asset group.
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
 * An asset group.
 *
 * Asset group's responsibility, in context of Wordpress, is to capture all of
 * either CSS or JS files included in a page, concatenate them all together
 * and provide means to access the minified results to be included in a page,
 * instead of individual, raw files.
 *
 * @category Pbg\Wordpress\Plugin
 * @package  Pbg\Wordpress\Plugin\Minify
 * @author   Piotr Bogdan <ppbogdan@gmail.com>
 * @license  http://opensource.org/licenses/gpl-license.php GNU Public License
 * @link     http://github.com/pbogdan/pbg-minify
 */
abstract class AssetGroup
{
    protected $assets = array();
    protected $wpAssets = array();

    protected $options;

    /**
     * Constructor.
     *
     * Takes WP_Dependencies objects, forces it to calculate dependencies and
     * return only files that actually are included in a page. Then, for each
     * one of them, inject our own implementation of individual asset (via
     * means of assetFactory()).
     *
     * @param WP_Dependencies $wpAssets Either $wp_styles or $wp_scripts objects
     *
     * @return AssetGroup
     */
    public function __construct(\WP_Dependencies $wpAssets)
    {
        $this->wpAssets = $wpAssets;
        $wpAssets->all_deps($wpAssets->queue);

        // filter assets
        $this->assets = array_filter(
            $wpAssets->registered,
            function($asset) use ($wpAssets) {
                return (in_array($asset->handle, $wpAssets->to_do) &&
                        $wpAssets->registered[$asset->handle]->src);
            }
        );

        foreach ($this->assets as $i => $a) {
            $this->assets[$i] = $this->assetFactory($a);
        }

        $this->options = (array)json_decode(get_option("pbg/minify"), "{}");
    }

    /**
     * Instantiates an individual asset, be it CSS or JS.
     *
     * @param array $wpAsset Single element from WP_Dependencies queue
     *
     * @return Asset
     */
    public abstract function assetFactory($wpAsset);
    /**
     * Provides access to options for specific asset group.
     *
     * All of the options are stored in "pbg/minify" json_encoded array. This,
     * implementation-specific method tells us which key in that array shall
     * we access for this specific asset group.
     *
     * @return string
     */
    public abstract function getOptionGroup();
    /**
     * Returns file extensions for specific asset group.
     *
     * Used to construct URLs and file paths.
     *
     * @return string
     */
    public abstract function getFileType();

    /**
     * Returns options for this specific asset group.
     *
     * Following options are being used and saved:
     * - [asset-group]/mtimes - collection of all modification times of files
     *                          in the group, at the time of last
     *                          minification; user to determine whether
     *                          regeneration of minified content is required
     * - [asset-group]/url - URL to the latest, minified bundle
     * - [asset-group]/path - path to the latest, minified bundle
     *
     * Managing the options is delegated to *_option Wordpress' functions.
     *
     * @return array
     */
    public function getOptions()
    {
        return $this->options[$this->getOptionGroup()];
    }

    /**
     * Return an option's value.
     *
     * @param string $key Option's name
     *
     * @return mixed
     */
    public function getOption($key)
    {
        return @$this->options[$this->getOptionGroup()][$key];
    }

    /**
     * Set an option's value.
     *
     * @param string $key   Option's name
     * @param mixed  $value Option's value
     *
     * @return void
     */
    public function setOption($key, $value)
    {
        $this->options[$this->getOptionGroup()][$key] = $value;
        \update_option("pbg/minify", json_encode($this->options));
    }

    /**
     * Minify all assets in this group.
     *
     * @return string
     */
    public function minify()
    {
        $output = "";

        foreach ($this->assets as $asset) {
            $output .= $asset->minify();
        }

        return $output;
    }


    /**
     * If required, forces minification.
     *
     * @return AssetGroup
     */
    public function refresh()
    {
        if (!$this->isFresh()) {
            $this->cleanOrphans();
            $this->minify();
            $this->save();
        }

        return $this;
    }

    /**
     * Checks whether a re-minification is required.
     *
     * Calculates modification times of all the files in the group, and
     * compares them against modification times at last run. Will also check
     * whether a minified bundle generated at last run still does exits.
     *
     * @return bool
     */
    public function isFresh()
    {
        $currentMtimes = array();
        $oldMtimes = (array)$this->getOption("mtimes");

        foreach ($this->getAssetsFilePaths() as $path) {
            $currentMtimes[$path] = \filemtime($path);
        }

        // check if current version exists
        return (\sizeof(\array_diff($currentMtimes, $oldMtimes)) == 0 &&
                @file_exists($this->getMinifiedFileName()));
    }

    /**
     * Removes any stale files that don't correspond to current group's
     * content.
     *
     * @return void
     */
    public function cleanOrphans()
    {
        foreach ($this->getOrphans() as $orphan) {
            unlink($orphan);
        }
    }

    /**
     * Returns a list of files to be purged in orphan-cleaning process.
     *
     * A list contains absolute paths.
     *
     * @return array
     */
    public function getOrphans()
    {
        $path = sprintf(
            "%s/application-*.%s",
            $this->getCurrentThemeDir(),
            $this->getFileType()
        );

        return glob($path);
    }

    /**
     * Returns a list of files included in this bundle.
     *
     * @return array
     */
    public function getAssetsFilePaths()
    {
        $paths = array();

        foreach ($this->assets as $asset) {
            $paths[] = $asset->getFilePath();
        }

        return $paths;
    }

    /**
     * File path to the minified bundle.
     *
     * @return string
     */
    public function getMinifiedFileName()
    {
        return $this->getOption("path");
    }

    /**
     * Store file path to the minified bundle as an option.
     *
     * @param string $value File path
     *
     * @return void
     */
    public function setMinifiedFileName($value)
    {
        return $this->setOption("path", $value);
    }

    /**
     * URL path to the minified bundle.
     *
     * @return string
     */
    public function getMinifiedUrl()
    {
        return $this->getOption("url");
    }

    /**
     * Store URL path to the minified bundle as an option.
     *
     * @param string $value URL path
     *
     * @return void
     */
    public function setMinifiedUrl($value)
    {
        return $this->setOption("url", $value);
    }

    /**
     * Factory pattern.
     *
     * @param string $group Name of the group
     *
     * @return AssetBundle
     */
    public static function factory($group)
    {
        switch ($group) {
            case "css": {
                global $wp_styles;
                $ag = new CssAssetGroup($wp_styles);
                break;
            }
            case "js": {
                global $wp_scripts;
                $ag = new JsAssetGroup($wp_scripts);
                break;
            }
            default: {
                throw new \RuntimeException("Unknown asset group: {$group}");
            }
        }

        return $ag;
    }

    /**
     * Minify current bundle.
     *
     * Saves current minified bundle as a file, and updates all relevant
     * options.
     *
     * @return void
     */
    protected function save()
    {
        $content  = $this->minify();
        $fileName = sprintf(
            "%s/application-%s.%s",
            $this->getCurrentThemeDir(),
            md5($content),
            $this->getFileType()
        );

        if (@file_put_contents($fileName, $content) !== false) {
            $this->setMinifiedFileName($fileName);
            $this->setMinifiedUrl(
                \get_stylesheet_directory_uri() . "/" . basename($fileName)
            );

            $this->setOption(
                "mtimes",
                array_combine(
                    $this->getAssetsFilePaths(),
                    array_map(
                        function($file) {
                            return \filemtime($file);
                        },
                        $this->getAssetsFilePaths()
                    )
                )
            );
        } else {
            throw new \RuntimeException("Cannot write to file {$fileName}");
        }
    }

    /**
     * File-system path to the current Wordpress' theme.
     *
     * @return string
     */
    protected function getCurrentThemeDir()
    {
        return \get_theme_root() . "/" . \get_template();
    }
}
