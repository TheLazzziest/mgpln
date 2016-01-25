<?php
namespace Vendor\Db;

abstract class Connector implements Interfaces\ConnectorInterface
{
    //MIN number of db parameters to establish the connection for mysql db
    const MIN_PARAMS = 4;

    //If you are going to use generic wp db handle
    private $link;
    //

    //If you are going to establish the separate connection to your wp
    private $dbhost;
    private $dbname;
    private $dbuser;
    private $dbpassword;
    //

    protected $prefix;
    protected $charset;
    protected $collate;

    public function __construct()
    {
        require_once dirname(__FILE__)."/Settings.php";
        if(empty($config) || count($config) < MIN_PARAMS )
            throw new WP_Error('missing', "PLugin db error. Missing db configuration set");
        $this->initProps($config);
    }

    private function initProps(array $config){
        foreach($config as $key => $value){
                if(property_exists($this,$key))
                    $this->$key = $value;
        }
    }

    /**
     *
     */
    public function connect()
    {
        if(empty($this->link)){
            try{
                $this->link = new PDO("mysql:host=$this->dbhost;dbname=$this->dbname", $this->dbuser,$this->dbpassword);
            }catch(PDOException $e){
               throw new WP_Error($e->getCode(), $e->getMessage());
            }
        }
        return !empty($this->link); // if the link does exist
    }

    public function disconnect()
    {
        if(!empty($this->link))
            $this->link = null;
        return empty($this->link);
    }

    public function get_link()
    {
        return !empty($this->link) ? $this->link : null;
    }
}
?>
