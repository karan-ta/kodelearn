
function showAjaxError(beforeDiv,msgArr){
	
	$('#warning').remove();
	var warning = '<div class="block-error" id="error"><ul>';
	
	for(var i = 0; i < msgArr.length ; i++ ){		
	  warning += '<li>'+msgArr[i]+'</li>';	
	}		
	
	warning += '</ul></div>';
	console.log(beforeDiv);
	beforeDiv.before(warning);
	scroll(0,0);
	$('#error').slideDown(200);
	setTimeout('$("#error").slideUp()', 2000);
	
}

function showAjaxSuccess(beforeDiv,msgArr){
	
	$('#warning').remove();
	var warning = '<div class="block-success" id="success"><ul>';
	
	for(var i = 0; i < msgArr.length ; i++ ){		
	  warning += '<li>'+msgArr[i]+'</li>';	
	}		
	
	warning += '</ul></div>';
	console.log(beforeDiv);
	beforeDiv.before(warning);
	scroll(0,0);
	$('#success').slideDown(200);
	setTimeout('$("#success").slideUp()', 2000);
	
}


var ajaxLoad = function(opts){
	
	this.options = {
			'container' : '#middle-content',
			'controller' : 'index',
			'action'    : '',
			'onBefore'    : function() {  }, //will be called before the request is sent
			'loadingGif' : true, //loading image enabled or disabled while response arrives
			'response'   : 'html',   //the type of response expected
			'callback'   : function(resp) {  },
			'scrollTop'  : { 'enabled' : true , 'val' : 0   },
			'history'    : true,  //the action will get appended to the url
			'loginReq' : false
	};	
	
	$.extend(this.options,opts);	
	
	this.before();	
	this.request();
};

ajaxLoad.prototype.before = function(){	
	
	if(this.options.loadingGif){
		$("#ajax-loader").show();
	}
	
	if(typeof this.options.onBefore == 'function'){
		this.options.onBefore.call(this,this);
	}
};

ajaxLoad.prototype.request = function(){
	var o = this;	
	var url = KODELEARN.config.base_url+""+this.options.controller+"/"+this.options.action+"/";
	
	$.get(url,function(resp){
		
		
		if('html' == o.options.response){	
			$(o.options.container).html(resp);			
		}
		
		if(typeof o.options.callback == 'function'){
			o.options.callback.call(this,resp);
		}		
		
		$("#ajax-loader").hide();
		
	});
};


function showError(){
	var msg = ['Please enter first name', 'Please Enter Last Name'];
	showAjaxError($("#foo"),msg);
}

function showSuccess(){
	var msg = ['Your Settings are saved Successfully', 'Now you can login using your new password'];
	showAjaxSuccess($("#foo"),msg);
}



