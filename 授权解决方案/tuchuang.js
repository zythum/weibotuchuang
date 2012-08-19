/*
 这边使用的是莫大的berserkJS https://github.com/tapir-dream/berserkJS
 找台机器用这个一直跑这段代码就行。
*/
function loop(){
	console.log('>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>');
	console.log(new Date());
	App.webview.open('http://weibotuchuang.sinaapp.com/xlogin.php');//把这里写你的xlogin页
	
}
var loadCallback = function(){
	var pos;
	console.log(App.webview.getUrl());
	if(/http\:\/\/weibotuchuang\.sinaapp\.com\/xlogin\.php/.test(App.webview.getUrl())){//把这个正则表达式改写成你的xlogin页
        console.log('@@@');
		setTimeout(function(){
			pos = App.webview.elementRects('a')[0];
		  	App.webview.sendMouseEvent(pos);
		  	console.log('!!!!!!!!!!!!!!!!!!!!!!!');
		  	console.dir(pos);
		},2000);		
	}
	if(/api\.weibo\.com/.test(App.webview.getUrl())){
		console.log('授权');
		setTimeout(function(){
			App.webview.execScript(function(){
				document.getElementById('userId').value = "微博帐号";//微博帐号
				document.getElementById('passwd').value = "微博密码";//微博密码
			});
			pos = App.webview.elementRects('[node-type=submit]')[0];
		  	App.webview.sendMouseEvent(pos);
		  	console.log('!!!!!!!!!!!!!!!!!!!!!!!');
		  	console.dir(pos);
		},2000);
	}
}
var t;
App.webview.clearInterval(t);
t = App.webview.setInterval(loop,20*60*1000);
App.webview.removeEventListener('load',loadCallback);
App.webview.addEventListener('load',loadCallback);
loop();