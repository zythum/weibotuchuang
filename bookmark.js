;(function(window){
	if(window.weibotuchuangbyzythum){
		return false;
	}
	if(!window.FormData){
		return false;	
	}
	var d = document.createElement('div');
	d.style.cssText = 'width:250px;height:250px;box-shadow:0 0 10px #333;position:fixed;top:0;right:0;z-index:1000000;font-family:arial,sans-serif;padding:0;margin:0;border-radius:0;background:#515151;';
	document.body.appendChild(d);

	var i = document.createElement('iframe');
	i.setAttribute('width','250');
	i.setAttribute('height','250');
	i.style.cssText = ';border:none;';
	i.src = 'http://weibotuchuang.sinaapp.com';
	d.appendChild(i);
	
	var c = document.createElement('div');
	c.innerHTML = '关闭';
	c.style.cssText = 'width:40px;height:25px;box-shadow:0 0 2px #333;position:absolute;top:0;left:-40px;line-height:25px;padding:0;margin:0;border-radius:0;border:none;background:#515151;z-index:99999;text-align:center;color:#aaa;cursor:pointer;';
	d.appendChild(c);
	var clickHandler = function(){
		d.parentNode && document.body.removeChild(d);
		c.removeEventListener('click',clickHandler);	
		d = i = c = null;
		delete window.weibotuchuangbyzythum;				
	}			
	c.addEventListener('click',function(){
		clickHandler();
	});
	window.weibotuchuangbyzythum = d;
})(window);