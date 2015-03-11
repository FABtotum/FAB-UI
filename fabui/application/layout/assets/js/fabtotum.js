function number_format(number, decimals, dec_point, thousands_sep) {

    number = (number + '').replace(/[^0-9+\-Ee.]/g, '');
	var n = !isFinite(+number) ? 0 : +number, prec = !isFinite(+decimals) ? 0
			: Math.abs(decimals), sep = (typeof thousands_sep === 'undefined') ? ','
			: thousands_sep, dec = (typeof dec_point === 'undefined') ? '.'
			: dec_point, s = '', toFixedFix = function(n, prec) {
		var k = Math.pow(10, prec);
		return '' + Math.round(n * k) / k;
	};

    // Fix for IE parseFloat(0.55).toFixed(0) = 0;
	s = (prec ? toFixedFix(n, prec) : '' + Math.round(n)).split('.');
	if (s[0].length > 3) {
		s[0] = s[0].replace(/\B(?=(?:\d{3})+(?!\d))/g, sep);
	}
	if ((s[1] || '').length < prec) {
		s[1] = s[1] || '';
		s[1] += new Array(prec - s[1].length + 1).join('0');
	}
	return s.join(dec);
}

function precise_round(num,decimals) {
   return Math.round(num * Math.pow(10, decimals)) / Math.pow(10, decimals);
}

/**
 * @param time
 * @returns {String}
 */
function _time_to_string(time) {

	var hours   = parseInt(time / 3600) % 24;
	var minutes = parseInt(time / 60) % 60;
	var seconds = time % 60;

	var day = 86400;

	if(time < day){
		return pad(precise_round(hours, 0)) + ":" + pad(precise_round(minutes, 0)) + ":" + pad(precise_round(seconds,0));
	}else{
		return '1 day';
	}

}

/**
 *
 * @param val
 * @returns
 */
function pad(val) {
	return val > 9 ? val : "0" + val;
}

function freeze_menu(except){


	var excepet_item_menu = new Array();

	excepet_item_menu[0] = 'dashboard';
	excepet_item_menu[1] = 'objectmanager';
	excepet_item_menu[2] =  except;



	$( "#left-panel a" ).each( function( index, element ){

		var controller =  $( this ).attr('data-controller');



		// se non è nella lista allora la rendo disabled
		if(excepet_item_menu.indexOf(controller) < 0){

			$(this).addClass('menu-disabled');
			$(this).removeAttr('href');
		}
		//se corrisponde aggiungo punto esclamativo per notifica
		if(controller == except){

            if($(this).find('.freeze-menu').length <= 0){
                $(this).append('<span class="badge bg-color-red pull-right inbox-badge freeze-menu">!</span>');
                freezed = true;
            }

		}

	});
}


/**
*
*/
function unfreeze_menu (){


    $( "#left-panel a" ).each( function( index, element ){
        $(this).removeClass('menu-disabled');
        $(this).attr('href', $(this).attr('data-href'));
    });

    $(".freeze-menu").remove();

    freezed = false;

}

function bytesToSize(bytes) {
	   var k = 1000;
	   var sizes = ["B", "Kb", "Mb", "Gb", "Tb"];
	   if (bytes === 0) return '0 Bytes';
	   var i = parseInt(Math.floor(Math.log(bytes) / Math.log(k)),10);
	   return parseFloat((bytes / Math.pow(k, i))).toFixed(3) + ' ' + sizes[i];
}

/**
 *
 *
 */

/**
 * VARIABLES: fabui global variables
 */

/**
 *  MODAL WAITING
 */
var loading = $.magnificPopup.instance;


function openWait(title){


     if($(".wait-content").length > 0){
        $(".wait-content").html('');
        $(".wait-content").remove();
    }

    var src_html  = '<div class="white-popup animated bounceInDown">';
        src_html += '<h2 class="text-align-center wait-title">' + title +' </h2>';
        src_html += '<h4 class="text-align-center wait-spinner"><i class="fa fa-spinner fa-spin"></i></h4>'
        src_html += '<div class="wait-content margin-top-10" style="display:none;"><pre></pre></div>';
        src_html += '</div>';

	loading.open({
		items: {
			     src : src_html
              },
	    removalDelay: 100,
	    type: 'inline',
	    preloader: false,
		modal: true,
		mainClass: 'mfp-zoom-in',
        alignTop : false,
        preloader: true
	 });

}

function closeWait(){
	loading.close();
}


function waitTitle(title){
    if($(".wait-title").length > 0){
        $(".wait-title").html(title);
    }
}


function waitContent(content){
    if($(".wait-content").length > 0){

    	$(".wait-content").show();

        $(".wait-content").find('pre').html(content);
    }
}



var color_green = "#659265";
var color_red   = "#C46A69";
var freezed     = false;

function show_small_box(title, message, color, icon, timeout){
	$.smallBox({
		title : title,
		content : message,
		color : color,
		//timeout: 6000,
		icon : icon,
		timeout : timeout
	});
}

