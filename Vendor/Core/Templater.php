<?php

namespace Megaforms\Vendor\Core;


use Megaforms\Vendor\Exceptions\ArgException;
use Megaforms\Vendor\Exceptions\HTTPException;
use Megaforms\Vendor\Libs\Helpers\CommonHelpers;
use megaforms\Vendor\Libs\Helpers\Http;
use Megaforms\Vendor\Libs\Helpers\PluginDataSet;
use Megaforms\Vendor\Libs\View\Twig\Twig_Autoloader;
use Megaforms\Vendor\Libs\Traits\Registry;
use Megaforms\Vendor\Libs\View\TemplateInterface;
use Megaforms\Vendor\Libs\Wpapi\Asset;

/**
 * Class Templater
 * @package Megaforms\Vendor\Core
 */
final class Templater implements TemplateInterface
{

    use Registry;

    /**
     * @access public
     * @property Asset $_asset using wp api for managing css and js libraries
     */
    public $_asset;

    /**
     * @access private
     * @property Templater $_renderer Based on Twig template engine
     */
    private $_renderer;

    /**
     * @access private
     * @property string $_file
     */
    private $_file;

    /**
     * @access private
     * @property array $_params
     */
    private $_params;

    /**
     * @access public
     * Extension to add at the end of calling file during render
     */
    const HTML_EXT = '.html';

    /**
     * Const to determine Link type of the source
     */
    const LINK_TYPE = 5;
    /**
     * Const to determine file type of the source
     */
    const FILE_TYPE = 7;

    /**
     * Determine source path for
     * @param $path
     * @return int
     */
    private function getSourceType($path){
            if(is_link($path)){
                return self::LINK_TYPE;
            }elseif(is_file($path)){
                return self::LINK_TYPE;
            }
            return ArgException::INVALID_PARAMETER_TYPE;
    }

    private function loadExtlib($lib){
        $extPath = explode('/',$lib['src']);
        $name = array_pop($extPath);
        if(Http::load()->isValid($extPath)){
            // return local path to the downloaded file
            return CommonHelpers::loadFile($lib['src'],$name);
        }else{
            throw new HTTPException(
                HTTPException::NOT_FOUND,
                [$extPath]
            );
        }

    }

    private function addJs(){
        $js = PluginAsset::load()->getJs();
        foreach($js as $label => $lib){
            $type = $this->getSourceType($lib['src']);

            if($type === self::LINK_TYPE){
               $lib['src'] = $this->loadExtlib($lib['src']);
            }
            if($type === self::FILE_TYPE) {
                $this->_asset->addJs(
                    $lib['handle'],$lib['src'],
                    $lib['deps'], $lib['ver'],
                    $lib['in_footer']
                );
            }
            if($type === ArgException::INVALID_PARAMETER_TYPE)
                    throw new ArgException(
                        $type,
                        ['Undefined source type']
                    );
            }

    }

    private function addCss()
    {
        $css = PluginAsset::load()->getCss();
        foreach ($css as $label => $lib) {
            $type = $this->getSourceType($lib['src']);
            if ($type === self::LINK_TYPE) {
                $lib['src'] = $this->loadExtlib($lib['src']);
            }
            if ($type === self::FILE_TYPE) {
                $this->_asset->addCss(
                    $lib['handle'], $lib['src'],
                    $lib['deps'], $lib['ver'],
                    $lib['media']
                );
            }
            if ($type === ArgException::INVALID_PARAMETER_TYPE) {

                throw new ArgException(
                    $type,
                    ['Undefined source type']
                );
            }
        }
    }
    /**
     * @param $path
     */
    public function init($path){
        if( !isset($this->_asset) || !isset($this->_renderer) ){
            Twig_Autoloader::register();
        }
        $this->_asset = Asset::load();


        $loader = new \Twig_Loader_Filesystem($path);
        $this->_renderer = new \Twig_Environment($loader);
    }

    /**
     * Set path to the rendering file
     * @access public
     * @param string $filepath
     */
    public function setFile($filepath){
        $this->_file = $filepath;
    }

    /**
     * Set params of the rendering files
     * @access public
     * @param array $params
     */
    public function setParams($params){
        $this->_params = $params;
    }


    public function registerDependencies(){
        $this->addJs();
        $this->addCss();
        $this->_asset->register();
    }

    /**
     * Render page called by controller
     * @param string $file
     * @param array $params
     * @return string html page
     */
    public function render()
    {
        $this->_file .= !strpos($this->_file,'.') ? self::HTML_EXT : '';
        include_once(PluginDataSet::load()->views_path . $this->_file);
//        return $this->_renderer->render($this->_file,$this->_params);
    }

}
?>
