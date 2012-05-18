<?PHP
header('Content-Type:text/html;charset=utf-8'); 
session_start();
include_once( 'oauth.php' );
include_once( 'config.php' );

$c = new WeiboClient( WB_AKEY , 
                      WB_SKEY , 
                      $_SESSION['last_key']['oauth_token'] , 
                      $_SESSION['last_key']['oauth_token_secret']);

// print_r($_FILES["my_uploaded_file"]);
if(isset($_FILES['my_uploaded_file']['tmp_name'])){
$msg1  = $c->upload(date("l dS \of F Y h:i:s A"), $_FILES['my_uploaded_file']['tmp_name']);
$msg2  = $c->user_timeline(1,1);
?>
<?php if( is_array( $msg2 ) ): ?>
<?php foreach( $msg2 as $item2 ): ?>
<?PHP 
    echo $item2['original_pic'];
?>
<?php endforeach; ?>
<?php endif; ?>
<?PHP
}
?>



