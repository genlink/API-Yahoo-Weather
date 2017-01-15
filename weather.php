<?php 
error_reporting(1);	
date_default_timezone_set('Asia/Ho_Chi_Minh');
$tinh = "Hà Nội";
$tinh = $_GET['tinh'];
class weatherfc{
	public $result;
	function weather($city){
		$city2 = $this->get_ascii($city);
		$BASE_URL = "http://query.yahooapis.com/v1/public/yql";
		$yql_query = 'select * from weather.forecast where woeid in (select woeid from geo.places(1) where text="'.$city2.'") and u="c"';
		$yql_query_url = $BASE_URL . "?q=" . urlencode($yql_query) . "&format=json";
		$session = curl_init($yql_query_url);
		curl_setopt($session, CURLOPT_RETURNTRANSFER,true);
		$json = curl_exec($session);
		$phpObj =  json_decode($json);
		//var_dump($phpObj);
		$weatherd='{ "text": "Thời tiết tại  '.$city.'"},';
		$fcast=$phpObj->query->results->channel->item->forecast;
		$t = count($fcast);
		for($i=0; $i <5 ; $i++)
		{
			$fdate = strtotime($fcast[$i]->date);
			$weekday = $fcast[$i]->day;
			switch($weekday) {
				case 'Mon':
					$weekday = 'Thứ hai';
					break;
				case 'Tue':
					$weekday = 'Thứ ba';
					break;
				case 'Wed':
					$weekday = 'Thứ tư';
					break;
				case 'Thu':
					$weekday = 'Thứ năm';
					break;
				case 'Fri':
					$weekday = 'Thứ sáu';
					break;
				case 'Sat':
					$weekday = 'Thứ bảy';
					break;
				default:
					$weekday = 'Chủ nhật';
					break;
			}
			$weatherd.= '{"text":"'.$weekday.' '.date('d/m/Y',$fdate).'\n'.$fcast[$i]->text.'\n';
			$weatherd.= ' Nhiệt độ: '.$fcast[$i]->low.' - '.$fcast[$i]->high.' độ C "},';
			
		}
		$this->result=$weatherd;
	}
	function get_ascii($str) {
		$chars = array(
			'a'	=>	array('ấ','ầ','ẩ','ẫ','ậ','Ấ','Ầ','Ẩ','Ẫ','Ậ','ắ','ằ','ẳ','ẵ','ặ','Ắ','Ằ','Ẳ','Ẵ','Ặ','á','à','ả','ã','ạ','â','ă','Á','À','Ả','Ã','Ạ','Â','Ă'),
			'e' =>	array('ế','ề','ể','ễ','ệ','Ế','Ề','Ể','Ễ','Ệ','é','è','ẻ','ẽ','ẹ','ê','É','È','Ẻ','Ẽ','Ẹ','Ê'),
			'i'	=>	array('í','ì','ỉ','ĩ','ị','Í','Ì','Ỉ','Ĩ','Ị'),
			'o'	=>	array('ố','ồ','ổ','ỗ','ộ','Ố','Ồ','Ổ','Ô','Ộ','ớ','ờ','ở','ỡ','ợ','Ớ','Ờ','Ở','Ỡ','Ợ','ó','ò','ỏ','õ','ọ','ô','ơ','Ó','Ò','Ỏ','Õ','Ọ','Ô','Ơ'),
			'u'	=>	array('ứ','ừ','ử','ữ','ự','Ứ','Ừ','Ử','Ữ','Ự','ú','ù','ủ','ũ','ụ','ư','Ú','Ù','Ủ','Ũ','Ụ','Ư'),
			'y'	=>	array('ý','ỳ','ỷ','ỹ','ỵ','Ý','Ỳ','Ỷ','Ỹ','Ỵ'),
			'd'	=>	array('đ','Đ'),
			'b'	=>	array('B'),	
			'u'	=>	array('U'),
			'n'	=>	array('N'),	
		);
		foreach ($chars as $key => $arr) 
			foreach ($arr as $val)
				$str = str_replace($val,$key,$str);
		return $str;
	}

}
if(isset($tinh))
{

	$h= new weatherfc;
	$h->weather($tinh);
	$d =  '{"messages": ['.$h->result.']}';
	$d = str_replace(",]}","]}",$d);
	$d = str_replace("Partly Cloudy","Trời có mây nhẹ",$d);
	$d = str_replace("Mostly Cloudy","Trời nhiều mây",$d);
	$d = str_replace("Cloudy","Mây rải rác vài nơi",$d);
	$d = str_replace("Scattered Thunderstorms","Mưa giông rải rác vài nơi",$d);
	$d = str_replace("Scattered Showers","Mưa rào nhẹ rải rác",$d);
	$d = str_replace("Showers","Mây rải rác vài nơi",$d);
	$d = str_replace("Rain","Mưa rào",$d);
	$d = str_replace("Mostly sunny","Trời nắng",$d);
	$d = str_replace("Sunny","Trời nắng to",$d);
	$d = str_replace("Breezy","Gió nhẹ",$d);
	$d = str_replace("Thunderstorms","Mưa giông",$d);
	//$d = str_replace("Cloudy","Mây rải rác vài nơi",$d);
	echo $d;
}
	

?>


