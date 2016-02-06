<?php
namespace Megaforms\Vendor\Db;

use PDO;

abstract class Connector implements Interfaces\ConnectorInterface
{
    //MIN number of db parameters to establish the connection for mysql db
    const MIN_PARAMS = 4;

    //If you are going to use generic wp db handle
    private $_link;
    //

    //If you are going to establish the separate connection to your wp
    private $_dbhost;
    private $_dbname;
    private $_dbuser;
    private $_dbpassword;

    protected $_prefix;
    protected $_charset;
    protected $_collate;

    public function __construct()
    {
        require_once dirname(__FILE__)."/Settings.php";
        if(empty($config) || count($config) < MIN_PARAMS )
            throw new \Exception("PLugin db error. Missing db configuration set");
        $this->initProps($config);
    }

    private function initProps(array $config){
        foreach($config as $key => $value){
                if(property_exists($this,$key))
                    $this->$key = $value;
                throw new \Exception("Missing {$key} property");
        }
    }

    /**
     *
     */
    public function connect()
    {
        if(empty($this->_link)){
            try{
                $this->_link = new PDO("mysql:host=$this->_dbhost;dbname=$this->_dbname", $this->_dbuser,$this->_dbpassword);
            }catch(\PDOException $e){
               throw new \Exception($e->getMessage(),$e->getCode());
            }
        }
        return !empty($this->_link); // if the link does exist
    }

    public function disconnect()
    {
        if(!empty($this->_link))
            $this->link = null;
        return empty($this->_link);
    }

    public function get_link()
    {
        return !empty($this->_link) ? $this->_link : null;
    }
}
?>
