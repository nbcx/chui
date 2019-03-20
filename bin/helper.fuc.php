<?php
/**
 * 截取中英混排字符串
 *
 * @param $string
 * @param $length
 * @return string
 */
function sb_substr($string, $length) {
    $slen = str_len($string);
    if ($slen <= $length) {
        return $string;
    }
    $returnstr = '';
    $i = 0;
    $n = 0;
    $str_length = strlen($string); //字符串的字节数
    while (($n < $length) and ($i <= $str_length)) {
        $temp_str = substr($string, $i, 1);
        $ascnum = Ord($temp_str); //得到字符串中第$i位字符的ascii码
        //如果ASCII位高与224，
        if ($ascnum >= 224)  {
            $returnstr = $returnstr . substr($string, $i, 3); //根据UTF-8编码规范，将3个连续的字符计为单个字符
            $i = $i + 3; //实际Byte计为3
            $n++; //字串长度计1
        }
        //如果ASCII位高与192，
        elseif ($ascnum >= 192) {
            $returnstr = $returnstr . substr($string, $i, 2); //根据UTF-8编码规范，将2个连续的字符计为单个字符
            $i = $i + 2; //实际Byte计为2
            $n++; //字串长度计1
        }
        //如果是大写字母，
        elseif ($ascnum >= 65 && $ascnum <= 90)  {
            $returnstr = $returnstr . substr($string, $i, 1);
            $i = $i + 1; //实际的Byte数仍计1个
            $n++; //但考虑整体美观，大写字母计成一个高位字符
        }
        //其他情况下，包括小写字母和半角标点符号，
        else  {
            $returnstr = $returnstr . substr($string, $i, 1);
            $i = $i + 1; //实际的Byte数计1个
            $n = $n + 0.5; //小写字母和半角标点等与半个高位字符宽...
        }
    }
    if ($str_length > $length) {
        $returnstr = $returnstr . "..."; //超过长度时在尾处加上省略号
    }
    return $returnstr;
}

/**
 * 清除HTML标记
 *
 * @param    string $str
 * @return  string
 */
function cleanhtml($str) {
    $str = strip_tags($str);
    $str = htmlspecialchars($str);
    $str = preg_replace("/\s+/", " ", $str); //过滤多余回车
    $str = preg_replace("/ /", "", $str);
    $str = preg_replace("/&nbsp;/", "", $str);
    $str = preg_replace("/　/", "", $str);
    $str = preg_replace("/\r\n/", "", $str);
    $str = str_replace(chr(13), "", $str);
    $str = str_replace(chr(10), "", $str);
    $str = str_replace(chr(9), "", $str);
    return $str;
}

//无编辑器的过滤
function filter_check($str) {

    $pattern = "/<pre[^>]*>(.*?)<\/pre>/si";
    preg_match_all($pattern, $str, $matches);
    $str = htmlspecialchars_decode($str);
    $str = stripslashes($str);
    if ($matches[1]) {
        foreach ($matches[1] as $v) {
            $replace[] = addslashes(htmlspecialchars(trim($v)));
        }
        $str = str_replace($matches[1], $replace, $str);
    }
    else {
        $str = strip_tags($str, "<img> <pre> <a> <font> <span> <em>");
    }
    $str = nl2br($str);

    return $str;
}

//过滤
function filter_code($str) {
    $str = htmlspecialchars_decode($str);
    $pattern = "/<pre[^>]*>(.*?)<\/pre>/si";
    preg_match_all($pattern, $str, $matches);
    if ($matches[1]) {
        foreach ($matches[1] as $v) {
            $replace = trim(htmlentities($v));
            $str = str_replace($v, $replace, $str);
        }
        $str = strip_tags($str, "<img> <pre> <a> <font> <span> <em> <p> <b>");
    }
    else {
        $str = strip_tags($str, "<img> <pre> <a> <font> <span> <em> <p> <b>");
        $str = trim(nl2br($str));
    }
    return $str;
}

