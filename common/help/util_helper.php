<?php
// namespace common\help;
// if(!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * is_chinese
 *
 * 判断字符串是否中文
 *
 * 转换形如'2010-10-11 10:10:10'的时间格式为形如'N分钟/小时/天前'的字符串
 * 正常英文单词的编码范围在0x00-0x7F之间，超出该范围的特殊符号，我们都将其认作中文
 *
 * @access	public
 * @param	string	$str
 * @return	bool
 */
if(!function_exists('is_chinese')){
    function is_chinese($str) {
        $test = preg_replace('/\w+/', '', $str);
        if (strlen($test) % 2 == 0 && preg_match('/^(\xA8[\xA1-\xC0])+$/', $test)) {//支持ā á ǎ à ē é ě è ī í ǐ ì ō ó ǒ ò ū ú ǔ ù ǖ ǘ ǚ ǜ ü ê ɑ ń ň ɡ
            return FALSE;
        } else {
            return preg_match('/[\x80-\xFF]/', $str) > 0;
        }
    }
}


if(!function_exists('time_to_dhs')){
    function time_to_dhs($str) {
        if (!is_numeric($str)) {
            return '';
        }
        $day = '';
        if ($str > 24 * 60) {
            $day = intval($str/(24 * 60));
            $str -= $day * 24 * 60;
            $day = $day . "天";
        }
        $hour = '';
        if ($str > 60) {
            $hour = intval($str/(60));
            $str -= $hour * 60;
            $hour = $hour . "小时";
        }
        $second = '';
        if ($str > 0) {
            $second = intval($str);
            $second = $second . "分钟";
        }
        return $day . $hour . $second;
    }
}

/**
 * 使用给定的时间戳获取天时分
 */
if(!function_exists('time_date_dhs')){
    function time_date_dhs($str) {
        if (!is_numeric($str)) {
            return '';
        }
        $day = '';
        if ($str > 24 * 60 * 60) {
            $day = intval($str/(24 * 60 *60));
            $str -= $day * 24 * 60 *60;
            $day = $day . "天";
        }

        $hour = '';
        if ($str > 60 * 60) {
            $hour = intval($str/(60 * 60));
            $str -= $hour * 60 * 60;
            $hour = $hour . "小时";
        }

        $second = '';
        if ($str > 60) {
            $second = intval($str/(60));
            $second = $second . "分钟";
        }
        return $day . $hour . $second;
    }
}



/**
*数字金额转换成中文大写金额的函数
*String Int $num 要转换的小写数字或小写字符串
*return 大写字母
*小数位为两位
**/
if (!function_exists('num_to_rmb')) {
	function num_to_rmb($num,$rmb=true){
	    $c1 = "零壹贰叁肆伍陆柒捌玖";
	    $c2 = "分角元拾佰仟万拾佰仟亿";
	    //精确到分后面就不要了，所以只留两个小数位
	    $num = round($num, 2); 
	    //将数字转化为整数
	    $num = $num * 100;
	    if (strlen($num) > 10) {
	        return "金额太大，请检查";
	    } 
	    $i = 0;
	    $c = "";
	    // return $num;
	    while (1) {
	        if ($i == 0) {
	            //获取最后一位数字
	            $n = substr($num, strlen($num)-1, 1);
	        } else {
	            $n = $num % 10;
	        }
	        //每次将最后一位数字转化为中文
	        $p1 = substr($c1, 3 * $n, 3);
	        $p2 = substr($c2, 3 * $i, 3);
	        if ($n != '0' || ($n == '0' && ($p2 == '亿' || $p2 == '万' || $p2 == '元'))) {
	            $c = $p1 . $p2 . $c;
	        } else {
	            $c = $p1 . $c;
	        }
	        $i = $i + 1;
	        //去掉数字最后一位了
	        $num = bcdiv($num, 10);
	        // $num = (int)$num;
	        //结束循环
	        if ($num == 0) {
	            break;
	        } 
	    }
	    $j = 0;
	    $slen = strlen($c);

	    while ($j < $slen) {
	        //utf8一个汉字相当3个字符
	        $m = substr($c, $j, 6);
	        //处理数字中很多0的情况,每次循环去掉一个汉字“零”
	        if ($m == '零元' || $m == '零万' || $m == '零亿' || $m == '零零') {
	            $left = substr($c, 0, $j);
	            $right = substr($c, $j + 3);
	            $c = $left . $right;
	            $j = $j-3;
	            $slen = $slen-3;
	        } 
	        $j = $j + 3;
	    } 
	    //这个是为了去掉类似23.0中最后一个“零”字
	    if (substr($c, strlen($c)-3, 3) == '零') {
	        $c = substr($c, 0, strlen($c)-3);
	    }
	    //将处理的汉字加上“整”
	    if (empty($c)) {
	        $c = "零元";
	    }
	    if (!$rmb) {
	    	if (strpos($c, '分') || strpos($c, '角')) {
	    		$c = str_replace('元',"点",$c);
	    	}
	    	$c = str_replace(['分','角','元'],["","",""],$c);
	    }
	    return $c;
	}
}

/**
*返回一串中文字符串首字母大写
*
*return 大写字母字符串
*
**/
if (!function_exists('str_firstchar')) {
	function str_firstchar($str){
		$charlist = preg_split('/(?<!^)(?!$)/u', $str);
		$char_array = [];
		foreach ($charlist as $s0) {
			$fchar = ord(substr($s0, 0, 1));
		    if (mb_detect_encoding($s0, 'UTF-8') !== 'UTF-8') {
		    	continue;
		    }
		    $s = iconv("UTF-8", "GBK", $s0);
		    if (!isset($s{1}) || !isset($s{0})) {
		    	continue;
		    }
		    $asc = ord($s{0}) * 256 + ord($s{1})-65536;
		    if ($asc >= -20319 and $asc <= -20284)$char_array[] = "A";
		    if ($asc >= -20283 and $asc <= -19776)$char_array[] = "B";
		    if ($asc >= -19775 and $asc <= -19219)$char_array[] = "C";
		    if ($asc >= -19218 and $asc <= -18711)$char_array[] = "D";
		    if ($asc >= -18710 and $asc <= -18527)$char_array[] = "E";
		    if ($asc >= -18526 and $asc <= -18240)$char_array[] = "F";
		    if ($asc >= -18239 and $asc <= -17923)$char_array[] = "G";
		    if ($asc >= -17922 and $asc <= -17418)$char_array[] = "H";
		    if ($asc >= -17417 and $asc <= -16475)$char_array[] = "J";
		    if ($asc >= -16474 and $asc <= -16213)$char_array[] = "K";
		    if ($asc >= -16212 and $asc <= -15641)$char_array[] = "L";
		    if ($asc >= -15640 and $asc <= -15166)$char_array[] = "M";
		    if ($asc >= -15165 and $asc <= -14923)$char_array[] = "N";
		    if ($asc >= -14922 and $asc <= -14915)$char_array[] = "O";
		    if ($asc >= -14914 and $asc <= -14631)$char_array[] = "P";
		    if ($asc >= -14630 and $asc <= -14150)$char_array[] = "Q";
		    if ($asc >= -14149 and $asc <= -14091)$char_array[] = "R";
		    if ($asc >= -14090 and $asc <= -13319)$char_array[] = "S";
		    if ($asc >= -13318 and $asc <= -12839)$char_array[] = "T";
		    if ($asc >= -12838 and $asc <= -12557)$char_array[] = "W";
		    if ($asc >= -12556 and $asc <= -11848)$char_array[] = "X";
		    if ($asc >= -11847 and $asc <= -11056)$char_array[] = "Y";
		    if ($asc >= -11055 and $asc <= -10247)$char_array[] = "Z";
		}
		return empty($char_array) ? '' : implode($char_array);
	}
}

/**
 * is_username
 * 
 * 判断是否是用户名规则
 * 
 * 字符串只允许出现英文字符 数字及下划线
 * 
 * @access public
 * @param string $str
 * @return bool
 */
if(!function_exists('is_username')){
	function is_username($str){
		$pattern = '/^[A-Za-z0-9_]+$/';
		return preg_match($pattern, $str);
	}
}

/**
 * is_special
 * 
 * 判断特殊字符规则
 * 
 * 匹配规则包含单双引号、反斜杠、尖括号、等号
 * 
 * @access public
 * @param string $str
 * @return bool
 */
if(!function_exists('is_special')){
	function is_special($str){
		$pattern = '/[\'"\\<>=]+/';
		return preg_match($pattern, $str);
	}
}

/**
 * is_jsonstr
 *
 * 判断字符串是否json串
 *
 * @access	public
 * @param	string	$str
 * @return	bool
 */
if(!function_exists('is_jsonstr')){
    function is_jsonstr($str) {
        return preg_match('/^[\[\{](.+:.+,*){0,}[\}\]]$/', $str);
    }
}

/**
 * is_intstr
 *
 * 判断是否int或int类型的字符串
 *
 * @access	public
 * @param	int|string	$value
 * @return	bool
 */
if(!function_exists('is_intstr')){
    function is_intstr($value) {
        return is_int($value) || ( (is_string($value)||is_float($value)) && preg_match('/^\d+$/', $value) );
    }
}

