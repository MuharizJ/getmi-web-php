<?php
	class Singleton {
		private static $instances = [];
		protected function __construct() { }
		protected function __clone() { }
		public function __wakeup() {
			throw new \Exception('Cannot deserialize Singleton');
		}
		public static function getInstance() {
			$subClass = static::class;

			if (!isset(self::$instances[$subClass]))
				self::$instances[$subClass] = new static;

			return self::$instances[$subClass];
		}
	}

	class SFAuth extends Singleton {
		private static $debug = false;
		private static $accessToken;
		private static $instanceUrl;

		public static function getInstanceUrl() {
			if (empty(self::$instanceUrl))
				self::authenticate();

			return self::$instanceUrl;
		}

		public static function getAccessToken() {
			if (empty(self::$accessToken))
				self::authenticate();

			return self::$accessToken;
		}

		public static function authenticate() {
			$response = self::send('POST', 'https://login.salesforce.com/services/oauth2/token', self::$authArray);

			if (self::$debug)
				echo '<h5>SF.authenticate() result</h5><pre>' . var_export($response, true) . '</pre><br />';
			
			if (!empty($response) && !empty($response->status) && $response->status == 200) {
				self::$accessToken = $response->data->access_token;
				self::$instanceUrl = $response->data->instance_url;
			}
		}
	
		private static $authArray = array(
			'grant_type' => 'password',
			'client_id' => '3MVG97quAmFZJfVyWSr.jallbUo17XgfSBuzCpemTQTYB2Pe8wo7MOwpLiurYgy27hl5HSQTaFYPc3o_LdBaD',
			'client_secret' => '7806B8C1ADBF6FE4B2FB7B547EFFDA57E3E2AF984954D527A230ADF875AC7086',
			'username' => 'muharizj@getmi.com.au',
			'password' => 'Kenobi1981!8HU82Cd5Fi6aElEw9QijpliaW'
		);

		private static function send($method, $path, $jsonPost) {
			$relativeUrl = false;
			if (substr($path, 0, 1) === '/')
				$relativeUrl = true;

			if(self::$debug) {
				echo "send()<br />";
				echo "relativeUrl: $relativeUrl<br />";
			}

			$path = $relativeUrl ? self::$instanceUrl . $path : $path;
			if(self::$debug)
				echo '<p>[[ ' . $path . ' ]]</p>';

			$ch = curl_init($path);

			curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $method);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			curl_setopt($ch, CURLOPT_VERBOSE, true);

			if ($method == 'POST' && !empty($jsonPost)) {
				curl_setopt($ch, CURLOPT_POST, true);
				curl_setopt($ch, CURLOPT_POSTFIELDS, $jsonPost);

				if(self::$debug)
					echo "isPOST has non-empty jsonPost: " . var_export($jsonPost, true) . "<br />";
			}

			if ($relativeUrl) {
				$headers = array(
					'Authorization: Bearer ' . self::getAccessToken(),
					'Content-Type: application/json',
					'Content-Length: ' . strlen($jsonPost)
				);

				curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
				if(self::$debug)
					echo "using these headers: " . var_export($headers, true) . "<br />";
			} else {
				curl_setopt($ch, CURLOPT_HEADER, false);
				if(self::$debug)
					echo "its login... no headers";
			}

			//file_put_contents("php://stderr", var_dump($ch));

			$result = curl_exec($ch);

			$curlInfo = curl_getinfo($ch);

			if(self::$debug) {
				foreach ($curlInfo as $key => $val)
					echo '<p><strong>CURL INFO ' .  $key . '</strong>&nbsp;' . var_export($val, true) . '</p>';
			}


			$statusCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
			$decoded = json_decode($result);

			$response = new StdClass();
			$response->data = $decoded;
			$response->raw = $result;
			$response->status = $statusCode;
			$response->errno = curl_errno($ch);
			$response->errmsg = curl_error($ch);

			if (self::$debug) {
				echo "<h5>curl.send(method: $method, path: $path, jsonPost: $jsonPost)</h5>";
				echo "response<br /><pre>" . var_export($response, true) . "</pre>";
			}

			curl_close($ch);
			//file_put_contents("php://stderr", '@# ' . var_dump($response));
			return $response;
		}

		private static function checkSessionInit() {
			if (!isset($_SESSION))
				session_start();
		}

	}
?>