function auto_link_pic($str, $type = 'both', $popup = false) {
    if ($type != 'email') {
        if (preg_match_all("#(^|\s|\()((http(s?)://)|(www\.))(\w+[^\s\)\<]+)#i", $str, $matches)) {
            $pop = ($popup == true) ? " target=\"_blank\" " : "";

            for ($i = 0; $i < count($matches['0']); $i++) {
                $period = '';
                if (preg_match("|\.$|", $matches['6'][$i])) {
                    $period = '.';
                    $matches['6'][$i] = substr($matches['6'][$i], 0, -1);
                }
                $img_ext = ['jpg', 'png', 'gif', 'jpeg'];
                $file_ext = strtolower(end(explode(".", $matches['0'][$i])));
                if (in_array($file_ext, $img_ext)) {
                    $str = str_replace($matches['0'][$i],
                        $matches['1'][$i] . '<img src="http' .
                        $matches['4'][$i] . '://' .
                        $matches['5'][$i] .
                        $matches['6'][$i] . '" alt="">' .
                        $period, $str);
                }
                else {
                    $str = str_replace($matches['0'][$i],
                        $matches['1'][$i] . '<a href="http' .
                        $matches['4'][$i] . '://' .
                        $matches['5'][$i] .
                        $matches['6'][$i] . '"' . $pop . '>http' .
                        $matches['4'][$i] . '://' .
                        $matches['5'][$i] .
                        $matches['6'][$i] . '</a>' .
                        $period, $str);
                }
            }
        }
    }

    if ($type != 'url') {
        if (preg_match_all("/([a-zA-Z0-9_\.\-\+]+)@([a-zA-Z0-9\-]+)\.([a-zA-Z0-9\-\.]*)/i", $str, $matches)) {
            for ($i = 0; $i < count($matches['0']); $i++) {
                $period = '';
                if (preg_match("|\.$|", $matches['3'][$i])) {
                    $period = '.';
                    $matches['3'][$i] = substr($matches['3'][$i], 0, -1);
                }

                $str = str_replace($matches['0'][$i], safe_mailto($matches['1'][$i] . '@' . $matches['2'][$i] . '.' . $matches['3'][$i]) . $period, $str);
            }
        }
    }

    return $str;
}


function br2nl($text) {
    return preg_replace('/<br\\s*?\/??>/i', '', $text);
}

function xss_clean($input_str) {
    $return_str = str_replace(array('<', '>', "'", '"', ')', '('), array('&lt;', '&gt;', '&apos;', '&#x22;', '&#x29;', '&#x28;'), $input_str);
    $return_str = str_ireplace('%3Cscript', '', $return_str);
    return $return_str;
}

function xss_clean3($str) {

    if (isset($str)) {
        $str = trim($str);  //清理空格
        $str = strip_tags($str);   //过滤html标签
        $str = htmlspecialchars($str);   //将字符内容转化为html实体
        $str = addslashes($str);
        return $str;
    }
}

/*
 * XSS filter 
 *
 * This was built from numerous sources
 * (thanks all, sorry I didn't track to credit you)
 */
function xss_clean1($data) {
    // Fix &entity\n;
    $data = str_replace(array('&amp;', '&lt;', '&gt;'), array('&amp;amp;', '&amp;lt;', '&amp;gt;'), $data);
    $data = preg_replace('/(&#*\w+)[\x00-\x20]+;/u', '$1;', $data);
    $data = preg_replace('/(&#x*[0-9A-F]+);*/iu', '$1;', $data);
    $data = html_entity_decode($data, ENT_COMPAT, 'UTF-8');

    // Remove any attribute starting with "on" or xmlns
    $data = preg_replace('#(<[^>]+?[\x00-\x20"\'])(?:on|xmlns)[^>]*+>#iu', '$1>', $data);

    // Remove javascript: and vbscript: protocols
    $data = preg_replace('#([a-z]*)[\x00-\x20]*=[\x00-\x20]*([`\'"]*)[\x00-\x20]*j[\x00-\x20]*a[\x00-\x20]*v[\x00-\x20]*a[\x00-\x20]*s[\x00-\x20]*c[\x00-\x20]*r[\x00-\x20]*i[\x00-\x20]*p[\x00-\x20]*t[\x00-\x20]*:#iu', '$1=$2nojavascript...', $data);
    $data = preg_replace('#([a-z]*)[\x00-\x20]*=([\'"]*)[\x00-\x20]*v[\x00-\x20]*b[\x00-\x20]*s[\x00-\x20]*c[\x00-\x20]*r[\x00-\x20]*i[\x00-\x20]*p[\x00-\x20]*t[\x00-\x20]*:#iu', '$1=$2novbscript...', $data);
    $data = preg_replace('#([a-z]*)[\x00-\x20]*=([\'"]*)[\x00-\x20]*-moz-binding[\x00-\x20]*:#u', '$1=$2nomozbinding...', $data);

    // Only works in IE: <span style="width: expression(alert('Ping!'));"></span>
    $data = preg_replace('#(<[^>]+?)style[\x00-\x20]*=[\x00-\x20]*[`\'"]*.*?expression[\x00-\x20]*\([^>]*+>#i', '$1>', $data);
    $data = preg_replace('#(<[^>]+?)style[\x00-\x20]*=[\x00-\x20]*[`\'"]*.*?behaviour[\x00-\x20]*\([^>]*+>#i', '$1>', $data);
    $data = preg_replace('#(<[^>]+?)style[\x00-\x20]*=[\x00-\x20]*[`\'"]*.*?s[\x00-\x20]*c[\x00-\x20]*r[\x00-\x20]*i[\x00-\x20]*p[\x00-\x20]*t[\x00-\x20]*:*[^>]*+>#iu', '$1>', $data);

    // Remove namespaced elements (we do not need them)
    $data = preg_replace('#</*\w+:\w[^>]*+>#i', '', $data);

    do {
        // Remove really unwanted tags
        $old_data = $data;
        $data = preg_replace('#</*(?:applet|b(?:ase|gsound|link)|embed|frame(?:set)?|i(?:frame|layer)|l(?:ayer|ink)|meta|object|s(?:cript|tyle)|title|xml)[^>]*+>#i', '', $data);
    }
    while ($old_data !== $data);

    // we are done...
    return $data;
}


