<?php
session_start ();

include_once ('config.php');
include_once ('saetv2.ex.class.php');



if (empty ( $_SESSION ['token_ring'] )) {
	// mysql -> session
	$mysql = new SaeMysql ();
	
	$sql = "SELECT * FROM `token` WHERE `access_time` + `expires_in` >= " . time () . " LIMIT 10";
	$data = $mysql->getData ( $sql );
	foreach ( $data as $key => $val ) {
		$_SESSION ['token_ring'] [$val ['uid']] = $val;
	}
	if ($mysql->errno () != 0) {
		die ( "Error:" . $mysql->errmsg () );
	}
	
	$mysql->closeDb ();
}

$token = get_live_token ();
$c = new SaeTClientV2 ( WB_AKEY, WB_SKEY, $token ['access_token'] );

if (! is_object ( $c ) || empty ( $token )) {
	echo '请刷新页面重试，或联系朱一同学，登录后台帐号！喵';
} else {
	if (isset ( $_FILES ['my_uploaded_file'] ['tmp_name'] )){
		$msg1 = $c->upload ( date ( "l dS \of F Y h:i:s A" ), $_FILES ['my_uploaded_file'] ['tmp_name'] );
	} elseif(isset ( $_POST ['my_uploaded_url'] )){
		$msg1 = $c->upload ( date ( "l dS \of F Y h:i:s A" ),  $_POST ['my_uploaded_url']);
	}

	if (isset ( $msg1 ['original_pic'] ) && ! empty ( $msg1 ['original_pic'] )) {
		echo $msg1 ['original_pic'];
	} else {
		if (in_array ( $msg1 ['error_code'], array (21325, 21326, 21327 ) )) {
			unset ( $_SESSION ['token_ring'] [$token ['uid']] ); //  干掉没用的token
		}
		echo 'error_code: ' . $msg1 ['error_code'] . ', error_description: ' . $msg1;
		// echo '喵～上传失败。你上传的真的是图片么？';
	}

}

function get_live_token() {
	$token = array ();
	// 有令牌就进去while了
	while ( is_array ( $_SESSION ['token_ring'] ) ) {
		$token = array_shift ( $_SESSION ['token_ring'] );
		// 令牌过期了
		if (time () > $token ['access_time'] + $token ['expires_in']) {
			// 干掉它！
			unset ( $token );
			$token = array (); // for gc? fxxk
			continue;
		} else {
			array_push ( $_SESSION ['token_ring'], $token );
			break; // 拿到token了
		}
	}
	return $token;
}
