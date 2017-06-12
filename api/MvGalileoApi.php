<?php
/*
 * Developer : Agus Puryanto
 * Email : aguspuryanto@gmail.com
 * Project : Galileo API
 * ===============================
 * Development Site: Endpoint URL: http://202.129.226.202:8082/JSON/Merchant/LowFareSearch.aspx. Method: HTTP GET.  
 * Production Site: Endpoint URL: http://202.129.224.196:8082/JSON/Merchant/LowFareSearch.aspx. Method: HTTP GET.   
 */

class Galileo {
	
	protected $apiUsername	= "galileo";
	protected $apiPassword	= "galileo123";
	
	public $accountCode	= "";
	public $merchantID	= "1G";
	public $sessionID	= "";
	
	public $Development_Site = "http://202.129.226.202:8082/JSON/Merchant/LowFareSearch.aspx";
	public $Production_Site = "http://202.129.224.196:8082";
	
	public function __construct(){
		//
	}
	
	public function set_sessionID($sessionID){
		$this->sessionID = $sessionID;
	}
	
	public function get_Production_Site(){
		return $this->Production_Site;
	}
	
	public function get_Curl($url){
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		$result = curl_exec($ch);
		curl_close($ch);
		return $result;
	}
	 
	public function LowFareSearchDemo($file_cache="SUBDPS2016-11-091.json"){
		$json = file_get_contents($file_cache);
		$json = json_decode($json, true);
		return ($json);		
	}
	 
	public function LowFareSearch_Filter(){
		global $LowFareSearch;
		
		$newArray = array();
		foreach ($LowFareSearch as $v){
		   $newArray[] = $v['TotalPrice'];
		}
		
		$newArray = array_unique($newArray, SORT_REGULAR);
		$r=1;
		foreach($newArray as $array){
			$data[$r] = $array;
			$r++;
		}
		return $data;
	}
	
	/*
	 * Search:
	 *http://localhost:10/JSON/Merchant/LowFareSearch.aspx?request=availability&originCode=CGK&destinationCode=BKK&departDate=2016-04-30&returnDate=2016-05-22&cabinClass=Economy&routeType=2&totalAdult=1&totalChild=1&totalInf=1&apiUsername=galileo&apiPassword=123&accountCode=&merchantID=1G&sessionID=1234567890
	 */
	 
	public function LowFareSearch($param){
		
		$data_param = array(			
			'apiUsername' 		=> $this->apiUsername,
			'apiPassword' 		=> $this->apiPassword,
			'accountCode' 		=> $this->accountCode,
			'merchantID' 		=> $this->merchantID,
			'sessionID' 		=> $this->sessionID
		);
		
		$param = array_merge($param, $data_param);
		
		$url = $this->Production_Site . "/JSON/Merchant/LowFareSearch.aspx";
		$url .= "?".http_build_query($param);
		//echo '<blockquote style="word-break: break-all;">'.$url.'</blockquote>';
		
		$file_cache = $param['originCode'];
		$file_cache .= $param['destinationCode'];
		$file_cache .= $param['departDate'];
		$file_cache .= $param['routeType'];
		$file_cache .= ".json";		
		if(!file_exists($file_cache)){
			$json = file_get_contents($url);
			if($json === FALSE) {
				$error = error_get_last();
				echo "HTTP request failed. Error was: " . $error['message'];
			}else{
				file_put_contents($file_cache, $json);
			}
		}
		
		$json = file_get_contents($file_cache);
		return json_decode($json, true);
	}
	
	public function addParam(){
		$data_param = array(			
			'apiUsername' 		=> $this->apiUsername,
			'apiPassword' 		=> $this->apiPassword,
			'accountCode' 		=> $this->accountCode,
			'merchantID' 		=> $this->merchantID,
			'sessionID' 		=> $this->sessionID
		);
		
		return $data_param;
	}
	
	/*
	 * Fare Rule:
	 * http://localhost:20/JSON/Merchant/AirFareRule.aspx?request=getFareRule&flightKey=f0bf2bc6-c379-4c60-86cb-170f52a3285e&fareInfoKey=/1mtIKWWTsintEzUXZFIHg==&apiUsername=galileo&apiPassword=123&merchantID=1G&sessionID=1234567890
	 */
	public function FareRule($param){
		$data_param = $this->addParam();
		$param = array_merge($param, $data_param);
		
		$url = $this->Production_Site . "/JSON/Merchant/AirPrice.aspx";
		$url .= "?".http_build_query($param);
		//echo '<blockquote style="word-break: break-all;">'.$url.'</blockquote>';
		
		$json = $this->get_Curl($url);
		if($json){
			$json = json_decode($json, true);
			return $json;
		}
		
	}
	
	/*
	 * Air Price:
	 * http://localhost:20/JSON/Merchant/AirPrice.aspx?request=validatePrice&flightKey=da2a63cf-c48b-44bc-bc93-40feaa9494c1&apiUsername=galileo&apiPassword=123&merchantID=1G&sessionID=1234567890
	 */
	 
