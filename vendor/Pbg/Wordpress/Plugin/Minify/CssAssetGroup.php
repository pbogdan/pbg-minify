<?php

namespace Pbg\Wordpress\Plugin\Minify;

class CssAssetGroup extends AssetGroup
{
    public function assetFactory($wpAsset)
    {
        return new CssAsset((array)$wpAsset);
    }

    public function getOptionGroup()
    {
        return "css";
    }

    public function getFileType()
    {
        return "css";
    }

    public function getHtml()
    {
        $url = $this->getMinifiedUrl();

        return "\n<link rel='stylesheet' id='lol'  href='{$url}' type='text/css' media='all' />\n";
    }
}