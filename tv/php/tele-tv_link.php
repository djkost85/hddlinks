#!/usr/local/bin/Resource/www/cgi-bin/php
<?php
function str_between($string, $start, $end){ 
	$string = " ".$string; $ini = strpos($string,$start); 
	if ($ini == 0) return ""; $ini += strlen($start); $len = strpos($string,$end,$ini) - $ini; 
	return substr($string,$ini,$len); 
}
function vk($string) {
  if (strpos($string,"video_ext.php") === false) {
  //echo $string;
	$h = file_get_contents($string);
	$t1=explode("nvar vars",$h);
	$l=$t1[1];
	$uid=str_between($l,'\"uid\":\"','\"');
	$host=str_between($l,'"host\":\"','\"');
	$host=str_replace("\\/","/",$host);
	$host=str_replace("\\/","/",$host);
	$host=str_replace("\/","/",$host);
	$vtag=str_between($l,'"vtag\":\"','\"');
	$r=$host."u".$uid."/videos/".$vtag.".360.mp4";
 } else {
    $baza = file_get_contents($string);
    //echo $string."<BR>";
    preg_match("/hd=\d{1}/",$string,$m);
    //print_r ($m);
    $host = str_between($baza,"var video_host = '","'");
    $uid = str_between($baza,"var video_uid = '","'");
    $vtag = str_between($baza,"var video_vtag = '","'");
    //$hd = str_between($baza,"var video_max_hd = '","'");
    $t1=explode("hd=",$m[0]);
    $hd=trim($t1[1]);
    //echo $hd;
    if ($hd == "0") {
      $r = $host."u".$uid."/videos/".$vtag.".240.mp4";
    } elseif ($hd=="3") {
      $r = $host."u".$uid."/videos/".$vtag.".720.mp4";
    } elseif ($hd=="2") {
      $r = $host."u".$uid."/videos/".$vtag.".480.mp4";
      $test = $host."u".$uid."/videos/".$vtag.".480.mp4";
      $ch = curl_init();
      curl_setopt($ch, CURLOPT_URL, $test);
      curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
      curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows; U; Windows NT 6.1; en-US; rv:1.9.1.2) Gecko/20090729 Firefox/3.5.2 GTB5');
      curl_setopt($ch, CURLOPT_HEADER, 1);
      curl_setopt($ch, CURLOPT_NOBODY, 1);
      $h1 = curl_exec($ch);
      curl_close($ch);
      if (strpos($h1,"200 OK") === false)
       $r= $host."u".$uid."/videos/".$vtag.".360.mp4";
    } elseif ($hd=="1") {
      $r = $host."u".$uid."/videos/".$vtag.".360.mp4";
    } else {
      $r = $host."u".$uid."/videos/".$vtag.".360.mp4";
      $test = $host."u".$uid."/videos/".$vtag.".480.mp4";
      $ch = curl_init();
      curl_setopt($ch, CURLOPT_URL, $test);
      curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
      curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows; U; Windows NT 6.1; en-US; rv:1.9.1.2) Gecko/20090729 Firefox/3.5.2 GTB5');
      curl_setopt($ch, CURLOPT_HEADER, 1);
      curl_setopt($ch, CURLOPT_NOBODY, 1);
      $h1 = curl_exec($ch);
      curl_close($ch);
      if (strpos($h1,"200 OK") !== false)
       $r=$test;
    }
 }
  return $r;
}
//http://www.tele-tv.info/js/embed.js
/*
var teletv_type='emisiuni';
var teletv_width=615;
var teletv_height=315;
var teletv_id=838;
*/
//http://www.tele-tv.info/embed/' + teletv_type + '/' + teletv_id
//http://www.tele-tv.info/embed/emisiuni-link/991.swf
$link = $_GET["file"];
$link=urldecode($link);
$html = file_get_contents($link);
$type = str_between($html,"teletv_type='","'");
$id = str_between($html,"teletv_id=",";");
$l="http://www.tele-tv.info/embed/".$type."/".$id.".swf";
//echo $l;
if (preg_match("/emisiuni/",$type)) {
$html = file_get_contents($l);
$link=trim(str_between($html,'flashvars="file=','&'));
if (!$link) {
  $l1=str_between($html,"vk.com",'"');
  if ($l1) {
    $l1="http://vk.com".$l1;
    $link=vk($l1);
  } else {
    $link=str_between($html,'iframe id="myFrame" src="','"');
    if (!$link) $link=str_between($html,"iframe src='","'");
    //echo $link;
    if (strpos($link,"http") === false) $link="http:".$link;
if (strpos($link,"mail.ru") !==false) {
    $filelink=$link;
   //$filelink="http://api.video.mail.ru/videos/mail/alex.costantin/_myvideo/162.json";
   //http://api.video.mail.ru/videos/embed/mail/alex.costantin/_myvideo/1029.html
   //http://my.mail.ru/video/mail/best_movies/_myvideo/4412.html
   //http://api.video.mail.ru/videos/embed/inbox/virusandrei/_myvideo/38.html
   //http://api.video.mail.ru/videos/mail/best_movies/_myvideo/6501.json
   if (strpos($filelink,"json") === false) {
     //$filelink=str_replace("/embed","",$filelink);
     //$filelink=str_replace("html","json",$filelink);
   }
   if (strpos($filelink,"videoapi.my.mail.ru") === false)
   $filelink=str_replace("my.mail.ru/video","api.video.mail.ru/videos/",$filelink);
   //echo $filelink;
   $ch = curl_init($filelink);
   curl_setopt($ch, CURLOPT_FOLLOWLOCATION  ,1);
   curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows; U; Windows NT 6.1; en-US; rv:1.9.1.2) Gecko/20090729 Firefox/3.5.2 GTB5');
   curl_setopt($ch, CURLOPT_RETURNTRANSFER  ,1);  // RETURN THE CONTENTS OF THE CALL
   curl_setopt($ch, CURLOPT_REFERER, "http://my9.imgsmail.ru/r/video2/uvpv3.swf?3");
   //curl_setopt($ch, CURLOPT_COOKIEJAR, '/tmp/cookies.txt');
   //curl_setopt($ch, CURLOPT_COOKIEFILE, '/tmp/cookies.txt');
   $h = curl_exec($ch);
   curl_close($ch);
   //echo $h;
   //die();
  $t1=explode('hd":"',$h);
  $t2=explode('"',$t1[1]);
  $link=$t2[0];
  if (!$link) {
  $t1=explode('md":"',$h);
  $t2=explode('"',$t1[1]);
  $link=$t2[0];
  }
  if (!$link) {
  $t1=explode('sd":"',$h);
  $t2=explode('"',$t1[1]);
  $link=$t2[0];
  }
   $link=urldecode($link);
   $link=str_replace("[","\[",$link);
   $link=str_replace("]","\]",$link);
}
if (strpos($link,"dailymotion") !== false) {
$link=file_get_contents("http://127.0.0.1/cgi-bin/scripts/filme/php/link.php?file=".urlencode($link));
}
  }
}
} else {
$html = file_get_contents($l);
$l=str_between($html,'src="','&');
  $ch = curl_init();
  curl_setopt($ch, CURLOPT_URL, $l);
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
  curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows; U; Windows NT 6.1; en-US; rv:1.9.1.2) Gecko/20090729 Firefox/3.5.2 GTB5');
  curl_setopt($ch, CURLOPT_FOLLOWLOCATION  ,1);
  $html = curl_exec($ch);
  curl_close($ch);
$link = str_between($html,"file: '","'");
}
if (strpos($link, 'youtu') !== false)
 $link=file_get_contents("http://127.0.0.1/cgi-bin/scripts/util/yt.php?file=".urlencode($link));
print $link;
?>
