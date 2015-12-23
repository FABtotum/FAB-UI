<script type="text/javascript">
	
	var more_url = '<?php echo widget_url('instagram').'ajax/more.php' ?>';
	var more_hash_url = '';
	
	var $grid;


	var InstagramFancyBox = function() {
		return {
			initFancybox : function() {
				jQuery(".fancybox-instagram").fancybox({
					groupAttr : 'data-rel',
					prevEffect : 'fade',
					nextEffect : 'fade',
					openEffect : 'elastic',
					closeEffect : 'fade',
					closeBtn : true,
					helpers : {
						title : {
							type : 'float'
						}
					}
				});

				$(".fbox-modal").fancybox({
					maxWidth : 800,
					maxHeight : 600,
					fitToView : false,
					width : '70%',
					height : '70%',
					autoSize : false,
					closeClick : false,
					closeEffect : 'fade',
					openEffect : 'elastic'
				});
			}
		};

	}();
	
	
	function load_more(){
		
		
		$.ajax({
		  url: more_url,
		  type: "POST",
		  dataType: 'json',
		  data : {hash : more_hash_url}
		}).done(function( response ) {
		   
		   more_hash_url = response.hash_next_url;
		   var elements ='';
		   
		   $.each(response.images, function() {
				var $tmp = $(createGridImage(this));
				$grid.append( $tmp ).masonry( 'appended', $tmp );
				window.dispatchEvent(new Event('resize'));
					    
		 	});
		 	
		 	
		   
		});
	}
	
	
	function createGridImage(image){
		
		var html = '';
		
		html += '<div class="grid-item"><div class="grid-item-inner">';
		html += '<img class="user-profile-picture rounded-x" src="'+ image.user.profile_picture +'" /><span  class="instagram-profile-link"><a href="http://www.instagram.com/'+ image.user.username +'" target="_blank">'+ image.user.username +' </a></span>';
		html += '<a href="'+ image.image +'" class="fancybox-instagram" data-rel="fancybox-button" title="'+ image.text +'"><img src="'+ image.image +'" /></a>';
		
		html += '<ul class="list-inline">';
		
		if(image.likes > 0){
			html += '<li ><i class="fa  fa-heart txt-color-red"></i>'+ image.likes +'</li>';
		}
		
		if(image.comments > 0){
			html += '<li ><i class="fa  fa-comments txt-color-blue"></i>'+ image.comments +'</li>';
		}
		
		html += '</ul>';
		
		html += '<p>' + image.text + '</p>';
		html += '</div></div>';
		
		return html;
		
	}
	
</script>