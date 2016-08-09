<?php
/**
 * TFileUpload class file
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
 * @version $Revision: 1.9 $  $Date: 2005/08/17 22:09:08 $
 * @package System.Web.UI.WebControls
 */

/**
 * TFileUpload class
 *
 * Creates a file upload HTML widget with a Browse... button for selecting
 * the file.
 *
 * Maintains the following information about the file once it is uploaded
 * 
 * Namespace: System.Web.UI.WebControls
 *
 * Properties
 * - <b>LocalName</b>, string, 
 *   <br>the full path to the file on the local system after it has been uploaded
 * - <b>FileName</b>, string, 
 *   <br>The name of the file as uploaded
 * - <b>FileType</b>, string, 
 *   <br>the mime type of the file uploaded
 * - <b>FileSize</b>, int 
 *	 <br>The size of the file uploaded
 * - <b>UploadError</b>, int 
 *	 <br>Any error that occured during file uploading.
 * - <b>Uploaded</b>, boolean 
 *	 <br>Whether the file was uploaded successfully.
 * - <b>MaxFileSize</b>, int 
 *	 <br>The maximum size of file the browser will let the client upload.
 *	  By default, no limit is set.
 *
 * Events
 * - <b>OnFileUpload</b> Occurs when a file is uploaded successfully
 * - <b>OnFileUploadFailed</b> Occurs when a file upload failes
 *
 * Examples
 * - On a page template file, insert the following line to create a TFileUpload component,
 * <code>
 *   <com:TFileUpload OnFileUpload="handleFileUpload" />
 * </code>
 * 
 *
 * @author Marcus Nyeholt <tanus@users.sourceforge.net>
 * @version $Revision: 1.9 $ $Date: 2005/08/17 22:09:08 $
 * @package System.Web.UI.WebControls
 */
class TFileUpload extends TWebControl implements IPostBackDataHandler
{
	/**
	 * Constructor.
	 * Sets TagName property to 'input'.
	 */
	function __construct()
	{
		parent::__construct();
		$this->setTagName('input');
	}
	
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
	 * @return string the local name of the file (where it is after being uploaded)
	 */
	function getLocalName()
	{
		return $this->getViewState('LocalName','');
	}

	/**
	 * @return string the name of file before it was uploaded
	 */
	function getFileName()
	{
		return $this->getViewState('FileName','');
	}
	
	/**
	 * @return string the size of the file in bytes
	 */
	function getFileSize()
	{
		return $this->getViewState('FileSize','');
	}
	
	/**
	 * @return string the MIME-type of the file
	 */
	function getFileType()
	{
		return $this->getViewState('FileType','');
	}
	
	/**
	 * @return string if there was an upload error, the code for it is here
	 */
	function getUploadError()
	{
		return $this->getViewState('UploadError',0);
	}
	
	/**
	* @return string whether the file was uploaded or not.
	*/
	function getUploaded()
	{
		return $this->getViewState('Uploaded',false);
	}
	
	/**
	* @return string the maximum file size
	*/
	function getMaxFileSize()
	{
		return $this->getViewState('MaxFileSize','');
	}
	
	/**
	* @param int The maximum upload size allowed for a file
	*/
	function setMaxFileSize($size)
	{
		$this->setViewState('MaxFileSize',$size);
	}
	
	/**
	 * Loads user input data.
	 * This method is primarly used by framework developers.
	 * @param string the key that can be used to retrieve data from the input data collection
	 * @param array the input data collection
	 * @return boolean whether the data of the component has been changed
	 */
	function loadPostData($key,&$values)
	{
		if (isset($_FILES[$key])) {
			$this->setViewState('LocalName', $_FILES[$key]['tmp_name']);
			$this->setViewState('FileName', $_FILES[$key]['name']);
			$this->setViewState('FileSize', $_FILES[$key]['size']);
			$this->setViewState('FileType', $_FILES[$key]['type']);
			$this->setViewState('UploadError', $_FILES[$key]['error']);
			
			if ($this->getUploadError() > 0) {
				$this->setViewState('Uploaded', false);
				$this->onFileUploadFailed(new TEventParameter);
			}
			else {
				$this->setViewState('Uploaded', true);
			}
			
			return true;
		}
		
		return false;
	}
	
	
	/**
	 * Raises postdata changed event.
	 * This method calls {@link onFileUpload} method.
	 * This method is primarly used by framework developers.
	 */
	function raisePostDataChangedEvent()
	{
		$this->onFileUpload(new TEventParameter);
	}

	/**
	 * This method is invoked when the value of the <b>LocalName</b> property 
	 * changes between posts to the server.
	 * The method raises 'OnFileUpload' event to fire up the event delegates.
	 * If you override this method, be sure to call the parent implementation
	 * so that the event delegates can be invoked.
	 * @param TEventParameter event parameter to be passed to the event handlers
	 */
	function onFileUpload($param)
	{
		if ($this->getUploaded()) {
			$this->raiseEvent('OnFileUpload',$this,$param);
		}
	}
	
	/**
	 * This method is invoked when the value of the <b>Uploaded</b> property 
	 * changes to false after a postback event, which means the upload 
	 * failed.
	 * If you override this method, be sure to call the parent implementation
	 * so that the event delegates can be invoked.
	 * @param TEventParameter event parameter to be passed to the event handlers
	 */
	function onFileUploadFailed($param)
	{
		$this->raiseEvent('OnFileUploadFailed',$this,$param);
	}

	/**
	 * Returns the value of the property that needs validation.
	 * @return mixed the property value to be validated
	 */
	function getValidationPropertyValue()
	{
		return $this->getFileName();
	}
	
	/**
	 * @return integer the display width of the text box in characters.
	 */
	public function getColumns()
	{
		return $this->getViewState('Columns',0);
	}

	/**
	 * Sets the display width of the text box in characters.
	 * @param integer the display width
	 */
	public function setColumns($value)
	{
		$this->setViewState('Columns',$value,0);
	}
	
	/**
	 * This overrides the parent implementation by rendering more TButton-specific attributes.
	 * @return ArrayObject the attributes to be rendered
	 */
	protected function getAttributesToRender()
	{
		$attributes=parent::getAttributesToRender();
		$attributes['type']='file';
		$attributes['name']=$this->getUniqueID();
		if(($cols=$this->getColumns())>0)
				$attributes['size']=$cols;
		return $attributes;
	}

	public function render()
	{
		$maxSize=$this->getMaxFileSize();
		if(!empty($maxSize))
			$this->getPage()->registerHiddenField('MAX_FILE_SIZE',$maxSize);
		return parent::render();
	}
}

?>