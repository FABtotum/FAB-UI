<?php
shell_exec('sudo python /var/www/fabui/python/gmacro.py shutdown');
echo "<h2 style='text-align:center;'>Shutdown in progress...</h2>";
?>
<script type="text/javascript">
	setTimeout(function(){
		
		document.getElementById('final-message').innerHTML = 'Now you can switch off the power';
		
	}, 15000);
</script>

<h2 style="text-align:center;" id="final-message"></h2>
<?php
shell_exec('sudo shutdown now');
?>
