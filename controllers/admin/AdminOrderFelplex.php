<?php
# /modules/felplexconnector/controllers/admin/AdminOrderFelplex.php

/**
 * Felplex Connector - A Prestashop Module
 * 
 * Felplex Connector Module
 * 
 * @author Aleks Zotov <alekszotovdev@gmail.com>
 * @version 0.0.1
 */

if ( !defined('_PS_VERSION_') ) exit;

class AdminOrderFelplexController extends ModuleAdminController
{
	private $entity_id = '';
	private $api_key = '';
	private $type = '';
	
	public function __construct()
	{
		parent::__construct();
		$entity_id = Configuration::get('FELPLEX_ENTITY_ID');
		$api_key = Configuration::get('FELPLEX_API_KEY');
		$type = Configuration::get('FELPLEX_INVOICE_TYPE');
	}

	public function postProcess()
	{
		if (Tools::isSubmit('create'))
		{
			$id_order = Tools::getValue('id_order');
			$this->createInvoice($id_order);
		}
		if (Tools::isSubmit('delete'))
		{
			$id_order = Tools::getValue('id_order');
			$this->deleteInvoice($id_order);
		}
		return parent::postProcess();
	}

	public function createInvoice($id_order)
	{
		$post_fields = array('type' => $this->type,'datetime_issue' => '2020-10-27T15:03:02','total' => '79.70','total_tax' => '20.30','emails[0][email]' => 'email@example.com','emails_cc[0][email]' => 'email@example.com','to_cf' => '0','to[tax_code_type]' => 'NIT','to[tax_code]' => '12345678','to[tax_name]' => '"John Doe"','to[address][street]' => '"1a Calle"','to[address][city]' => '"Mixco"','to[address][state]' => '"Guatemala"','to[address][zip]' => '01001','to[country]' => 'GT','exempt_phrase' => '16');
		// $item = array('items[0][qty]' => '1','items[0][type]' => 'B','items[0][price]' => '100','items[0][description]' => '"Vasos plásticos"','items[0][without_iva]' => '0','items[0][discount]' => '25','items[0][is_discount_percentage]' => '0','items[0][taxes][qty]' => '1','items[0][taxes][tax_code]' => '1','items[0][taxes][full_name]' => 'Petróleo','items[0][taxes][short_name]' => 'IDP','items[0][taxes][tax_amount]' => '4.7','items[0][taxes][taxable_amount]' => 'null');
		$order = Db::getInstance()->executeS("SELECT * FROM `"._DB_PREFIX_."orders` WHERE id_order=$id_order");
		$order_details = Db::getInstance()->executeS("SELECT * FROM `"._DB_PREFIX_."order_detail` WHERE id_order=$id_order");
		$id_address = $order[0]['id_address_delivery'];
		$address = Db::getInstance()->executeS("SELECT * FROM `"._DB_PREFIX_."address` WHERE id_address=$id_address");
		$id_customer = $address[0]['id_customer'];
		$id_state = $address[0]['id_state'];
		$id_country = $address[0]['id_country'];
		$customer = Db::getInstance()->executeS("SELECT * FROM `"._DB_PREFIX_."customer` WHERE id_customer=$id_customer");
		$state = Db::getInstance(_PS_USE_SQL_SLAVE_)->getValue("SELECT `name` FROM `"._DB_PREFIX_."state` WHERE id_state=$id_state");
		$country = Db::getInstance(_PS_USE_SQL_SLAVE_)->getValue("SELECT iso_code FROM `"._DB_PREFIX_."country` WHERE id_country=$id_country");
		
		// $date = new DateTime($order[0]['date_upd']);
		// $datetime_issue = $date->format('Y-m-d\TH:i:s');
		$datetime_issue = date("Y-m-d\TH:i:s", time());
		$post_fields['datetime_issue'] = $datetime_issue;
		$post_fields['total'] = $this->format_crrency($order[0]['total_products_wt']);
		$post_fields['total_tax'] = $this->format_crrency($order[0]['total_products_wt'] - $order[0]['total_products']);
		$post_fields['to[tax_name]'] = $address[0]['firstname'].' '.$address[0]['lastname'];
		$post_fields['to[tax_code]'] = $address[0]['vat_number'];
		$post_fields['emails[0][email]'] = $customer[0]['email'];
		$post_fields['emails_cc[0][email]'] = $customer[0]['email'];
		$post_fields['to[address][street]'] = $address[0]['address1'];
		$post_fields['to[address][city]'] = $address[0]['city'];
		$post_fields['to[address][zip]'] = $address[0]['postcode'];
		$post_fields['to[address][state]'] = $state;
		$post_fields['to[address][country]'] = $country;

		foreach($order_details as $key=>$order_detail){
			$post_fields['items[$key][qty]'] = $order_detail['product_quantity'];
			$post_fields['items[$key][type]'] = 'B';
			$post_fields['items[$key][price]'] = $this->format_crrency($order_detail['product_price']);
			$post_fields['items[$key][description]'] = $order_detail['product_name'];
			$post_fields['items[$key][without_iva]'] = '0';
			if($order_detail['reduction_percent'] > 0) {
				$post_fields['items[$key][is_discount_percentage]'] = '1';
				$post_fields['items[$key][discount]'] = $order_detail['reduction_percent'];
			} else {
				$post_fields['items[$key][is_discount_percentage]'] = '0';
				$post_fields['items[$key][discount]'] = $this->format_crrency($order_detail['reduction_amount']);
			}
			$post_fields['items[$key][taxes][qty]'] = $order_detail['product_quantity'];
			$post_fields['items[$key][taxes][tax_code]'] = '1';
			$post_fields['items[$key][taxes][full_name]'] = $order_detail['tax_name'];
			$post_fields['items[$key][taxes][short_name]'] = '';
			$post_fields['items[$key][taxes][tax_amount]'] = (string)(int)Db::getInstance(_PS_USE_SQL_SLAVE_)->getValue("SELECT `unit_amount` FROM `"._DB_PREFIX_."order_detail_tax` WHERE id_order_detail=".$order_detail['id_order_detail']);
			$post_fields['items[$key][taxes][taxable_amount]'] = 'null';

		}
		// var_dump($post_fields);
		$curl = curl_init();
		$entity_id = $this->entity_id;
		
		curl_setopt_array($curl, array(
		CURLOPT_URL => "https://app.felplex.com/api/entity/$entity_id/invoices",
		CURLOPT_RETURNTRANSFER => true,
		CURLOPT_ENCODING => "",
		CURLOPT_MAXREDIRS => 10,
		CURLOPT_TIMEOUT => 0,
		CURLOPT_FOLLOWLOCATION => true,
		CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
		CURLOPT_CUSTOMREQUEST => "POST",
		CURLOPT_POSTFIELDS => $post_fields,
		CURLOPT_HTTPHEADER => array(
			"Accept: application/json",
			"X-Authorization: ".$this->api_key
		),
		));

		$result = json_decode(curl_exec($curl));

		curl_close($curl);

		$uuid_felplex = $result->uuid;
		return Db::getInstance()->execute("INSERT INTO `"._DB_PREFIX_."felplex` (id_order,uuid_felplex) VALUES ($id_order,'$uuid_felplex')");
	}

