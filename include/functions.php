<?php if(!defined('BASEPATH')){ require_once("index.html");exit; }
    /**
     * @param array $array
     * @param boolean format, default true
     * @param boolean exit, default true
     * @return string
     * */
    function printr($array,$formate=true,$exit=true){
        if($formate===true){
            echo '<pre>';print_r($array);echo '</pre>';
        }
        else{
            print_r($array);
        }
        if($exit===true){
            exit;
        }
    }
    
    function time_elapsed_string($datetime, $full = false) {
        $now = new DateTime;
        $ago = new DateTime($datetime);
        $diff = $now->diff($ago);
        
        $diff->w = floor($diff->d / 7);
        $diff->d -= $diff->w * 7;
        
        $string = array(
            'y' => 'year',
            'm' => 'month',
            'w' => 'week',
            'd' => 'day',
            'h' => 'hour',
            'i' => 'minute',
            's' => 'second',
        );
        foreach ($string as $k => &$v) {
        if ($diff->$k) {
            $v = $diff->$k . ' ' . $v . ($diff->$k > 1 ? 's' : '');
        } else {
            unset($string[$k]);
        }
        }
        
        if (!$full) $string = array_slice($string, 0, 1);
        return $string ? implode(', ', $string) . ' ago' : 'just now';
    }

    function get_extension($file) {
        $extension = pathinfo($file, PATHINFO_EXTENSION);
        return $extension ? $extension : false;
    }
    
    function get_filename($file,$ext=true) {
        if($ext===true){
            $name = basename($file);
        }
        else{
            $name = basename($file,'.'.get_extension($file));
        }
        return $name ? $name : false;
    }
    
    function get_thumb($file,$size){
        $image = get_filename($file,false).'-'.$size.'.'.get_extension($file);
        return $image;
    }
    
    /**
     * @param string session type
     * @param boolean redirect, default true
     * @return json
     * */
    function is_login($type,$redirect=true){
        if($type=='user'){
            if(isset($_SESSION['user_id'])&&$_SESSION['user_id']>0){
                $response['status'] = 1;
                $response['id'] = $_SESSION['user_id'];
                $response['message'] = 'User is logged in.';
            }
            else{
                $response['status'] = 0;
                $response['id'] = 0;
                $response['message'] = 'User is not logged in.';
            }
        }
        else if($type=='admin'){
            if(isset($_SESSION['admin_id'])&&$_SESSION['admin_id']>0){
                $response['status'] = 1;
                $response['id'] = $_SESSION['admin_id'];
                $response['message'] = 'Admin is logged in.';
            }
            else{
                $response['status'] = 0;
                $response['id'] = 0;
                $response['message'] = 'Admin is not logged in.';
            }
        }
        else{
            $response['status'] = 0;
            $response['id'] = 0;
            $response['message'] = 'Something went wrong.';
        }
        if($response['status']==0 && $redirect===true){
            if($type=='user'){
                header("location:".SITE_URL."sign-in.php");exit;
            }
            else if($type=='admin'){
                header("location:".ADMIN_URL."sign-in.php");exit;
            }
        }
        return json_encode($response);
    }
    
    /**
     * @param array exclude array
     * @param string extra, add to parameter
     * @return string
     * */
    function get_params($exclude_array = '', $extra=''){
    	if(!is_array($exclude_array)) $exclude_array = array();
    	$get_url = '';
    	if (is_array($_GET) && (sizeof($_GET) > 0)){
    		reset($_GET);
    	  	while (list($key, $value) = each($_GET)){
                if ( !is_array($value) && (strlen($value) > 0) && ($key != $this->re_session_name()) && ($key != 'error') && (!in_array($key, $exclude_array)) && ($key != 'x') && ($key != 'y') ){
    		   		$get_url .=  '&' .$key . '=' . rawurlencode(stripslashes($value));
    			}
    	  	}
    	}
    	return ($extra?substr($get_url,1):$get_url);
    }
    
    /**
     * @param string str
     * @param int maxlenth, default 150
     * @return string
     * */
    function shortstr($str,$maxlen=150){
    	if(strlen($str)>$maxlen){
    		$str=substr($str,0,$maxlen);
    		$str.="...";
    	}
    	return $str;
    }
    
    /**
     * @param string date
     * @return boolean
     * */
    function is_date($str){
    	$stamp = strtotime( $str );
    	if (!is_numeric($stamp)){
    		return FALSE;
    	}
    	//$month = date( 'm', $stamp ); $day   = date( 'd', $stamp ); $year  = date( 'Y', $stamp );
        $date_arr = explode('/', $this->output_date($str));    //output_date public function return date in dd/mm/yyyy formate
        $day = isset($date_arr[0]) ? $date_arr[0] : "";
        $month = isset($date_arr[1]) ? $date_arr[1] : "";
        $year = isset($date_arr[2]) ? $date_arr[2] : "";
    	
    	if($day!="" && $month!="" && $year!="" && checkdate($month, $day, $year)){
    	 	return TRUE;
    	}
    	else{
            return FALSE;
    	}
    }
    
    /**
     * @param string email
     * @return boolean
     * */
    function is_email($email){
    	return filter_var($email,FILTER_VALIDATE_EMAIL);
    }
    
    /**
     * @param string username
     * @return boolean
     * */
    function is_username($username){
        if(strlen(trim($username))>=5 && strlen(trim($username))<=30 && preg_match('/^[a-z]+([a-z0-9._]*)?[a-z0-9]+$/i', $username)){
            return true;
        }
        else{
            return false;
        }
    }
    
    /**
     * @param string url
     * @return boolean
     * */
    function is_url($url){
        if (!preg_match("/\b(?:(?:https?|ftp):\/\/|www\.)[-a-z0-9+&@#\/%?=~_|!:,.;]*[-a-z0-9+&@#\/%=~_|]/i",$url)){
          return false;
        }
        else{
            return true;
        }
    }
    
    /**
     * @param string mobile no
     * @return bool
     * */
    function is_phone_number($phoneNumber){
        //Check to make sure the phone number format is valid 
        if( !preg_match("/^([1]-)?[0-9]{3}-[0-9]{3}-[0-9]{4}$/i", $phoneNumber)){
    		return false; 
    	}
    	else{
    		return true; 
    	}
    }
    
    /**
     * @param int length, default 6
     * @return boolean
     * */
    function random_password_generate($length=6){
        $possible_letters = '23456789bcdfghjkmnpqrstvwxyz';
        $code="";
        for($i=0; $i<=$length; $i++){ 
            $code .= substr($possible_letters, mt_rand(0, strlen($possible_letters)-1), 1);
        }
        return $code;
    }
    
    /**
     * @param int length, defaullt 6
     * @return boolean
     * */
    function random_otp_generate($length=6){
        $possible_letters = '1234567890';
        $code="";
        for($i=0; $i<=$length; $i++){
            $code .= substr($possible_letters, mt_rand(0, strlen($possible_letters)-1), 1);
        }
        return $code;
    }
    
    /**
     * @param string url
     * @return boolean
     * */
    function is_url_exist($url){
        $ch = curl_init($url);    
        curl_setopt($ch, CURLOPT_NOBODY, true);
        curl_exec($ch);
        $code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        if($code == 200){
            $status = true;
        } else{
            $status = false;
        }
        curl_close($ch);
        return $status;
    }
    
    /**
     * @param string date
     * @return date
     * */
    function format_date($date){
    	if($date!='' && $date!='00-00-0000'){
           return date('Y-m-d',strtotime($date));
    	}
        else{
            return '0000-00-00';
        }
    }
    
    /**
     * @param string datetime
     * @param int format, default 24
     * @return datetime
     * */
    function format_datetime($date,$format=24){
    	if($date!='' && $date!='00-00-0000 00:00:00'){
            if($format==24){
                return date('Y-m-d H:i:s',strtotime($date));
            }
            else{
                return date('Y-m-d h:i:s A',strtotime($date));
            }
    	}
        else{
            return '0000-00-00 00:00:00';
        }
    }
    
    /**
     * @param string datetime
     * @param int format, default 24
     * @return string datetime
     * */
    function display_datetime($date,$format=24){
    	if($date!='' && $date!='0000-00-00 00:00'){
            if($format=='24'){
                return date('d-m-Y H:i:s',strtotime($date));
            }
            else{
                return date('d-m-Y h:i:s A',strtotime($date));
            }
    	}
        else{
            return '00-00-0000 00:00';
        }
    }
    /**
     * @param string date
     * @return string date
     * */
    function display_date($date){
    	if($date!='' && $date!='0000-00-00'){
           return date('d-m-Y',strtotime($date));
    	}
        else{
            return '00-00-0000';
        }
    }
    
    /**
     * @param array element
     * @param int parentid, default 0
     * @param string primary column, default id
     * @param string parent column, default parent_id
     * @return array
     * */
    function buildTree(array $elements, $parentId = 0, $id='id', $parent_key='parent_id'){
        $branch = array();
        foreach ($elements as $element) {
            if ($element[$parent_key] == $parentId) {
                $children = buildTree($elements, $element[$id]);
                if ($children) {
                    $element['children'] = $children;
                }
                $branch[$element[$id]] = $element;
            }
        }
        return $branch;
    }
    
    /**
     * @param string message
     * @return string
     * */
    function error_msg($msg){
        ?>
        <div class="alert alert-danger fade in">
            <button class="close" data-dismiss="alert"><i class="pci-cross pci-circle"></i></button>
            <strong><i class="fa fa-warning"></i></strong>
            <?php
                if(is_array($msg)){
                    foreach($msg as $key=>$val){
                        echo $val.'<br/>';
                    }
                }
                else{
                    echo $msg;
                }
            ?>
        </div>
        <?php
    }
    
    /**
     * @param string message
     * @return string
     * */
    function warning_msg($msg){
        ?>
        <div class="alert alert-warning fade in">
            <button class="close" data-dismiss="alert"><i class="pci-cross pci-circle"></i></button>
            <strong><i class="fa fa-thumbs-down"></i></strong>
            <?php
                if(is_array($msg)){
                    foreach($msg as $key=>$val){
                        echo $val.'<br/>';
                    }
                }
                else{
                    echo $msg;
                }
            ?>
        </div>
        <?php
    }
    
    /**
     * @param string message
     * @return string
     * */
    function info_msg($msg){
        ?>
        <div class="alert alert-info fade in">
            <button class="close" data-dismiss="alert"><i class="pci-cross pci-circle"></i></button>
            <strong><i class="fa fa-volume-up"></i></strong>
            <?php
                if(is_array($msg)){
                    foreach($msg as $key=>$val){
                        echo $val.'<br/>';
                    }
                }
                else{
                    echo $msg;
                }
            ?>
        </div>
        <?php
    }
    
    /**
     * @param string message
     * @return string
     * */
    function success_msg($msg){
        ?>
        <div class="alert alert-success fade in">
            <button class="close" data-dismiss="alert"><i class="pci-cross pci-circle"></i></button>
            <strong><i class="ion-android-done-all"></i></strong>
            <?php
                if(is_array($msg)){
                    foreach($msg as $key=>$val){
                        echo $val.'<br/>';
                    }
                }
                else{
                    echo $msg;
                }
            ?>
        </div>
        <?php
    }

    /**
     * @param int number
     * @param int decimal, default 2
     * @return float
     * */
    function to_decimal($number,$decimal=2){
        return number_format((float)$number, $decimal, '.', ',');
    }
    
    /**
     * @param array
     * @parram string rearray column name
     * @return string
     * */
    function reArray($array,$param) {
        $file_ary = array();
        $file_count = count($array[$param]);
        $file_keys = array_keys($array);
        for ($i=0; $i<$file_count; $i++) {
            foreach ($file_keys as $key) {
                $file_ary[$i][$key] = $array[$key][$i];
            }
        }
        return $file_ary;
    }
    
    /**
     * @param array to
     * @param string subject
     * @param string message body
     * @param array cc
     * @param array bcc
     * @param array attachment
     * @return boolean
     * */
    function send_email($to,$subject,$body,$cc=array(),$bcc=array(),$attachemnt=array()){
        // Configuring SMTP server settings
        $mail = new PHPMailer;
	    $mail->isSMTP(true);
	    $mail->Host = SMTP_HOST;
	    $mail->Port = 587; // 465 587
	    $mail->SMTPSecure = 'tls'; //tls ssl
	    $mail->SMTPAuth = true;
	    $mail->setFrom(SMTP_ID,'real_estate');
	    $mail->Username = SMTP_ID;
	    $mail->Password = SMTP_PASSWORD;
        
        foreach($to as $key=>$val){
            $mail->addAddress($val);
        }
        foreach($cc as $key=>$val){
            $mail->AddCC($val);
        }
        foreach($bcc as $key=>$val){
            $mail->AddBCC($val);
        }
        foreach($attachemnt as $key=>$val){
            $mail->AddAttachment($val,'Attachment');
        }
        //$mail->AddBCC('scspl.maisuri@gmail.com');
        $mail->Subject = $subject;
        $mail->isHTML(true);    
        $mail->msgHTML($body);
        
        // Success or Failure
        if(! @ $mail->send()){
            return true;
        }
        else {
            return false;
        }
    }
    
    function count_digit($number) {
  return strlen($number);
}