/**
 * is_datestr
 *
 * 判断是否形如'2014-03-05‘的字符串
 *
 * @access	public
 * @param	int|string	$value
 * @return	bool
 */
if(!function_exists('is_datestr')){
    function is_datestr($value) {
	    $arr = explode('-', $value);
	    if(count($arr)!=3) return FALSE;
	    list($year, $month, $day) = $arr;
	    return checkdate($month, $day, $year);
    }
}

/**
 * is_url
 * 判断一个字符串是否合法的url
 *
 * @access	public
 * @param	string $uri 原始url
 * @return	BOOL
 */
if(!function_exists('is_url')){
    function is_url($uri=NULL){
        return filter_var(trim($uri), FILTER_VALIDATE_URL) ? TRUE : FALSE;
    }
}

/**
 * is_email
 *
 * 检查字符串是否符合邮箱格式
 *
 * @access	public
 * @param	string	$str
 * @return	bool
 */
if(!function_exists('is_email')){
    function is_email($str){
        return ( ! preg_match("/^([a-z0-9\+_\-]+)(\.[a-z0-9\+_\-]+)*@([a-z0-9\-]+\.)+[a-z]{2,6}$/ix", $str)) ? FALSE : TRUE;
    }
}

/**
 * is_mobile_num
 *
 * 检验字符串是否是手机号码
 *
 * @access	public
 * @param	string	$str
 * @return	bool
 */
if(!function_exists('is_mobile_num')){
	function is_mobile_num($str){
        return ( ! preg_match("/^1[3456789]\d{9}$/", $str)) ? FALSE : TRUE;
    }
}

/**
 * is_chinese_alpha_dash
 *
 * 检验字符串是否由中文、英文字母、数字、下划线和破折号组成
 *
 * @access	public
 * @param	string	$str
 * @return	bool
 */
if(!function_exists('is_chinese_alpha_dash')){
	function is_chinese_alpha_dash($str){
		return ( ! preg_match("/^([-a-z0-9_\一-\龥-])+$/i", $str)) ? FALSE : TRUE;
	}
}

/**
 * is_idcard
 *
 * 检验字符串是否合法的中国居民身份证号码
 *
 * @access	public
 * @param	string	$idcard
 * @return	bool
 */
if(!function_exists('is_idcard')){
	function is_idcard($idcard){
		if(empty($idcard)) return FALSE;

		$area = array(11=>"北京",12=>"天津",13=>"河北",14=>"山西",15=>"内蒙古",21=>"辽宁",22=>"吉林",23=>"黑龙江",31=>"上海",32=>"江苏",33=>"浙江",34=>"安徽",35=>"福建",36=>"江西",37=>"山东",41=>"河南",42=>"湖北",43=>"湖南",44=>"广东",45=>"广西",46=>"海南",50=>"重庆",51=>"四川",52=>"贵州",53=>"云南",54=>"西藏",61=>"陕西",62=>"甘肃",63=>"青海",64=>"宁夏",65=>"新疆",71=>"台湾",81=>"香港",82=>"澳门",91=>"国外");
		//长度验证
		if(!preg_match('/^\d{17}(\d|x)$/i',$idcard) AND !preg_match('/^\d{15}$/i',$idcard)) return FALSE;
		//地区验证
		if(!array_key_exists(intval(substr($idcard,0,2)), $area)) return FALSE;
		// 15位身份证验证生日，转换为18位
		if (strlen($idcard) == 15){
			// 如果身份证顺序码是996 997 998 999，这些是为百岁以上老人的特殊编码
			if (array_search(substr($idcard, 12, 3), array('996', '997', '998', '999')) !== false){
	            $century = '18';
	        }else{
				$century = '19';
	        }

			$sBirthday = $century.substr($idcard,6,2).'-'.substr($idcard,8,2).'-'.substr($idcard,10,2);
            try{
                $d = new DateTime($sBirthday);
            }catch(Exception $e){
                return FALSE;
            }
			$dd = $d->format('Y-m-d');
			if($sBirthday != $dd){
				return FALSE;
			}

			$idcard = substr($idcard,0,6).$century.substr($idcard,6,9); //15to18
			$Bit18 = get_verify_bit($idcard); //算出第18位校验码
			$idcard = $idcard.$Bit18;
		}
		// 判断是否大于2078年，小于1900年
		$year = substr($idcard,6,4);
		if ($year<1900 || $year>2078 ){
			return FALSE;
		}

		//18位身份证处理
		$sBirthday = substr($idcard,6,4).'-'.substr($idcard,10,2).'-'.substr($idcard,12,2);
		$d = new DateTime($sBirthday);
		$dd = $d->format('Y-m-d');
		if($sBirthday != $dd){
			return FALSE;
		}
		//身份证编码规范验证
		$idcard_base = substr($idcard,0,17);
		return strtoupper(substr($idcard,17,1)) == get_verify_bit($idcard_base);
	}

	// 计算身份证校验码，根据国家标准GB 11643-1999
	function get_verify_bit($idcard_base){
		if(strlen($idcard_base) != 17) return FALSE;
		//加权因子
		$factor = array(7, 9, 10, 5, 8, 4, 2, 1, 6, 3, 7, 9, 10, 5, 8, 4, 2);
		//校验码对应值
		$verify_number_list = array('1', '0', 'X', '9', '8', '7', '6', '5', '4','3', '2');
		$checksum = 0;
		for ($i = 0; $i < strlen($idcard_base); $i++){
			$checksum += intval(substr($idcard_base, $i, 1)) * $factor[$i];
		}
		$mod = $checksum % 11;
		$verify_number = $verify_number_list[$mod];
		return $verify_number;
	}
}

/**
 * 判断字符串是否'page_1'格式的分页
 * @access	public
 * @param	int $str
 */
if(!function_exists('is_pagestr')){
    function is_pagestr($str){
        return preg_match('/^page_\d+$/', $str);
    }
}

/**
 * utf8_substr
 *
 * 按字数截取UTF8字符
 *
 * @access	public
 * @param	string	$str
 * @param	int		$start
 * @param	int		$length
 * @return	bool
 */
if(!function_exists('utf8_substr')){
	function utf8_substr($str, $start = 0, $length) {
		if (function_exists('utf8_substr')) {
			return mb_substr($str, $start, $length, 'UTF-8');
		}
		preg_match_all("/./u", $str, $arr);
		return implode("", array_slice($arr[0], $start, $length));
	}
}

/**
 * utf8_str
 *
 * 按字符串转为UTF8格式. 原格式可以为ASCII|EUC-CN|GB2312|GBK|UTF8等格式
 *
 * @access	public
 * @param	string	$str
 * @return	string
 */
if(!function_exists('utf8_str')){
	function utf8_str($str){
		$en = array('ASCII','EUC-CN','GB2312','GBK');
		$encoding = mb_detect_encoding($str, $en);
		if(in_array($encoding, $en)) {
			$str = iconv("GBK", "UTF-8", $str);
		}
		return $str;
	}
}

/**
 * short_time
 *
 * 从特定时间开始计算的秒数。这里采用2012-08-15 00:00:00
 * @return int
 */
if(!function_exists('short_time')){
	function short_time(){
		return time() - strtotime('2012-08-15'); //发布时间 - 2012-08-15 00:00:00
	}
}

/**
 * easytime
 *
 * 转换形如'1334783482'或'2010-10-11 10:10:10'的时间格式为形如'N分钟/小时/天前'的字符串
 *
 * @access	public
 * @param	string	$datatime 形如'1334783482'或'2010-10-11 10:10:10'的时间
 * @return	string
 */
if(!function_exists('easytime')){

	function easytime($datatime){

		if(empty($datatime)) return NULL;

		if(preg_match('/[\d]{10}/', $datatime)) $datatime = date('Y-m-d H:i:s', $datatime);

		$overtime = time()-strtotime($datatime);

		//现在
		if($overtime==0) {
			return "刚刚";
		}
		//过去
		elseif($overtime>0){
			$str = '';
			$overtime_today = strtotime(date('Y-m-d'))-strtotime($datatime);
			if($overtime_today>0){
				$d = intval($overtime_today/(24*60*60));
				if($d==0) return "昨天";
				elseif($d==1) return "前天";
				elseif($d<10 && $d >1) $str=$d."天";
				else return date('Y-m-d',strtotime($datatime));
				//else $str = $d."天";
			}else{
				$overtime = $overtime%(24*60*60);
				$h = intval($overtime/(60*60));
				if($h) $str .= $h."小时";

				$overtime = $overtime%(60*60);
				$i = intval($overtime/60);
				if(!$h && $i) $str .= $i."分钟";

				$s = intval($overtime%60);
				if(!$h && !$i && $s) $str .= $s."秒";
			}
			return $str . "前";
		}
		//未来
		else{
			$str = '';
			$overtime_tomorrow = strtotime($datatime)-strtotime(date('Y-m-d', strtotime('+1 day')));
			if($overtime_tomorrow>0){
				$d = intval($overtime_tomorrow/(24*60*60));
				if($d==0) return "明天";
				elseif($d==1) return "后天";
				else $str = $d."天";
			}else{
				$overtime = -$overtime%(24*60*60);
				$h = intval($overtime/(60*60));
				if($h) $str .= $h."小时";

				$overtime = $overtime%(60*60);
				$i = intval($overtime/60);
				if(!$h && $i) $str .= $i."分钟";

				$s = intval($overtime%60);
				if(!$h && !$i && $s) $str .= $s."秒";
			}
			return $str . "后";
		}
	}
}

