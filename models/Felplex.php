<?php
# /modules/felplexconnector/modules/Felplex.php

/**
 * Felplex Connector - A Prestashop Module
 * 
 * Felplex Connector Module
 * 
 * @author Aleks Zotov <alekszotovdev@gmail.com>
 * @version 0.0.1
 */

if ( !defined('_PS_VERSION_') ) exit;

class FelplexModel extends ObjectModel
{
	/** Your fields names, adapt to your needs */
	public $id_felplex;
	public $id_order;
	public $uuid_felplex;
	public $deleted;

	/** Your table definition, adapt to your needs */
	public static $definition = [
		'table' => 'felplex',
		'primary' => 'id_felplex',
		'fields' => [
			'id_order' => [
				'type' => self::TYPE_INT,
				'validate' => 'isInteger',
				'size' => 10,
				'required' => true,
			],
			'uuid_felplex' => [
				'type' => self::TYPE_STRING,
				'validate' => 'isString',
				'size' => 64,
				'required' => true,
			],
			'deleted' => [
				'type' => self::TYPE_INT,
				'validate' => 'isInteger',
                'size' => 1,
                'required' => true,
			],
		],
	];

	/** Create your table into database, adapt to your needs */
	public static function installSql()
	{
		$tableName = _DB_PREFIX_ . self::$definition['table'];
		$primaryField = self::$definition['primary'];

		$sql = "
			CREATE TABLE IF NOT EXISTS `{$tableName}` (
				`{$primaryField}` int(10) unsigned NOT NULL AUTO_INCREMENT,
				`id_order` int(10) NOT NULL,
				`uuid_felplex` varchar(64) NOT NULL DEFAULT '',
				`deleted` tinyint(1) DEFAULT 0,
				PRIMARY KEY (`{$primaryField}`)
			) ENGINE=InnoDB DEFAULT CHARSET=utf8;
		";

		return Db::getInstance()->execute($sql);
	}
}