function convertNumber($number)
{
    @ list($integer, $fraction) = explode(".", (string) $number);

    $output = "";

    if ($integer{0} == "-")
    {
        $output = "negative ";
        $integer    = ltrim($integer, "-");
    }
    else if ($integer{0} == "+")
    {
        $output = "positive ";
        $integer    = ltrim($integer, "+");
    }

    if ($integer{0} == "0")
    {
        $output .= "zero";
    }
    else
    {
        $integer = str_pad($integer, 36, "0", STR_PAD_LEFT);
        $group   = rtrim(chunk_split($integer, 3, " "), " ");
        $groups  = explode(" ", $group);

        $groups2 = array();
        foreach ($groups as $g)
        {
            $groups2[] = convertThreeDigit($g{0}, $g{1}, $g{2});
        }

        for ($z = 0; $z < count($groups2); $z++)
        {
            if ($groups2[$z] != "")
            {
                $output .= $groups2[$z] . convertGroup(11 - $z) . (
                        $z < 11
                        && !array_search('', array_slice($groups2, $z + 1, -1))
                        && $groups2[11] != ''
                        && $groups[11]{0} == '0'
                            ? " and "
                            : ", "
                    );
            }
        }

        $output = rtrim($output, ", ");
    }

    if ($fraction > 0)
    {
        $output .= " point";
        for ($i = 0; $i < strlen($fraction); $i++)
        {
            $output .= " " . convertDigit($fraction{$i});
        }
    }

    return $output;
}