function show_error(message){
	show_small_box('Error', message, color_red, 'fa fa-warning shake animated', 6000);
}

function show_info_message(message){
	show_small_box('Info', message, color_green, 'fa fa-check bounce animated', 4000);

}


var notifications_interval;
var safety_interval;
var tasks_interval;
var emergency = false;
var idleTime  = 0;
var idleInterval;
var do_system_call = true;

/** CHECK PRINTE SAFETY */
function safety(){

	if(!do_system_call){
		return false;
	}

   	var timestamp = new Date().getTime();
  	if(emergency == false){
       $.get( "/temp/fab_ui_safety.json?time=" + jQuery.now() , function( data ) {

             if(parseInt(data.state.emergency) == 1 ) {

                emergency = true;

                var error_message = data.state.status;

                if(error_message == ''){
                 	error_message = 'An error occured, please check head conncections and front panel';
                }


                $.SmartMessageBox({
    				//title: "Attention!",
    				title: "<h4><span class='txt-color-orangeDark'><i class='fa fa-warning fa-2x'></i></span>&nbsp;&nbsp;" + error_message + "<br>&nbsp;Press OK to continue or Ignore to disable this warning</h4>",
    				buttons: '[OK][IGNORE]'
    			}, function(ButtonPressed) {
    				if (ButtonPressed === "OK") {
    					 secure(1);
    				}

    				if (ButtonPressed === "IGNORE") {
    					 secure(0);
    				}

    			});
             }
       });
   }

}

function secure(mode){


    	$.ajax({
			type: "POST",
			url: "/fabui/application/modules/controller/ajax/secure.php",
            data: {mode: mode},
            dataType: 'json'
		}).done(function(response) {

            emergency = false;

		});

}


function set_tasks(data){
    number_tasks = data.number;
    var controller = '';

    $(".task-list").find('span').html('	Tasks (' + data.number + ') ');

    $.each(data.items, function() {

        var row = this;

        controller = row.controller;

    });

    if(data.number > 0){
            freeze_menu(controller);
            freezed = true;
        }else{
            freezed = false;
            unfreeze_menu();
    }

}


function set_updates(number){

    number_updates = number;
    $(".update-list").find('span').html('	Updates (' + number + ') ');


    if(number > 0){

    	$("#left-panel").find('nav').find('ul').find('li').each(function(){


          if($(this).find('a').attr("data-controller") == 'updates'){
          	$(this).find('a').append('<span class="badge bg-color-red pull-right inbox-badge">'+ number +'</span>');
          }


        });


    }




}


function update_notifications(){

    var total = number_updates + number_tasks + number_notifications ;

    if(total > 0){
        $("#activity").find('.badge').addClass('bg-color-red bounceIn animated');
        document.title = 'FAB UI beta (' + total + ')';
    }else{
        $("#activity").find('.badge').removeClass('bg-color-red bounceIn animated');
         document.title = 'FAB UI beta';
    }


    if(number_tasks == 0){
    	freezed = false;
    	unfreeze_menu();
    }

    $("#activity").find('.badge').html(total);

}



function refresh_notifications(){


	if(!do_system_call){
		return false;
	}


    $( ".notification" ).each( function( index, element ){
        var obj = $(this);
        if(obj.hasClass('active')){
            var url = obj.find('input[name="activity"]').attr("id");
            var container = $(".ajax-notifications");
            loadURL(url, container);
        }
	});
}



/** CHECK TASKS, MENU  */
function check_notifications(){

	if(!do_system_call){
		return false;
	}

	if(idleTime < max_idle_time || max_idle_time == 0){
	    var timestamp = new Date().getTime();
	    $.ajax({
	            type: "POST",
	            url: "/fabui/application/modules/controller/ajax/check_notifications.php?time="+timestamp,
	            dataType: 'json',
	            cache : false
	        }).done(function( data ) {

	            //set_updates(data.updates);
	            set_tasks(data.tasks);
	            update_notifications();

	             if(data.internet == true){
	                $('.internet').show();
	             }else{
	                $('.internet').hide();
	             }
	        });

    }else{

			$("#lock-screen-form").submit();
    }
}


/** ON LOAD */

$(function() {


	if(fabui){

	  	// Handler for .ready() called.
	  	idleInterval = setInterval(timerIncrement, 1000);
	  	safety_interval= setInterval(safety, 3000); /* START TIMER... */
	  	check_notifications();
	  	notifications_interval = setInterval(check_notifications, 10000);
	  	$("#refresh-notifications").on('click', refresh_notifications);
	  	check_for_updates();


	  	check_for_wizard_setup();





  }



});



$(".language").click(function () {


    var actual_lang = $("#actual_lang").val();
    var new_lang    = $(this).attr("data-value");

    if(actual_lang != new_lang){
        $("#lang").val(new_lang);


        openWait('<i class="fa fa-flag"></i><br> loading language... ');
        $("#lang_form").submit();
    }


});



/** MOUSE MOVE FOR LOCK SCREEN */
$(document).mousemove(function(e){
      idleTime = 0;
});

