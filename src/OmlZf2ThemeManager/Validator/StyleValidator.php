<?php
/**
 * OmlZf2ThemeManager - ThemeValidator class
 *
 * @author Ibrahim Azhar Armar <azhar@iarmar.com>
 * @version 0.1
 * @package OmlZf2
 */
namespace OmlZf2ThemeManager\Validator;

use OmlZf2ThemeManager\Core\Utility;

class StyleValidator
{
    protected $config;

    public function __construct(array $config)
    {
        $this->config = $config;
    }

    protected function validate()
    {
        $config = $this->getConfig();
        $styleConfig = array_key_exists('style', $config) ? $config['style'] : array();

        $activeStyleIdentifier = array_key_exists('active', $styleConfig) ? $styleConfig['active'] : null;
        $activeStyleIdentifierIsAvailableInCollection = false;

        if (!array_key_exists('active', $styleConfig) || empty($styleConfig['active'])) {
            throw new \Exception('Invalid or non-existent active style');
        }
        if (!array_key_exists('style_asset_path', $styleConfig) || empty($styleConfig['style_asset_path'])) {
            throw new \Exception('Invalid or non-existent style_asset_path');
        }
        $styleAssetAbsolutePath = Utility::PUBLIC_DIRECTORY_PATH().$styleConfig['style_asset_path'];
        if (!file_exists($styleAssetAbsolutePath) || !is_dir($styleAssetAbsolutePath)) {
            throw new \Exception('Directory does not exist at given path for style_asset_path');
        }
        if (!array_key_exists('collection', $styleConfig) || empty($styleConfig['collection'])) {
            throw new \Exception('Invalid or empty style collection');
        }
        $styleCollection = $styleConfig['collection'];
        foreach ($styleCollection as $style) {
            if (!array_key_exists('name', $style) || empty($style['name'])) {
                throw new \Exception('Style name does not exist in collection');
            }
            if (!array_key_exists('identifier', $style) || empty($style['identifier'])) {
                throw new \Exception('Style identifier does not exist in collection');
            }
            if (!array_key_exists('load_assets', $style) || empty($style['load_assets']) || !is_array($style['load_assets'])) {
                throw new \Exception('Invalid load_assets params or load_assets missing from style collection');
            }
            // Check if active style has a matching identifier in collection
            if ($activeStyleIdentifier === $style['identifier']) {
                $activeStyleIdentifierIsAvailableInCollection = true;
            }
        }
        if (false === $activeStyleIdentifierIsAvailableInCollection) {
            throw new \Exception('Active style "'. $activeStyleIdentifier .'" is not not available in collection');
        }
        return true;
    }

    public function isValid()
    {
        return $this->validate();
    }

    protected function getConfig()
    {
        return $this->config;
    }
}