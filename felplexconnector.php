<?php
# /modules/felplexconnector/felplexconnector.php

/**
 * Felplex Connector - A Prestashop Module
 * 
 * Felplex Connector Module
 * 
 * @author Aleks Zotov <alekszotovdev@gmail.com>
 * @version 0.0.1
 */

if ( !defined('_PS_VERSION_') ) exit;

require_once(__DIR__ . '/controllers/admin/AdminOrderFelplex.php');
require_once(__DIR__ . '/models/Felplex.php');

class FelplexConnector extends Module
{
	const DEFAULT_CONFIGURATION = [
		'FELPLEX_ENTITY_ID' => '',
		'FELPLEX_API_KEY' => '',
		'FELPLEX_INVOICE_TYPE' => ''
	];

	public function __construct()
	{
		$this->initializeModule();
	}

	public function install()
	{
		return
			parent::install()
			&& $this->initDefaultConfigurationValues()
			&& $this->installTab()
			&& $this->registerHook('actionOrderStatusPostUpdate')
			&& FelplexModel::installSql()
		;
	}

	public function uninstall()
	{
		return
			parent::uninstall()
			&& $this->uninstallTab()
		;
	}

	public function hookActionOrderStatusPostUpdate($param) {
        $newOrderStatus = $param['newOrderStatus'];
		if($newOrderStatus->id == 2) {
			$a = new AdminOrderFelplexController();
			$a->createInvoice($param['id_order']);
		}

		return true;
    }
	
	public function getContent()
	{
		if(Tools::isSubmit('save'))
		{
			Configuration::updateValue('FELPLEX_ENTITY_ID', Tools::getValue('entity_id'));
			Configuration::updateValue('FELPLEX_API_KEY', Tools::getValue('api_key'));
			Configuration::updateValue('FELPLEX_INVOICE_TYPE', Tools::getValue('type'));
		}
		$this->context->smarty->assign([
			'entity_id' => Configuration::get('FELPLEX_ENTITY_ID'),
			'api_key' => Configuration::get('FELPLEX_API_KEY'),
			'type' => Configuration::get('FELPLEX_INVOICE_TYPE')
		]);
		$this->context->controller->addCSS(_MODULE_DIR_ . 'felplexconnector/views/css/style.css');
		return $this->fetch('module:felplexconnector/views/templates/admin/config.tpl');
	}

	private function initializeModule()
	{
		$this->name = 'felplexconnector';
		$this->tab = 'administration';
		$this->version = '0.0.1';
		$this->author = 'Aleks Zotov';
		$this->need_instance = 0;
		$this->ps_versions_compliancy = [
			'min' => '1.6',
			'max' => _PS_VERSION_,
		];
		$this->bootstrap = true;
		
		parent::__construct();

		$this->displayName = $this->l('Felplex Connector');
		$this->description = $this->l('Felplex Connector Module');
		$this->confirmUninstall = $this->l('Are you sure you want to uninstall this module ?');
	}

	private function initDefaultConfigurationValues()
	{
		foreach ( self::DEFAULT_CONFIGURATION as $key => $value )
		{
			if ( !Configuration::get($key) )
			{
				Configuration::updateValue($key, $value);
			}
		}

		return true;
	}

	private function installTab()
	{
		$languages = Language::getLanguages();
		
		$tab = new Tab();
		$tab->class_name = 'AdminFelplex';
		$tab->module = $this->name;
		$tab->id_parent = (int) Tab::getIdFromClassName('AdminParentOrders');

		foreach ( $languages as $lang )
		{
			$tab->name[$lang['id_lang']] = 'FelplexInvoices';
		}

		try
		{
			$tab->save();
		}
		catch ( Exception $e )
		{
			return false;
		}

		$tab = new Tab();
		$tab->class_name = 'AdminOrderFelplex';
		$tab->module = $this->name;
		$tab->id_parent = (int) Tab::getIdFromClassName('AdminFelplex');

		foreach ( $languages as $lang )
		{
			$tab->name[$lang['id_lang']] = 'OrderFelplex';
		}

		try
		{
			$tab->save();
		}
		catch ( Exception $e )
		{
			return false;
		}

		$tab = new Tab();
		$tab->class_name = 'AdminManualFelplex';
		$tab->module = $this->name;
		$tab->id_parent = (int) Tab::getIdFromClassName('AdminFelplex');

		foreach ( $languages as $lang )
		{
			$tab->name[$lang['id_lang']] = 'ManualFelplex';
		}

		try
		{
			$tab->save();
		}
		catch ( Exception $e )
		{
			return false;
		}

		return true;
	}

	private function uninstallTab()
	{
		$tab = (int) Tab::getIdFromClassName('AdminFelplex');

		if ( $tab )
		{
			$mainTab = new Tab($tab);
			
			try
			{
				$mainTab->delete();
			}
			catch ( Exception $e )
			{
				echo $e->getMessage();
				return false;
			}
		}

		$tab = (int) Tab::getIdFromClassName('AdminOrderFelplex');

		if ( $tab )
		{
			$mainTab = new Tab($tab);
			
			try
			{
				$mainTab->delete();
			}
			catch ( Exception $e )
			{
				echo $e->getMessage();
				return false;
			}
		}

		$tab = (int) Tab::getIdFromClassName('AdminManualFelplex');

		if ( $tab )
		{
			$mainTab = new Tab($tab);
			
			try
			{
				$mainTab->delete();
			}
			catch ( Exception $e )
			{
				echo $e->getMessage();
				return false;
			}
		}

		return true;
	}
}
