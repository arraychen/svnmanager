<?php

/**
 * TPageDirective class file.
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
 * @author Wei Zhuo <weizhuo [at] gmail [dot] com>
 * @version $Revision: 1.5 $  $Date: 2005/08/04 05:27:19 $
 * @package System
 */

/**
 * TPageDirective class.
 * Assess the <% @Page ... %> directive.
 * @author Xiang Wei Zhuo <weizhuo [at] gmail [dot] com>
 * @version v1.0, last update on Friday, December 24, 2004
 * @package System
 */

class TPageDirective
{
	function assess($parameters,$pageDefinition)
	{
		if(isset($parameters['Master']))
			$pageDefinition->setMasterPageName($parameters['Master']);
		$app = pradoGetApplication()->getGlobalization();
		if(empty($app))
			return;
		if(isset($parameters['Culture']))
			$app->Culture = $parameters['Culture'];	
			
		if(isset($parameters['Charset']))
			$app->Charset = $parameters['Charset'];

		if(isset($parameters['ContentType']))
			$app->ContentType = $parameters['ContentType'];
			
		if(isset($parameters['Catalogue']))
			$app->Translation['catalogue'] = $parameters['Catalogue'];								
	}
}

?>