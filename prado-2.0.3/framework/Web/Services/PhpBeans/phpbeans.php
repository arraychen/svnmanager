<?php

/**
 * PHP Bean client base package.  Allows PHP scripts to communicate with
 * servers implementing the PHP Bean specification, such as the Sitellite
 * Object Server.  For more info, see:
 *
 * http://www.phpbeans.com/
 *
 * <code>
 * <?php
 *
 * loader_import ('saf.Database.Bean');
 *
 * // connect to the server
 *
 * $client = new PHP_Bean_Client ('localhost', 3843, 2);
 * if (! $client->connect ()) {
 *   die ($client->error);
 * }
 *
 * // authenticate yourself
 *
 * if (! $client->authenticate ($user, $pass)) {
 *   die ($client->error);
 * }
 *
 * // call a method using the literal query syntax
 *
 * $res = $client->call ('server/uptime');
 * if (! $res) {
 *   die ($client->error);
 * }
 *
 * echo 'Server up since: ' . $res;
 *
 * // or use a local object to alias a server-side one
 *
 * $server =& $client->getObject ('server');
 *
 * echo 'Server up since: ' . $server->uptime ();
 *
 * // and finally, disconnect
 *
 * $client->disconnect ();
 *
 * ? >
 * </code>
 *
 * @package Database
 * @author John Luxford <lux@simian.ca>
 * @copyright Copyright (C) 2004, Simian Systems Inc.
 * @license http://www.opensource.org/licenses/lgpl-license.php
 * @version 1.0, 2004-09-10, $Id: phpbeans.php,v 1.2 2005/04/14 02:00:47 weizhuo Exp $
 * @access public
 */
class PHP_Bean_Client {
	/**3078
	 * Socket connection resource.
	 * 
	 * @access	public
	 */
	public $connection;

	/**
	 * Server name or IP address.
	 * 
	 * @access	public
	 */
	public $server = 'localhost';

	/**
	 * Server port.
	 * 
	 * @access	public
	 */
	public $port = 3843;

	/**
	 * Socket timeout for connection and requests.
	 * 
	 * @access	public
	 */
	public $timeout = 15;

	/**
	 * Username to authenticate with the bean server.
	 * 
	 * @access	public
	 */
	public $user;

	/**
	 * Password to authenticate with the bean server.
	 * 
	 * @access	public
	 */
	public $pass;

	/**
	 * Error number when an error occurs.
	 * 
	 * @access	public
	 */
	public $errno;

	/**
	 * Error message when an error occurs.
	 * 
	 * @access	public
	 */
	public $error;

	/**
	 * Log of communication between the client and the server.
	 * 
	 * @access	public
	 */
	public $log = array ();

	/**
	 * Whether to keep a log of the communication or not.
	 * 
	 * @access	public
	 */
	public $logging = false;

	/**
	 * The maximum size of a response message from the server.
	 * Default is 4KB.
	 * 
	 * @access	public
	 */
	public $maxResponseLength = 4096;

	/**
	 * Constructor method.
	 *
	 * @access public
	 * @param string
	 * @param int
	 * @param int
	 */
	public function PHP_Bean_Client ($server = 'localhost', $port = 3843, $timeout = 15) {
		$this->server = $server;
		$this->port = $port;
		$this->timeout = $timeout;
	}

	/**
	 * Connects to the server.
	 *
	 * @access public
	 * @return boolean
	 */
	public function connect () {
		$this->connection = fsockopen (
		$this->server,
		$this->port,
		$this->errno,
		$this->error,
		$this->timeout
		);
		if (! $this->connection) {
			$this->errno = 0;
			$this->error = 'Connection failed';
			return false;
		}
		$response = $this->parseResponse ($this->getResponse ());
		if ($response == 'identify') {
			return true;
		}
		$this->error = $response['message'];
		return false;
	}

