<?php
# /modules/felplexconnector/controllers/admin/AdminManualFelplex.php

/**
 * Felplex Connector - A Prestashop Module
 * 
 * Felplex Connector Module
 * 
 * @author Aleks Zotov <alekszotovdev@gmail.com>
 * @version 0.0.1
 */

if ( !defined('_PS_VERSION_') ) exit;

class AdminManualFelplexController extends ModuleAdminController
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
			$nit = Tools::getValue('nit');
			$name = Tools::getValue('name');
			$street = Tools::getValue('street');
			$city = Tools::getValue('city');
			$state = Tools::getValue('state');
			$zip = Tools::getValue('zip');
			$country = Tools::getValue('country');
			$datetime_issue = date("Y-m-d\TH:i:s", time());

			// $post_fields['total'] = $this->format_crrency($order[0]['total_products_wt']);
			// $post_fields['total_tax'] = $this->format_crrency($order[0]['total_products_wt'] - $order[0]['total_products']);

			$post_fields = array(
				'type' => $this->type,
				'datetime_issue' => $datetime_issue,
				'total' => '79.70',
				'total_tax' => '20.30',
				'to_cf' => '0',
				'to[tax_code_type]' => 'NIT',
				'to[tax_code]' => $nit,
				'to[tax_name]' => $name,
				'to[address][street]' => $street,
				'to[address][city]' => $city,
				'to[address][state]' => $state,
				'to[address][zip]' => $zip,
				'to[country]' => $country,
				'exempt_phrase' => '16');
			// 'emails[0][email]' => 'email@example.com','emails_cc[0][email]' => 'email@example.com',
			// $item = array('items[0][qty]' => '1','items[0][type]' => 'B','items[0][price]' => '100','items[0][description]' => '"Vasos plásticos"','items[0][without_iva]' => '0','items[0][discount]' => '25','items[0][is_discount_percentage]' => '0','items[0][taxes][qty]' => '1','items[0][taxes][tax_code]' => '1','items[0][taxes][full_name]' => 'Petróleo','items[0][taxes][short_name]' => 'IDP','items[0][taxes][tax_amount]' => '4.7','items[0][taxes][taxable_amount]' => 'null');
			$emails = Tools::getValue('emails');
			die($emails[0].$emails[1]);
			// $post_fields['emails[0][email]'] = $customer[0]['email'];
			// $post_fields['emails_cc[0][email]'] = $customer[0]['email'];

			// foreach($order_details as $key=>$order_detail){
			// 	$post_fields['items[$key][qty]'] = $order_detail['product_quantity'];
			// 	$post_fields['items[$key][type]'] = 'B';
			// 	$post_fields['items[$key][price]'] = $this->format_crrency($order_detail['product_price']);
			// 	$post_fields['items[$key][description]'] = $order_detail['product_name'];
			// 	$post_fields['items[$key][without_iva]'] = '0';
			// 	if($order_detail['reduction_percent'] > 0) {
			// 		$post_fields['items[$key][is_discount_percentage]'] = '1';
			// 		$post_fields['items[$key][discount]'] = $order_detail['reduction_percent'];
			// 	} else {
			// 		$post_fields['items[$key][is_discount_percentage]'] = '0';
			// 		$post_fields['items[$key][discount]'] = $this->format_crrency($order_detail['reduction_amount']);
			// 	}
			// 	$post_fields['items[$key][taxes][qty]'] = $order_detail['product_quantity'];
			// 	$post_fields['items[$key][taxes][tax_code]'] = '1';
			// 	$post_fields['items[$key][taxes][full_name]'] = $order_detail['tax_name'];
			// 	$post_fields['items[$key][taxes][short_name]'] = '';
			// 	$post_fields['items[$key][taxes][tax_amount]'] = (string)(int)Db::getInstance(_PS_USE_SQL_SLAVE_)->getValue("SELECT `unit_amount` FROM `"._DB_PREFIX_."order_detail_tax` WHERE id_order_detail=".$order_detail['id_order_detail']);
			// 	$post_fields['items[$key][taxes][taxable_amount]'] = 'null';

			// }
			// var_dump($post_fields);
			// $curl = curl_init();
			// $entity_id = $this->entity_id;
			
			// curl_setopt_array($curl, array(
			// CURLOPT_URL => "https://app.felplex.com/api/entity/$entity_id/invoices",
			// CURLOPT_RETURNTRANSFER => true,
			// CURLOPT_ENCODING => "",
			// CURLOPT_MAXREDIRS => 10,
			// CURLOPT_TIMEOUT => 0,
			// CURLOPT_FOLLOWLOCATION => true,
			// CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
			// CURLOPT_CUSTOMREQUEST => "POST",
			// CURLOPT_POSTFIELDS => $post_fields,
			// CURLOPT_HTTPHEADER => array(
			// 	"Accept: application/json",
			// 	"X-Authorization: ".$this->api_key
			// ),
			// ));

			// $result = json_decode(curl_exec($curl));

			// curl_close($curl);

			// $uuid_felplex = $result->uuid;
			// return Db::getInstance()->execute("INSERT INTO `"._DB_PREFIX_."felplex` (id_order,uuid_felplex) VALUES ($id_order,'$uuid_felplex')");
		}
		if (Tools::isSubmit('delete'))
		{
			
		}
		return parent::postProcess();
	}

	public function createInvoice($id_order)
	{
		
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

		$this->setTemplate('manual_felplex.tpl');
	}
}