/**
 * easyage
 *
 * 求出给定时间到现在的时长，表达为“N天/月/年”的字符串
 *
 * @access	public
 * @param	string	$datatime 形如'1334783482'或'2010-10-11 10:10:10'的时间
 * @return	string
 */
if(!function_exists('easyage')){

	function easyage($datatime){

		if(empty($datatime)) return NULL;

		if(preg_match('/[\d]{10}/', $datatime)) $datatime = date('Y-m-d H:i:s', $datatime);

		$overtime = time()-strtotime($datatime);

		//现在
		if($overtime==0) {
			return "0天";
		}
		//过去
		elseif($overtime>0){
			$str = '';
			$overtime_today = strtotime(date('Y-m-d'))-strtotime($datatime);
			if($overtime_today>0){
				$d = intval($overtime_today/(24*60*60));
				$str = $d."天";
			}else{
				$overtime = $overtime%(24*60*60);
				$h = intval($overtime/(60*60));
				if($h) $str .= $h."小时";

				$overtime = $overtime%(60*60);
				$i = intval($overtime/60);
				if(!$h && $i) $str .= $i."分钟";

				$s = intval($overtime%60);
				if(!$h && !$i && $s) $str .= $s."秒";
			}
			return $str;
		}
		//未来
		else{
			$str = '';
			$overtime_tomorrow = strtotime($datatime)-strtotime(date('Y-m-d', strtotime('+1 day')));
			if($overtime_tomorrow>0){
				$d = intval($overtime_tomorrow/(24*60*60));
				$str = $d."天";
			}else{
				$overtime = -$overtime%(24*60*60);
				$h = intval($overtime/(60*60));
				if($h) $str .= $h."小时";

				$overtime = $overtime%(60*60);
				$i = intval($overtime/60);
				if(!$h && $i) $str .= $i."分钟";

				$s = intval($overtime%60);
				if(!$h && !$i && $s) $str .= $s."秒";
			}
			return $str;
		}
	}
}

/**
 * datetime
 *
 * 返回“2010-10-16 12:30:25”格式的时间字符串
 *
 * @access	public
 * @param	string	$time 时间戳
 * @return	string
 */
if(!function_exists('datetime')){
	function datetime($time = NULL){
		return $time ? date('Y-m-d H:i:s', $time) : date('Y-m-d H:i:s');
	}
}

/**
 * is_date
 * 
 * 判断传入参数是否符合Y-m-d的格式规范
 * 
 * @access	public
 * @param	date	Y-m-d
 * @return	bool
 * 
 */
if(!function_exists('is_date')){
	function is_date($date){
		$pattern = '/^([\d]{4})-([\d]{1,2})-([\d]{1,2})$/';
		if(!preg_match($pattern, $date)){
			return false;
		}
		if(!strtotime($date)){
			return false;
		}
		return true;
	}
}

/**
 * is_datetime
 *
 * 判断传入参数是否符合Y-m-d H:i:s的格式规范
 *
 * @access	public
 * @param	datetime	Y-m-d H:i:s
 * @return	bool
 *
 */
if(!function_exists('is_datetime')){
	function is_datetime($datetime){
		$pattern = '/^([\d]{4})-([\d]{1,2})-([\d]{1,2})\s([\d]{2}):([\d]{2}):([\d]{2})$/';
		if(!preg_match($pattern, $datetime)){
			return false;
		}
		if(!strtotime($datetime)){
			return false;
		}
		return true;
	}
}

/**
 * today
 *
 * 今天的日期。“2010-10-16”格式的时间字符串
 *
 * @access	public
 * @return	string
 */
if(!function_exists('today')){
	function today(){
		return date('Y-m-d');
	}
}

/**
 * yesterday
 *
 * 昨天的日期。“2010-10-16”格式的时间字符串
 *
 * @access	public
 * @return	string
 */
if(!function_exists('yesterday')){
	function yesterday(){
		return date('Y-m-d', strtotime('yesterday'));
	}
}

/**
 * today
 *
 * 明天的日期。“2010-10-16”格式的时间字符串
 *
 * @access	public
 * @return	string
 */
if(!function_exists('tomorrow')){
    function tomorrow(){
        return date('Y-m-d', strtotime('tomorrow'));
    }
}

/**
 * this_monday
 *
 * 本周一的日期。“2010-10-16”格式的时间字符串
 *
 * @access	public
 * @return	string
 */
if(!function_exists('this_monday')){
    function this_monday(){
        $time = date("N")==1 ? strtotime('this monday') : strtotime('last monday');
        return datetime($time);
    }
}

/**
 * next_monday
 *
 * 下周一的日期。“2010-10-16”格式的时间字符串
 *
 * @access	public
 * @return	string
 */
if(!function_exists('next_monday')){
    function next_monday(){
        $time = date("N")==7 ? strtotime('this monday') : strtotime('next monday');
        return datetime($time);
    }
}

/**
 * strtodate
 *
 * 根据字符串获得“2014-06-25”格式的日期字符串
 *
 * @access	public
 * @return	string
 */
if(!function_exists('strtodate')){
    function strtodate($str){
        return date('Y-m-d', strtotime($str));
    }
}

/**
 * curtime
 *
 * 返回“12:30:25”格式的时间字符串
 *
 * @access	public
 * @return	string
 */
if(!function_exists('curtime')){
	function curtime(){
		return date('H:i:s');
	}
}

/**
 * is_iphone
 *
 * 检测客户端是否iPhone
 *
 * @access	public
 * @return	string
 */
if(!function_exists('is_iphone')){
	function is_iphone($ua = NULL){
        if($ua==NULL) $ua = $_SERVER['HTTP_USER_AGENT'];
        $iphone = strstr(strtolower($ua), 'iphone'); //Search for 'mobile' in user-agent (iPhone have that)
        return $iphone ? TRUE : FALSE;
	}
}

/**
 * is_android
 *
 * 检测客户端是否android
 *
 * @access	public
 * @return	string
 */
if(!function_exists('is_android')){
    function is_android($ua = NULL){
        if($ua==NULL) $ua = $_SERVER['HTTP_USER_AGENT'];
        $android = strstr(strtolower($ua), 'android'); //Search for 'mobile' in user-agent (android have that)
        return $android ? TRUE : FALSE;
    }
}

/**
 * user_agent
 *
 * 获知用户客户端类型
 *
 * @access	public
 * @param	string	$datatime
 * @return	string
 */
if(!function_exists('user_agent')){

	function user_agent($ua = NULL){

		if($ua==NULL) $ua = $_SERVER['HTTP_USER_AGENT'];

		$android = strstr(strtolower($ua), 'android'); //Search for 'android' in user-agent
        $iphone = strstr(strtolower($ua), 'mobile'); //Search for 'mobile' in user-agent (iPhone have that)
		$windowsPhone = strstr(strtolower($ua), 'phone'); //Search for 'phone' in user-agent (Windows Phone uses that)

		$androidTablet = androidTablet($ua); //Do androidTablet function
		$ipad = strstr(strtolower($ua), 'ipad'); //Search for iPad in user-agent

		if($androidTablet || $ipad){ //If it's a tablet (iPad / Android)
			return 'tablet';
		}
		elseif($iphone && !$ipad || $android && !$androidTablet || $windowsPhone){ //If it's a phone and NOT a tablet
			return 'mobile';
		}
		else{ //If it's not a mobile device
			return 'desktop';
		}

	}

	function androidTablet($ua){ //Find out if it is a tablet
		if(strstr(strtolower($ua), 'android') ){//Search for android in user-agent
			if(!strstr(strtolower($ua), 'mobile')){ //If there is no ''mobile' in user-agent (Android have that on their phones, but not tablets)
				return TRUE;
			}
		}
		return FALSE;
	}
}

/**
 * client_mobile
 *
 * 获知用户客户端类型是否手机（mobile）
 *
 * @access	public
 * @param	string	$datatime
 * @return	string
 */
if(!function_exists('client_mobile')){
	function client_mobile(){
		return (user_agent()=='mobile');
	}
}

/**
 * get_nextmonthday
 *
 * 获得一个月或几个月后的当前日期
 *
 * @access	public
 * @param	int	$i 向后数第几个月。默认为1，下个月
 * @return	bool
 */
if(!function_exists('get_nextmonthday')){

    function get_nextmonthday($i=1){

        $date = getdate();

        $ya = $date['year'];
        $ma = $date['mon'];
        $da = $date['mday'];

        $year_n = $ya;
        $month_n = $ma + $i;
        $day_n = $da;

        if($ma + $i > 12){
            $month_n = ($ma + $i)%12;
            $year_n = floor(($ma+$i)/12) + $ya;
        }
        $next_date = mktime (0,0,0,$month_n, $day_n,$year_n);
        //对于借款日期在 28之后，需要特殊处理
        if($da > 28){
            //判断$year_n - $month_n - $day_n 是否存在，如果存在，则返回。否则取最后一天
            if(checkdate($month_n, $day_n, $year_n )){}
            else { $next_date =  mktime(0,0,0,$month_n+1, 1,$year_n) - 24* 3600; }
        }

        return date('Y-m-d H:i:s', $next_date);
    }
}