	public function deleteInvoice($id_order)
	{
		$uuid_felplex = Db::getInstance(_PS_USE_SQL_SLAVE_)->getValue("SELECT uuid_felplex FROM `"._DB_PREFIX_."felplex` WHERE id_order=$id_order AND deleted=0");
		$entity_id = $this->entity_id;
		$reason = json_encode(['reason'=>'Lorem ipsum']);
		echo $reason;
		echo "https://app.felplex.com/api/entity/$entity_id/invoices/$uuid_felplex";
		$curl = curl_init();

		curl_setopt_array($curl, array(
		CURLOPT_URL => "https://app.felplex.com/api/entity/$entity_id/invoices/$uuid_felplex",
		CURLOPT_RETURNTRANSFER => true,
		CURLOPT_ENCODING => "",
		CURLOPT_MAXREDIRS => 10,
		CURLOPT_TIMEOUT => 0,
		CURLOPT_FOLLOWLOCATION => true,
		CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
		CURLOPT_CUSTOMREQUEST => "DELETE",
		CURLOPT_POSTFIELDS =>"{\"reason\": \"Lorem\"}",
		CURLOPT_HTTPHEADER => array(
			"Accept: application/json",
			"X-Authorization: ".$this->api_key,
			"Content-Type: application/json"
		),
		));

		$result = curl_exec($curl);
		echo $result;
		curl_close($curl);
		return Db::getInstance()->execute("UPDATE `"._DB_PREFIX_."felplex` SET deleted=1 WHERE id_order=$id_order");

	}

	public function format_crrency($number)
	{
		return number_format($number, 2, '.', '');
	}

	public function initContent()
	{
		parent::initContent();

		$this->context->controller->addCSS(_MODULE_DIR_ . 'felplexconnector/views/css/style.css');
		$this->context->controller->addJS(_MODULE_DIR_ . 'felplexconnector/views/js/script.js');
		$orders = Db::getInstance()->executeS("SELECT * FROM `"._DB_PREFIX_."orders`");
		$order_details = Db::getInstance()->executeS("SELECT * FROM `"._DB_PREFIX_."order_detail`");
		$felplex_invoices = Db::getInstance()->executeS("SELECT * FROM `"._DB_PREFIX_."felplex` WHERE deleted=0");
		$invoice_order_ids = array_column($felplex_invoices, 'id_order');

		$products = [];
		foreach ($order_details as $order_detail) {
			$products[(int)$order_detail['id_order']] []= $order_detail['product_name'];
		}

		foreach ($orders as &$order) {
			$order['products'] = implode('<br>', $products[(int)$order['id_order']]);
			if (array_search($order['id_order'], $invoice_order_ids) === false)
				$order['invoice'] = 0;
			else
				$order['invoice'] = 1;
		}
		
		$this->context->smarty->assign([
			'orders' => $orders,
		]);

		$this->setTemplate('order_felplex.tpl');
	}
}