	/**
	 * Authenticates with the server.
	 *
	 * @access public
	 * @param string
	 * @param string
	 * @return boolean
	 */
	public function authenticate ($user, $pass) {
		$this->user = $user;
		$this->pass = $pass;

		if ($this->logging) {
			$this->log[] = "SEND: $user/$pass\r\n";
		}

		fputs ($this->connection, "$user/$pass\r\n");
		$response = $this->parseResponse ($this->getResponse ());
		if ($this->isError ($response)) {
			$this->errno = $response->code;
			$this->error = $response->message;
			return false;
		}

		return true;
	}

	/**
	 * Creates a local object that mimicks the specified server-side object.
	 *
	 * @access public
	 * @param string
	 * @return object
	 */
	public function &getObject ($name) {
		$class = 'PHP_Bean_' . $name;

		if (class_exists ('PHP_Bean_' . $name)) {
			return new $class ($this);
		}

		$methods = $this->call ($name . '/objectInfo');
		if (! $methods) {
			$this->error = $this->client->error;
			return false;
		}

		$code = '?' . "><?php\n\nclass $class {\n";
		$code .= "\t" . 'function ' . $class . ' (&$client) {' . "\n";
		$code .= "\t\t" . '$this->client =& $client;' . "\n";
		$code .= "\t}\n\n";

		foreach ($methods as $method => $info) {
			$code .= "\t" . 'function ' . $method . ' (';
			if (is_array ($info['parameters'])) {
				$sep = '';
				foreach ($info['parameters'] as $param => $type) {
					$code .= $sep . '$' . $param;
					$sep = ', ';
				}
			}
			$code .= ") {\n";
			$code .= "\t\t" . '$res = $this->client->call (\'' . $name . '/' . $method;
			if (is_array ($info['parameters'])) {
				$sep = '?';
				foreach ($info['parameters'] as $param => $type) {
					$code .= $sep . '\' . $this->client->makeStr (\'' . $param . '\', $' . $param . ') . \'';
					$sep = '&';
				}
			}
			$code .= "');\n";
			$code .= "\t\tif (! \$res) {\n";
			$code .= "\t\t\t\$this->error = \$this->client->error;\n";
			$code .= "\t\t\treturn false;\n";
			$code .= "\t\t}\n";
			$code .= "\t\treturn \$res;\n";
			$code .= "\t}\n\n";
		}

		$code .= "}\n\n?" . '>';

		//echo '<pre style="border: 1px solid #aaa; padding: 10px; margin: 10px; background: #eee">';
		//echo htmlentities ($code);
		//echo '</pre>';

		ob_start ();
		if (eval ($code) === false) {
			$this->error = ob_get_contents ();
			ob_end_clean ();
			return false;
		}
		ob_end_clean ();

		return new $class ($this);
	}

	/**
	 * Calls a method on a remote object.
	 *
	 * @access public
	 * @param string
	 * @return mixed
	 */
	public function call ($request) {
		if ($this->logging) {
			$this->log[] = "SEND: $request\r\n";
		}

		fputs ($this->connection, "$request\r\n");

		$response = $this->parseResponse ($this->getResponse (false));

		if ($this->isError ($response)) {
			$this->errno = $response->code;
			$this->error = $response->message;
			return false;
		}

		return $response;
	}

	/**
	 * Fetches the response from the server.  This is called automatically by
	 * the call() method.
	 *
	 * @return string
	 */
	public function getResponse () {
		$response = '';
		while (true) {
			$resp = fread ($this->connection, 8192);
			$response .= $resp;
			if ($this->logging) {
				$this->log[] = 'RECV: ' . $resp;
			}
			if (strpos ($resp, "\n") !== false) {
				break;
			}
		}
		return trim ($response);
	}

	/**
	 * Unserializes the response from the server.  Called automatically by the
	 * call() method.
	 *
	 * @param string
	 * @return mixed
	 */
	public function parseResponse ($resp) {
		$resp = unserialize ($resp);
		return $this->decode ($resp);
	}