/**
 * get_client_ip
 *
 * 获得客户端IP
 *
 * @access	public
 * @param	string	$str
 * @return	bool
 */
if(!function_exists('get_client_ip')){

	function get_client_ip(){

		$ip = false;
		if (!empty ($_SERVER["HTTP_CLIENT_IP"])) {
			$ip = $_SERVER["HTTP_CLIENT_IP"];
		}
		if (!empty ($_SERVER['HTTP_X_FORWARDED_FOR'])) {
			$ips = explode(", ", $_SERVER['HTTP_X_FORWARDED_FOR']);
			if($ip){
				array_unshift($ips, $ip);
				$ip = FALSE;
			}
			for ($i = 0; $i < count($ips); $i++) {
				if (!eregi("^(10|172\.16|192\.168)\.", $ips[$i])) {
					$ip = $ips[$i];
					break;
				}
			}
		}
		return ($ip ? $ip : $_SERVER['REMOTE_ADDR']);
	}
}

/**
 * get_ip_address
 *
 * 获得IP的归属地址
 *
 * @依赖 本函数依赖 IP 库
 *
 * @access	public
 * @param	string	$ip
 * @return	bool
 */
if(!function_exists('get_ip_address')){

	function get_ip_address($ip){
		$ci = & get_instance();
		$ci->load->library('IP');
		return IP::find($ip);
	}
}

/**
 * url_filename
 *
 * 从一个URL中获得文件名
 * 注意，仅当路径的最后一节为带后缀的文件名时才会被返回，否则返回NULL
 *
 * @access	public
 * @param	string	$url
 * @return	string | NULL
 */
if(!function_exists('url_filename')){
    function url_filename($url){
        preg_match('/\/([^\/]+\.[a-z0-9-_]+)[^\/]*$/', $url, $match);
        return isset($match[1]) ? trim($match[1]) : NULL;
    }
}

/**
 * url_fileext
 *
 * 从一个URL中获得文件后缀
 *
 * @access	public
 * @param	string	$url
 * @return	string | NULL
 */
if(!function_exists('url_fileext')){
    function url_fileext($url){
        $path = parse_url($url);
        $arr = explode('/', $path['path']);
        $str = array_pop($arr);
        $arr = explode('.', $str);
        if(count($arr)==1) return NULL;
        $str = array_pop($arr);
        return $str ? $str : NULL;
    }
}

/**
 * make_dir
 *
 * 递归模式生成目录
 *
 * @access	public
 * @param	string	$dir
 * @return	bool
 */
if(!function_exists('make_dir')){
    function make_dir($dir){
        if(is_dir($dir)){
            return TRUE;
        }
        $oldumask=umask(0);
        $flag = mkdir($dir, 0777, TRUE);
        umask($oldumask);
        return $flag;
    }
}


/**
 * gzdecode
 *
 * 解压gzip压缩的字符串
 *
 * @access	public
 * @param	string	$data
 * @return	string
 */
if(!function_exists('gzdecode')){
    function gzdecode($data) {
        $len = strlen($data);
        if ($len < 18 || strcmp(substr($data,0,2),"\x1f\x8b")) {
            return null;  // Not GZIP format (See RFC 1952)
        }
        $method = ord(substr($data,2,1));  // Compression method
        $flags  = ord(substr($data,3,1));  // Flags
        if ($flags & 31 != $flags) {
            // Reserved bits are set -- NOT ALLOWED by RFC 1952
            return null;
        }
        // NOTE: $mtime may be negative (PHP integer limitations)
        $mtime = unpack("V", substr($data,4,4));
        $mtime = $mtime[1];
        $xfl   = substr($data,8,1);
        $os    = substr($data,8,1);
        $headerlen = 10;
        $extralen  = 0;
        $extra     = "";
        if ($flags & 4) {
            // 2-byte length prefixed EXTRA data in header
            if ($len - $headerlen - 2 < 8) {
                return false;    // Invalid format
            }
            $extralen = unpack("v",substr($data,8,2));
            $extralen = $extralen[1];
            if ($len - $headerlen - 2 - $extralen < 8) {
                return false;    // Invalid format
            }
            $extra = substr($data,10,$extralen);
            $headerlen += 2 + $extralen;
        }

        $filenamelen = 0;
        $filename = "";
        if ($flags & 8) {
            // C-style string file NAME data in header
            if ($len - $headerlen - 1 < 8) {
                return false;    // Invalid format
            }
            $filenamelen = strpos(substr($data,8+$extralen),chr(0));
            if ($filenamelen === false || $len - $headerlen - $filenamelen - 1 < 8) {
                return false;    // Invalid format
            }
            $filename = substr($data,$headerlen,$filenamelen);
            $headerlen += $filenamelen + 1;
        }

        $commentlen = 0;
        $comment = "";
        if ($flags & 16) {
            // C-style string COMMENT data in header
            if ($len - $headerlen - 1 < 8) {
                return false;    // Invalid format
            }
            $commentlen = strpos(substr($data,8+$extralen+$filenamelen),chr(0));
            if ($commentlen === false || $len - $headerlen - $commentlen - 1 < 8) {
                return false;    // Invalid header format
            }
            $comment = substr($data,$headerlen,$commentlen);
            $headerlen += $commentlen + 1;
        }

        $headercrc = "";
        if ($flags & 1) {
            // 2-bytes (lowest order) of CRC32 on header present
            if ($len - $headerlen - 2 < 8) {
                return false;    // Invalid format
            }
            $calccrc = crc32(substr($data,0,$headerlen)) & 0xffff;
            $headercrc = unpack("v", substr($data,$headerlen,2));
            $headercrc = $headercrc[1];
            if ($headercrc != $calccrc) {
                return false;    // Bad header CRC
            }
            $headerlen += 2;
        }

        // GZIP FOOTER - These be negative due to PHP's limitations
        $datacrc = unpack("V",substr($data,-8,4));
        $datacrc = $datacrc[1];
        $isize = unpack("V",substr($data,-4));
        $isize = $isize[1];

        // Perform the decompression:
        $bodylen = $len-$headerlen-8;
        if ($bodylen < 1) {
            // This should never happen - IMPLEMENTATION BUG!
            return null;
        }
        $body = substr($data,$headerlen,$bodylen);
        $data = "";
        if ($bodylen > 0) {
            switch ($method) {
                case 8:
                    // Currently the only supported compression method:
                    $data = gzinflate($body);
                    break;
                default:
                    // Unknown compression method
                    return false;
            }
        } else {
            // I'm not sure if zero-byte body content is allowed.
            // Allow it for now...  Do nothing...
        }

        // Verifiy decompressed size and CRC32:
        // NOTE: This may fail with large data sizes depending on how
        //       PHP's integer limitations affect strlen() since $isize
        //       may be negative for large sizes.
        if ($isize != strlen($data) || crc32($data) != $datacrc) {
            // Bad format!  Length or CRC doesn't match!
            return false;
        }
        return $data;
    }
}

/**
 * encode_passwd
 *
 * 根据源字符串和盐生成密码
 * @param $str_src
 * @param $salt
 * @return string 40位的加密字符串
 */
if(!function_exists('encode_passwd')){
	function encode_passwd($str_src, $salt = '@)!#!!)@'){
		return sha1(md5($str_src) . $salt);
	}
}

/**
 * get_at_users
 *
 * 从一段文本中获得被@的用户名列表
 * @param $content string 文本内容
 * @return array|FALSE 结果数组，无@内容则返回NULL
 */
if(!function_exists('get_at_users')){
	function get_at_users($content){
		if(preg_match_all('/@([^，。！\s!"#\$%&\'\(\)\*\+,\.\/:;<=>\?@\[\\\\\]\^`\{\|\}~]{2,16})/iu', $content, $m)){
			return array_unique($m[1]);
		}
		return NULL;
	}
}

/**
 * webpage_title
 *
 * 获得一张网页的标题
 *
 * @access	public
 * @param	string	$url
 * @return	string
 */
if(!function_exists('webpage_title')){

	function webpage_title($url){

		$c = curl_init($url) ;
		curl_setopt($c, CURLOPT_RETURNTRANSFER, true) ;
		curl_setopt($c, CURLOPT_BINARYTRANSFER, true) ;
		$src = curl_exec($c) ;

		if(preg_match('/<title>([^<>]*)<\/title>/i', $src, $head)){
			return utf8_str(trim($head[1]));
		}else{
			return NULL;
		}
	}
}

/**
 * get_htmltext
 *
 * 从一段HTML文档中获得文本
 *
 * @依赖 本函数依赖 PQuery 库
 *
 * @param $html string html文档文本
 * @return string
 */
