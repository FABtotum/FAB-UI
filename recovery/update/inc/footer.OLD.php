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
		
		<!-- EASY PIE CHARTS
		<script src="/assets/js/plugin/easy-pie-chart/jquery.easy-pie-chart.min.js"></script>
		 -->
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
			var interval_download;
			var interval_install;
			var interval_extract;
			var interval_pause;
			var file;
			var download_started  = false;
			var download_finished = false;
			var interval_pause_after_download;
			var interval_pause_after_extract;
			var extract_started = false;
			var extract_finished = false;
			var seconds = 0;

			var type;
			var version;

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

						//update_manager();
						download(type, version);
						$('#progress_container').removeClass('hide');
						$('.download').attr('disabled', 'disbled');
						
					}
					if (ButtonPressed === "No") {
						
					}
		
				});

				
				
				
			}




			function update_manager(){

				//console.log("update_manager");

				/** START DOWNLOAD */
				interval_download  = setInterval(download_manager, 100);
				
				
				
				/** START EXTRACT */
				interval_extract  = setInterval(extract_manager, 100);
				
				
				
				
				/** START INSTALL */
				interval_install = setInterval(install_manager, 500);
				
			
			}


			function download(){


				$('.download-container').removeClass('hide');
				
				$.ajax({
					  url: "/recovery/update/ajax/download.php",
					  type: "POST",
                      dataType: 'json',
					  data: {type: type, version:version}
				}).done(function( data ) {
						  
                          if(data.status == 'ok'){
                            
                            $('.progress-container').show();
                            
                            $("#status").html("Downloading...");
                            download_started = true;
						    interval_download  = setInterval(donwnload_manager, 5000);

						    $('#download-container').slideDown('slow', function() {
								
						     });
                            
                          }
                          
				});
				
			}


			function donwnload_manager(){

				if(!download_finished){
					download_progress();
				}else{
					finalize_download();
				}
				
			}


			function finalize_download(){
				
				clearInterval(interval_download);
				$("#status").html("Download complete");
				$("#percentuale").html('<i class="fa fa-check"></i>');
				$("#velocita").html('');
				$("#extract-status").html('Preparing file <i class="fa fa-spinner fa-spin"></i>');
				
				interval_pause_after_download = setInterval(pause_after_download, 1000);
				$('.extract-container').removeClass('hide');
				
				
			}
			
			
			function download_progress(){

				$.ajax({
					  url: "/recovery/update/temp/"+ type +"_progress.json",
					  async: true,
					  dataType: 'json'
				}).done(function( response ) {

						var percent = response.percent;
						file        = response.file;

						percent = number_format(precise_round(percent, 2), 2, ',', '.')						  

						$("#progress-download").attr('style', 'width:' + precise_round(response.percent, 2)+'%');
						$("#percentuale").html(percent + "%");
						$("#size").html(bytesToSize(response.downloaded) + " of " + bytesToSize(response.download_size));
						$("#velocita").html('(' + bytesToSize(response.velocita) + '/s)');  
						download_finished = parseInt(percent) == 100 ? true : false;
						 
							  
				});

				
			 }

			function extract_manager(){
				
				//if(!extract_finished){
					extract();
				//}else{
				//	finalize_extract();
					//console.log("extract finished");
				//}
			}

			
			 function extract(){

				 
					
				 $.ajax({
					  url: "/recovery/update/ajax/extract.php",
					  type: "POST",
					  async: true,
					  dataType: 'json',
					  data: {type: type,file:file},
					  beforeSend: function(  ) {
						  $("#extract-status").html('Extracting files <i class="fa fa-spinner fa-spin"></i>');
						  $("#progress-extract").attr('style', 'width:60%');
						  extract_started = true;
				      }
				}).done(function( response ) {
					extract_finished = true;
					finalize_extract();
						 
				});
				 
				 
			 }


			 function finalize_extract(){
				 //clearInterval(interval_extract);
				 $("#progress-extract").attr('style', 'width:100%');
				 $("#extract-status").html("Extracting complete");
				 $("#extract-percentuale").html('<i class="fa fa-check"></i>');
				 $("#install-status").html('Preparing intallation <i class="fa fa-spinner fa-spin"></i>');
				 interval_pause_after_extract = setInterval(pause_after_extract, 1000);
				 $('.install-container').removeClass('hide');
			 }


			function install_manager(){

				install();
			
					
			}




			function install(){

				
				
				$.ajax({
					  url: "/recovery/update/ajax/install.php",
					  type: "POST",
					  async: true,
					  dataType: 'json',
					  data: {type: type,file:file},
					  beforeSend: function(  ) {
						  $("#install-status").html('Updating files <i class="fa fa-spinner fa-spin"></i>');
						  $("#progress-install").attr('style', 'width:50%');
						  install_started = true;
				      }
				}).done(function( response ) {
					console.log('finish');
					install_finished = true;
					finalize_installation();
						 
				});
				
			}


			function finalize_installation(){

				console.log("finalize installation");
				 $("#install-status").html('Installation complete');
				 $("#progress-install").attr('style', 'width:100%');
				 $("#install-percentuale").html('<i class="fa fa-check"></i>');

				document.location.href = '/recovery/update/?type='+type+'&update=1';
				 
			}


			 function pause_after_download(){

				 if(seconds < 3){
					 seconds++;
				 }else{
					 clearInterval(interval_pause_after_download);
					 seconds = 0;
					 $("#progress-extract").attr('style', 'width:20%');
					 extract_manager();
					 
				 }
			 }

			 function pause_after_extract(){

				 if(seconds < 3){
					 seconds++;
					 //console.log(seconds);
				 }else{
					 clearInterval(interval_pause_after_extract);
					 seconds = 0;
					 install_manager();
					 
				 }
				 
			 }


			
			
		</script>

	</body>
</html>