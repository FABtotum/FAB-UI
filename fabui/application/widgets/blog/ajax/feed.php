<?php
require_once '/var/www/lib/config.php';

$reload = isset($_GET['manually']) ? filter_var($_GET['manually'], FILTER_VALIDATE_BOOLEAN) : false;

if($reload || !file_exists(BLOG_FEED_XML)){
	require_once '/var/www/cron/blog_feed.php';
}

$xml = simplexml_load_file(BLOG_FEED_XML,'SimpleXMLElement', LIBXML_NOCDATA);
$feeds = $xml->channel->item;





$doc = new DOMDocument();

?>

<?php foreach($feeds as $feed):?>
	<?php
		//if(!isCategoryAvailable($feed->category)) continue;
		$doc->loadHTML($feed->description);  
		$images = $doc->getElementsByTagName('img');
		$paragraphs = $doc->getElementsByTagName('p');
		$image_src = '';
		foreach($images as $tag) {
			$image_src = $tag->getAttribute('src');
			$tag->parentNode->removeChild($tag); 
			
		}
	?>
	<div class="row">
		<div class="col-md-4">
			<?php if(isset($image_src)): ?>
			<img class="img-responsive" src="<?php echo $image_src; ?>">
			<?php endif; ?>
			<ul class="list-inline" style="margin-top:5px;">
				<li class="font-xs">
					 <?php echo date('j M, Y',strtotime($feed->pubDate)); ?>
				</li>
			</ul>
		</div>
		<div class="col-md-8  padding-left-0">
			<h3 class="margin-top-0"><a target="_blank" href="<?php echo $feed->guid ?>"><?php echo $feed->title; ?></a></h3>
			<p style="text-align: justify;"><?php echo str_replace('[…]', '...', $doc->textContent) ; ?></p>
			<a class="btn btn-primary" href="<?php echo $feed->guid ?>" target="_blank" > Read more </a>
		</div>
	</div>
	<hr>
<?php endforeach; ?>


<?php 


function isCategoryAvailable($category)
{
	$category_to_show = array('Fabtotum Development', 'Fabtotum Team');
	if(is_array($category)){
		$return  = false;
		foreach($category as $cat){
			$return = in_array($cat, $category_to_show);
			return $return;
		}
	}else{
		return in_array($category, $category_to_show);
	}
	return false;
}

?>