if(!function_exists('get_htmltext')){
	function get_htmltext($html){
		$ci = & get_instance();
		$ci->load->library('PQuery');
		return phpQuery::newDocument($html)->text();
	}
}

/**
 * get_summary
 *
 * 从一段HTML文档中获得文本概要
 * @param $html string html文档文本
 * @param $length int 摘要字数。默认为220字
 * @return string
 */
if(!function_exists('get_summary')){
	function get_summary($html, $length=220){
		$str = get_htmltext(htmlspecialchars_decode($html));
		$str = preg_replace('/\s+/', '', $str);
		return mb_substr(trim($str), 0, $length);
	}
}

/**
 * fetch_img
 *
 * 抓取网络图片并存放到本地
 *
 * @依赖 本函数依赖 Hdl 库
 *
 * @param $url string 图片地址 必须为“http://”或“https://”开头的合法URL
 * @param $ext_first string 首先根据文件后缀决定存放后的文件后缀，其次再根据头信息中的Content-Type来判断；否则直接根据头信息来判断
 * @param $force_ext bool 强制以指定后缀保存图片，仅仅当$ext_first参数为假、或者未取到真实后缀时生效
 * @return bool|string 成功则返回图片名称（md5码文件名）；失败则返回FALSE
 */
if(!function_exists('fetch_img')){

	function fetch_img($url, $save_dir, $ext_first = TRUE, $force_ext = ''){

		if(!is_url($url)){
			return FALSE;
		}

		$ci = & get_instance();
		$ci->load->library('Hdl');
		$hdl = $ci->hdl;
		$hdl->OpenUrl($url);

		$_suffix = $ext_first ? url_fileext($url) : '';
		if(empty($_suffix)){
			$type_str = strtolower($hdl->GetHead('Content-Type'));
			if(
				strpos($type_str, 'image/jpeg')!==FALSE ||
				strpos($type_str, 'image/jpg')!==FALSE ||
				strpos($type_str, 'image/pjpeg')!==FALSE
			){
				$_suffix = 'jpg';
			}elseif(
				strpos($type_str, 'image/png')!==FALSE ||
				strpos($type_str, 'image/x-png')!==FALSE
			){
				$_suffix = 'png';
			}elseif(strpos($type_str, 'image/gif')!==FALSE){
				$_suffix = 'png';
			}elseif(
				strpos($type_str, 'image/bmp')!==FALSE ||
				strpos($type_str, 'image/x-ms-bmp')!==FALSE
			){
				$_suffix = 'bmp';
			}else{
				if($force_ext!=''){
					$_suffix = $force_ext;
				}else{
					return FALSE;
				}
			}
		}

		$save_dir = trim($save_dir,'/').'/';
		make_dir($save_dir);

		$filename = time().rand(1, 10000).'.'.$_suffix;
		$hdl->SaveToBin($save_dir.$filename);

		return file_exists($save_dir.$filename) ? $filename : FALSE;
	}
}

/**
 * make_thumb
 * @param $srcFile string 源图路径
 * @param $dstFile string 生成图路径
 * @param $Limit int 长宽限制
 * @param $WOrH string W H NULL 限制类型
 * @return BOOL
 */
if(!function_exists('make_thumb')){

	function make_thumb($srcFile, $dstFile, $Limit=1024, $WOrH=NULL){
		if(!file_exists($srcFile)) return FALSE;

		$data_pic = @GetImageSize($srcFile);
		if(empty($data_pic)) return FALSE;

		switch ($data_pic[2]) {
			case 1:
				$old_pic = @ImageCreateFromGIF($srcFile);break;
			case 2:
				$old_pic = @imagecreatefromjpeg($srcFile);break;
			case 3:
				$old_pic = @ImageCreateFromPNG($srcFile);break;
		}
		$srcW = ImageSX($old_pic);
		$srcH = ImageSY($old_pic);

		if($WOrH==NULL) $WOrH = ($srcW>$srcH) ? "W" : "H" ;

		if($WOrH=="W"){
			$dstW = ($srcW>$Limit) ? $Limit : $srcW;
			$dstH = $srcH*$dstW/$srcW;
		}else{
			$dstH = ($srcH>$Limit) ? $Limit : $srcH;
			$dstW = $srcW*$dstH/$srcH;
		}

		$new_pic = imagecreatetruecolor($dstW,$dstH);
		imagecopyresampled($new_pic,$old_pic,0,0,0,0,$dstW,$dstH,$srcW,$srcH);
		$flag = ImageJpeg($new_pic,$dstFile);

		imagedestroy($new_pic);
		imagedestroy($old_pic);

		return $flag;
	}
}

/**
 * print_ci_codetip_note
 *
 * 打印出当前框架可使用的代码提示注释，放置在APP_Controller类名前
 *
 * @access	public
 * @return	bool
 */
if(!function_exists('print_codetip_note')){

	function print_codetip_note(){

		//类列表
		echo " * -------------------------------------------------------------------------<br /> * 应用类<br /> * -------------------------------------------------------------------------<br />";
		$dir = dir(APPPATH.'/libraries');
		while (($file = $dir->read()) !== false){
			if(is_dir(APPPATH.'/libraries/'.$file)) continue;
			$name = str_replace('.php', '', $file);
			$name2 = str_replace('APP_', '', $name);
			echo " * @property " . ucfirst($name)  .' '. strtolower($name2) . "<br />";
		}
		$dir->close();

		//第三方类列表
		echo " * -------------------------------------------------------------------------<br /> * 第三方应用类<br /> * -------------------------------------------------------------------------<br />";
		$dir = dir(APPPATH.'/third_party/libraries');
		while (($file = $dir->read()) !== false){
			if(is_dir(APPPATH.'/libraries/'.$file)) continue;
			$name = str_replace('.php', '', $file);
			$name2 = str_replace('APP_', '', $name);
			echo " * @property " . ucfirst($name)  .' '. strtolower($name2) . "<br />";
		}
		$dir->close();

		//模型列表
		echo " * -------------------------------------------------------------------------<br /> * 数据模型<br /> * -------------------------------------------------------------------------<br />";
		$dir = dir(APPPATH.'/models');
		while (($file = $dir->read()) !== false){
			if(is_dir(APPPATH.'/libraries/'.$file)) continue;
			$name = str_replace('.php', '', $file);
			$name2 = str_replace('_model', '', $name);
			echo " * @property " . ucfirst($name)  .' '. ucfirst($name2) . "<br />";
		}
		$dir->close();
	}
}



/**
 * form_error_styled
 *
 * 输出带有样式的表单错误提示
 *
 * @依赖 本函数对以下项存在依赖：
 *  1，css文件'common_model'中的.alert_float/.alert相关类
 *
 * @param $field string 字段名
 * @param $float bool 是否浮动
 * @return string
 */
if(!function_exists('form_error_styled')){
	function form_error_styled($field, $float = TRUE){
		if($float){
			return form_error($field, '<div class="alert_float"><div class="alert warn"><i></i><div class="txt">', '</div></div></div>');
		}else{
			return form_error($field, '<div class="alert warn"><i></i><div class="txt">', '</div></div>');
		}
	}
}

/**
 * form_result_styled
 *
 * 输出带有样式的表单结果提示
 *
 * @依赖 本函数对以下项存在依赖：
 *  1，css文件'common_model'中的.alert_float/.alert相关类
 *
 * @param $result string 结果：success/failed/warn/info
 * @param $text string 成功文字
 * @param $float bool 是否浮动
 * @return string
 */
if(!function_exists('form_result_styled')){
	function form_result_styled($result, $text, $float = TRUE){
        if($result=='success'){
            $class = '';
        }elseif($result=='failed'){
            $class = 'wrong';
        }elseif($result=='warn'){
            $class = 'warn';
        }elseif($result=='info'){
            $class = 'pro';
        }else{
            $class = '';
        }

		if($float){
			return '<span class="alert_float"><div class="alert warn '.$class.'"><i></i><div class="txt">'.$text.'</div></div></span>';
		}else{
            return '<div class="alert '.$class.'"><i></i><div class="txt">'.$text.'</div></div>';
		}
	}
}

/**
 * display_pager
 *
 * 输出分页控件
 *
 * @param $pager array 分页数据，一般为 model->get_page() 取得的数据
 * @param $options array 分页选项。详情参见 _component/pager 中的说明
 *  @param $only_next boolean 分页选项。是否选用只有上下页的分页
 * @return string
 */
if(!function_exists('display_pager')){
	function display_pager($pager, $options=array(), $only_next = false){
		$url = $only_next ? '_component/pagers' : '_component/pager';
        get_instance()->load->view($url, array('pager'=>$pager, 'options'=>$options));
        return '';
	}
}

/**
 * display_hotstars
 *
 * 输出热度五星评分
 *
 * @param $type string 类型：article|writer|wesite|product
 * @param $score float 得分
 * @param $style null|string 样式。一般是margin-left属性，用于调整五星的水平位置
 * @return string
 */
if(!function_exists('display_hotstars')){
	function display_hotstars($type, $score, $style=NULL){
        get_instance()->load->view('_component/hot_star', array('type'=>$type, 'score'=>$score, 'star_style'=>$style));
        return '';
	}
}


