<?php
namespace Vendor\Db\Interfaces;

interface ConnectorInterface {

    /**
     * Open a connection to the current database
     */
    function connect();

    /**
     * Close the opend conection
     */
    function disconnect();

    /**
     * Get the link of current connection
     */
    function get_link();

}
?>
