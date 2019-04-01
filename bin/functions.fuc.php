<?php
/**
 * 快速登陆一个用户，方便插件里使用
 * 登陆成功将返回用户详细信息
 * 失败返回false
 *
 * @param $usrname|user
 * @return bool|\model\User
 */
function login($user) {
    if(!is_object($user)) {
        $user = \model\User::find('username=?',$user);
        if($user->empty) {
            return false;
        }
    }
    //生产一个登录token，存放数据库
    //\nb\Cookie::set('_user',$user->uid);
    $token = md5($user->id+'u'+time());
    //更新最后登录时间
    $data['lastlogin'] = time();
    $data['token'] = $token;
    $rows =  \model\User::updateId($user->id,$data);
    return $rows?$token:false;
}

function logout() {
    //unset($_SESSION);
    \nb\Cookie::clear();
    return true;
}

/**
 * 快速注册一个用户，方便插件里调用
 * @param $user
 * @param $pass
 * @param $email
 * @param int $is_active
 * @return bool|\model\User
 */
function register($user,$pass,$email,$group_type=2,$gid=3,$is_active=1,$ip='127.0.0.1') {
    $salt = get_salt();
    $conf = \model\System::init();
    $data = [
        'username' => strip_tags($user),
        'password' => password_dohash($pass,$salt),
        'salt' => $salt,
        'mail' => $email,
        'credit' => $conf->credit_start,
        'ip' => $ip,
        //'group_type' => $group_type,
        'gid' => $gid,
        'ct' => $conf->timestamp,
        'is_active' => $is_active
    ];
    $id = \model\User::insert($data);
    return $id?\model\User::findId($id):false;
}

/**
 * 判断指定主题的模板文件是否存在
 * 不指定主题，则判断当前正在使用的主题模版文件是否存在
 *
 * @param $file
 * @param null $theme
 * @return bool 成功返回完整文件路径，石板返回false
 */
function theme_file_exist($file,$theme=null) {
    $system = \model\System::init();
    $themePath = \bin\Config::$o->themePath;
    if($theme) {
        $file = $themePath.$theme.DS.$file;
        return is_file($file)?$file:false;
    }
    //$theme = \model\System::init()->theme->folder;
    $file = $themePath.$system->themes.DS.$file;
    b('b',$file);
    return is_file($file)?$file:false;
}


//大小写不敏感的array_diff
function tagsDiff($arr1, $arr2) {
    $conf = \model\System::init();
    $arr2 = array_change_key_case(array_flip($arr2), CASE_LOWER); //flip，排重，Key有Hash索引，速度更快
    foreach ($arr1 as $Key => $Item) {
        if (mb_strlen($Item, "UTF-8") > $conf["max_tag_chars"] || isset($arr2[strtolower(trim($Item))]) || strpos("|", $Item) || !preg_match('/^[a-zA-Z0-9\x80-\xff\-_\s]{1,' . $conf["max_tag_chars"] . '}$/i', $Item) || $Item != filter($Item)['content']) {
            unset($arr1[$Key]);
        }
        else {
            $arr1[$Key] = htmlspecialchars(trim($arr1[$Key])); //XSS
        }
    }
    sort($arr1);
    return $arr1;
}


//关键词过滤
function filter($content) {
    $filtering = load('words');
    $prohibited = false;
    $gag_time = 0;
    foreach ($filtering as $searchRegEx => $rule) {

        if (preg_match_all("/" . $searchRegEx . "/i", $content, $search_words_list)) {
            //var_dump($SearchWordsList);
            foreach ($search_words_list as $search_word) {
                if (is_array($rule)) {
                    $content = str_ireplace($search_word, $rule[0], $content);
                    $prohibited |= ($rule[0] === false);
                    $gag_time = ($rule[1] > $gag_time) ? $rule[1] : $gag_time; //将规则中封禁时间最长的一个赏给用户
                }
                else {
                    $content = str_ireplace($search_word, $rule, $content);
                    //$Prohibited |= false;
                    //$GagTime = 0;
                }
            }
        }
    }
    return [
        'content' => $content, //过滤后的内容
        'prohibited' => $prohibited, //是否包含有禁止发布的词
        'gag_time' => $gag_time //赏赐给用户的禁言时间（秒）
    ];
}


// 去除注释的JsonDecode
function json2Arr($json) {
    return json_decode(
        preg_replace("/\/\*[\s\S]+?\*\//", "", $json),
        true
    );
}


/**
 * 发送电子邮件
 *
 * @param $address 接收地址
 * @param $subject 邮件主题
 * @param $body 邮件内容
 *
 * @return bool
 * 成功返回false
 * 失败返回错误信息
 */