/**
 * 生成唯一编号
 * @param string $namespace 作用域
 * @return string
 */
if ( ! function_exists('create_guid')) {
    function create_guid($namespace = 'OrderID') {
        static $guid = '';
        $uid = uniqid("", true);
        $data = $namespace;
        $data .= microtime();
        $data .= $_SERVER['REMOTE_ADDR'];
        $data .= $_SERVER['REMOTE_PORT'];
        $hash = hash('ripemd128', $uid . $guid . md5($data));

        $guid =
            substr($hash,  0,  8) .
            '-' .
            substr($hash,  8,  4) .
            '-' .
            substr($hash, 12,  4) .
            '-' .
            substr($hash, 16,  4) .
            '-' .
            substr($hash, 20, 12);
        return $guid;
    }
}

/**
 * float变量是否近似相等
 * @param float $v1
 * @param float $v2
 * @param float $diff 差值
 * @return string
 */
if ( ! function_exists('float_equal')) {
    function float_equals($v1, $v2, &$diff=NULL) {
        $diff = abs($v1 - $v2);
        return $diff < 1/10000/10000;
    }
}

/**
 * float变量是否近似大于
 * @param float $v1
 * @param float $v2
 * @param float $diff 差值
 * @return string
 */
if ( ! function_exists('float_greater')) {
    function float_greater($v1, $v2, &$diff=NULL) {
        $diff = $v1 - $v2;
        return $diff > 1/10000/10000;
    }
}

/**
 * float变量是否近似小于
 * @param float $v1
 * @param float $v2
 * @param float $diff 差值
 * @return string
 */
if ( ! function_exists('float_less')) {
    function float_less($v1, $v2, &$diff=NULL) {
        $diff = $v2 - $v1;
        return $diff > 1/10000/10000;
    }
}

/**
 * 银行卡号归属地(所属银行)查询
 *   ps:该函数只校验卡号BIN代码是否匹配，不进行卡号长度合规性校验
 * @param string $bank_card 银行卡号
 * @return bool|string
 */
if ( ! function_exists('get_bank_by_card')) {
    function get_bank_by_card($bank_card) {

        $bank_card = str_replace(array('-', ' '),'',$bank_card);
		$bankList = json_decode(file_get_contents(ROOTPATH.'data' . "/bank.json"),true);

		$result='';
		foreach(array(8,6,5,4) as $n) {
			$tmp = substr($bank_card, 0, $n);
			if (isset($bankList[$tmp])) {
				$result = $bankList[$tmp];

				$result=explode('-',$result);
				$result=$result[0];

				break;
			}
		}
		return $result;


//        static $bank = array();
//        // 引入银行BIN相关文件
//        if ( ! $bank) {
//            $bank = unserialize(file_get_contents(dirname(__FILE__) . "/bank.data"));
//        }
//
//        foreach ($bank as $k=>$v) {
//
//            $max = strlen(max($v));
//            $min = strlen(min($v));
//
//            for ($i = $min; $i<=$max; $i++) {
//                if (in_array(substr($bank_card, 0, $i), $v)) {
//                    return $k;
//                }
//            }
//        }

        return false;
    }
}

/**
 * 校验客户端是否为手机用户
 * @return bool
 */
if ( ! function_exists('is_mobile')) {
    function is_mobile() {
        $regExp = "/nokia|iphone|android|samsung|htc|motorola|blackberry|ericsson|huawei|dopod|amoi|gionee|^sie\-|^bird|^zte\-|haier|";
        $regExp .= "blazer|netfront|helio|hosin|novarra|techfaith|palmsource|^mot\-|softbank|foma|docomo|kddi|up\.browser|up\.link|";
        $regExp .= "symbian|smartphone|midp|wap|phone|windows ce|CoolPad|webos|iemobile|^spice|longcos|pantech|portalmmm|";
        $regExp .= "alcatel|ktouch|nexian|^sam\-|s[cg]h|^lge|philips|sagem|wellcom|bunjalloo|maui|";
        $regExp .= "jig\s browser|hiptop|ucweb|ucmobile|opera\s*mobi|opera\*mini|mqqbrowser|^benq|^lct";
        $regExp .= "480×640|640x480|320x320|240x320|320x240|176x220|220x176/i";
        if (!isset($_SERVER['HTTP_USER_AGENT'])) {
            return true;
        } else {
            return !empty($_GET['mobile']) || isset($_SERVER['HTTP_X_WAP_PROFILE']) || isset($_SERVER['HTTP_PROFILE']) || preg_match($regExp, strtolower($_SERVER['HTTP_USER_AGENT']));

        }
    }
}


/**
 * 保留x位小数并且不进行四舍五入(floor_down别名)
 * @param float $number 数字
 * @param int $format 保留几位
 * @return string|float
 */
if ( ! function_exists('sprintf_floor')) {
    function sprintf_floor($number, $format = 2) {
        return (string)floor_down($number ? $number : 0, $format);
    }
}

/**
 * 转义 SQL 语句中使用的字符串中的特殊字符 (本函数转义字符包括 % 和 _)
 * @param $str
 * @return string
 */
if ( ! function_exists('escape_str')) {
    function escape_str($str) {
        $a = array("%","_");
        $b = array("\\%","\\_");
        return str_replace($a, $b, mysql_real_escape_string($str));
    }
}

/**
 * 判断客户端是否来自weixin
 * @return bool
 */
if ( ! function_exists('is_weixin')) {
    function is_weixin() {
        if (strpos($_SERVER['HTTP_USER_AGENT'], 'MicroMessenger') !== false) {
            return true;
        }
        return false;
    }
}

/**
 * 获取给定日期的前一天
 * @param string $date
 * @return string $yesterday
 */
if ( ! function_exists('day_before')) {
    function day_before($date) {
        if (empty($date)) {
            $yesterday = date("Y-m-d",strtotime("-1 day"));
        } else {
            $arr = explode('-', $date);
            $year = $arr[0];
            $month = $arr[1];
            $day = $arr[2];
            $unixtime = mktime(0,0,0,$month,$day,$year)-86400;
            $yesterday = date('Y-m-d',$unixtime);
        }

        return $yesterday;
    }
}

/**
 * 获取两个日期之间的所有日期，包含这两个日期
 * @param string $start 开始日期，格式："Y-m-d"
 * @param string $end 结束日期，格式："Y-m-d"
 * @return array|false
 */
if ( ! function_exists('date_array')) {
    function date_array($start, $end = NULL) {
        if(empty($start)) {
            return false;
        }

        if(empty($end)) {
            $end = date("Y-m-d",time());
        }

        $date_arr = array();
        $tmp_start = strtotime($start);
        $tmp_end = strtotime($end);
        while ($tmp_start <= $tmp_end) {
            $date_arr[] = date("Y-m-d", $tmp_start);
            $tmp_start = $tmp_start + 86400;
        }

        return $date_arr;
    }
}

/**
 * 由长连接生成短链接操作
 *
 * 算法描述：使用6个字符来表示短链接，我们使用ASCII字符中的'a'-'z','0'-'9','A'-'Z'，共计62个字符做为集合。
 *           每个字符有62种状态，六个字符就可以表示62^6（56800235584），那么如何得到这六个字符，
 *           具体描述如下：
 *        1. 对传入的长URL+设置key值 进行Md5，得到一个32位的字符串(32 字符十六进制数)，即16的32次方；
 *        2. 将这32位分成四份，每一份8个字符，将其视作16进制串与0x3fffffff(30位1)与操作, 即超过30位的忽略处理；
 *        3. 这30位分成6段, 每5个一组，算出其整数值，然后映射到我们准备的62个字符中, 依次进行获得一个6位的短链接地址。
 *
 * @author flyer0126
 * @since 2012/07/13
 */
if ( ! function_exists('shortUrl')) {
    function shortUrl( $long_url ) {
        $key = 'flyer0126';
        $base32 = "abcdefghijklmnopqrstuvwxyz0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ";

        // 利用md5算法方式生成hash值
        $hex = hash('md5', $long_url.$key);
        $hexLen = strlen($hex);
        $subHexLen = $hexLen / 8;

        $output = array();
        for( $i = 0; $i < $subHexLen; $i++ )
        {
            // 将这32位分成四份，每一份8个字符，将其视作16进制串与0x3fffffff(30位1)与操作
            $subHex = substr($hex, $i*8, 8);
            $idx = 0x3FFFFFFF & (1 * ('0x' . $subHex));

            // 这30位分成6段, 每5个一组，算出其整数值，然后映射到我们准备的62个字符
            $out = '';
            for( $j = 0; $j < 6; $j++ )
            {
                $val = 0x0000003D & $idx;
                $out .= $base32[$val];
                $idx = $idx >> 5;
            }
            $output[$i] = $out;
        }

        return $output;
    }
}

/**
 * 浮点数近一法
 * @param float $value 数字
 * @param int $places 保留小数
 * @return float
 */
if ( ! function_exists('round_up')) {
	function round_up($value, $places = 4) {
		if ($places < 0) {
			$places = 0;
		}
		$mult = pow(10, $places);
		return ceil($value * $mult) / $mult;
	}
}

