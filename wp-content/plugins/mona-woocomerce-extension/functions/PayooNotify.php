<?php
class PayooNotify
{
	private $NotifyData= "";
	private $Signature = "";
	private $PayooSessionID = "";
	private $KeyFields = "";
	
	public function __construct($NotifyData)
	{
		$this->NotifyData = $NotifyData;
		$doc = new DOMDocument();
		$doc->loadXML($this->NotifyData);
		
		$this->NotifyData = ($doc->getElementsByTagName("Data")->item(0)->nodeValue);
		$this->Signature = ($doc->getElementsByTagName("Signature")->item(0)->nodeValue);
		$this->PayooSessionID = $doc->getElementsByTagName("PayooSessionID")->item(0);
		$this->KeyFields = $doc->getElementsByTagName("KeyFields")->item(0)->nodeValue;
		var_dump($doc);
	}
	
	function GetNotifyData()
	{
		return $this->NotifyData;
	}
	
	function GetSignature()
	{
		return $this->Signature;
	}
	function GetKeyFields()
	{
		return $this->KeyFields;
	}
		
	function GetPaymentNotify()
	{
		if(trim($this->NotifyData) == "")
		{
			return;
		}
		$doc = new DOMDocument();//PHP5
		$dataValue = base64_decode($this->NotifyData);
		$doc->loadXML($dataValue);
		
		$invoice = new PaymentNotification();;
		
		if($this->ReadNodeValue($doc, "BillingCode") == '')
		{
			$invoice->setSession($this->ReadNodeValue($doc, "session"));
			$invoice->setBusinessUsername($this->ReadNodeValue($doc, "username"));
			$invoice->setShopID($this->ReadNodeValue($doc, "shop_id"));
			$invoice->setShopTitle($this->ReadNodeValue($doc, "shop_title"));
			$invoice->setShopDomain($this->ReadNodeValue($doc, "shop_domain"));
			$invoice->setShopBackUrl($this->ReadNodeValue($doc, "shop_back_url"));
			$invoice->setOrderNo($this->ReadNodeValue($doc, "order_no"));
			$invoice->setOrderCashAmount($this->ReadNodeValue($doc, "order_cash_amount"));
			$invoice->setStartShippingDate($this->ReadNodeValue($doc, "order_ship_date"));
			$invoice->setShippingDays($this->ReadNodeValue($doc, "order_ship_days"));
			$invoice->setOrderDescription(urldecode(($this->ReadNodeValue($doc, "order_description"))));
			$invoice->setNotifyUrl($this->ReadNodeValue($doc, "notify_url"));
			$invoice->setState($this->ReadNodeValue($doc, "State"));	
			$invoice->setPaymentMethod($this->ReadNodeValue($doc, "PaymentMethod"));
			$invoice->setPaymentExpireDate($this->ReadNodeValue($doc, "validity_time"));			
		}
		else
		{
			$invoice->setBillingCode($this->ReadNodeValue($doc, "BillingCode"));
			$invoice->setOrderNo($this->ReadNodeValue($doc, "OrderNo"));
			$invoice->setOrderCashAmount($this->ReadNodeValue($doc, "OrderCashAmount"));
			$invoice->setState($this->ReadNodeValue($doc, "State"));
			$invoice->setPaymentMethod($this->ReadNodeValue($doc, "PaymentMethod"));	
			$invoice->setShopID($this->ReadNodeValue($doc, "ShopId"));
					$invoice->setPaymentExpireDate($this->ReadNodeValue($doc, "PaymentExpireDate"));
		}
		return $invoice;
	}
	function ReadNodeValue($Doc, $TagName)
	{
		$nodeList = $Doc->getElementsByTagname($TagName);
		$tempNode = $nodeList->item(0);
		if($tempNode == null)
			return '';
		return $tempNode->nodeValue;
	}
}
class PaymentNotification
{
	var $PaymentMethod = "";
	var $State = "";
	var $Session= "";
    var $BusinessUsername="";
    var $ShopID =0;
    var $ShopTitle="";
    var $ShopDomain="";
    var $ShopBackUrl="";
    var $OrderNo="";
    var $OrderCashAmount=0;
    var $StartShippingDate=""; //Format: dd/mm/yyyy
    var $ShippingDays= 0;
    var $OrderDescription="";
    var $NotifyUrl = "";
    var $BillingCode="";
	var $PaymentExpireDate = "";
	
	function setPaymentMethod($PaymentMethod)
    {
    	$this->PaymentMethod = $PaymentMethod;
    }
	function getPaymentMethod()
    {
    	return $this->PaymentMethod;
    }
	function setState($State)
	{
		$this->State = $State;
	}
	function getState()
	{
		return $this->State;
	}
	
    function getSession()
    {
    	return $this->Session;
    }
    function getBusinessUsername()
    {
    	return $this->BusinessUsername;
    }
    function getShopID()
    {
    	return $this->ShopID;
    }
    function getShopTitle()
    {
    	return $this->ShopTitle;
    }
    function getShopDomain()
    {
    	return $this->ShopDomain;
    }
    function getShopBackUrl()
    {
    	return $this->ShopBackUrl;
    }
    function getOrderNo()
    {
    	return $this->OrderNo;
    }
    function getOrderCashAmount()
    {
    	return $this->OrderCashAmount;
    }
    function getStartShippingDate()
    {
    	return $this->StartShippingDate;
    }
    function getShippingDays()
    {
    	return $this->ShippingDays;
    }
    function getOrderDescription()
    {
    	return $this->OrderDescription;
    }
    function getNotifyUrl()
    {
    	return $this->NotifyUrl;
    }
    function setSession($Session)
    {
    	$this->Session = $Session;
    }
    function setBusinessUsername($BusinessUsername)
    {
    	$this->BusinessUsername = $BusinessUsername;
    }
    function setShopID($ShopID)
    {
    	$this->ShopID = $ShopID;
    }
    function setShopTitle($ShopTitle)
    {
    	$this->ShopTitle = $ShopTitle;
    }
    function setShopDomain($ShopDomain)
    {
    	$this->ShopDomain = $ShopDomain;
    }
    function setShopBackUrl($ShopBackUrl)
    {
    	$this->ShopBackUrl = $ShopBackUrl;
    }
    function setOrderNo($OrderNo)
    {
    	$this->OrderNo = $OrderNo;
    }
    function setOrderCashAmount($OrderCashAmount)
    {
    	$this->OrderCashAmount = $OrderCashAmount;
    }
    function setStartShippingDate($StartShippingDate)
    {
    	$this->StartShippingDate = $StartShippingDate;
    }
    function setShippingDays($ShippingDays)
    {
    	$this->ShippingDays = $ShippingDays;
    }
    function setOrderDescription($OrderDescription)
    {
    	$this->OrderDescription = $OrderDescription;
    }
    function setNotifyUrl($NotifyUrl)
    {
    	$this->NotifyUrl = $NotifyUrl;
    }
	//////////////// BillingCode ////////////////////
	function setBillingCode($BillingCode)
    {
    	$this->BillingCode = $BillingCode;
    }
	function getBillingCode()
    {
    	return $this->BillingCode;
    }
	function setPaymentExpireDate($PaymentExpireDate)
    {
    	$this->PaymentExpireDate = $PaymentExpireDate;
    }
	function getPaymentExpireDate()
    {
    	return $this->PaymentExpireDate;
    }
}
?>