fabApp = (function(app) {
	
	app.FabActions = function(){
		
		var fabActions = {
		
			userLogout: function($this){
				
				$.SmartMessageBox({
					title: "<i class='fa fa-sign-out txt-color-orangeDark'></i> Hi <span class='txt-color-orangeDark'><strong>" + $this.data("user-name") + "</strong></span> ",
					content : $this.data('logout-msg') || "You can improve your security further after logging out by closing this opened browser",
					buttons: "[Cancel][Go]",
					input: "select",
					options: "[Shutdown][Restart][Logout]"
		
				}, function(ButtonPressed, Option) {
					
					if(ButtonPressed == 'Cancel'){
						return;
					}
					
					if (Option == "Logout") {
						$.root_.addClass('animated fadeOutUp');
						setTimeout(logout, 1000);
					}
					
					if(Option == 'Shutdown'){
						shutdown();
					}
					
					if(Option == 'Restart'){
						restart();
					}
					
					
				});
				
				function logout() {
					window.location = $this.attr('href');
				}
			},
			
			resetController: function($this){
				
				$.SmartMessageBox({
                    title: "<i class='fa fa-bolt'></i> <span class='txt-color-orangeDark'><strong>Reset Controller</strong></span> ",
                    content: $this.data("reset-msg") || "You can improve your security further after logging out by closing this opened browser",
                    buttons: "[No][Yes]"
                }, function(ButtonPressed) {
                   if(ButtonPressed == 'Yes'){
                   	reset_controller();
                   }
               });
				
			},
			
			
			emergencyButton: function($this){
				pressedEmergencyButton = true;
				stopAll();
			}
		};
		
		
		$.root_.on('click', '[data-action="fabUserLogout"]', function(e) {
			var $this = $(this);
			fabActions.userLogout($this);
			e.preventDefault();
			//clear memory reference
			$this = null;
			
		});
		
		$.root_.on('click', '[data-action="resetController"]', function(e) {
			var $this = $(this);
			fabActions.resetController($this);
			e.preventDefault();
			//clear memory reference
			$this = null;
			
		});
		
		$.root_.on('click', '[data-action="emergencyButton"]', function(e) {
			var $this = $(this);
			fabActions.emergencyButton($this);
			e.preventDefault();
			//clear memory reference
			$this = null;
			
		});
		
	};
	
	app.domReadyMisc = function() {
		
		
		$("#top-temperatures").click(function(a) {
			var b = $(this);
		   	b.next(".top-ajax-temperatures-dropdown").is(":visible") ? (b.next(".top-ajax-temperatures-dropdown").fadeOut(150), b.removeClass("active")) : (b.next(".top-ajax-temperatures-dropdown").fadeIn(150), b.addClass("active"));
		   	var c = b.next(".top-ajax-temperatures-dropdown").find(".btn-group > .active > input").attr("id");
		   	b = null, c = null, a.preventDefault()
       	});
       
       
       	$("#jog-shortcut").click(function(a) {
        	var b = $(this);
            b.next(".top-ajax-jog-dropdown").is(":visible") ? (b.next(".top-ajax-jog-dropdown").fadeOut(150), b.removeClass("active")) : (b.next(".top-ajax-jog-dropdown").fadeIn(150), b.addClass("active"));
            var c = b.next(".top-ajax-jog-dropdown").find(".btn-group > .active > input").attr("id");
            b = null, c = null, a.preventDefault()
        });
        
        
        $(".language").click(function() {

			var actual_lang = $("#actual_lang").val();
			var new_lang = $(this).attr("data-value");
		
			if (actual_lang != new_lang) {
				$("#lang").val(new_lang);
				openWait('<i class="fa fa-flag"></i><br> Loading language ');
				$("#lang_form").submit();
			}
		
		});
		
		$(".lock-ribbon").click(function() {
			lockscreen();
		});
        
        $(document).mouseup(function(a) {
            $(".top-ajax-temperatures-dropdown").is(a.target) || 0 !== $(".top-ajax-temperatures-dropdown").has(a.target).length || ($(".top-ajax-temperatures-dropdown").fadeOut(150), $(".top-ajax-temperatures-dropdown").prev().removeClass("active"))
            $(".top-ajax-jog-dropdown").is(a.target) || 0 !== $(".top-ajax-jog-dropdown").has(a.target).length || ($(".top-ajax-jog-dropdown").fadeOut(150), $(".top-ajax-jog-dropdown").prev().removeClass("active"))
        });
        
        $('#fabtotum-activity').click(function(e) {
			var $this = $(this);
	
			if ($this.find('.badge').hasClass('bg-color-red')) {
				//$this.find('.badge').removeClassPrefix('bg-color-');
			}
	
			if (!$this.next('.ajax-dropdown').is(':visible')) {
				$this.next('.ajax-dropdown').fadeIn(150);
				$this.addClass('active');
			} else {
				$this.next('.ajax-dropdown').fadeOut(150);
				$this.removeClass('active');
			}
	
			var theUrlVal = $this.next('.ajax-dropdown').find('.btn-group > .active > input').attr('id');
			
			//clear memory reference
			$this = null;
			theUrlVal = null;
					
			e.preventDefault();
		});
		
		$('input[name="fabtotum-activity"]').change(function() {
			
			var $this = $(this);
			url = $this.attr('id');
			container = $('.ajax-notifications');
			
			loadURL(url, container);
			
			//clear memory reference
			$this = null;		
		});
			
		$this = $('#activity > .badge');
	
		if (parseInt($this.text()) > 0) {
			$this.addClass("bg-color-red bounceIn animated");
			
			//clear memory reference
			$this = null;
		}
		
		
	};
	
	app.drawBreadCrumb = function () {
			
		var a = $("nav li.active > a");
		var b = a.length;
		a.each(function() {
			bread_crumb.append($("<li></li>").html($.trim($(this).clone().children(".badge").remove().end().text()))), --b || (document.title = 'FABUI - ' + bread_crumb.find("li:last-child").text())
		});
		
	};
	
	
	app.freezeMenu = function(except){
		var excepet_item_menu = new Array();
		excepet_item_menu[0] = 'dashboard';
		excepet_item_menu[1] = 'objectmanager';
		excepet_item_menu[2] = 'make/history';
		excepet_item_menu[3] = except;
		
		var a = $("nav li > a");
		a.each(function() {
			var controller = $(this).attr('data-controller');
			if(jQuery.inArray( controller, excepet_item_menu ) >= 0 ){
				if(controller == except){
					$(this).append('<span class="badge bg-color-red pull-right inbox-badge freeze-menu">!</span>');
				}
			}else{
				$(this).addClass('menu-disabled');
				$(this).removeAttr('href');
			}
		});
	};
	
	app.checkForFirstSetupWizard = function(){
		$.get('/fabui/controller/first_setup', function(data, status){
			if(data.response == true){
				setTimeout(function() {
						$.smallBox({
							title : "Wizard Setup",
							content : "It seems that you still did not complete the first recommended setup:<ul><li>Manual Bed Calibration</li><li>Probe Lenght Calibration</li><li>Engage Feeder</li></ul><br>Without a proper calibration you will not be able to use the FABtotum correctly<br>Do you want to do it now?<br><br><p class='text-align-right'><a href='/fabui/maintenance/first-setup' class='btn btn-primary btn-sm'>Yes</a> <a href='javascript:dont_ask_wizard();' class='btn btn-danger btn-sm'>No</a> <a href='javascript:finalize_wizard();' class='btn btn-warning btn-sm'>Don't ask me anymore</a> </p>",
							color : "#296191",
							icon : "fa fa-warning swing animated"
						});
				}, 1000);
			}
		});
	}
	
	app.checkUpdateActionFile= function(){
		$.get('/fabui/controller/update_action_file', function(data, status){
			if(data.response == true){
				
				$.SmartMessageBox({
                    title: "<i class='fa fa-refresh'></i> <span class='txt-color-orangeDark'><strong>Update completed</strong></span> ",
                    content: "Now that the update is completed, please make sure to shutdown the machine, wait a few seconds and then turn it on again",
                    buttons: "[Shutdown]"
                }, function(ButtonPressed) {
                   if(ButtonPressed == 'Shutdown'){
                	   shutdown();
                   }
               });
				
			}
		});
	}

	return app;


})({});

jQuery(document).ready(function() {
		fabApp.FabActions();
		fabApp.domReadyMisc();
		fabApp.drawBreadCrumb();
		fabApp.checkForFirstSetupWizard();
		fabApp.checkUpdateActionFile();
});