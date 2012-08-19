<?php
session_start ();

include_once ('config.php');
include_once ('saetv2.ex.class.php');

$o = new SaeTOAuthV2 ( WB_AKEY, WB_SKEY );

if (isset ( $_REQUEST ['code'] )) {
	$keys = array ();
	$keys ['code'] = $_REQUEST ['code'];
	$keys ['redirect_uri'] = WB_CALLBACK_URL;
	try {
		$token = $o->getAccessToken ( 'code', $keys );
	} catch ( OAuthException $e ) {
	}
}

if ($token) {
	$token ['access_time'] = time ();
	{ // 增加写入数据库的功能
		$mysql = new SaeMysql ();
		$sql = "SELECT * FROM `token` WHERE uid='" . $token ['uid'] . "' LIMIT 1";
		$data = $mysql->getData ( $sql );
		if (empty ( $data )) {
			$sql = "INSERT INTO `token`(`uid` ,`access_time` ,`access_token` ,`expires_in` ,`remind_in`) VALUES('$token[uid]','$token[access_time]','$token[access_token]','$token[expires_in]','$token[remind_in]')";
			$mysql->runSql ( $sql );
			if ($mysql->errno () != 0) {
				die ( "Error:" . $mysql->errmsg () );
			}
		} elseif (time () >= ($data ['access_time'] + $data ['expires_in'])) {
			$sql = "UPDATE `token` SET `access_time`='$token[access_time]', `access_token`='$token[access_token]', `expires_in`='$token[expires_in]', `remind_in`='$token[remind_in]' WHERE `uid`='$token[uid]' LIMIT 1";
			$mysql->runSql ( $sql );
			if ($mysql->errno () != 0) {
				die ( "Error:" . $mysql->errmsg () );
			}
		}
		
		$mysql->closeDb ();
	}
	$_SESSION ['token_ring'] [$token ['uid']] = $token;
	// setcookie ( 'weibojs_' . $o->client_id, http_build_query ( $token ) );
	?>
授权完成,个人信息已记录。<a href="index.html">进入你的微博列表页面</a><br />
<?php
} else {
	?>
授权失败。
<?php
}echo '<pre>';var_dump ( $_SESSION );
?>
