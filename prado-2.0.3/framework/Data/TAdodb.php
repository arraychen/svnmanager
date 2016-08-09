<?php
/**
 * TAdodb class file
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
 * @version $Revision: 1.12 $  $Date: 2005/03/31 12:33:12 $
 * @package System.Data
 */

require_once(dirname(__FILE__).'/adodb/adodb-exceptions.inc.php');
require_once(dirname(__FILE__).'/adodb/adodb.inc.php');

/**
 * TAdodb class
 *
 * TAdodb is a wrapper class of the ADODB ADOConnection class.
 * For more information about the ADODB library, see {@link http://adodb.sourceforge.net/}.
 *
 * Before establishing a DB connection, you need to set either the 
 * <b>DataSourceName</b> property or <b>Driver</b>, <b>Host</b>, <b>User</b>, <b>Password</b>,
 * and <b>Database</b> properties. If both are provided, the <b>DataSourceName</b> takes
 * precedence. The explanation of these properties are very straightforward and can be
 * found in ADODB documentations. Below are some examples about the <b>DataSourceName</b>
 * property,
 * <code>
 *    mysql://user:password@localhost/dbname
 *    postgres7://user:pwd@localhost/mydb?persist
 * </code>
 *
 * Note, if any part of <b>DataSourceName</b> (e.g. password, localhost) 
 * contains special characters such as /:? you need to rawurlencode it first.
 * For example,
 * <code>
 *    $adodb=new TAdodb;
 *    $adodb->DataSourceName="sqlite://".rawurlencode('c:/databases/profile.db');
 * </code>
 *
 * You can call any method implemented in ADOConnection class via TAdodb,
 * such as TAdodb::Execute(), TAdodb::FetchRow(), and so on. The method calls
 * will be passed to ADOConnection class.
 *
 * TAdodb implements two methods <b>open()</b> and <b>close()</b> to allow
 * explicitly establishing and closing DB connection. The <b>open()</b> method will
 * be invoked automatically to prepare a ADOConnection object 
 * if any ADOConnection class method is invoked.
 *
 * Namespace: System.Data
 *
 * Properties
 * - <b>DataSourceName</b>, string
 *   <br>Gets or sets the data source name (DSN) of the DB connection.
 * - <b>Driver</b>, string
 *   <br>Gets or sets the driver of the DB, e.g. 'mysql', 'sqlite', 'postgres7'.
 *   See ADODB documentation for the supported drivers.
 * - <b>Host</b>, string
 *   <br>Gets or sets the host name/ip of the DB server.
 *   A port number may be included as well, for example, 'localhost:6789'.
 * - <b>User</b>, string
 *   <br>Gets or sets the username for logging into DB server.
 * - <b>Password</b>, string
 *   <br>Gets or sets the password for logging into DB server.
 * - <b>Database</b>, string
 *   <br>Gets or sets the name of the database to be used.
 * - <b>FetchMode</b>, string, default=TAdodb::FETCH_DEFAULT
 *   <br>Gets or sets how data is fetched from DB server.
 *   Possible values include TAdodb::FETCH_ASSOCIATIVE, TAdodb::FETCH_NUMERIC,
 *   TAdodb::FETCH_BOTH, TAdodb::FETCH_DEFAULT. See ADODB documentation
 *   for more details about the fetch mode.
 * - <b>PersistentConnection</b>, boolean, default=false
 *   <br>Gets or sets whether the DB connection is persistent or not.
 *
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @version v1.0, last update on 2004/08/13 21:44:52
 * @package System.Data
 */
class TAdodb extends TComponent
{
	const FETCH_ASSOCIATIVE='Associative';
	const FETCH_NUMERIC='Numeric';
	const FETCH_BOTH='Both';
	const FETCH_DEFAULT='Default';
	private $dsn='';
	private $driver='';
	private $host='';
	private $username='';
	private $password='';
	private $database='';
	private $connection=null;
	private $fetchMode='Associative';
	private $persistent=true;
	private $cachedir='';

	/**
	 * PHP magic function.
	 * This method will pass all method calls to ADOConnection class
	 * provided in the ADODB library.
	 * @param mixed method name
	 * @param mixed method call parameters
	 * @param mixed return value of the method call
	 */
	public function __call($method, $params)
	{
		if(is_null($this->connection) || !$this->connection->IsConnected())
			$this->open();
		return call_user_func_array(array($this->connection,$method),$params);
	}

	/** 
	 * Cleanup work before serializing. 
	 * This is a PHP defined magic method. 
	 * @return array the names of instance-variables to serialize. 
	 */ 
	public function __sleep() 
	{ 
		$this->close(); 
		return array_keys(get_object_vars($this)); 
	} 

	/** 
	 * This method will be automatically called when unserialization happens. 
	 * This is a PHP defined magic method. 
	 */ 
	public function __wakeup() 
	{ 
	} 

