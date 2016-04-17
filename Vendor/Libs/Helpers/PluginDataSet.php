<?php
namespace Megaforms\Vendor\Libs\Helpers;
/**
 * Created by PhpStorm.
 * User: max
 * Date: 12.04.16
 * Time: 15:55
 */
use Megaforms\Vendor\Exceptions\ArgException;
use Megaforms\Vendor\Libs\Traits\Registry;

defined("MEGAFORMS_BOOTSTRAPPED") or die("I'm only the WP plugin");
/** @class  PluginDataSet container for local Plugin data*/
final class PluginDataSet
{
    use Registry{
        load as loadDataSet;
    }

    public static function loadDataSet(){
        if(!isset(self::$_instance)){

            self::$_instance = new \ArrayObject('', \ArrayObject::ARRAY_AS_PROPS);
        }

        return self::$_instance;

    }
    /**--------------FOUR REQUIRED PARAMETERS----------------------------*/
    /*
     * The current version of the plugin.
     * @access   protected
     * @var      string    $version    The current version of the plugin.
     */
    public $version;
    /*
     * The unique identifier of this plugin.
     * @access   protected
     * @var      string    $plugin_name    The string is  used to uniquely identify this plugin.
     */
    public $plugin_name;

    /*
     *   The full url path to the plugin
     *   @access protected
     *   @var    Registry    $registy    Container for storing all objects of the plugin
     */

    public $plugin_url;

    /*
     *   The full real path to the plugin
     *   @access public
     *   @property    string    $plugin_path
     */
    public $plugin_path;
    /** -------------------END----------------------------------------- */

    private $errors = [];

    public function checkDependencies(){

        if(!is_string($this->plugin_name) || empty($this->plugin_name)){
            $this->errors[ArgException::WRONG_PARAMETER][] = $this->plugin_name;
        }
        if(!is_string($this->version) || empty($this->version)){
            $this->errors[ArgException::WRONG_PARAMETER][] = $this->version;
        }
        // There is no need to check Plugin path and url until u use wp api functions
        if(!is_string($this->plugin_path) || empty($this->plugin_path)){
            $this->errors[ArgException::WRONG_PARAMETER][] = $this->plugin_path;
        }
        if(!is_string($this->plugin_url) || empty($this->plugin_url)){
            $this->errors[ArgException::WRONG_PARAMETER][] = $this->plugin_url;
        }

        if(!empty($this->errors)){
            throw new ArgException(
                ArgException::WRONG_PARAMETER,
                $this->errors[ArgException::WRONG_PARAMETER]
            );
        }
    }
}