/*生成盐salt*/
function get_salt($length = -6) {
    return substr(uniqid(rand()), $length);
}

/*生成密码*/
function password_dohash($password, $salt) {
    $salt = $salt ? $salt : get_salt();
    return md5(md5($password) . $salt);
}

/*补全代码*/
function closetags($html) {
    // 不需要补全的标签
    $arr_single_tags = ['meta', 'img', 'br', 'link', 'area'];
    // 匹配开始标签
    preg_match_all('#<([a-z]+)(?: .*)?(?<![/|/ ])>#iU', $html, $result);
    $openedtags = $result[1];
    // 匹配关闭标签
    preg_match_all('#</([a-z]+)>#iU', $html, $result);
    $closedtags = $result[1];
    // 计算关闭开启标签数量，如果相同就返回html数据
    $len_opened = count($openedtags);
    if (count($closedtags) == $len_opened) {
        return $html;
    }
    // 把排序数组，将最后一个开启的标签放在最前面
    $openedtags = array_reverse($openedtags);
    // 遍历开启标签数组
    for ($i = 0; $i < $len_opened; $i++) {
        // 如果需要补全的标签
        if (!in_array($openedtags[$i], $arr_single_tags)) {
            // 如果这个标签不在关闭的标签中
            if (!in_array($openedtags[$i], $closedtags)) {
                // 直接补全闭合标签
                $html .= '</' . $openedtags[$i] . '>';
            }
            else {
                unset($closedtags[array_search($openedtags[$i], $closedtags)]);
            }
        }
    }
    return $html;
}

function strip_url_tags($str) {
    $str = preg_replace("/<a[^>]*href=[^>]*>|<\/[^a]*a[^>]*>/i", "\\2", $str);
    return $str;
}

function decode_format($content) {
    $content = strip_url_tags(strip_image_tags($content));
    return $content;
}

function strip_image_tags($str) {
    $str = preg_replace("#<img\s+.*?src\s*=\s*[\"'](.+?)[\"'].*?\>#", "\\1", $str);
    $str = preg_replace("#<img\s+.*?src\s*=\s*(.+?).*?\>#", "\\1", $str);

    return $str;
}

function get_tree_array(&$data, $parentId = 0) {
    $category = [];
    foreach ($data as $key => $value) {
        if ($value['pid'] == $parentId) {
            unset($data[$key]);
            $value['child'] = category($data, $value['id']);
            $category[] = $value;
        }
    }
    return $category;
}

function get_tree(&$data, $parentId = 0) {
    global $str;
    $str .= '<ul>';
    foreach ($data as $key => $value) {
        if ($value['pid'] == $parentId) {
            unset($data[$key]);
            $str .= "<li>|--<a href='/'>" . $value['name'] . '</a></li>';
            get_tree($data, $value['id']);
        }
    }
    $str .= '</ul>';
    return $str;
}


//生成友好时间形式
function friendly_date($from) {
    static $now = NULL;
    $now == NULL && $now = time();
    !is_numeric($from) && $from = strtotime($from);
    $seconds = $now - $from;
    $minutes = floor($seconds / 60);
    $hours = floor($seconds / 3600);
    $day = round((strtotime(date('Y-m-d', $now)) - strtotime(date('Y-m-d', $from))) / 86400);
    if ($seconds == 0) {
        return '刚刚';
    }
    if (($seconds >= 0) && ($seconds <= 60)) {
        return "{$seconds}秒前";
    }
    if (($minutes >= 0) && ($minutes <= 60)) {
        return "{$minutes}分钟前";
    }
    if (($hours >= 0) && ($hours <= 24)) {
        return "{$hours}小时前";
    }
    if ((date('Y') - date('Y', $from)) > 0) {
        return date('Y-m-d', $from);
    }

    switch ($day) {
        case 0:
            return date('今天H:i', $from);
            break;

        case 1:
            return date('昨天H:i', $from);
            break;

        default:
            //$day += 1;
            return "{$day} 天前";
            break;
    }
}

function str_len($str) {
    $length = strlen(preg_replace('/[\x00-\x7F]/', '', $str));

    if ($length) {
        return strlen($str) - $length + intval($length / 3) * 2;
    }
    else {
        return strlen($str);
    }
}


function is_today($time) {
    $date = date('Y-m-d', $time);
    $today = date('Y-m-d');
    if ($date == $today) {
        return true;
    }
    else {
        return false;
    }
}
