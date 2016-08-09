<?php
/**
 * TListControl interface file
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
 * @author Marcus Nyeholt <tanus@users.sourceforge.net>
 * @version $Revision: 1.4 $ $Date: 2005/01/04 21:32:36 $
 * @package System.Web.UI.WebControls
 */

/**
* IListItemSource
* 
* This interface provides a means for arbitrary class objects to be 
* 'listed' in a list. By implementing these interfaces, an object can
* be used used in the data source for a list. 
*

* @author Marcus Nyeholt <tanus@users.sourceforge.net>
* @version $Revision: 1.4 $ $Date: 2005/01/04 21:32:36 $
* @package System.Web.UI.WebControls
*/
interface IListItemSource
{
	/**
	* Retrieve the text to display when this object is part of the list
	*
	* @return string
	*/
	public function getItemText();
	
	/**
	* Retrieve the value to be used for the TListItem's Value property
	*
	* @return string
	*/
	public function getItemValue();
}

?>