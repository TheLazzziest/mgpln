<?php
/**
 * Created by PhpStorm.
 * User: max
 * Date: 13.04.16
 * Time: 10:47
 */

namespace Megaforms\Vendor\Libs\Wpapi;


use Megaforms\Vendor\Libs\Traits\Registry;

/**
 * Class Asset
 * @package Megaforms\Vendor\Libs\Wpapi
 */
final class Asset
{
    use Registry;

    /**
     * Temporary solution for handle uniqueness,
     * concatinate counter value at the of the handle
     * @var array
     */
    private static $counter = 0;

    private $_js = [];

    private $_css = [];

    /**
     * @param array $files
     * @param string $handle
     * @return bool $result
     */
    private function handleExist($files,$handle){
        $result = false;
        foreach($files as $file){
            if(!strcmp($file['handle'],$handle)){
                return $result = true;
            }
        }
        return $result;
    }

    /**
     * @param array $files
     * @param string $handle
     * @return string $handle
     */
    private function formHandle($files,$handle){
        if($this->handleExist($files,$handle)){
            $handle .= self::$counter++;
        }
        return $handle;
    }

    /**
     * @param $files
     * @param $handle
     * @param $src
     * @param array $deps
     * @param $ver
     * @param array $add_params
     * @return array
     */
    private function add($files, $handle, $src, array $deps, $ver, array $add_params ){
        $files[] = [
            'handle' => $this->formHandle($files,$handle),
            'src' => $src,
            'deps'=> $deps,
            'ver' => $ver
        ];
        array_walk($add_params, function($key,$value) use ($files){
            return array_push($files,$key = $value);
        });
        return $files;
    }

    /**
     * @param $handle
     * @param $src
     * @param array $deps
     * @param bool|false $ver
     * @param string $media
     * @return $this
     */
    public function addCss($handle, $src, array $deps = [],$ver = false, $media = 'all'){
        $this->_css = $this->add($this->_css,$handle,$src, $deps, $ver, ['media' => $media] );
        return $this;
    }

    /**
     * @param $handle
     * @param $src
     * @param array $deps
     * @param bool|false $ver
     * @param bool|false $in_footer
     * @return $this
     */
    public function addJs($handle, $src, array $deps = [],$ver = false,$in_footer = false){
        $this->_js[] = $this->add($this->_js,$handle,$src,$deps,$ver,['in_footer' => $in_footer]);
        return $this;
    }


    public function register_css(){
        foreach($this->_css as $file){
            wp_register_script(
                $file['handle'],
                $file['src'],
                $file['deps'],
                $file['ver'],
                $file['media']
            );
        }
    }

    public function register_js(){
        foreach($this->_js as $file){
            wp_register_style(
                $file['handle'],
                $file['src'],
                $file['deps'],
                $file['ver'],
                $file['in_footer']
            );
        }
    }

    public function register(){
        Loader::load()->add_action(
            'wp_enqueue_scripts',
            $this,
            'register_js'
        );
        Loader::load()->add_action(
            'wp_enqueue_style',
            $this,
            'register_css'
        );
    }
}