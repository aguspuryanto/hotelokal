<?php
/*
Hi Teman-teman,
Karena banyak sekali permintaan teman-teman untuk mendapatkan API dari pegipegi maka kita putusin 
untuk bantuin semua teman-teman disini dengan memberikan akses API staging/development server kita. 
Untuk prosesnya sendiri kurang lebih seperti ini;

1. Affiliate configure web/apps menggunakan API kita dengan akses staging/development
2. Affiliate mengirimkan notifikasi ke affiliate@pegipegi.com untuk review atau bisa ke adi.rian@pegipegi.com
3. Tim pegipegi review bila lolos maka akan diberikan auth key production
4. Affiliate configure API request untuk production

Untuk auth key staging/development ialah sebagai berikut: peg11187712b79
Auth key untuk production bisa didapatkan setelah proses review berhasil
Contoh Request API development bisa lihat dibawah dan setelah review selesai maka yang harus 
diganti ialah menghilangkan dv00 dalam request dan mengganti auth key untuk akses production server.

Get Area List
http://jws.pegipegi.com/rs/rsp0200/Rst0201Action.do?key=peg11187712b79

Get Search Result with Price
http://jws.pegipegi.com/rs/rsp0100/Rst0101Action.do?key=peg11187712b79&pref=JB&l_area=JB0100&s_area=JB0113&h_type=0&start=1&count=10&xml_ptn=2

Get Hotel Plan Price
http://jws.pegipegi.com/rs/rsp0100/Rst0103Action.do?key=peg11187712b79&h_id=900048

http://www.pegipegi.com/hotel/bali/?stayYear=2016&stayMonth=1&stayDay=29&stayCount=1&roomCrack=100000
*/

class pegipegiHotel {
	
	private $key = 'peg11187712b79';
	private $affid = 'AFF11295';
	
	public function affid(){
		return $this->affid;
	}
	
	// Depresed at Server iPage
	public function Get_Data($url){
		$result = @simplexml_load_file($url);
		if (!$result) {
			echo "<pre>Uh oh's, we have an error!</pre>";
		} else {
			return $result;
		}
	}
	
	public function Get_Data2($url){
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
		$request = curl_exec($ch);

		@$xml = simplexml_load_string(utf8_decode($request));
		return $xml;
	}
	
	public function Get_Area(){
		$url = 'http://jws.pegipegi.com/rs/rsp0200/Rst0201Action.do?key='.$this->key;
		return $this->Get_Data2($url);
	}
	
	public function Get_RedirectUri($url){
		if($url) {
			$url .= '?affid='.$this->affid;
		}		
		return ($url);
	}
	
	public function Get_Redirect($hotelid, $room){		
		$args = array(
			'stayDay' => date('d', strtotime($room['check_in'])),
			'stayCount' => 1,
			'yadNo' => $hotelid,
			'roomTypeCd' => $room['RoomCD'],
			'STATUS' => 0,
			'stayMonth' => date('n', strtotime($room['check_in'])),
			'roomCount' => 1,
			'planCd' => $room['PlanCD'],
			'stayYear' => date('Y', strtotime($room['check_in'])),
			'roomCrack' => 100000,
			'TEMP1' => 'LEVEL_R'
		);
		
		$url = "https://www.pegipegi.com/uo/uop5200/uow5207.do?affid=".$this->affid;
		if($args){
			$url .= "&".http_build_query($args);
		}
		return ($url);
	}
	
	public function Get_HotelDetail($url){
		$url = preg_replace('%(https?://)?(www\.)?pegipegi\.com/hotel?%im', '', $url);
		$url = rtrim($url,"/");
		return ($url);
	}
	
	public function Get_HotelID($id){
		$url = 'http://jws.pegipegi.com/rs/rsp0100/Rst0103Action.do?key='.$this->key;
		$url .= '&'.http_build_query($id);
		
		return $this->Get_Data2($url);
	}
	
	public function Get_Search($params){
		$url = 'http://jws.pegipegi.com/rs/rsp0100/Rst0101Action.do?key='.$this->key;
		$url .= '&'.http_build_query($params);
		$url .= '&h_type=0&count=10&xml_ptn=2';
		
		//echo $url;
		return $this->Get_Data2($url);
	}
}

/*$xml = new pegipegiHotel;
$xml = $xml->Get_Data('http://jws.pegipegi.com/rs/rsp0200/Rst0201Action.do?key=peg11187712b79');
if($xml):
	foreach($xml->Area as $area){
		echo $area->Region['name'].'<br>';
	}
endif;*/