function sendmail($address,$config=null) {

    /**
     * 之后可以在数据库里读取
     */
    $server = \nb\Config::$o->mail;

    $config['name'] = isset($config['name'])?$config['name']:$server['name'];

    $conf = [
        'subject' => 'NullBB邮箱验证码!',
        'altBody'=>'NullBB! 官方团队'
    ];

    $conf = array_merge($conf,$config);

    $mail = new \util\PHPMailer();
    $mail->isSMTP();
    $mail->Host = $server['host'];
    $mail->SMTPAuth = true;
    $mail->Username = $server['username'];
    $mail->Password = $server['password'];
    $mail->SMTPSecure = 'tls';
    $mail->Port = $server['port'];
    $mail->CharSet='UTF-8';
    $mail->setFrom($server['from'], $config['name']);
    $mail->addAddress($address);
    $mail->isHTML(true);
    $mail->Subject = $conf['subject'];
    $mail->AltBody = $conf['altBody']?:$conf['subject'];

    if( isset($conf['body']) ) {
        $body = $conf['body'];
    }
    else if( isset($conf['tbl']) ) {
        $body = fetchView($conf['tpl'],$config);
    }
    else {
        $tpl = theme_file_exist('other'.DS.'tips'.DS.'mail');
        $tpl = $tpl?:__APP__.\nb\Config::$o->app.DS.'view'.DS.'tips'.DS.'mail.php';
        $body = fetchView($tpl,$config);
    }
    $mail->Body  = $body;

    if($mail->send()) {
        b('sendmail',"[{$address}]发送成功");
        return false;
    }
    $error = $mail->ErrorInfo;
    b('sendmail',"[{$address}]{$error}");
    return $error?:'邮件发送失败';
}

function fetchView($tbl,$arr=null) {
    ob_start();
    if($arr) {
        extract($arr);
    }
    include $tbl;
    $buffer = ob_get_contents();
    ob_end_clean();
    return $buffer;
}

function code($type='number') {
    return rand(1000,9999);
}

///////////////////////////////////////////////////////
function url2($url) {
    return '/' . $url;
}

function is_php($version = '5.0.0') {
    static $_is_php;
    $version = (string)$version;

    if (!isset($_is_php[$version])) {
        $_is_php[$version] = (version_compare(PHP_VERSION, $version) < 0) ? FALSE : TRUE;
    }

    return $_is_php[$version];
}

// ------------------------------------------------------------------------

/**
 * Tests for file writability
 *
 * is_writable() returns TRUE on Windows servers when you really can't write to
 * the file, based on the read-only attribute.  is_writable() is also unreliable
 * on Unix servers if safe_mode is on.
 *
 * @access    private
 * @return    void
 */
function is_really_writable($file) {
    // If we're on a Unix server with safe_mode off we call is_writable
    if (DIRECTORY_SEPARATOR == '/' AND @ini_get("safe_mode") == FALSE) {
        return is_writable($file);
    }

    // For windows servers and safe_mode "on" installations we'll actually
    // write a file then read it.  Bah...
    if (is_dir($file)) {
        $file = rtrim($file, '/') . '/' . md5(mt_rand(1, 100) . mt_rand(1, 100));

        if (($fp = @fopen($file, FOPEN_WRITE_CREATE)) === FALSE) {
            return FALSE;
        }

        fclose($fp);
        @chmod($file, DIR_WRITE_MODE);
        @unlink($file);
        return TRUE;
    }
    elseif (!is_file($file) OR ($fp = @fopen($file, FOPEN_WRITE_CREATE)) === FALSE) {
        return FALSE;
    }

    fclose($fp);
    return TRUE;
}

// ------------------------------------------------------------------------



// --------------------------------------------------------------------


// ------------------------------------------------------------------------


// ------------------------------------------------------------------------

/**
 * Error Handler
 *
 * This function lets us invoke the exception class and
 * display errors using the standard error template located
 * in application/errors/errors.php
 * This function will send the error page directly to the
 * browser and exit.
 *
 * @access    public
 * @return    void
 */
function show_error($message, $status_code = 500, $heading = 'An Error Was Encountered') {
    ed($message);
    $_error =& load_class('Exceptions', 'core');
    echo $_error->show_error($heading, $message, 'error_general', $status_code);
    exit;
}

// ------------------------------------------------------------------------

/**
 * 404 Page Handler
 *
 * This function is similar to the show_error() function above
 * However, instead of the standard error template it displays
 * 404 errors.
 *
 * @access    public
 * @return    void
 */
function show_404($page = '', $log_error = TRUE) {
    $_error =& load_class('Exceptions', 'core');
    $_error->show_404($page, $log_error);
    exit;
}

// ------------------------------------------------------------------------

