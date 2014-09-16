<section id="widget-grid">    
   	<!-- row -->
	<div class="row">
         <!-- Blocco 2 -->
        <div class="col-sm-6">
        
            <?php foreach($_blok_1 as $widget): ?>
            <article class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                <?php echo $widget; ?>
            </article>
            <?php endforeach; ?>
        
        </div>
         <!-- Blocco 3 -->
        <div class="col-sm-6">
            
            <?php foreach($_blok_2 as $widget): ?>
            <article class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                <?php echo $widget; ?>
            </article>
            <?php endforeach; ?>
            
        </div>
            
	</div>
	<!-- end row -->
    
</section>