	public function AirPrice($param){
		
		$data_param = $this->addParam();
		$param = array_merge($param, $data_param);
		//var_dump ($param);
		
		$url = $this->Production_Site . "/JSON/Merchant/AirPrice.aspx";
		$url .= "?".http_build_query($param);
		//echo '<blockquote style="word-break: break-all;">'.$url.'</blockquote>';
		
		$file_cache = $param['flightKey'].".json";
		//print_r ($file_cache);
		
		if(!file_exists($file_cache)){
			$json = $this->get_Curl($url);
			if($json === FALSE) {
				$error = error_get_last();
				echo "HTTP request failed. Error was: " . $error['message'];
			}
			file_put_contents($file_cache, $json);
		}
		
		$json = @file_get_contents($file_cache);
		if($json=="success"){
			return $json;
		}else{
			return $json;
		}
	}
	
	/*
	 * Create Reservation:
	 *	http://localhost:20/JSON/Merchant/AirCreateReservation.aspx?request=airBooking&flightKey=f0bf2bc6-c379-4c60-86cb-170f52a3285e&apiUsername=galileo&apiPassword=123&merchantID=1G&&sessionID=1234567890&jsonPax=[{
		"PaxKey": "0d96a824-7a32-47eb-8f3d-4af52e7699c8",
		"Dob": "1991-02-06",
		"Age": "25",
		"Gender": "M",
		"TravelerType": "ADT",
		"FirstName": "jaka",
		"LastName": "sembung",
		"Prefix": "Mr",
		"PhoneCountryCode": "62",
		"PhoneAreaCode": "",
		"PhoneNumber": "812333323",
		"Email": "jakasembung@yahoo.com",
		"AddressName": "",
		"AddressStreet": "",
		"AddressCity": "",
		"AddressPostalCode": "",
		"AddressCountry": "",
		"PaxLeader": "true",
		"PassportNo": "",
		"PassportExpiry": null,
		"PassportIssueCountryID": "ID",
		"PassportSSR": null,
		"FrequentFlyerNo": "",
		"TravelerKey": null,
		"NationalityID": "ID",
		"MealPreference": "N",
		"SeatPreference": "Any"
	}]*/
	
	public function AirCreateReservation($param, $jsonPax){
		$data_param = $this->addParam();
		$param = array_merge($param, $data_param);
		//var_dump ($param);
		
		$url = $this->Production_Site . "/JSON/Merchant/AirCreateReservation.aspx";
		$url .= "?".http_build_query($param);
		$url .= "&jsonPax=".json_encode($jsonPax);
		echo '<blockquote style="word-break: break-all;">'.$url.'</blockquote>';
		
		$file_cache = "airBooking-".$param['flightKey'].".json";
		if(!file_exists($file_cache)){
			$json = $this->get_Curl($url);
			if($json === FALSE) {
				$error = error_get_last();
				echo "HTTP request failed. Error was: " . $error['message'];
			}
			file_put_contents($file_cache, $json);
		}
		
		$json = file_get_contents($file_cache);
		return $json;
	}
	
	public function Airport(){
		if(!file_exists("Airport.json")){
			$airport = file_get_contents("http://202.129.224.196:10000/GenerateAirportJson.aspx");
			file_put_contents("Airport.json", $airport);
		}
		
		$airportJson = file_get_contents("Airport.json");
		return $airportJson;
	}
	
	public function Airport_City($string){
		if(!file_exists("Airport.json")){
			$airport = file_get_contents("http://202.129.224.196:10000/GenerateAirportJson.aspx");
			file_put_contents("Airport.json", $airport);
		}
		
		$airportJson = file_get_contents("Airport.json");
		$airportJson = json_decode($airportJson, true);
		
		foreach($airportJson['City'] as $City){
			if($City['AirportCode'] == strtoupper($string)){
				$CityName = $City['CityName'] . " (".strtoupper($string).")";
			}
		}
		
		return $CityName;
	}
	
	public function toIDR($number){
		//if(!$number) return false;
		//if (is_string($number)) return $number;		
		return number_format($number,0,',','.');
	}
	
	/*
	 * Generate XXXXXXXX-XXXX-XXXX-XXXX-XXXXXXXXXXXX style unique id, (8-4-4-4-12 letters)
	 * http://phpgoogle.blogspot.co.id/2007/08/four-ways-to-generate-unique-id-by-php.html
	 */
	 
	public function uniqueID(){
		//$s = strtoupper(md5(uniqid(rand(),true)));
		$s = md5(bin2hex(openssl_random_pseudo_bytes(10)));
		$guidText = substr($s,0,8) . '-' . substr($s,8,4) . '-' . substr($s,12,4). '-' . substr($s,16,4). '-' . substr($s,20); 
		return $guidText;
	}

}