	/**
	 * @return string the data source name (DSN)
	 */
	public function getDataSourceName()
	{
		return $this->dsn;
	}

	/**
	 * Sets the data source name (DSN).
	 * The format of DSN is:
	 *    $driver://$username:$password@hostname/$database?options[=value]
	 * Note, each part if it contains special characters you need to
	 * rawurlencode it first.
	 * @param string the data source name (DSN)
	 */
	public function setDataSourceName($value)
	{
		$this->dsn=$value;
	}

	/**
	 * @return string the DB driver (mysql, sqlite, etc.)
	 */
	public function getDriver()
	{
		return $this->driver;
	}

	/**
	 * Sets the DB driver (mysql, sqlite, etc.)
	 * @param string the DB driver
	 */
	public function setDriver($value)
	{
		$this->driver=$value;
	}

	/**
	 * @return string the DB host name/IP (and port number) in the format "host[:port]"
	 */
	public function getHost()
	{
		return $this->host;
	}

	/**
	 * Sets the DB host name/IP (and port number) in the format "host[:port]"
	 * @param string the DB host
	 */
	public function setHost($value)
	{
		$this->host=$value;
	}

	/**
	 * @return string the DB username
	 */
	public function getUsername()
	{
		return $this->username;
	}

	/**
	 * Sets the DB username
	 * @param string the DB username
	 */
	public function setUsername($value)
	{
		$this->username=$value;
	}

	/**
	 * @return string the DB password
	 */
	public function getPassword()
	{
		return $this->password;
	}

	/**
	 * Sets the DB password
	 * @param string the DB password
	 */
	public function setPassword($value)
	{
		$this->password=$value;
	}

	/**
	 * @return string the database name
	 */
	public function getDatabase()
	{
		return $this->database;
	}

	/**
	 * Sets the database name
	 * @param string the database name
	 */
	public function setDatabase($value)
	{
		$this->database=$value;
	}

	/**
	 * @return boolean whether the DB connection is persistent
	 */
	public function isPersistentConnection()
	{
		return $this->persistent;
	}

	/**
	 * Sets whether the DB connection should be persistent
	 * @param boolean whether the DB connection should be persistent
	 */
	public function setPersistentConnection($value)
	{
		$this->persistent=$value;
	}

	/**
	 * @return string the cache directory for adodb module
	 */
	public function getCacheDir()
	{
		return $this->cachedir;
	}

	/**
	 * Sets the cache directory for ADODB (in adodb it is
	 * called to $ADODB_CACHE_DIR)
	 * @param string the cache directory for adodb module
	 */
	public function setCacheDir($value)
	{
		$this->cachedir=$value;
	}

	/**
	 * @return string fetch mode of query data
	 */
	public function getFetchMode()
	{
		return $this->fetchMode;
	}

	/**
	 * Sets the fetch mode of query data: Associative, Numeric, Both, Default (default)
	 * @param string the fetch mode of query data
	 */
	public function setFetchMode($value)
	{
		if($value===self::FETCH_ASSOCIATIVE || $value===self::FETCH_NUMERIC 
				|| $value===self::FETCH_BOTH)
			$this->fetchMode=$value;
		else
			$this->fetchMode=self::FETCH_DEFAULT;
	}

	/**
	 * Establishes a DB connection.
	 * An ADOConnection instance will be created if none.
	 * The data fetch mode is also set here.
	 */
	public function open()
	{
		if(is_null($this->connection) || !$this->connection->IsConnected())
		{
			if(empty($this->dsn))
			{
				if(empty($this->driver))
					throw new Exception('You need to specify the DB driver.');
				$this->connection=ADONewConnection($this->driver);
				if($this->persistent)
					$this->connection->PConnect($this->host,$this->username,$this->password,$this->database);
				else
					$this->connection->Connect($this->host,$this->username,$this->password,$this->database);
			}
			else
				$this->connection=ADONewConnection($this->dsn);
			global $ADODB_FETCH_MODE;
			if($this->fetchMode===self::FETCH_ASSOCIATIVE)
				$ADODB_FETCH_MODE=ADODB_FETCH_ASSOC;
			else if($this->fetchMode===self::FETCH_NUMERIC)
				$ADODB_FETCH_MODE=ADODB_FETCH_NUM;
			else if($this->fetchMode===self::FETCH_BOTH)
				$ADODB_FETCH_MODE=ADODB_FETCH_BOTH;
			else
				$ADODB_FETCH_MODE=ADODB_FETCH_DEFAULT;
			global $ADODB_CACHE_DIR;
			if($this->cachedir!=='')
				$ADODB_CACHE_DIR=$this->cachedir;
		}
		return $this->connection->IsConnected();
	}

	/**
	 * Closes the DB connection.
	 * You are not required to call this method as PHP will automatically
	 * to close any DB connections when exiting a script.
	 */
	public function close()
	{
		if(!is_null($this->connection) && $this->connection->IsConnected())
			$this->connection->Close();
	}
}

?>