/**
 * Error Logging Interface
 *
 * We use this as a simple mechanism to access the logging
 * class and send messages to be logged.
 *
 * @access    public
 * @return    void
 */
function log_message($level = 'error', $message, $php_error = FALSE) {
    static $_log;

    if (config_item('log_threshold') == 0) {
        return;
    }

    $_log =& load_class('Log');
    $_log->write_log($level, $message, $php_error);
}

// ------------------------------------------------------------------------

/**
 * Set HTTP Status Header
 *
 * @access    public
 * @param    int        the status code
 * @param    string
 * @return    void
 */
function set_status_header($code = 200, $text = '') {
    $stati = array(
        200 => 'OK',
        201 => 'Created',
        202 => 'Accepted',
        203 => 'Non-Authoritative Information',
        204 => 'No Content',
        205 => 'Reset Content',
        206 => 'Partial Content',

        300 => 'Multiple Choices',
        301 => 'Moved Permanently',
        302 => 'Found',
        304 => 'Not Modified',
        305 => 'Use Proxy',
        307 => 'Temporary Redirect',

        400 => 'Bad Request',
        401 => 'Unauthorized',
        403 => 'Forbidden',
        404 => 'Not Found',
        405 => 'Method Not Allowed',
        406 => 'Not Acceptable',
        407 => 'Proxy Authentication Required',
        408 => 'Request Timeout',
        409 => 'Conflict',
        410 => 'Gone',
        411 => 'Length Required',
        412 => 'Precondition Failed',
        413 => 'Request Entity Too Large',
        414 => 'Request-URI Too Long',
        415 => 'Unsupported Media Type',
        416 => 'Requested Range Not Satisfiable',
        417 => 'Expectation Failed',

        500 => 'Internal Server Error',
        501 => 'Not Implemented',
        502 => 'Bad Gateway',
        503 => 'Service Unavailable',
        504 => 'Gateway Timeout',
        505 => 'HTTP Version Not Supported'
    );

    if ($code == '' OR !is_numeric($code)) {
        show_error('Status codes must be numeric', 500);
    }

    if (isset($stati[$code]) AND $text == '') {
        $text = $stati[$code];
    }

    if ($text == '') {
        show_error('No status text available.  Please check your status code number or supply your own message text.', 500);
    }

    $server_protocol = (isset($_SERVER['SERVER_PROTOCOL'])) ? $_SERVER['SERVER_PROTOCOL'] : FALSE;

    if (substr(php_sapi_name(), 0, 3) == 'cgi') {
        header("Status: {$code} {$text}", TRUE);
    }
    elseif ($server_protocol == 'HTTP/1.1' OR $server_protocol == 'HTTP/1.0') {
        header($server_protocol . " {$code} {$text}", TRUE, $code);
    }
    else {
        header("HTTP/1.1 {$code} {$text}", TRUE, $code);
    }
}


// --------------------------------------------------------------------

/**
 * Remove Invisible Characters
 *
 * This prevents sandwiching null characters
 * between ascii characters, like Java\0script.
 *
 * @access    public
 * @param    string
 * @return    string
 */
function remove_invisible_characters($str, $url_encoded = TRUE) {
    $non_displayables = array();

    // every control character except newline (dec 10)
    // carriage return (dec 13), and horizontal tab (dec 09)

    if ($url_encoded) {
        $non_displayables[] = '/%0[0-8bcef]/';    // url encoded 00-08, 11, 12, 14, 15
        $non_displayables[] = '/%1[0-9a-f]/';    // url encoded 16-31
    }

    $non_displayables[] = '/[\x00-\x08\x0B\x0C\x0E-\x1F\x7F]+/S';    // 00-08, 11, 12, 14-31, 127

    do {
        $str = preg_replace($non_displayables, '', $str, -1, $count);
    }
    while ($count);

    return $str;
}

// ------------------------------------------------------------------------

/**
 * Returns HTML escaped variable
 *
 * @access    public
 * @param    mixed
 * @return    mixed
 */
function html_escape($var) {
    if (is_array($var)) {
        return array_map('html_escape', $var);
    }
    else {
        return htmlspecialchars($var, ENT_QUOTES, config_item('charset'));
    }
}