/**
 * 浮点数减一法
 * @param float $value 数字
 * @param int $places 保留小数
 * @param string $separator 分隔符
 * @return string|float
 */

if ( ! function_exists('floor_down')) {
	function floor_down($value, $places=4, $separator = "."){
		$arr =  explode($separator, $value);

		if(count($arr) != 2) {
			return $value;
		}

		$len = strlen($arr[1]);

		if ($len <= $places) {
			return $value;
		}

		$str = $arr[0] . $separator;
		for ($i=0; $i<$places; $i++) {
			$str .= $arr[1][$i];
		}
		return $str;
	}

}

/**
 * 浮点数减一法，如果$places＝0，就不加分隔符
 * @param float $value 数字
 * @param int $places 保留小数
 * @param string $separator 分隔符
 * @return string|float
 */

if ( ! function_exists('floor_down02')) {
    function floor_down02($value, $places=4, $separator = "."){
        $arr =  explode($separator, $value);

        if(count($arr) != 2) {
            return $value;
        }

        $len = strlen($arr[1]);

        if ($len <= $places) {
            return $value;
        }

        if ($places == 0) {
            return $arr[0];
        }

        $str = $arr[0] . $separator;
        for ($i=0; $i<$places; $i++) {
            $str .= $arr[1][$i];
        }
        return $str;
    }

}

/**
 * 删除空格
 * @param $str string 要格式化的字符串
 * @return string
 */
if ( ! function_exists('trimall')) {
    function trimall($str)
    {
        $qian = array(" ", "　", "\t", "\n", "\r");
        $hou = array("", "", "", "", "");
        return str_replace($qian, $hou, $str);
    }
}

/**
 * 数组转xml
 * @param $data array 要转换的数组
 * @return xml
 */
if(! function_exists('arrayToXml')){
    function arrayToXml($data){
        if(!is_array($data) || count($data) <= 0){
            return false;
        }
        $xml = "<xml>";
        foreach ($data as $key=>$val){
            if (is_numeric($val)){
                $xml.="<".$key.">".$val."</".$key.">";
            }else{
                $xml.="<".$key."><![CDATA[".$val."]]></".$key.">";
            }
        }
        $xml.="</xml>";
        return $xml;
    }
}

/**
 * xml转数组
 * @param $xml xml 要转换的xml实体
 * @param $isfile bool 传入的xml参数是否是文件名
 */
if(! function_exists('xmlToArray')){
    function xmlToArray($xml,$isfile=false){
        //禁止引用外部xml实体
        libxml_disable_entity_loader(true);
        if($isfile){
            if(!file_exists($xml)) return false;
            $xmlstr = file_get_contents($xml);
        }else{
            $xmlstr = $xml;
        }
        $result= json_decode(json_encode(simplexml_load_string($xmlstr, 'SimpleXMLElement', LIBXML_NOCDATA)), true);
        return $result;
    }
}

/**
 * xml文档转为数组元素
 * @param obj $xmlobject XML文档对象
 * @return array
 */
if ( ! function_exists('xml_to_array_element')) {
    function xml_to_array_element($xmlobject) {
        $data = array();
        foreach ((array) $xmlobject as $key => $value) {
            $data[$key] = !is_string($value) ? xml_to_array_element($value) : $value;
        }
        return $data;
    }
}

/**
 * 二维数组根据某个字段排序
 * @param array $array 要排序的数组
 * @param string $keys   要排序的键字段
 * @param string $sort  排序类型  SORT_ASC     SORT_DESC
 * @return array 排序后的数组
 */
if(! function_exists('array_sort')){
    function array_sort($array, $keys, $sort = SORT_DESC) {
        $keysValue = [];
        foreach ($array as $k => $v) {
            $keysValue[$k] = $v[$keys];
        }
        array_multisort($keysValue, $sort, $array);
        return $array;
    }
}

/**
 * 隐藏用户真实姓名
 *  两个字的隐藏后一个字
 *  三个字隐藏中间一个字
 *  四个字隐藏中间两个字
 * @return bool|string
 */
if ( ! function_exists('name_fuzz')) {
    function name_fuzz($str) {
        $strlen = mb_strlen($str);
        switch ($strlen) {
            case '2' :
                $data = mb_substr($str, 0, 1) . "*";
                break;
            case '3' :
                $data = mb_substr($str, 0, 1) . "*" . mb_substr($str, -1);
                break;
            case '4' :
                $data = mb_substr($str, 0, 1) . "**" . mb_substr($str, -1);
                break;
        }
        return isset($data) ? $data : false;
    }
}





	/* add by fxh 2016 03.12 写日志 文件以每天的日期后缀生成
	 * filename string  em:test  项目名称
	 * msg string 日志内容
 	 *日志文件会在网站根目录的log目录下生成 会先生成以当天日期命名的目录,再在此目录下生成文件 比如test-2016-05-26.log
 	 *
	 * */
if(!function_exists('set_log')){

	function set_log($filename,$msg){
		//error_reporting(E_ALL ^ E_NOTICE);
		$log_ext=date('Y-m-d');
		$log_path='./log/';
		$log_dir=$log_path.$log_ext.'/';

		if(!file_exists($log_dir)){
			@mkdir($log_dir);
			@chmod($log_dir,0777);

		}

		$log_file=$filename.'-'.$log_ext.'.log';

		$filepath=$log_dir.$log_file;
		//echo $filepath;

		$fp = fopen($filepath, 'a+');

		$msg.='  '.date('Y-m-d H:i:s')."  \n";
		//flock($fp, LOCK_EX);
		fwrite($fp, $msg);
		//flock($fp, LOCK_UN);
		fclose($fp);

		@chmod($filepath, FILE_WRITE_MODE);


	}

}

/**
 * @param $latitude 纬度
 * @param $longitude 经度
 * @param $precision //精密度, 默认是12
 */
if(!function_exists('get_geohash')){

	function get_geohash($latitude, $longitude,$precision=12){
		$geohash=geohash_encode($latitude, $longitude,$precision);

		$arr['0']['hash']=$geohash;// 根据参数 $latitude $longitude 得出的HASH
		$arr['9']=substr($geohash,0,9); //9位hash 距离最精确，
		$arr['8']=substr($geohash,0,8);//8位，距离相对精确，
		$arr['7']=substr($geohash,0,7);//7位，距离也挺精确
		$arr['6']=substr($geohash,0,6);
		$arr['5']=substr($geohash,0,5);
		$arr['4']=substr($geohash,0,4);//4位hash,只要前4位相同，可以找出附近20KM的人事物。

		return $arr;
	}


}

/**
 * @param $key int
 * return 返回$key对应的数字
 *
*/
if(!function_exists('geohash_num')){
	function geohash_num($key){

		$arr['9']='10';
		$arr['8']='20';
		$arr['7']='80';
		$arr['6']='610';
		$arr['5']='2400';
		$arr['4']='20000';
		$arr['3']='78000';
		$arr['2']='630000';
		$arr['1']='2500000';

//		foreach($arr as $k=>&$v){
//			if($unit==1){
//
//				if(($v/1000)>1){
//					$v=round(($v/1000),2).'公里';
//				}else{
//
//					$v.='米';
//				}
//
//			}else{
//				if(($v/1000)>1){
//					$v=round(($v/1000),2).'km';
//				}else{
//
//					$v.='m';
//				}
//
//
//			}
//
//		}


		if(array_key_exists($key,$arr)){
			return $arr[$key];

		}

		return $arr[1];
	}

}

/**
 * @param $val
 *
 */
if(!function_exists('get_lbs_unit')){
	function get_lbs_unit($val){
		if(isset($val['lbs'])){

				if(($val['lbs']/1000)>1){
					$val['lbs']=round(($val['lbs']/1000),2).'公里';

				}else{
					$val['lbs'].='米';
				}
		}

		return $val;
	}


}


if(!function_exists('cmp_geohash')){
	function cmp_geohash($str1,$str2){

		//strncmp
		//$arr=[];
		for($i=9;$i>=1;$i--){
			if(strncmp($str1,$str2,$i)===0){
				
				return geohash_num($i);

			}


		}


	}


}

/**
 * 从一段文本内容中获取img图片的src地址
 * @param $content 文本内容
 *
*/
if(!function_exists('get_img_url')){
	function get_img_url($content){
		$soImages='/<img[^>]*>/';
		preg_match_all( $soImages,$content,$thePics);

		$allPics = count($thePics[0]);

		if( $allPics> 0 ){
			preg_match('/<img.+src=\"?(.+\.(jpg|gif|bmp|bnp|png))\"?.+>/i',$thePics[0][0],$match);
			if($match){
				return $match[1];

			}
		}
		else {
			//echo "没有图片"; // 设置默认图片
			return DEFAULT_IMG;

		}



	}


}



/**
 * 加密方法
 * @param string $str
 * @return string
 */
if(!function_exists('encrypt')){
	function encrypt($str){
		//AES, 128 模式加密数据 CBC

		$str = trim($str);
		$str = addPKCS7Padding($str);

		$encrypt_str =  mcrypt_encrypt(MCRYPT_RIJNDAEL_128, AESKEY, $str, MCRYPT_MODE_CBC,AESKEY);
		return base64_encode($encrypt_str);
	}

}


