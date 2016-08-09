<?php 

class HomePage extends TPage 
{
	function onLoad($param)
	{
		parent::onLoad($param);
		if(!$this->IsPostBack)
		{
			$products=array(
				array('id'=>'ITN001','name'=>'Motherboard','quantity'=>1,'price'=>100.00,'imported'=>true),
				array('id'=>'ITN002','name'=>'CPU','quantity'=>1,'price'=>150.00,'imported'=>true),
				array('id'=>'ITN003','name'=>'Harddrive','quantity'=>2,'price'=>80.00,'imported'=>true),
				array('id'=>'ITN004','name'=>'Sound card','quantity'=>1,'price'=>40.00,'imported'=>false),
				array('id'=>'ITN005','name'=>'Video card','quantity'=>1,'price'=>150.00,'imported'=>true),
				array('id'=>'ITN006','name'=>'Keyboard','quantity'=>1,'price'=>20.00,'imported'=>false),
				array('id'=>'ITN007','name'=>'Monitor','quantity'=>2,'price'=>300.00,'imported'=>true),
				array('id'=>'ITN008','name'=>'CDRW drive','quantity'=>1,'price'=>40.00,'imported'=>true),
				array('id'=>'ITN009','name'=>'Cooling fan','quantity'=>2,'price'=>10.00,'imported'=>false),
				array('id'=>'ITN010','name'=>'Video camera','quantity'=>20,'price'=>30.00,'imported'=>true),
				array('id'=>'ITN011','name'=>'Card reader','quantity'=>10,'price'=>24.00,'imported'=>true),
				array('id'=>'ITN012','name'=>'Floppy drive','quantity'=>50,'price'=>12.00,'imported'=>false),
				array('id'=>'ITN013','name'=>'CD drive','quantity'=>25,'price'=>20.00,'imported'=>true),
				array('id'=>'ITN014','name'=>'DVD drive','quantity'=>15,'price'=>80.00,'imported'=>true),
				array('id'=>'ITN015','name'=>'Mouse pad','quantity'=>50,'price'=>5.00,'imported'=>false),
				array('id'=>'ITN016','name'=>'Network cable','quantity'=>40,'price'=>8.00,'imported'=>true),
				array('id'=>'ITN017','name'=>'Case','quantity'=>8,'price'=>65.00,'imported'=>false),
				array('id'=>'ITN018','name'=>'Surge protector','quantity'=>45,'price'=>15.00,'imported'=>false),
				array('id'=>'ITN019','name'=>'Speaker','quantity'=>35,'price'=>65.00,'imported'=>false),
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
		$product['price']=floatval($param->item->Cells[2]->Bodies[0]->Text);
		$product['quantity']=intval($param->item->Cells[3]->Bodies[0]->Text);
		$product['imported']=$param->item->Cells[4]->importCheck->Checked;
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

	function toggleColumn($sender,$param)
	{
		foreach($this->columnSelect->getItems() as $item)
			$this->list->Columns[intval($item->Value)]->setVisible($item->Selected);
		$this->list->dataBind();
	}

	function sortItem($sender,$param)
	{
		$sortExpression=$param->parameter;
		$ds=multi_sort($this->list->getDataSource()->getArray(),$sortExpression);
		$this->list->setDataSource($ds);
		$this->list->dataBind();
	}

	function pageItem($sender,$param)
	{
		$pageIndex=$param->parameter;
		$this->list->setCurrentPageIndex($pageIndex);
		$this->list->dataBind();
	}
} 

function multi_sort($tab,$key)
{
	$compare = create_function('$a,$b','if ($a["'.$key.'"] == $b["'.$key.'"]) {return 0;}else {return ($a["'.$key.'"] > $b["'.$key.'"]) ? 1 : -1;}');
	usort($tab,$compare) ;
	return $tab ;
} 

?>