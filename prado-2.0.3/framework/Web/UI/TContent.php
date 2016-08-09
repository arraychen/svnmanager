<?php
/**
 * TContent class file
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
 * @version $Revision: 1.1 $  $Date: 2005/03/11 03:26:14 $
 * @package System.Web.UI
 */

/**
 * TContent class
 *
 * TContent serves as the container of block of content within a content page.
 * Note, any control appeared within the body of TContent has the TContent
 * as its naming container.
 *
 * When using TContent, pay attention to its ID assignment. The rendering result
 * of a TContent control will be inserted in the master page at the place where
 * a TContentPlaceHolder with the same ID is located.
 *
 * Namespace: System.Web.UI
 *
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @version v1.0, last update on 2004/08/13 21:44:52
 * @package System.Web.UI
 */
class TContent extends TControl
{
	/**
	 * Override the parent implementation. 
	 * Adds all components within the TContent body as it's child.
	 * @param object an object within the TWizardTemplate
	 * has been handled.
	 * @param object a component object.
	 * @param object the template owner object
	 */
	public function addParsedObject($object,$context)
	{
		if($object instanceof TComponent)
			$this->addChild($object);
		$this->addBody($object);
	}
}

?>