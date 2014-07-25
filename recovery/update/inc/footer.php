<!--================================================== -->	

		<!-- PACE LOADER - turn this on if you want ajax loading to show (caution: uses lots of memory on iDevices)
		<script data-pace-options='{ "restartOnRequestAfter": true }' src="js/plugin/pace/pace.min.js"></script>-->

	    <!-- Link to Google CDN's jQuery + jQueryUI; fall back to local -->
	    <script src="//ajax.googleapis.com/ajax/libs/jquery/2.0.2/jquery.min.js"></script>
		<script> if (!window.jQuery) { document.write('<script src="/assets/js/libs/jquery-2.0.2.min.js"><\/script>');} </script>

	    <script src="//ajax.googleapis.com/ajax/libs/jqueryui/1.10.3/jquery-ui.min.js"></script>
		<script> if (!window.jQuery.ui) { document.write('<script src="/assets/js/libs/jquery-ui-1.10.3.min.js"><\/script>');} </script>

		<!-- JS TOUCH : include this plugin for mobile drag / drop touch events 		
		<script src="/assets/js/plugin/jquery-touch/jquery.ui.touch-punch.min.js"></script> -->

		<!-- BOOTSTRAP JS -->		
		<script src="/assets/js/bootstrap/bootstrap.min.js"></script>

		<!-- CUSTOM NOTIFICATION -->
		<script src="/assets/js/notification/SmartNotification.min.js"></script>
		 
		<!-- JARVIS WIDGETS 
		<script src="/assets/js/smartwidgets/jarvis.widget.min.js"></script>
		
		<!-- EASY PIE CHARTS<script src="/assets/js/plugin/easy-pie-chart/jquery.easy-pie-chart.min.js"></script>-->
		<!-- SPARKLINES 
		<script src="/assets/js/plugin/sparkline/jquery.sparkline.min.js"></script>
		-->
		<!-- JQUERY VALIDATE 
		<script src="/assets/js/plugin/jquery-validate/jquery.validate.min.js"></script>
		-->
		<!-- JQUERY MASKED INPUT 
		<script src="/assets/js/plugin/masked-input/jquery.maskedinput.min.js"></script>
		-->
		<!-- JQUERY SELECT2 INPUT 
		<script src="/assets/js/plugin/select2/select2.min.js"></script>
		-->

		<!-- JQUERY UI + Bootstrap Slider  
		<script src="/assets/js/plugin/bootstrap-slider/bootstrap-slider.min.js"></script>
		 -->
		<!-- browser msie issue fix -->
		<script src="/assets/js/plugin/msie-fix/jquery.mb.browser.min.js"></script>
		
		<!-- FastClick: For mobile devices -->
		<script src="/assets/js/plugin/fastclick/fastclick.js"></script>
		
		<!-- FastClick: For mobile devices -->
		<script src="/assets/js/fabtotum.js"></script>
		
		<!--[if IE 7]>
			
			<h1>Your browser is out of date, please update your browser by going to www.microsoft.com/download</h1>
			
		<![endif]-->
		
		<!-- MAIN APP JS FILE -->
		<script src="/assets/js/app.js"></script>

		<script type="text/javascript">
			runAllForms();
		</script>
		
		
		<script>
            
            var interval_monitor;
            var interval_refresh;
            var json_uri;
            
            var finished = false;
            
            var first_extracting = true;
            var first_installing = true;
            
            var timer_refresh = 0;    
        
			$(document).ready(function() {

				//init progress bar
				$("#progressbar").progressbar({value: 0});
                
                $('.download').on('click', ask_download);
				
				
			});
            
            
            
            function ask_download (){


				type    = $(this).attr('download-item');
				version = $(this).attr('download-version');
				
				$.SmartMessageBox({
					title : "Updates Center",
					content : "Do you want to download and install the new update?",
					buttons : '[No][Yes]'
				}, function(ButtonPressed) {
					if (ButtonPressed === "Yes") {
					   
                       $('.download').addClass('disabled');
                       start_update();
                       
					}
					if (ButtonPressed === "No") {
						
					}
		
				});
	
			}
            
            
            
            function start_update(){
                
                switch (type) {
                    
                    case 'myfab':
                        start_update_myfab();
                        break;
                    case 'marlin':
                        break;
                    
                }
                
            }
            
            
            
            
            function start_update_myfab(){
                
                
                $.ajax({
					  url: "/recovery/update/ajax/update_myfab.php",
					  type: "POST",
                      dataType: 'json'
				}).done(function( data ) {
						  
                            json_uri = data.json_uri;
                            
                            $('.progress-container').show();
                            
						    interval_monitor  = setInterval(monitor, 2500);

						    $('#download-container').slideDown('slow', function() {
								
						     });
                            
                         
                          
				});
                
                
                
            }
            
            
            
            function monitor(){
                
                
                if(finished == false){
                    json_call();
                }else{
                    finalize_download();
                }
                
            }
            
            
            
            function json_call(){
                
                	$.ajax({
					  url: json_uri,
					  dataType: 'json'
				}).done(function( response ) {
				    
                    
                    finished   = parseInt(response.completed) == 0 ? false : true;
                    var status = response.status;
                    
                    switch(status){
                        case 'downloading':
                            _downloading(response.download);
                            break;
                        case 'extracting':
                            _extracting(response.extract);
                            break;
                        case 'installing':
                            _installing(response.install);
                            break;
                    }
                    	  
				});
                
            }
            
            
            
            function _downloading(data){
                
                
                                
                $("#status").html("Downloading...");
                $('.progress-container').slideDown('slow', function() {});   
                var percent = data.percent;
				
				percent = number_format(precise_round(percent, 2), 2, ',', '.')						  

				$("#progress-download").attr('style', 'width:' + precise_round(data.percent, 2)+'%');
				$("#percentuale").html(percent + "%");
				$("#size").html(bytesToSize(data.downloaded) + " of " + bytesToSize(data.download_size));
				$("#velocita").html('(' + bytesToSize(data.velocita) + '/s)');  
                
            }
            
            
            function _extracting(data){
                
                if(first_extracting){
                    
                    $("#status").html("Download complete");
                    $("#progress-download").attr('style', 'width:100%');
    				$("#percentuale").html("<i class='fa fa-check'></i>");
    				$("#size").html("");
    				$("#velocita").html('');
                    first_extracting = false;
                    
                }else{
                    $("#status").html("Extracting files..");
                    $("#progress-download").attr('style', 'width:' + precise_round(data.percent, 2)+'%');
                    $("#percentuale").html(data.percent + "%");
                    
                }

            }
            
            
            function _installing(data){
                
                
                if(first_installing){
                    $("#status").html("Extraction complete");
                    $("#progress-download").attr('style', 'width:100%');
                    $("#percentuale").html("<i class='fa fa-check'></i>");
                    first_installing = false;
                }else{
                    $("#status").html("Installing new files");
                    $("#progress-download").attr('style', 'width:' + precise_round(data.percent, 2)+'%');
                    $("#percentuale").html(data.percent + "%");
                }
                
            }
            
            
            function finalize_download(){
                
                clearInterval(monitor);
                
                $("#status").html("Installation complete");
                $("#percentuale").html("<i class='fa fa-check'></i>");
                
                interval_refresh  = setInterval(refresh, 1000);
                
            }
            
            
            function refresh(){
                
                if(timer_refresh == 3){
                    
                    document.location.href = document.location.href;
                    
                }else{
                    timer_refresh++;
                }
                
            }


			
		</script>

	</body>
</html>