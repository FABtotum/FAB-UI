<script type="text/javascript">
	var TwitterFancyBox = function() {
		return {
			initFancybox : function() {
				jQuery(".fancybox-twitter").fancybox({
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
</script>