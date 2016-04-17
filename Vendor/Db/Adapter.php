<?php
namespace Megaforms\Vendor\Db;

use PDO;
use Megaforms\Vendor\Exceptions\DbException;

final class Adapter
{
    //MIN number of db parameters to establish the connection for mysql db
    const MIN_PARAMS = 4;

    //Adapter instance
    public static $_instance;

    //Connection link
    private $_link;

    // Db driver for establishing connection
    private $_driver = 'mysql';
    //If you are going to establish the separate connection to your wp db
    private $_dbhost;
    private $_dbname;
    private $_dbuser;
    private $_dbpassword;

    protected $prefix;
    protected $charset;
    protected $collate;

    /**
     * @param array $settings
     *
     * @throws DbException
     *
     */
    private function __construct(array $settings)
    {
        if(empty($settings)){
            $dbSettings = sprintf("%s/%s", dirname(__FILE__), "Settings.php");
            require_once $dbSettings;
        }else{
            $config = $settings;
        }
        if (empty($config) || count($config) < self::MIN_PARAMS ) {
            throw new DbException(
                DbException::MISSING_SETTINGS,
                $config
            );
        }

        $this->initProps($config);
    }

    /**
     * @param array $config
     *
     * @throws DbException
     *
     */
    private function initProps(array $config){
        foreach($config as $key => $value){
                if (property_exists($this,$key)) {
                    $this->$key = $value;
                }else{
                    // @TODO: Fix error code
                    throw new DbException("Missing {$key} property", 500);
                }
        }
    }

    /**
     * @return bool
     *
     */
    private function isDbLink(){
        return $this->_link instanceof PDO
                || $this->_link instanceof \mysqli;
    }


    /**
     * @return PDO
     * @throws DbException
     *
     */
    public function connect()
    {
        if(empty($this->_link) == true && $this->isDbLink() === false){
            try{
                switch($this->_driver){
                    default:
                        $this->_link = new PDO(
                            "$this->_driver:
                            host=$this->_dbhost;
                            dbname=$this->_dbname",
                            $this->_dbuser,
                            $this->_dbpassword
                        );
                }
            }catch(\PDOException $e){
               throw new DbException($e->getMessage(),$e->getCode());
            }
        }
        return $this->_link; // if the link does exist
    }

    /**
     * @return bool
     *
     */
    public function disconnect()
    {
        if (!empty($this->_link)) {
            $this->_link = null;
        }
        return empty($this->_link);
    }

    /**
     * @param array $settings
     * @return
     * @throws DbException
     *
     */
    public static function get_instance(array $settings = [])
    {
        if (empty(self::$_instance) === true) {
            self::$_instance = new self($settings);
        }
        return self::$_instance;
    }

    public function get_link(){
        return $this->_link;
    }

    public function __clone(){
        return self::get_instance();
    }

    public function __destruct(){
        $this->disconnect();
    }

    public function getCharset(){
        return $this->charset;
    }

    public function getCollate(){
        return $this->collate;
    }

    public function getPrefix(){
        return $this->prefix;
    }
}
?>
