<?php
/**
 * TTableHeaderCell class file
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the BSD License.
 *
 * Copyright(c) 2004 by Qiang Xue. All rights reserved.
 *
 * To contact the author write to {@link mailto:qiang.xue@gmail.com Qiang Xue}
 * The latest version of PRADO can be obtained from:
 * {@link http://prado.sourceforge.net/}
 *
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @version $Revision: 1.2 $  $Date: 2005/01/23 23:01:30 $
 * @package System.Web.UI.WebControls
 */

/** 
 * TTableCell class file
 */
require_once(dirname(__FILE__).'/TTableCell.php');

/**
 * TTableHeaderCell class
 *
 * A TTableHeaderCell control represents an HTML table header cell.
 *
 * Namespace: System.Web.UI.WebControls
 *
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @version v1.0, last update on 2004/08/13 21:44:52
 * @package System.Web.UI.WebControls
 */
class TTableHeaderCell extends TTableCell
{
	/**
	 * Constructor.
	 * Initializes the tagname to 'th'.
	 */
	public function __construct()
	{
		parent::__construct();
		$this->setTagName('th');
	}
}

?>