/**
 * 解密方法
 * @param string $str
 * @return string
 */
if(!function_exists('decrypt')){
	function decrypt($str){
		//AES, 128 模式加密数据 CBC
		$str = base64_decode($str);

		$encrypt_str =  mcrypt_decrypt(MCRYPT_RIJNDAEL_128, AESKEY, $str, MCRYPT_MODE_CBC,AESKEY);
		$encrypt_str = trim($encrypt_str);

		//$encrypt_str = stripPKSC7Padding($encrypt_str);
		return $encrypt_str;

	}
}


/**
 * 填充算法
 * @param string $source
 * @return string
 */
function addPKCS7Padding($source){
	$source = trim($source);
	$block = mcrypt_get_block_size(MCRYPT_RIJNDAEL_128, MCRYPT_MODE_CBC);

	$pad = $block - (strlen($source) % $block);
	if ($pad <= $block) {
		$char = chr($pad);
		$source .= str_repeat($char, $pad);
	}
	return $source;
}
/**
 * 移去填充算法
 * @param string $source
 * @return string
 */
function stripPKSC7Padding($source){
	$source = trim($source);
	$char = substr($source, -1);
	$num = ord($char);
	if($num==62)return $source;
	$source = substr($source,0,-$num);
	return $source;
}



/*
    批量处理gbk->utf-8
*/
if(!function_exists('icon_to_utf8')){


	function icon_to_utf8($s) {
		if(is_array($s)) {
			foreach($s as $key => $val) {
				$s[$key] = icon_to_utf8($val);
			}
		} else {
			$s = ct2($s);
		}
		return $s;
	}


}

/*
    字符串GBK转码为UTF-8，数字转换为数字。
*/
if(!function_exists('ct2')){

	function ct2($s){
		if(is_numeric($s)) {
			return intval($s);
		} else {
			return iconv("GBK","UTF-8",$s);
		}
	}


}

if(!function_exists('arr_encrypt')){
	function arr_encrypt($u_infos=[]){
//		if(!isset($u_infos['time'])){
//			$u_infos['time']=time();
//		}

		$str=json_encode($u_infos);

		$res=aes_encrypt($str);
		return $res;
	}

}

/* 根据文件名称获取文件名和文件后缀
 * @param $file  string xxx.jpg
 * return array  ['name'=>'xxx', 'ext'=>'.jpg']
 * */
if(!function_exists('_get_file_info')){
	function _get_file_info($file){
		$data['ext']=strrchr($file,'.');  // 获取文件扩展名 .jpg .png .jpeg .gif
		$data['name']=substr($file, 0,strrpos($file,'.'));

		return $data;
	}


}

/*
 *  生成随机字符串
 * @param $len int
 * return string
 * */

if(!function_exists('get_rand_str')){
	function get_rand_str($len=8){
		$_str='0123456789abcdefghijklmnopqrstABCDEFGHIJKLMNOPQRST';

		$str='';
		for($i=0;$i<$len;$i++){
			$str.=$_str[mt_rand(0,(strlen($_str)-1))];

		}

		return $str;


	}


}

if(!function_exists('get_notify_txt')){
	function get_notify_txt($key){
		$ci=&get_instance();
		$ci->config->load('notify',true);
		$notify=$ci->config->item('notify');
		//var_dump($notify);
		return $notify['notify'][$key];

	}

}

if(!function_exists('get_label')){
	function get_label($key,$val,$model){
		$CI=&get_instance();
		$CI->load->model($model);

		$_arr=explode('/',$model);
		$_model=array_pop($_arr);

		$setting=$CI->{$_model}->setting[$key];

		if(array_key_exists($val,$setting)){

			return $setting[$val];

		}

	}

}

if(!function_exists('getDistance')){
    /**
     *  @desc 根据两点间的经纬度计算距离
     *  @param float $lat 纬度值
     *  @param float $lng 经度值
     */
    function getDistance($lat1, $lng1, $lat2, $lng2)
    {
        $earthRadius = 6367000; //approximate radius of earth in meters

        /*
          Convert these degrees to radians
          to work with the formula
        */

        $lat1 = ($lat1 * pi() ) / 180;
        $lng1 = ($lng1 * pi() ) / 180;

        $lat2 = ($lat2 * pi() ) / 180;
        $lng2 = ($lng2 * pi() ) / 180;

        /*
          Using the
          Haversine formula

          http://en.wikipedia.org/wiki/Haversine_formula

          calculate the distance
        */

        $calcLongitude = $lng2 - $lng1;
        $calcLatitude = $lat2 - $lat1;
        $stepOne = pow(sin($calcLatitude / 2), 2) + cos($lat1) * cos($lat2) * pow(sin($calcLongitude / 2), 2);
        $stepTwo = 2 * asin(min(1, sqrt($stepOne)));
        $calculatedDistance = $earthRadius * $stepTwo;

        return round($calculatedDistance);
    }


}



if(!function_exists('returnSquarePoint')){

    /**
     *计算某个经纬度的周围某段距离的正方形的四个点
     *
     *@param log float 经度
     *@param lat float 纬度
     *@param distance float 该点所在圆的半径，该圆与此正方形内切，默认值为5千米
     *@return array 正方形的四个点的经纬度坐标
     */
    function returnSquarePoint($log, $lat,$distance = 3){

        $earthRadius=6371; //地球半径，平均半径为6371km
        $dlng =  2 * asin(sin($distance / (2 * $earthRadius)) / cos(@deg2rad($lat)));
        $dlng = rad2deg($dlng);

        $dlat = $distance/$earthRadius;
        $dlat = rad2deg($dlat);

        return array(
            'left-top'=>array('lat'=>$lat + $dlat,'lon'=>$log-$dlng),
            'right-top'=>array('lat'=>$lat + $dlat, 'lon'=>$log + $dlng),
            'left-bottom'=>array('lat'=>$lat - $dlat, 'lon'=>$log - $dlng),
            'right-bottom'=>array('lat'=>$lat - $dlat, 'lon'=>$log + $dlng)
        );
    }


    /*
     *
$info_sql = " lat>{$squares['right-bottom']['lat']}
and
 lat<{$squares['left-top']['lat']} and
 lng>{$squares['left-top']['lng']} and
 lng<{$squares['right-bottom']['lng']} ";
     * */

}



if(!function_exists('formatMoney')) {
    /**
     *   单位转换（保留2位小数，不做四舍五入处理）
     *   必须要大于限制金额，要不就返回0
     */
    function formatMoney($money,$limit=0)
    {
        if ($money >= $limit) {
            $money = sprintf("%.2f", $money);
        }else{
            return 0;
        }
        $money = floatval($money);
        return $money;
    }
}

/**
 * 银行卡号归属地(所属银行)和对应code查询
 *   ps:该函数只校验卡号BIN代码是否匹配，不进行卡号长度合规性校验
 * @param string $bank_card 银行卡号
 * @return bool|array
 */
if ( ! function_exists('get_bank_code_by_card')) {
    function get_bank_code_by_card($bank_card) {
        $bank_card = str_replace(array('-', ' '),'',$bank_card);
        $ci=&get_instance();
		$ci->config->load('bankList',true);
        $bankList = $ci->config->item('bank_config','bankList');

		$result=false;
		foreach(array(8,6,5,4) as $n) {
			$tmp = substr($bank_card, 0, $n);
			if (isset($bankList[$tmp])) {
				$result = $bankList[$tmp];
				break;
			}
		}
		return $result;
    }
}



/**
 * 银行卡号归属地(所属银行)和对应code查询
 *   ps:该函数只校验卡号BIN代码是否匹配，不进行卡号长度合规性校验
 * @return array
 */
if ( ! function_exists('get_bank_code_list')) {
    function get_bank_code_list() {
        $ci=&get_instance();
		$ci->config->load('bankList',true);
        $bankList = $ci->config->item('bank_config','bankList');
        is_array($bankList) || $bankList = [];

        $code_name = [];
        foreach ($bankList as $key => $value) {
        	$code_name[$value[1]] = $value[0];
        }
		return $code_name;
    }
}


/**
 * 生成全球唯一标识符(GUID)
 */
if(! function_exists('get_guid')){
    function get_guid() {
        $charid = strtoupper(md5(uniqid(mt_rand(), true)));
        $hyphen = chr(45);
        $uuid = substr($charid, 0, 8).$hyphen
        .substr($charid, 8, 4).$hyphen
        .substr($charid,12, 4).$hyphen
        .substr($charid,16, 4).$hyphen
        .substr($charid,20,12);
        return $uuid;
    }
}

/**
 * 对唯一标识符进行截断(GUID)
 */
if(! function_exists('get_shot_guid')){
    function get_shot_guid($guid) {
        $guid_array = explode('-', $guid);
        return end($guid_array);
    }
}

/**
 * sha512加密
 */
if(! function_exists('sha512')){
    function sha512($data, $rawOutput = false){
        if (!is_scalar($data)) {
            return false;
        }
        $data = (string)$data;
        $rawOutput = !!$rawOutput;
        return hash('sha512', $data, $rawOutput);
    }
}