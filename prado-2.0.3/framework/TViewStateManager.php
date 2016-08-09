<?php
/**
 * TViewStateManager class file.
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
 * @version $Revision: 1.6 $  $Date: 2005/06/09 14:49:20 $
 * @package System
 */
 
/**
 * TViewStateManager class
 *
 * TViewStateManager maintains the storage of viewstate data on server side.
 * When it is enabled, page viewstate data will be saved in session.
 * You can extend this class to store viewstate data with other methods.
 *
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @version v1.0, last update on 2004/08/13 21:44:52
 * @package System
 */
class TViewStateManager
{
	const MAX_PAGE_NUMBER=100;
	const SESSION_VSM='vsm';
	const SESSION_KEY='key';
	private $bufferSize=10;
	private $enabled=false;
	protected $currentID=-1;
	protected $key='';
	protected $encrypt=false;

	/**
	 * Constructor.
	 * Reads the configuration given in the app spec.
	 * In particular, the attribute 'enabled' and 'buffer-size' are read.
	 * @param mixed the configuration.
	 */
	function __construct($config)
	{
		if(isset($config['enabled']) && (string)$config['enabled']=='true')
			$this->enabled=true;
		else
			$this->enabled=false;
		if(isset($config['key']))
			$this->key=(string)$config['key'];
		if(isset($config['encrypt']) && (string)$config['encrypt']=='true')
			$this->encrypt=true;
		else
			$this->encrypt=false;

		if(isset($config['buffer-size']))
			$this->bufferSize=intval((string)$config['buffer-size']);
		if($this->bufferSize<=0 || $this->bufferSize>self::MAX_PAGE_NUMBER)
			throw new Exception('buffer-size must be an integer between 1 and '.self::MAX_PAGE_NUMBER.'.');
	}

	/**
	 * @return integer the number of page viewstates that will be saved
	 */
	public function getBufferSize()
	{
		return $this->bufferSize;
	}

	/**
	 * @return boolean is server-side viewstate maintenance enabled
	 */
	public function isEnabled()
	{
		return $this->enabled;
	}

	/**
	 * Saves a viewstate (in terms of a string) in session.
	 * Default implementation saves current page viewstate
	 * into an array that contains a list of pages' viewstate
	 * that are due to past visits within current session.
	 * The array is then saved into session.
	 * This method can be overriden to provide customized storage of
	 * viewstate data. If you override this method, please
	 * override {@link loadViewState()} as well.
	 * @param string viewstate data
	 * @return integer an ID identifying the storage of this viewstate
	 */
	public function saveViewState(&$viewState)
	{
		$session=pradoGetApplication()->getSession();
		if(!$session->isStarted())
			throw new Exception('Session is required in order to use '.get_class($this).'.');
		$name=pradoGetApplication()->getApplicationID().':'.self::SESSION_VSM;
		if($session->has($name))
		{
			$viewStates=$session->get($name);
			if($this->currentID>=0)
				$id=$this->currentID+1;
			else
			{
				$n=count($viewStates);
				$id=0;
				for($i=0;$i<$n;++$i)
					if(isset($viewStates[$i][0]) && $viewStates[$i][0]>=$id)
						$id=$viewStates[$i][0]+1;
			}
			$index=$id%$this->getBufferSize();
		}
		else
		{
			$viewStates=array();
			$id=0;
			$index=0;
		}
		$viewStates[$index]=array($id,$viewState);
		$session->set($name,$viewStates);
		return $id;
	}

	/**
	 * Loads a viewstate (in terms of a string) from session.
	 * This method can be overriden to provide customized retrieval of
	 * viewstate data.
	 * If you override this method, please
	 * override {@link saveViewState()} as well.
	 * @param integer an ID identifying the storage of this viewstate
	 * @return string viewstate data
	 */
	public function &loadViewState($id)
	{
		$data=null;
		if($id<0)
			return $data;
		$this->currentID=$id;
		$session=pradoGetApplication()->getSession();
		if(!$session->isStarted())
			throw new Exception('Session is required in order to use '.get_class($this).'.');
		$name=pradoGetApplication()->getApplicationID().':'.self::SESSION_VSM;
		if($session->has($name))
		{
			$viewStates=$session->get($name);
			$index=$id%$this->getBufferSize();
			if(isset($viewStates[$index][0]) && $viewStates[$index][0]===$id && isset($viewStates[$index][1]))
				return $viewStates[$index][1];
		}
		return $data;
	}

	/**
	 * Computes the HMAC of the input data.
	 * This method returns MD5-based HMAC.
	 * It can be overriden to provide other way of HMAC computation.
	 * @param string data
	 * @param string key
	 * @return string HMAC of the data
	 */
	protected function computeHMAC(&$data,$key)
	{
//		if (function_exists('mhash'))
//			return mhash(MHASH_MD5,$data,$key);
		if (strlen($key) > 64)
			$key = pack('H32', md5($key));
		elseif (strlen($key) < 64)
			$key = str_pad($key, 64, "\0");
		return md5((str_repeat("\x5c", 64) ^ substr($key, 0, 64)) . pack('H32', md5((str_repeat("\x36", 64) ^ substr($key, 0, 64)) . $data)));
	}

	protected function generateRandomKey()
	{
		$key='';
		for($a=0;$a<24;$a++)  // generate a 24-byte key for 3DES use
			$key.=chr(rand(0,255));
		return $key;
	}

	protected function getKey()
	{
		$key='';
		if(strlen($this->key))
			$key=$this->key;
		else
			$key=pradoGetApplication()->getApplicationID();
		return $key;
	}

	public function &encode(&$viewstate)
	{
		$key=$this->getKey();
		if($this->encrypt)
		{
			using('System.Security.TDESCrypto');
			$des=new TDESCrypto;
			$viewstate=&$des->encrypt($viewstate,$key);
		}
		$hmac=$this->computeHMAC($viewstate,$key);
		$data=$hmac.$viewstate;
		if(function_exists('gzuncompress') && function_exists('gzcompress'))
			$v=base64_encode(gzcompress($data));
		else
			$v=base64_encode($data);
		return $v;
	}

	public function &decode(&$data)
	{
		$key=$this->getKey();
		if(function_exists('gzuncompress') && function_exists('gzcompress'))
			$v=gzuncompress(base64_decode($data));
		else
			$v=base64_decode($data);
		if($v!==false && strlen($v)>32)
		{
			$hmac=substr($v,0,32);
			$viewstate=substr($v,32);
			if($hmac===$this->computeHMAC($viewstate,$key))
			{
				if($this->encrypt)
				{
					using('System.Security.TDESCrypto');
					$des=new TDESCrypto;
					$viewstate=&$des->decrypt($viewstate,$key);
				}
				return $viewstate;
			}
		}
		throw new Exception('ViewState data is corrupted.');
	}
}

?>