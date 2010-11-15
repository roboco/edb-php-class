<?php
/**
   * EDB Class -- Easy Database Class
   * @version 0.1.0
   * @author Eduards Marhelis <eduards.marhelis@gmail.com>
   * @link http://code.google.com/p/edb-php-class/
   * @copyright Copyright 2010 Eduards Marhelis
   * @license http://www.opensource.org/licenses/mit-license.php MIT License
   * @package EDB Class
   */
class edb{
	private $con;
	public $res;
	public $line;
	public $one;
	/**
	   * @function 			__Construct 
	   * @description 		Connects to database when created new edb(); object.
	   * @param string 		$host 	Database Host.
	   * @param string 		$user 	Database user.
	   * @param string 		$pass 	Database pass.
	   * @param string 		$db 	Database name.
	   * @return 			nothing.
	   */
	public function __construct($host, $user, $pass, $db){
		$this->con = mysql_connect($host, $user, $pass) or die(mysql_error());
		mysql_select_db($db) or die(mysql_error());
	}
	/**
	   * @function 			q  (shortening for query) 
	   * @description 		runs mysql query and returns php array.
	   * @param string 		$a 	Mysql Code.
	   * @return 			array();
	   */
	public function q($a){
		$this->res = array();
		$q = mysql_query("$a") or die(mysql_error());  
		while($row = mysql_fetch_array($q)){
			$this->res[] = $row;
		}
		return $this->res;
	}
	/**
	   * @function 			line   
	   * @description 		runs mysql query and returns php array with line from db.
	   * @param string 		$a 	Mysql Code.
	   * @return 			array();
	   */
	public function line($a){
		$query = mysql_query("$a");
		$r = mysql_fetch_array( $query );
		$this->line = $r;		
		return $this->line;
	}
	/**
	   * @function 			one   
	   * @description 		runs mysql query and returns php string db.
	   * @param string 		$a 	Mysql Code.
	   * @return 			string.
	   */
	public function one($a){
		$query = mysql_query("$a");
		$r = mysql_fetch_array( $query );
		$i=0; if(isset($b)) {$i=$b;}
		$this->one = $r[$i];
		return $this->one;
	}
	/**
	   * @function 			s   
	   * @description 		runs mysql query and returns result from mysql query. used for inserts and updates. 
	   * @param string 		$a 	Mysql Code.
	   * @return 			string.
	   */
	public function s($a){
		$q = mysql_query("$a") or die(mysql_error());  
		return $this->q;
	}
	/**
	   * @function 			__destruct   
	   * @description 		closes mysql connection.
	   */
	public function __destruct(){
		mysql_close($this->con);
	}

}

?>