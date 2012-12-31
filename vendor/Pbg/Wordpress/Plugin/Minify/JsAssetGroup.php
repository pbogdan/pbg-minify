<?php

namespace Pbg\Wordpress\Plugin\Minify;

class JsAssetGroup extends AssetGroup
{
    public function assetFactory($wpAsset)
    {
        return new JsAsset((array)$wpAsset);
    }
    public function getOptionGroup()
    {
        return "js";
    }

    public function getFileType()
    {
        return "js";
    }

    public function getHtml()
    {
        $url = $this->getMinifiedUrl();
        echo "\n<script type='text/javascript' src='{$url}'></script>\n";
    }
}