<?php 
require_once '/var/www/lib/config.php';
require_once '/var/www/lib/utilities.php';

$reload = isset($_GET['manually']) ? filter_var($_GET['manually'], FILTER_VALIDATE_BOOLEAN) : false;

if($reload || !file_exists(INSTAGRAM_FEED_JSON) || file_get_contents(INSTAGRAM_FEED_JSON) == ''){
	require_once '/var/www/cron/instagram_feed.php';
}

$show = false;

if(file_get_contents(INSTAGRAM_FEED_JSON) != ''){
	$feed = json_decode(file_get_contents(INSTAGRAM_FEED_JSON), true);
	$items = $feed['data'];
	$items = array_unique($items, SORT_REGULAR);
	
	$filtered_items    = array();
	$new_items_id = array();
	
	foreach ($items as $i) {
		if(!in_array($i['id'], $new_items_id)){
			array_push($new_items_id, $i['id']);
			$filtered_items[] = $i;
		}
	}
	$items = $filtered_items;
	uasort($items, 'cmp');
	$show = true;

}
function cmp($a, $b) {
	
	if ($a['date'] == $b['date']) {
		return 0;
	}

	return ($a['date'] > $b['date']) ? -1 : 1;
}


?>
<div class="row images-container">
	<div class="col-sm-12">
		<div class="grid">
			<div class="grid-sizer"></div>
			<?php if($show):?>
			<?php foreach($items as $item): ?>
				<div class="grid-item">
					<div class="grid-item-inner">
						<ul class="list-inline">
							<li class="font-xs" ><a target="_blank" href="http://www.instagram.com/p/<?php echo $item['code'] ?>"><i class="fa  fa-instagram fa-fw fa-lg"></i></a></li>
							<li class="pull-right text-muted font-xs"><?php echo date('j M, Y', $item['date']); ?></li>
						</ul>	
						<a href="<?php echo $item['display_src'] ?>" class="fancybox-instagram" data-rel="fancybox-button" title="<?php echo $item['caption'] ?>"><img src="<?php echo $item['display_src'] ?>" /></a>
						<ul class="list-inline">
							<?php if($item['likes']['count'] > 0): ?>
							<li class="font-xs" ><i class="fa  fa-heart txt-color-red"></i> <?php echo $item['likes']['count']; ?></li>
							<?php endif; ?>
							<?php if($item['comments']['count'] > 0): ?>
							<li class="font-xs"><i class="fa  fa-comments txt-color-blue"></i> <?php echo $item['comments']['count']; ?></li>
							<?php endif; ?>
						</ul>	
						<p><?php echo word_limiter($item['caption'], 50) ?></p>
					</div>
				</div>
			<?php endforeach; ?>
			<?php endif;?>
		</div>
	</div>
	<div>
		<div class="col-sm-12">
			<a href="http://www.instagram.com/fabtotum" target="_blank" class="btn btn-primary btn-block load-more"><i class="fa fa-instagram"></i> View More</a>
		</div>
	</div>
</div>
<script type="text/javascript">
	more_hash_url = '';
	$(document).ready( function() {
		$grid = $('.grid').masonry({
			itemSelector: '.grid-item',
		    percentPosition: true,
		    columnWidth: '.grid-sizer',    
		});
		$grid.imagesLoaded().progress( function() {
		    $grid.masonry();
		});
		InstagramFancyBox.initFancybox();
	});
</script>