	/**
	 * Makes a properly formatted key/value pair ready for inclusion in a
	 * method call.
	 *
	 * @access	public
	 * @param	string
	 * @param	string
	 * @return	string
	 */
	public function makeStr ($name, $value) {
		if (is_object ($value)) {
			$value = (array) $value;
		}
		if (is_array ($value)) {
			$str = '';
			$sep = '';
			foreach ($value as $k => $v) {
				$str .= $sep . $name . '[' . $k . ']=' . urlencode ($v);
				$sep = '&';
			}
			return $str;
		}
		return $name . '=' . urlencode ($value);
	}

	/**
	 * Determines whether a response is an error or not.  Called automatically by
	 * the call() method.
	 *
	 * @param mixed
	 * @return boolean
	 */
	public function isError ($resp) {
		if (is_object ($resp) && strtolower (get_class ($resp)) == 'php_bean_error') {
			return true;
		}
		return false;
	}

	/**
	 * Disconnects from the server.
	 */
	public function disconnect () {
		if(!$this->connection) return;
		if ($this->logging) {
			$this->log[] = "SEND: quit\r\n";
		}
		fputs ($this->connection, "quit\r\n");
		$response = $this->getResponse ();
		fclose ($this->connection);
	}

	/**
	 * Decode Unicode characters properly.
	 */
	public function decode ($resp) {
		if (is_array ($resp)) {
			foreach ($resp as $k => $v) {
				$resp[$k] = $this->decode ($v);
			}
			return $resp;
		} elseif (is_object ($resp)) {
			foreach (get_object_vars ($resp) as $k => $v) {
				$resp->{$k} = $this->decode ($v);
			}
			return $resp;
		} elseif (is_string ($resp)) {
			if (strpos ($resp, '%u') !== false) {
				return phpbeans_unicode_decode ($resp);
			} else {
				return $resp;
			}
		} else {
			return $resp;
		}
	}
}

/**
 * This is the error object type that is returned from the server when an error
 * occurs.
 *
 * @package Database
 * @author John Luxford <lux@simian.ca>
 * @copyright Copyright (C) 2004, Simian Systems Inc.
 * @license GPL
 * @version 1.0, 2004-09-10, $Id: phpbeans.php,v 1.2 2005/04/14 02:00:47 weizhuo Exp $
 * @access public
 */
class PHP_Bean_Error {
	/**
	 * The error message.
	 * 
	 * @access	public
	 */
	public $message = '';

	/**
	 * The error code.
	 * 
	 * @access	public
	 */
	public $code = 0;

	/**
	 * Constructor method.
	 *
	 * @access public
	 * @param int
	 * @param string
	 */
	public function PHP_Bean_Error ($code = 0, $message = '') {
		$this->code = $code;
		$this->message = $message;
	}
}

/**
 * Converts a unicode character number to the proper UTF-8 value.
 *
 * Borrowed from:
 *
 * http://www.randomchaos.com/document.php?source=php_and_unicode
 */
function phpbeans_unicode_chr ($unicode) {
	if ( $unicode < 128 ) {

		return chr ( $unicode );

	} elseif ( $unicode < 2048 ) {

		return chr ( 192 +  ( ( $unicode - ( $unicode % 64 ) ) / 64 ) )
		. chr ( 128 + ( $unicode % 64 ) );

	} else {

		return chr ( 224 + ( ( $unicode - ( $unicode % 4096 ) ) / 4096 ) )
		. chr ( 128 + ( ( ( $unicode % 4096 ) - ( $unicode % 64 ) ) / 64 ) )
		. chr ( 128 + ( $unicode % 64 ) );

	}
}

/**
 * Decodes URLs that contain unicode values.
 */
function phpbeans_unicode_decode ($txt) {
	return preg_replace_callback (
	'|%u([0-9A-Z]{4})|s',
	create_function (
	'$matches',
	'return phpbeans_unicode_chr (hexdec ($matches[1]));'
	),
	$txt
	);
}

?>