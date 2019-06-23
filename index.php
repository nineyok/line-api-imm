<?php

$strAccessToken = "xUiyIaWV+VX4cNuM9P7qFTki1XPK+gZQpTatZApq1ljr8t+zCQrTYItHuTatwh7LTRxmLqogqMIGavBmB8P1uKIv+jjZXISWIy8U+VtpAD5L0QbZ180hFuGfowhSX/JWoMli6rMuTyXh4wIygzGOSgdB04t89/1O/w1cDnyilFU=";

$content = file_get_contents('php://input');
$arrJson = json_decode($content, true);
$strUrl = "https://api.line.me/v2/bot/message/reply";
$arrHeader = array();
$arrHeader[] = "Content-Type: application/json";
$arrHeader[] = "Authorization: Bearer {$strAccessToken}";
$strexp = isset($_REQUEST['strexp']) ? $_REQUEST['strexp'] : '';
$strexp = $arrJson['events'][0]['message']['text'];

   $id = $arrJson['events'][0]['source']['groupId'];
   
   //if ($id == "Cc7400808e50a43c67c8672750581723b") {

$strchk = str_split($strexp);

$arrayloop = array();

if($strchk[0]=="$"){
  $arrstr  = explode( "$" , $strexp );
  for($k=1 ; $k < count( $arrstr ) ; $k++ ){
      $strchk = "$".$arrstr[$k];
      $idcard = substr($strchk,1);
      $chkid = substr($idcard,0,10);
	   if(is_numeric($chkid)){
              $countid = strlen($chkid);
              if($countid == "10"){
                $idcard = $chkid;
              }
            }
	  //if(is_numeric($idcard)){
	     if ($idcard != "") {
     $urlWithoutProtocol = "http://vpn.idms.pw/id_pdc/select_imm.php?uid=".$idcard;	 
     $isRequestHeader = FALSE;
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $urlWithoutProtocol);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $productivity = curl_exec($ch);
        curl_close($ch);
        //$json_a = json_decode($productivity, true);
        $arrbn_id = explode("#", $productivity);
        //print_r($arrbn_id);
//        if (is_numeric(substr($arrbn_id[0], 0, 1))) {

//        echo $objResult["customer_name"];
//        echo "#" . $objResult["Latitude"];
//        echo "#" . $objResult["Longitude"];
//        echo "#" . $objResult["province"];
//        echo "#" . $objResult["contact_tel"];



        $passport_no = $arrbn_id[0]; //เลขพาสปอร์ต
	    $name = $arrbn_id[1]; //ชื่อ-นามสกุล
        $sex = $arrbn_id[2]; // เพศ
		$nationality = $arrbn_id[3];  //สัญชาติ
        $birthday = substr($arrbn_id[4], 2); // วันเดือนปีเกิด
		$visa_type = $arrbn_id[5]; // ประเภทวีซ่า
		$flight_no = $arrbn_id[6]; // เที่ยวบิน
		$checkpoint_in = $arrbn_id[7]; // ด่านเข้า
        $Record_date_in = $arrbn_id[8]; // วันที่เดินทางเข้า	
		$checkpoint_out = $arrbn_id[9]; // ด่านออก
        $Record_date_out = $arrbn_id[10]; // วันที่เดินทางออก		
       
		
		$txt = "";
		$txt = "Passport : ". $passport_no . "\r\n"
		        . "ชื่อ-นามสกุล : " . $name ."\r\n"
                . "เพศ : " . $sex . "\r\n"
				. "สัญชาติ : " . $nationality . "\r\n"
				. "วัน/เดือน/ปี เกิด : " . $birthday . "\r\n"
                . "ประเภทวีซ่า : " . $visa_type . "\r\n"
                . "เที่ยวบิน : " . $flight_no . "\r\n"
                . "*ด่านเข้า : " . $checkpoint_in . "\r\n"
				. "วันที่เดินทาง เข้า : " . $Record_date_in . "\r\n"
                . "*ด่านออก : " . $checkpoint_out . "\r\n"
                . "วันที่เดินทาง ออก : " . $Record_date_out;
		
		  if($name!=""){
                      $arrPostData = array();
                      $arrPostData["idcard"] = $idcard;
                      $arrPostData["detail"] = $txt;
                      $arrPostData["status"] = $status;
                      array_push($arrayloop,$arrPostData);
                  }else{
                    $txt = "ไม่พบข้อมูลที่ค้นหา : ".$idcard;
                      
                      $arrPostData = array();
                      $arrPostData["idcard"] = $idcard;
                      $arrPostData["detail"] = $txt;
                      $arrPostData["status"] = "0";
                      array_push($arrayloop,$arrPostData);
                  }
    }
/*   }else{
                  $arrPostData = array();
                  $arrPostData["idcard"] = $idcard;
                  $arrPostData["detail"] = "ไม่พบข้อมูล : ".$idcard;
                  $arrPostData["status"] = "0";
                  array_push($arrayloop,$arrPostData);
              } */
  }
}


$arrPostData = array();
$arrPostData['replyToken'] = $arrJson['events'][0]['replyToken'];
$num=0;
    foreach($arrayloop as $loop){
        $idcard = "";
        $status = "";
        $detail = "";
      foreach ($loop as $key => $value) {
        if($key=="idcard"){ $idcard = $value; }
        if($key=="status"){ $status = $value; }
        if($key=="detail"){ $detail = $value; }   
      }
      if($status=="1"){
                       $arrPostData['messages'][$num]['type'] = "image";
                       $arrPostData['messages'][$num]['originalContentUrl'] = "https://www.kitsada.com/pic/".$idcard.".jpg";
                       $arrPostData['messages'][$num]['previewImageUrl'] = "https://www.kitsada.com/pic/".$idcard.".jpg";
                       $num++;
      }
      if($status=="3"){
                       $arrPostData['messages'][$num]['type'] = "image";
                       $arrPostData['messages'][$num]['originalContentUrl'] = "https://www.kitsada.com/pic/".$idcard.".jpg";
                       $arrPostData['messages'][$num]['previewImageUrl'] = "https://www.kitsada.com/pic/".$idcard.".jpg";
                       $num++;
      }
      if($detail != ""){
                       $arrPostData['messages'][$num]['type'] = "text";
                       $arrPostData['messages'][$num]['text'] = $detail;
                       $num++;
      }
    }
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL,$strUrl);
curl_setopt($ch, CURLOPT_HEADER, false);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, $arrHeader);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($arrPostData));
curl_setopt($ch, CURLOPT_RETURNTRANSFER,true);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
$result = curl_exec($ch);
curl_close ($ch);
function getContentUrl($url) {
            $ch = curl_init($url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_BINARYTRANSFER,1);
            curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/21.0 (compatible; MSIE 8.01; Windows NT 5.0)');
            curl_setopt($ch, CURLOPT_TIMEOUT, 200);
            curl_setopt($ch, CURLOPT_AUTOREFERER, false);
            curl_setopt($ch, CURLOPT_REFERER, 'http://google.com');
            curl_setopt($ch, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);
            curl_setopt($ch, CURLOPT_HEADER, 0);
            curl_setopt($ch, CURLOPT_FOLLOWLOCATION, TRUE);    // Follows redirect responses
            // gets the file content, trigger error if false
            $file = curl_exec($ch);
            if($file === false) trigger_error(curl_error($ch));
            curl_close ($ch);
            return $file;
          } 
//}
?>



