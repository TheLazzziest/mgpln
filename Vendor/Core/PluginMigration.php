<?php
/**
 * Created by PhpStorm.
 * User: max
 * Date: 21.02.16
 * Time: 9:56
 */

namespace Megaforms\Vendor\Core;


use Megaforms\Vendor\Db\Migration\AbstractMigration;
use Megaforms\Vendor\Libs\Traits\Registry;

final class PluginMigration extends AbstractMigration
{
    use Registry;

	/**
     * Adding plugin tables to WP
     */
    public function up()
    {
        $this->tManager->createTable('megaforms_api', true);
        $this->tManager->addColumn(
            'id', 'INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY'
        );
        $this->tManager->addColumn(
            'api_name', 'VARCHAR(255) NOT NULL'
        );
        self::$queries[] = $this->tManager->endCreateTable();

        $this->tManager->createTable('megaforms_users', true);
        $this->tManager->addColumn(
            'id', 'INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY'
        );
        $this->tManager->addColumn(
            'username', 'VARCHAR(255) NOT NULL'
        );
        $this->tManager->addColumn(
            'email', 'VARCHAR(255) NOT NULL'
        );
        $this->tManager->addColumn(
            'credentials', 'VARCHAR(255) NOT NULL'
        );
        $this->tManager->addColumn(
            'api_name', 'VARCHAR(255) NOT NULL'
        );
        self::$queries[] = $this->tManager->endCreateTable();

        $this->tManager->createTable('megaforms_forms', true);
        $this->tManager->addColumn(
            'id', 'INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY'
        );
        $this->tManager->addColumn(
            'name', 'VARCHAR(255) NOT NULL'
        );
        $this->tManager->addColumn(
            'fields', 'TEXT NOT NULL'
        );
        self::$queries[] = $this->tManager->endCreateTable();

        $this->tManager->createTable('megaforms_form_entries', true);
        $this->tManager->addColumn(
            'id', 'INT(6) AUTO_INCREMENT PRIMARY KEY'
        );
        $this->tManager->addColumn(
            'form_id', 'INT(6) NOT NULL'
        );
        $this->tManager->addColumn(
            'form_data', 'TEXT NOT NULL'
        );
        self::$queries[] = $this->tManager->endCreateTable();

        $this->commit();
    }

	/**
     * Remove tables from WP
     * @access public
     *
     */
    public function down()
    {
        self::$queries[] = $this->tManager->removeTable('megaforms_form_entries');
        self::$queries[] = $this->tManager->removeTable('megaforms_forms');
        self::$queries[] = $this->tManager->removeTable('megaforms_users');
        self::$queries[] = $this->tManager->removeTable('megaforms_api');

        $this->commit();
    }
}
