<?php 

class HomePage extends TPage 
{
	function onLoad($param)
	{
		parent::onLoad($param);
		if(!$this->IsPostBack)
		{
			$products=array(
				array('id'=>'ITN001','name'=>'Motherboard','quantity'=>1,'price'=>100.00),
				array('id'=>'ITN002','name'=>'CPU','quantity'=>1,'price'=>150.00),
				array('id'=>'ITN003','name'=>'Harddrive','quantity'=>2,'price'=>80.00),
				array('id'=>'ITN004','name'=>'Sound card','quantity'=>1,'price'=>40.00),
				array('id'=>'ITN005','name'=>'Video card','quantity'=>1,'price'=>150.00),
				array('id'=>'ITN006','name'=>'Keyboard','quantity'=>1,'price'=>20.00),
				array('id'=>'ITN007','name'=>'Monitor','quantity'=>2,'price'=>300.00),
				);
			$this->list->setDataSource($products);
			$this->list->dataBind();
		}
	}

	function editItem($sender,$param)
	{
		$this->list->setEditItemIndex($param->item->ItemIndex);
		$this->list->dataBind();
	}

	function cancelItem($sender,$param)
	{
		$this->list->setSelectedItemIndex(-1);
		$this->list->setEditItemIndex(-1);
		$this->list->dataBind();
	}

	function updateItem($sender,$param)
	{
		$dataSource=$this->list->getDataSource();
		$product=$dataSource[$param->item->ItemIndex];
		$product['name']=$param->item->nameEdt->Text;
		$product['price']=floatval($param->item->priceEdt->Text);
		$product['quantity']=intval($param->item->quantEdt->Text);
		$dataSource[$param->item->ItemIndex]=$product;
		$this->list->setSelectedItemIndex($param->item->ItemIndex);
		$this->list->setDataSource($dataSource);
		$this->list->dataBind();
	}

	function deleteItem($sender,$param)
	{
		$dataSource=$this->list->getDataSource();
		unset($dataSource[$param->item->ItemIndex]);
		$this->list->setEditItemIndex(-1);
		$this->list->setSelectedItemIndex(-1);
		$this->list->setDataSource($dataSource);
		$this->list->dataBind();
	}

	function selectItem($sender,$param)
	{
		$this->list->setSelectedItemIndex($param->item->ItemIndex);
		$this->list->dataBind();
	}
} 

?>