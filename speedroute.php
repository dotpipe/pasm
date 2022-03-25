<?php

require_once('pasm2.php');

class switcher extends PASM {

	public $QURY;
	public $resh;
	public $reqh;
	public $pasm;
	public $uri;

	/**
	* @method __construct
	* @param none
	*
	*/
	function __construct() {
		$this->pasm = new PASM();
		$GET = isset($_SERVER['REQUEST_METHOD']) ? $_SERVER['REQUEST_METHOD'] : null;
		$this->QURY = null;
		if ($GET != null) {
			if ($GET === 'GET')
				$this->QURY = $_GET;
			else if ($GET === 'POST')
				$this->QURY = $_POST;
			if (!isset($this->QURY) || $this->QURY['port'] == null)
				$this->QURY['port'] = 80;
			if (!isset($this->QURY) || $this->QURY['user'] == null)
				$this->QURY['user'] = "guest";
		}
		$this->reqHeaders();
		$this->resHeaders();

	}

	/**
	 * @method addContract
	 *
	 * With $QURY, create switch route
	 *
	 */
	public function addContract() {
		if ($this->group_id > 0)
			return false;
		if (count($this->pasm::$stack) == 0 && file_exists($_COOKIE['PHPSESSID']))
			$this->load($_COOKIE['PHPSESSID']);

		$sp = $this->getContract();

		if ($sp != -1) {
			$p = array_search($sp, $this->pasm::$stack);
			$sp['allowed'] = 1;
			$this->pasm::$stack[$p] = $sp;
		}
		else {

			$this->pasm::addr([
				"recv" => $this->QURY['recv'],
				"from" => $this->QURY['from'],
				"allowed" => 1,
				"redirect" => [basename($_SERVER['PHP_SELF']), $this->QURY['target']],
				"port" => $this->QURY['port'],
				"user" => $this->QURY['user']
				])
				->movr()
				->end();
		}

		return $this;
	}

	/**
	 * @method addContract
	 *
	 * Add user from $QURY
	 *
	 */
	public function addUserToContract() {
		if ($this->group_id > 0)
			return false;
		if (count($this->pasm::$stack) == 0 && file_exists($_COOKIE['PHPSESSID']))
			$this->load($_COOKIE['PHPSESSID']);

		$sp = $this->getContract();

		if ($sp != -1) {
			$p = array_search($sp, $this->pasm::$stack);
			$sp['allowed'] = 1;
			$this->pasm::$stack[$p] = $sp;
		}
		else {

			$this->pasm::addr([
				"from" => $this->QURY['from'],
				"allowed" => 1,
				"redirect" => [basename($_SERVER['PHP_SELF']), $this->QURY['group'] . '/' . $this->QURY['user'] . '/' . $this->QURY['sub'] . '/' . $this->QURY['target']],
				"port" => $this->QURY['port'],
				"user" => $this->QURY['user']
				])
				->movr()
				->end();
		}

		return $this;
	}

	/**
	 * @method remContract
	 *
	 * Remove switch route
	 *
	*/
	public function remContract() {
		if ($this->group_id > 0)
			return false;

		$sp = $this->getContract();
		if ($sp != -1) {
			$p = array_search($sp, $this->pasm::$stack);
			$sp['allowed'] = 0;
			$this->pasm::$stack[$p] = $sp;
		}
		else {
			$this->pasm::addr([
				"recv" => $this->QURY['recv'],
				"from" => $this->QURY['from'],
				"allowed" => 1,
				"redirect" => [basename($_SERVER['PHP_SELF']), $this->QURY['target']],
				"port" => $this->QURY['port'],
				"user" => $this->QURY['user']
				])
				->movr()
				->end();
		}
		return $this;
	}

	/**
	 * @method remUserFromContract
	 *
	 * Remove specific user from switch route
	 *
	*/
	public function remUserFromContract() {
		if ($this->group_id > 0)
			return false;

		$sp = $this->getContract();
		if ($sp != -1) {
			$p = array_search($sp, $this->pasm::$stack);
			$sp['allowed'] = 0;
			$this->pasm::$stack[$p] = $sp;
		}
		else {
			$this->pasm::addr([
				"from" => $this->QURY['from'],
				"allowed" => 1,
				"redirect" => [basename($_SERVER['PHP_SELF']), $this->QURY['recv'] . '/' . $this->QURY['user'] . '/' . $this->QURY['sub'] . '/' . $this->QURY['target']],
				"port" => $this->QURY['port'],
				"user" => $this->QURY['user']
				])
				->movr()
				->end();
		}
		return $this;
	}

