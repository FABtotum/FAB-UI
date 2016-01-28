<?php
require_once '/var/www/lib/config.php';


$reload = isset($_GET['manually']) ? filter_var($_GET['manually'], FILTER_VALIDATE_BOOLEAN) : false;

if($reload || !file_exists(INSTAGRAM_FEED_JSON) || !file_exists(INSTAGRAM_HASH_JSON)){
	require_once '/var/www/cron/instagram_feed.php';
}



$feed = json_decode(file_get_contents(INSTAGRAM_FEED_JSON), true);
$hash = json_decode(file_get_contents(INSTAGRAM_HASH_JSON), true);

$hash_ids = array();
$images = array();


foreach($hash['data'] as $item){
	$hash_ids[] = $item['id'];
	$images[] = array('image' => $item['images']['standard_resolution']['url'], 'text' =>$item['caption']['text'], 'user'=>$item['user'], 'likes'=>$item['likes']['count'], 'comments'=>$item['comments']['count'], 'time' => $item['created_time'], 'link'=>$item['link']);
}


foreach($feed['data'] as $item){
	if(!in_array($item['id'], $hash_ids)){
		$images[] = array('image' => $item['images']['standard_resolution']['url'], 'text' =>$item['caption']['text'], 'user'=>$item['user'], 'likes'=>$item['likes']['count'], 'comments'=>$item['comments']['count'], 'time' => $item['created_time'], 'link'=>$item['link']);
	}
}




uasort($images, 'cmp');

function cmp($a, $b) {
  if ($a['time'] == $b['time']) {
    return 0;
  }

  return ($a['time'] > $b['time']) ? -1 : 1;
}



//shuffle($images);

?>

<div class="row images-container">
	<div class="col-sm-12">
		<div class="grid">
			<div class="grid-sizer"></div>
			<?php foreach($images as $item): ?>
				<div class="grid-item">
					<div class="grid-item-inner">
						
						<ul class="list-inline">
							<li style="display:inline;"><img class="user-profile-picture rounded-x" src="<?php echo $item['user']['profile_picture'] ?>" /></li>
							<li style="display:inline;margin-left: -15px;"><span  class="instagram-profile-link"><a href="http://www.instagram.com/<?php echo $item['user']['username'] ?>" target="_blank"><?php echo $item['user']['username'] ?></a></span></li>
							<li style="display:inline;" class="pull-right"><a title="View on Instagram" target="_blank" href="<?php echo $item['link']; ?>"><i class="fa fa-instagram"></i></a></li>
						</ul>
						
						
						<a href="<?php echo $item['image'] ?>" class="fancybox-instagram" data-rel="fancybox-button" title="<?php echo $item['text'] ?>"><img src="<?php echo $item['image'] ?>" /></a>
						<ul class="list-inline">
							<?php if($item['likes'] > 0): ?>
							<li class="font-xs" ><i class="fa  fa-heart txt-color-red"></i> <?php echo $item['likes']; ?></li>
							<?php endif; ?>
							<?php if($item['comments'] > 0): ?>
							<li class="font-xs"><i class="fa  fa-comments txt-color-blue"></i> <?php echo $item['comments']; ?></li>
							<?php endif; ?>
							<li class="pull-right text-muted font-xs"><?php echo date('j M, Y', $item['time']); ?></li>
						</ul>				
						<p><?php echo $item['text'] ?></p>
					</div>
				</div>
			<?php endforeach; ?>
		
		</div>
	</div>
	<div>
		<div class="col-sm-12">
			<a href="http://www.instagram.com/fabtotum" target="_blank" class="btn btn-primary btn-block load-more"><i class="fa fa-instagram"></i> View More</a>
		</div>
	</div>
</div>
<script type="text/javascript">

	more_hash_url = '<?php echo $hash['pagination']['next_url']?>';

	$(document).ready( function() {
		
		
		
		$grid = $('.grid').masonry({
			itemSelector: '.grid-item',
		    percentPosition: true,
		    columnWidth: '.grid-sizer',
		    
		});
		
		
		$grid.imagesLoaded().progress( function() {
		    $grid.masonry();
		});
		
	
		
		/*$(".load-more").on('click', load_more);*/ 
		  
		InstagramFancyBox.initFancybox();
	});
</script>


