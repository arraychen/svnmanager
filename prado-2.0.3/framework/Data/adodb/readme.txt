
This directory contains adapted version of adodb v4.61.
The original distribution can be obtained from adodb homepage at
http://adodb.sourceforge.net/

The main update to the original distribution is to make adodb
run without any warning or error messages under PHP 5 with E_STRICT
error reporting option enabled.
- changed all class variable declaration from "var $varname" to "public $varname"
- changed all object creation frmo "=& new" to "= new"

Note, this version may not work with the adodb PHP extension module
because the latter may use an older version.


Zsolt Varga (vzsolt1981@gmail.com)