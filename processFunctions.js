function ajax(ajaxData, isReturn, activateTransition, transitionMessage, ajaxRequest){
	
	ajaxUrl = typeof ajaxUrl !== 'undefined' ? ajaxUrl : '';   
	ajaxData = typeof ajaxData !== 'undefined' ? ajaxData : ''; 
	ajaxRequest = typeof ajaxRequest !== 'undefined' ? ajaxRequest : 'get'; 
	activateTransition = typeof activateTransition !== 'undefined' ? activateTransition : false;
	transitionMessage = typeof transitionMessage !== 'undefined' ? transitionMessage : '';
	
	if(activateTransition != false){
		$('.execution-transition').css('display','block');
		$('.transition-message').html(transitionMessage + " Please wait...");
	}
	
	if(ajaxRequest == 'post'){
		
		$.ajax({
			url : 'process.php',
			type : "POST",
			cache : false, 
			async : false,
			contentType: false,
			processData: false,
			data : ajaxData,
			success: function(data){
				getData = data;
			}	
		});
		
	}else if(ajaxRequest == 'get'){
		
		$.ajax({
			url : 'process.php',
			type : "POST",
			cache : false, 
			async : false,
			data : ajaxData,
			success: function(data){
				getData = data;
			}	
		});
	}
	
	if(activateTransition != false){
		$('.execution-transition').css('display','none');
	}
	
	if(isReturn == true)
		return getData;
}

function processResult(result, successMessge, failedMessage, redirection, path){
	
	result = typeof result !== 'undefined' ? result : ''; 
	successMessge = typeof successMessge !== 'undefined' ? successMessge : ''; 
	failedMessage = typeof failedMessage !== 'undefined' ? failedMessage : '';
	redirection = typeof redirection !== 'undefined' ? redirection : false;
	path = typeof path !== 'undefined' ? path : '';
	
	if(result == 1){
		$('.transition-message').html(successMessge);
		$('.success-execution').show().fadeOut(3000,function(){
			if(redirection != false){
				window.location = path;
			}
		});
	}else{
		$('.transition-message').html(failedMessage);
		$('.failed-execution').show().fadeOut(3000);
	}
}

function modalForm(title,content,size){
	
	size = typeof size !== 'undefined' ? size : '';   
	
	box = bootbox.dialog({
		size: size,
		title : title,
		message : content
	});
	
	return box;
}

function showComponents(components, spot ,tblname ,page, sortOrder, id, textData, filterData, dateData, activateTransition, transitionMessage){
	
	id = typeof id !== 'undefined' ? id : '';
	spot = typeof spot !== 'undefined' ? spot : '.displayContents';
	tblname = typeof tblname !== 'undefined' ? tblname : '';
	page = typeof page !== 'undefined' ? page : '';
	sortOrder = typeof sortOrder !== 'undefined' ? sortOrder : 'DESC';
	textData = typeof textData !== 'undefined' ? textData : '';
	filterData = typeof filterData !== 'undefined' ? filterData : '';
	dateData = typeof dateData !== 'undefined' ? dateData : '';
	activateTransition = typeof activateTransition !== 'undefined' ? activateTransition : false;
	transitionMessage = typeof transitionMessage !== 'undefined' ? transitionMessage : '';
	
	content = ajax(
				{
					showComponents : 1,
					components : components, 
					tblname : tblname, 
					page : page,
					sortOrder : sortOrder,
					id : id, 
					textData : textData,
					filterData : filterData,
					dateData : dateData,
				},
				true,activateTransition,transitionMessage
			);
	
	$(spot).html(content);
}


function messageBody(type, message){
	
	var icon;
	
	if(type == "info"){
		icon = "fa fa-info-circle fa-3x text-info";
	}else if(type == "error"){
		icon = "fa fa-exclamation-circle fa-3x text-error";
	}else if(type == "warning"){
		icon = "fa fa-warning fa-3x text-warning";
	}else if(type == "question"){
		icon = "fa fa-question-circle fa-3x text-info";
	}
	
	container = "<div class='row'>" +
					"<div class='span1'>" + 
						"<p><span class='" + icon + "'></span></p>" +
					"</div>" +
					"<div class='span7' style='margin-left:10px;margin-top:10px;'>" + 
						"<span><b>" + message + "</b></span>" +
					"</div>" +
				"</div>";
			
	return container;
	
}