	/**
	 * @method getContract
	 *
	 * Extract contract for redirection
	 *
	*/
	public function getContract() {
		$user = [
			"from" => $this->QURY['from'],
			"allowed" => 1,
			"redirect" => [basename($_SERVER['PHP_SELF']), $this->QURY['group'] . '/' . $this->QURY['user'] . '/' . $this->QURY['sub'] . '/' . $this->QURY['target']],
			"port" => $this->QURY['port'],
			"user" => $this->QURY['user']
		];
		$redirect = [
			"recv" => $this->QURY['recv'],
			"from" => $this->QURY['from'],
			"allowed" => 1,
			"redirect" => [basename($_SERVER['PHP_SELF']), $this->QURY['target']],
			"port" => $this->QURY['port'],
			"user" => $this->QURY['user']
		];
		if (1 >= count(array_intersect_assoc($redirect,$this->pasm::$stack)))
			return $redirect;
		else if (1 >= count(array_intersect_assoc($user,$this->pasm::$stack)))
			return $user;
		return -1;
	}

	/**
	 * @method http_parse_query
	 *
	 * Look through url and exapnd on details
	 *
	*/
	public function http_parse_query(string $query) {
		$parameters = array();
		$query = explode('?', $query);
		$queryParts = explode('&', $query[1]);
		foreach ($queryParts as $queryPart) {
			$keyValue = explode('=', $queryPart, 2);
			if (substr($keyValue,strlen($keyValue)-3) != "[]")
				$parameters[$keyValue[0]] = $keyValue[1];
			else {
				$keyValue = substr($keyValue,strlen($keyValue)-3);
				$parameters[$keyValue][] = $keyValue[1];
			}
		}
		return $parameters;
	}

	/**
	 * @method route
	 *
	 * Redirect to proper route
	 *
	*/
	public function route(){
		$config = json_decode(file_get_contents("config.json"));
		if (($sp = $this->getContract()) != -1)
		{
			if ($sp['allowed'] == 0) {
				header("Location: error404.php");

			}
			$field = [];
			$protocol = getservbyport($sp['port'],'tcp');
			$aim = $sp['redirect'][1];
			$config = json_decode(file_get_contents("config.json"));
			# Create a connection
			$url = "{$protocol}://{$config->domain}/{$aim}";
			if ($_SERVER['REQUEST_METHOD'] == "POST") {
				$handle = curl_init();
				$this->reqHeaders();
				$user_agent=$_SERVER['HTTP_USER_AGENT'];
				curl_setopt($handle, CURLOPT_HTTPHEADER, $this->reqh);
				//curl_setopt($handle, CURLOPT_HEADER, true);
				//curl_setopt($handle, CURLOPT_TIMEOUT, 20);
				curl_setopt($handle, CURLOPT_URL, $url);
				curl_setopt($handle, CURLOPT_RETURNTRANSFER, true);
				curl_setopt($handle, CURLOPT_POST, true);
				curl_setopt($handle, CURLOPT_FOLLOWLOCATION,true);
				curl_setopt($handle, CURLOPT_POSTFIELDS, http_build_query($this->QURY));
				//curl_setopt($handle, CURLOPT_ENCODING, "");
				//curl_setopt($handle, CURLOPT_USERAGENT, $user_agent);
				$page_contents = curl_exec($handle);
				echo $page_contents;
			}
			else {
				$data = "";
				foreach ($this->QURY as $key => $value) {
					$data .= "&{$key}={$value}";
				}
				$data = substr($data,1);
				header("Location: {$aim}?{$data}");
			}
		}
		else {
			$this->reqHeaders();
			$q = basename($_SERVER['PHP_SELF']);
			header("Location: error.php");
		}
	}

	/**
	 * @method reqHeaders
	 *
	 * get request headers
	 *
	*/
	public function reqHeaders() {
		$this->reqh = apache_request_headers();
		return $this;
	}

	/**
	 * @method resHeaders
	 *
	 * get response headers
	 *
	*/
	public function resHeaders() {
		$this->resh = apache_response_headers();
		return $this;
	}

	/**
	 * @method save
	 * @param $filename
	 *
	 * save routing information
	 *
	*/
	public function save(string $filename = "") {

		if ($filename == "")
			$filename = $_COOKIE['PHPSESSID'];
		if (count($this->pasm::$stack) == 0 && file_exists($_COOKIE['PHPSESSID']))
			$this->pasm::recvr_stack($filename)->end();

		$this->pasm::load_str($filename)
			->save_stack_file()
			->end();
		return $this;
	}

	/**
	 * @method load
	 * @param $filename
	 *
	 * Remove specific user from switch route
	 *
	*/
	public function load(string $filename= "") {
		$this->pasm::$stack = [];
		if ($filename == "")
			$filename = $_COOKIE['PHPSESSID'];
		if (!file_exists($filename))
			return false;
		$this->pasm::recvr_stack($filename)
			->end();
		return $this;
	}

}
?>