function convertGroup($index)
{
    switch ($index)
    {
        case 11:
            return " decillion";
        case 10:
            return " nonillion";
        case 9:
            return " octillion";
        case 8:
            return " septillion";
        case 7:
            return " sextillion";
        case 6:
            return " quintrillion";
        case 5:
            return " quadrillion";
        case 4:
            return " trillion";
        case 3:
            return " billion";
        case 2:
            return " million";
        case 1:
            return " thousand";
        case 0:
            return "";
    }
}

function convertThreeDigit($digit1, $digit2, $digit3)
{
    $buffer = "";

    if ($digit1 == "0" && $digit2 == "0" && $digit3 == "0")
    {
        return "";
    }

    if ($digit1 != "0")
    {
        $buffer .= convertDigit($digit1) . " hundred";
        if ($digit2 != "0" || $digit3 != "0")
        {
            $buffer .= " and ";
        }
    }

    if ($digit2 != "0")
    {
        $buffer .= convertTwoDigit($digit2, $digit3);
    }
    else if ($digit3 != "0")
    {
        $buffer .= convertDigit($digit3);
    }

    return $buffer;
}

function convertTwoDigit($digit1, $digit2)
{
    if ($digit2 == "0")
    {
        switch ($digit1)
        {
            case "1":
                return "ten";
            case "2":
                return "twenty";
            case "3":
                return "thirty";
            case "4":
                return "forty";
            case "5":
                return "fifty";
            case "6":
                return "sixty";
            case "7":
                return "seventy";
            case "8":
                return "eighty";
            case "9":
                return "ninety";
        }
    } else if ($digit1 == "1")
    {
        switch ($digit2)
        {
            case "1":
                return "eleven";
            case "2":
                return "twelve";
            case "3":
                return "thirteen";
            case "4":
                return "fourteen";
            case "5":
                return "fifteen";
            case "6":
                return "sixteen";
            case "7":
                return "seventeen";
            case "8":
                return "eighteen";
            case "9":
                return "nineteen";
        }
    } else
    {
        $temp = convertDigit($digit2);
        switch ($digit1)
        {
            case "2":
                return "twenty-$temp";
            case "3":
                return "thirty-$temp";
            case "4":
                return "forty-$temp";
            case "5":
                return "fifty-$temp";
            case "6":
                return "sixty-$temp";
            case "7":
                return "seventy-$temp";
            case "8":
                return "eighty-$temp";
            case "9":
                return "ninety-$temp";
        }
    }
}