/** IDLE TIMER */
function timerIncrement(){
	idleTime++;
}


/** SHUTDOWN */
function shutdown()
{
	openWait('Shutdown in progress');

	clearInterval(notifications_interval);
	clearInterval(safety_interval);
	clearInterval(idleInterval);

	$.ajax({
		type: "POST",
		url: "/fabui/application/modules/controller/ajax/shutdown.php",
		dataType: 'json'
	}).done(function(response) {
		setTimeout(function() {
			$(".wait-spinner").remove();
			waitTitle('Now you can switch off the power');
			waitContent($("#power-off-img").html());
		}, 15000);
	});
}


/** SHUTDOWN */
function check_for_updates(){

	if(!do_system_call){
		return false;
	}

	$.ajax({
			type: "POST",
			url: "/fabui/application/modules/controller/ajax/check_updates.php",
            dataType: 'json'
	}).done(function(response) {

      	   set_updates(response.updates.number);
      	   update_notifications();
	});
}


/** SUGGESTION */
$("#send-suggestion").on('click', function(){

	if($.trim($("#suggestion-text").val()) == ''){
		return false;
	}

	$(".modal-content").find(".btn").addClass('disabled');
	$("#send-suggestion").html('<i class="fa fa-envelope-o"></i> Sending..');

	$.ajax({
			type: "POST",
			url: "/fabui/controller/suggestion",
            dataType: 'json',
            data: {text: $("#suggestion-text").val(), title: $("#suggestion-title").val()}
	}).done(function(response) {

		$(".modal-content").find(".btn").removeClass('disabled');
		$("#send-suggestion").html('<i class="fa fa-envelope-o"></i> Send');

		if(response.result == 1){
			$("#suggestion-text").val('');
			$("#suggestion-title").val('');
			$(".suggestion-modal").modal("hide");


			$.smallBox({
				title : "Thanks",
				content : "so much for taking the time to help improve FABUI .",
				color : "#659265",
				iconSmall : "fa fa-smile-o fa-2X",
                timeout : 7000
            });

		}else{

			$.smallBox({
				title : "Warning",
				content : "an error occurred, please try to send again",
				color : "#C46A69",
				iconSmall : "fa fa-warning shake animated",
                timeout : 7000
            });

		}

	});


});



/** REPORT BUG */
$("#send-bug").on('click', function(){

	if($.trim($("#bug-text").val()) == ''){
		return false;
	}

	$(".modal-content").find(".btn").addClass('disabled');
	$("#send-bug").html('<i class="fa fa-envelope-o"></i> Sending..');

	$.ajax({
			type: "POST",
			url: "/fabui/controller/bug",
            dataType: 'json',
            data: {text: $("#bug-text").val(), title: $("#bug-title").val()}
	}).done(function(response) {

		$(".modal-content").find(".btn").removeClass('disabled');
		$("#send-bug").html('<i class="fa fa-envelope-o"></i> Send');

		if(response.result == 1){
			$("#bug-text").val('');
			$("#bug-title").val('');
			$(".bug-modal").modal("hide");


			$.smallBox({
				title : "Thanks",
				content : "so much for taking the time to help improve FABUI .",
				color : "#659265",
				iconSmall : "fa fa-smile-o fa-2X",
                timeout : 7000
            });

		}else{

			$.smallBox({
				title : "Warning",
				content : "an error occurred, please try to send again",
				color : "#C46A69",
				iconSmall : "fa fa-warning shake animated",
                timeout : 7000
            });

		}

	});


});


/**
 *
 */

function check_for_wizard_setup(){

	if(!do_system_call){
		return false;
	}

	setTimeout(function(){
		if(setup_wizard){

			$.smallBox({
				title : "Wizard Setup",
				content : "It seems that you still did not complete the first recommended setup:<ul><li>Manual Bed Calibration</li><li>Probe Lenght Calibration</li><li>Engage Feeder</li></ul><br>Without a proper calibration you will not be able to use the FABtotum correctly<br>Do you want to do it now?<br><br><p class='text-align-right'><a href='/fabui/maintenance/first-setup' class='btn btn-primary btn-sm'>Yes</a> <a href='javascript:void(0);' class='btn btn-danger btn-sm'>No</a> <a href='javascript:dont_ask_wizard();' class='btn btn-warning btn-sm'>Don't ask me anymore</a> </p>",
				color : "#296191",
				icon : "fa fa-warning swing animated"
			});
		}
	}, 1000);
}



function dont_ask_wizard(){

	$.ajax({
			type: "POST",
			url: "/fabui/controller/wizard",
            dataType: 'json',
            data: {set: 0}
	}).done(function(response) {

	});

}



/** GET TRACE */
function getTrace(url, type, contenitor){


	$.ajax({
			type: type,
			url: url,
	}).done(function(data, statusText, xhr) {


		if(xhr.status == 200){
			contenitor.html(data);
			$('#console').scrollTop(1E10);
		}

	});
}
