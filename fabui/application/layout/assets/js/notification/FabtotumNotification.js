jQuery(document).ready(function () {

    // Plugins placing
    $("body").append("<div id='divFabSmallBoxes'></div>");
    $("body").append("<div id='divMiniIcons'></div><div id='divbigBoxes'></div>");

});

//Closing Rutine for Loadings
function SmartUnLoading() {

    $(".divFabMessageBox").fadeOut(300, function () {
        $(this).remove();
    });

    $(".LoadingBoxContainer").fadeOut(300, function () {
        $(this).remove();
    });
}



// Messagebox
var FabExistMsg = 0,
FabMSGboxCount = 0,
PrevTop = 0;

$.FabWaitBox = function(settings, callback) {
	var SmartMSG,
	    Content;
	settings = $.extend({
		title : "",
		content : "",
		stop: undefined,
		NormalButton : undefined,
		ActiveButton : undefined,
		buttons : undefined,
		input : undefined,
		inputValue : undefined,
		placeholder : "",
		options : undefined
	}, settings);
	
	
	
	FabMSGboxCount = FabMSGboxCount + 1;

	if (FabExistMsg == 0) {
		FabExistMsg = 1;
		SmartMSG = "<div class='divFabMessageBox animated fadeIn fast' id='FabMsgBoxBack'></div>";
		$("body").append(SmartMSG);

		if (isIE8orlower() == 1) {
			$("#FabMsgBoxBack").addClass("MessageIE");
		}
	}

	var InputType = "";
	var HasInput = 0;
	

	Content = "<div class='MessageBoxContainer animated fadeIn fast' id='FabMsg" + FabMSGboxCount + "'>";
	Content += "<div class='MessageBoxMiddle'>";
	Content += "<span class='MsgTitle'>" + settings.title + "</span class='MsgTitle'>";
	
	if(settings.stop == undefined){
		setting.stop = true;
	}
	
	if(settings.stop) Content += '<a rel="tooltip" data-original-title="Emergency Button. <br>This will stop all operations on the FABtotum" data-html="true" id="waitEmergencyButton" href="#" class="btn btn-default pull-right wait-button" data-action="emergencyButton"><i class="fa fa fa-warning"></i></a>';
	
	Content += '<hr class="simple">';
	
	var css_style = settings.content == '' ? 'display:none;': '';	
	Content += "<div class='pre' style='" + css_style + "'>" + settings.content + "</div>";

	//MessageBoxButtonSection
	Content += "</div>";
	//MessageBoxMiddle
	Content += "</div>";
	//MessageBoxContainer

	// alert(FabMSGboxCount);
	if (FabMSGboxCount > 1) {
		$(".MessageBoxContainer").hide();
		$(".MessageBoxContainer").css("z-index", 99999);
	}

	$(".divFabMessageBox").append(Content);

	// Focus
	if (HasInput == 1) {
		$("#txt" + FabMSGboxCount).focus();
	}
	
	$("[rel=tooltip]").tooltip();

}

function openWait(title, content, stopButton){
		
	if(title == undefined){
		title = '';
	}
	
	if(content == undefined){
		content = '';
	}
	
	if(stopButton == undefined){
		stopButton = true;
	}
	
	
	if(FabMSGboxCount > 0){
		waitTitle(title);
		waitContent(content);
		waitStopButton(stopButton);
		return;
	}
	
	
	$.FabWaitBox({
		title : title,
		content : content,
		stop: stopButton
	});

}



function waitTitle(title){
	
	if(FabMSGboxCount > 0){
		$(".MsgTitle").html(title);
	}
}

function waitContent(content){

	if(FabMSGboxCount > 0){
		$(".MessageBoxMiddle .pre").show();
		$(".MessageBoxMiddle .pre").html(content);
	}
}

function closeWait(){
	
	if(STOPPING_ALL) return;
			
	for (i = FabMSGboxCount; i <= FabMSGboxCount; i++) {
		
		if($("#FabMsg" + i).length > 0){	
	    	$("#FabMsg" + i).addClass("animated fadeOut fast");
	    	FabMSGboxCount = FabMSGboxCount - 1;
	    	$("#FabMsgBoxBack").removeClass("fadeIn").addClass("fadeOut").delay(300).queue(function () {
	         	FabExistMsg = 0;
	         	$(this).remove();
	        });
        } 
	}
		
}

function waitStopButton(bool){
	
	if(bool) $(".wait-button").show();
	else $(".wait-button").hide();
}