function convertDigit($digit)
{
    switch ($digit)
    {
        case "0":
            return "zero";
        case "1":
            return "one";
        case "2":
            return "two";
        case "3":
            return "three";
        case "4":
            return "four";
        case "5":
            return "five";
        case "6":
            return "six";
        case "7":
            return "seven";
        case "8":
            return "eight";
        case "9":
            return "nine";
    }
}

function divider($number_of_digits) {
    $tens="1";

  if($number_of_digits>8)
    return 10000000;

  while(($number_of_digits-1)>0)
  {
    $tens.="0";
    $number_of_digits--;
  }
  return $tens;
}

    function number_to_word($number){
        
        $f = new \NumberFormatter("en", NumberFormatter::SPELLOUT);
echo $f->format(1432);exit;
        
        //return $number;
        $num = round($number);
        
        $ext="";//thousand,lac, crore
$number_of_digits = count_digit($num); //this is call :)
    if($number_of_digits>3)
{
    if($number_of_digits%2!=0)
        $divider=divider($number_of_digits-1);
    else
        $divider=divider($number_of_digits);
}
else
    $divider=1;
/*
$fraction=$num/$divider;
$fraction=number_format($fraction,2);
if($number_of_digits==4 ||$number_of_digits==5)
    $ext="k";
if($number_of_digits==6 ||$number_of_digits==7)
    $ext="Lac";
if($number_of_digits==8 ||$number_of_digits==9)
    $ext="Cr";
return $fraction." ".$ext;
        */
        $no = round($number);
       $point = round($number - $no) * 100;
       $hundred = null;
       $digits_1 = strlen($no);
       $i = 0;
       $str = array();
       $words = array('0' => '', '1' => 'one', '2' => 'two',
        '3' => 'three', '4' => 'four', '5' => 'five', '6' => 'six',
        '7' => 'seven', '8' => 'eight', '9' => 'nine',
        '10' => 'ten', '11' => 'eleven', '12' => 'twelve',
        '13' => 'thirteen', '14' => 'fourteen',
        '15' => 'fifteen', '16' => 'sixteen', '17' => 'seventeen',
        '18' => 'eighteen', '19' =>'nineteen', '20' => 'twenty',
        '30' => 'thirty', '40' => 'forty', '50' => 'fifty',
        '60' => 'sixty', '70' => 'seventy',
        '80' => 'eighty', '90' => 'ninety');
       $digits = array('', 'hundred', 'thousand', 'lakh', 'crore');
       while ($i < $digits_1) {
         $divider = ($i == 2) ? 10 : 100;
         $number = floor($no % $divider);
         $no = floor($no / $divider);
         $i += ($divider == 10) ? 1 : 2;
         if ($number) {
            $plural = (($counter = count($str)) && $number > 9) ? 's' : null;
            $hundred = ($counter == 1 && $str[0]) ? ' and ' : null;
            $str [] = ($number < 21) ? $words[$number] .
                " " . $digits[$counter] . $plural . " " . $hundred
                :
                $words[floor($number / 10) * 10]
                . " " . $words[$number % 10] . " "
                . $digits[$counter] . $plural . " " . $hundred;
         } else $str[] = null;
      }
      $str = array_reverse($str);
      $result = implode('', $str);
      $points = ($point) ?
        "And " . $words[$point / 10] . " " . 
              $words[$point = $point % 10]." Paise" : '';
      return $result . "Rupees  " . $points . "";
    }
?>