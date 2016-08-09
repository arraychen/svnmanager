<?php
/**
 * TExpression class file
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
 * @version $Revision: 1.10 $  $Date: 2005/05/01 15:19:55 $
 * @package System.Web.UI.WebControls
 */

/**
 * TExpression class
 *
 * TExpression evaluates a PHP expression and renders the result.
 * The expression is evaluated during rendering stage. You can set
 * it via the property <b>Expression</b>. You should also specify
 * the context object by <b>Context</b> property which is used as
 * the object in which the expression is evaluated. If the <b>Context</b>
 * property is not set, the TExpression component itself will be
 * assumed as the context.
 *
 * Namespace: System.Web.UI.WebControls
 *
 * Properties
 * - <b>Expression</b>, string
 *   <br>Gets or sets the expression to be evaluated.
 *   The expression result will be inserted at the place of the component.
 * - <b>Context</b>, TComponent, default=$this
 *   <br>Gets or sets the context object used for evaluating the expression.
 *
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @version v1.0, last update on 2004/08/13 21:44:52
 * @package System.Web.UI.WebControls
 */
class TExpression extends TControl
{
	private $context=null;
	private $expression='';

	/**
	 * Overrides parent implementation to disable body addition.
	 * @param mixed the object to be added
	 * @return boolean
	 */
	public function allowBody($object)
	{
		return false;
	}

	/**
	 * @return string the expression to be evaluated
	 */
	public function getExpression()
	{
		return $this->expression;
	}

	/**
	 * Sets the expression of the TExpression
	 * @param string the expression to be set
	 */
	public function setExpression($value)
	{
		$this->expression=$value;
	}

	/**
	 * @return TComponent the context object of the TExpression
	 */
	public function getContext()
	{
		return $this->context;
	}

	/**
	 * Sets the context object of the TExpression
	 * @param TComponent the context object
	 */
	public function setContext(TComponent $value)
	{
		$this->context=$value;
	}

	/**
	 * Renders the evaluation result of the expression.
	 * @return string the rendering result
	 */
	public function render()
	{
		$expression=$this->getExpression();
		$context=$this->getContext();
		if(is_null($context))
			$context=$this;
		if(strlen($expression))
			return $context->evaluateExpression($expression);
		else
			return '';
	}
}

?>
