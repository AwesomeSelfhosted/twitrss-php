<?php
include 'config.inc.php';
function check_user($user){
    global $user;
    $curl = curl_init();  
    curl_setopt($curl,CURLOPT_URL,"https://twitter.com/{$user}");
    curl_setopt($curl,CURLOPT_HEADER,1);
    curl_setopt($curl,CURLOPT_NOBODY,1);
    curl_setopt($curl,CURLOPT_RETURNTRANSFER,1);
    curl_setopt($curl,CURLOPT_TIMEOUT,10);
    curl_exec($curl);  
    if (curl_getinfo($curl,CURLINFO_HTTP_CODE) == '404'){
        $user_status = false;
    }else{
        $user_status = true;
    }
    curl_close($curl);  
    return  $user_status;  
}
$user = $_GET['user'];
$hashtag = $_GET['hashtag'];
if ($hashtag){
    $rss_page = file_get_contents(perl_hashtag_api_url."?term={$hashtag}");
    echo $rss_page;
}else {
if (!$user){
    $rand_user_list = array('nisopict_bot_kr','nisopict_bot_k2','kneehigh_bot','akogare_ryoiki','exposed_cranium');
    $user = $rand_user_list[rand(0,count($rand_user_list) - 1)];
    header("HTTP/1.1 301 Moved Permanently");
    header("Location: http://tprss.puteulanus.com/index.php?user={$user}");
    exit;
}
if (!check_user($user)){
    header("Content-Type: text/html; charset=utf-8");
    echo "not found";
    exit;
}
$rss_page = file_get_contents(perl_api_url."?user={$user}");
echo $rss_page;
}