function format_content($text) {

    // 视频地址识别。
    // youku
    if (strpos($text, 'player.youku.com')) {
        $text = preg_replace('/http:\/\/player.youku.com\/player.php\/sid\/([a-zA-Z0-9\=]+)\/v.swf/', '<div class="embed-responsive embed-responsive-16by9"><embed src="http://player.youku.com/player.php/sid/\1/v.swf" quality="high" width="100%" height="auto" align="middle" allowScriptAccess="sameDomain" type="application/x-shockwave-flash"></embed></div>', $text);
    }

    if (strpos($text, 'v.youku.com')) {
        $text = preg_replace('/http:\/\/v.youku.com\/v_show\/id_([a-zA-Z0-9\=]+)(\/|.html?)?/', '<div class="embed-responsive embed-responsive-16by9"><embed src="http://player.youku.com/player.php/sid/\1/v.swf" quality="high" width="100%" height="auto" align="middle" allowScriptAccess="sameDomain" type="application/x-shockwave-flash"></embed></div>', $text);
    }

    // tudou
    if (strpos($text, 'www.tudou.com')) {
        if (strpos($text, 'programs/view')) {
            $text = preg_replace('/http:\/\/www.tudou.com\/(programs\/view|listplay)\/([a-zA-Z0-9\=\_\-]+)(\/|.html?)?/', '<div class="embed-responsive embed-responsive-16by9"><embed src="http://www.tudou.com/v/\2/" quality="high" width="100%" height="auto" align="middle" allowScriptAccess="sameDomain" type="application/x-shockwave-flash"></embed></div>', $text);
        }
        elseif (strpos($text, 'albumplay')) {
            $text = preg_replace('/http:\/\/www.tudou.com\/(albumplay)\/([a-zA-Z0-9\=\_\-]+)\/([a-zA-Z0-9\=\_\-]+)(\/|.html?)?/', '<embed src="http://www.tudou.com/a/\2/" quality="high" width="100%" height="auto" align="middle" allowScriptAccess="sameDomain" type="application/x-shockwave-flash"></embed>', $text);
        }
        else {
            $text = preg_replace('/http:\/\/www.tudou.com\/(programs\/view|listplay)\/([a-zA-Z0-9\=\_\-]+)(\/|.html?)?/', '<div class="embed-responsive embed-responsive-16by9"><embed src="http://www.tudou.com/l/\2/" quality="high" width="100%" height="auto" align="middle" allowScriptAccess="sameDomain" type="application/x-shockwave-flash"></embed></div>', $text);
        }
    }
    //$CI =& get_instance();
    //$CI->load->helper('typography');
    $text = auto_link_pic($text, 'url', true);
    $text = str_replace('&lt;pre&gt;', '<pre>', $text);
    $text = str_replace('&lt;/pre&gt;', '</pre>', $text);
    $text = nl2br_except_pre($text);
    return $text;
}

function nl2br_except_pre($str) {
    return \nb\Pool::object('\util\Typography')->nl2br_except_pre($str);
    //return \util\Typography::obj()->nl2br_except_pre($str);
}

function auto_typography($str, $strip_js_event_handlers = true, $reduce_linebreaks = false) {
    return \nb\Pool::object('\util\Typography')->auto_typography(
        $str,
        $strip_js_event_handlers,
        $reduce_linebreaks
    );
    //return \util\Typography::obj()->auto_typography($str, $strip_js_event_handlers, $reduce_linebreaks);
}

function entity_decode($str, $charset = 'UTF-8') {
    global $SEC;
    return $SEC->entity_decode($str, $charset);
}

/**
 * 获取gravatar头像地址
 *
 * @param string $mail
 * @param int $size
 * @param string $rating
 * @param string $default
 * @param bool $isSecure
 * @return string
 */
function gravatar($mail, $size=64) {
    $rating = "PG";
    $default = null;
    $isSecure = false;
    $url = $isSecure ? 'https://secure.gravatar.com' : 'http://www.gravatar.com';
    $url .= '/avatar/';

    if (!empty($mail)) {
        $url .= md5(strtolower(trim($mail)));
    }

    $url .= '?s=' . $size;
    $url .= '&amp;r=' . $rating;
    $url .= '&amp;d=' . $default;

    return $url;
}

/**
 * 将markdown转为html
 * @param $text
 * @return mixed
 */
function markdown($text) {
    $parser = \nb\Pool::value('util\HyperDown',function (){
        $parser = new \util\HyperDown();
        $parser->hook('afterParseCode', function ($html) {
            return preg_replace("/<code class=\"([_a-z0-9-]+)\">/i", "<code class=\"lang-\\1\">", $html);
        });
        $parser->hook('beforeParseInline', function ($html) use ($parser) {
            return preg_replace_callback("/^\s*<!\-\-\s*more\s*\-\->\s*$/s", function ($matches) use ($parser) {
                return $parser->makeHolder('<!--more-->');
            }, $html);
        });
        $parser->enableHtml(true);
        $parser->_commonWhiteList .= '|img|cite|embed|iframe';
        $parser->_specialWhiteList = array_merge($parser->_specialWhiteList, [
            'ol' => 'ol|li',
            'ul' => 'ul|li',
            'blockquote' => 'blockquote',
            'pre' => 'pre|code'
        ]);
        return $parser;
    });
    return $parser->makeHtml($text);
}