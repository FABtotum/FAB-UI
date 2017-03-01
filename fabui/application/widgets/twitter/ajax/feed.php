<?php
require_once '/var/www/lib/config.php';
require_once '/var/www/lib/utilities.php';

$reload = isset($_GET['manually']) ? filter_var($_GET['manually'], FILTER_VALIDATE_BOOLEAN) : false;

if($reload || !file_exists(TWITTER_FEED_JSON)){
	require_once '/var/www/cron/twitter_feed.php';
}


$twitter = json_decode(file_get_contents(TWITTER_FEED_JSON), true);

?>
<div class="row">
	<div class="col-sm-12">
		<ul class="twitter-posts">
		<?php foreach($twitter as $feed): ?>
			
			
			<?php
			
		
				$tweet = $feed;
				
				$has_photo = false;
				$retweeted = false;
				
				if(isset($feed['retweeted_status'])){
					$tweet = $feed['retweeted_status'];
					$retweeted = true;
				}
				
				$text = $tweet['text'];
				
				
				
				$hashtags = $tweet['entities']['hashtags'];
				$user_mentions = $tweet['entities']['user_mentions'];
				$urls = $tweet['entities']['urls'];
				
				foreach($hashtags as $hash){
					$text = str_replace("#".$hash['text'], "<a target='_blank' href='https://twitter.com/search?q=".$hash['text']."'>#".$hash['text']."</a>", $text);
				}
				
				foreach($user_mentions as $mention){
					$text = str_replace("@".$mention['screen_name'], "<a class='link' target='_blank' href='https://twitter.com/".$mention['screen_name']."'>@".$mention['screen_name']."</a>", $text);
				}
				
				foreach($urls as $url){
					$text = str_replace($url['url'], "<a class='link' target='_blank' href='".$url['url']."'>".$url['url']."</a>", $text);
				}
				
				if(isset($tweet['extended_entities'])){
					
					$images = array();
					foreach($tweet['extended_entities']['media'] as $media){
						if($media['type'] == 'photo'){
							$has_photo = true;
							$images[] = $media['media_url'];
						}
					}
				}
		
			?>
			
			<li>
				<?php if($retweeted): ?><div class="twitter-post-retwetted"><p><i class="fa fa-retweet txt-color-green"></i> Retweeted by Fabtotum</p></div><?php endif; ?>
				<img class="rounded-x" src="<?php echo $tweet['user']['profile_image_url']; ?>">
				<div class="twitter-posts-in">
					<strong><?php echo $tweet['user']['name'];?></strong>
					<span><a target="_blank" href="http://twitter.com/<?php echo $tweet['user']['screen_name']; ?>">@<?php echo $tweet['user']['screen_name']; ?></a></span>
					<span class="pull-right"> <?php echo date('j M',strtotime($tweet['created_at'])); ?></span>
					<p><?php echo $text; ?></p>
					<?php if($has_photo): ?>
					<div >
						<?php foreach($images as $image): ?>
							<a title="<?php echo $text; ?>" class="fancybox-twitter" data-rel="fancybox-button" href="<?php echo $image; ?>"><img class="twitter-media-photo" src="<?php echo $image; ?>"></a>
						<?php endforeach; ?>
					</div>
					<?php endif; ?>
					
					<ul class="list-inline pull-right">
						<?php if($tweet['retweet_count'] > 0): ?>
							<li class="txt-color-green"><i class="fa fa-retweet"></i> (<?php echo $tweet['retweet_count'] ?>)</li>
						<?php endif; ?>
						<?php if($tweet['favorite_count'] > 0): ?>
							<li class="txt-color-red"><i class="fa fa-heart "></i> (<?php echo $tweet['favorite_count']; ?>)</li>
						<?php endif; ?>
						<li>
							<a target="_blank" href="https://twitter.com/statuses/<?php echo $tweet['id_str']; ?>" title="View on twitter"><i class="fa fa-twitter"></i></a>
						</li>
					</ul>
				</div>
			</li>
		
		<?php endforeach; ?>
		</ul>
	</div>
</div>
<div class="row">
	<div class="col-sm-12">
		<a href="http://www.twitter.com/fabtotum" target="_blank" class="btn btn-primary btn-block load-more"><i class="fa fa-twitter"></i> View More</a>
	</div>
</div>
<script type="text/javascript">
	$(document).ready( function() {
		TwitterFancyBox.initFancybox();
	});
</script>