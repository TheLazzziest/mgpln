<?php
/**
 * Created by PhpStorm.
 * User: max
 * Date: 14.04.16
 * Time: 11:56
 */

namespace Megaforms\Vendor\Core;

use Megaforms\Vendor\Libs\Traits\Registry;

/**
 * Class PluginAsset
 * @package Megaforms\Vendor\Core
 */
final class PluginAsset {
    use Registry;

    /**
     * Container for external js libraries
     * @access private
     * @var array
     */
    private $js = [];

    /**
     * Container for external css libraries
     * @access private
     * @var array
     */
    private $css = [];

    /**
     *
     */
    private function setJs(){
        $this->js = [
            //Pattern for adding js external libraries to plugin
            // use function params of wp_register_script as keys
//            'jquery' => [
//                'handle' => 'jquery',
//                'src' => PluginDataSet::load()->vendor_path.'data/js/jquery-2.2.2.js',
//                'deps' => [],
//                'ver' => '2.2.2',
//                'in_footer' => true
//            ]
            // If you want to load library from external link, the following form should be used

//          'src' => 'https://ajax.googleapis.com/ajax/libs/jquery/1.12.0/jquery.min.js'
        ];
        return $this->js;
    }

    /**
     *
     */
    private function setCss(){
        $this->css = [
            //Pattern for adding css external libraries to plugin
            // use function params of wp_register_style as keys
//            'bootstrap' => [
//                'handle' => 'bootstrap',
//                'src' => PluginDataSet::load()->vendor_path.'data/css/bootstrap.css',
//                'deps' => [],
//                'ver' => '3',
//                'media' => 'all' // list of media types: @link http://www.w3.org/TR/CSS2/media.html#media-types
//            ]
            // If you want to load library from external link, the following form should be used

//          'src' => 'https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css'
        ];
        return $this->css;
    }

    public function getJs(){
        return $this->setJs();
    }

    public function getCss(){
        return $this->setCss();
    }

}