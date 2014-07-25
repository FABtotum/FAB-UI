<script type="text/javascript">
$('.shortcut').hover(function() {
        var icon = $(this).find('i');
        var effect = icon.attr('data-effect');
        icon.addClass(effect);
      }, function() { 
        var icon = $(this).find('i');
        var effect = icon.attr('data-effect');
        icon.removeClass(effect);
      }
    );
</script>