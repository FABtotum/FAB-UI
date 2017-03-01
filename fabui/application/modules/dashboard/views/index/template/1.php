<div class="row">
    <div class="col-xs-6 col-sm-3 col-md-3 col-lg-3">
    	<h1 class="page-title txt-color-blueDark">
    		<i class="fa fa-home fa-fw "></i> Dashboard
    		
    	</h1>
    </div>
    
    <!--
    <div class="col-xs-6 col-sm-9 col-md-9 col-lg-9 text-align-right">
		<div class="page-title">
			<a href="<?php echo site_url('dashboard/edit') ?>" class="btn btn-default">Edit</a>
		</div>
	</div>
	-->
</div>
<section id="widget-grid">
	<!-- row -->
	<div class="row">
    
        <!-- Blocco 1 -->
            <?php foreach($_blok_1 as $widget): ?>
            <article class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                <?php echo $widget; ?>
            </article>
            <?php endforeach; ?>            
        <!-- Fine Blocco 1 -->
        
            
	</div>
	<!-- end row -->
    
    
   	<!-- row -->
	<div class="row">
    
            <!-- Blocco 2 -->
            <?php foreach($_blok_2 as $widget): ?>
            <article class="col-xs-12 col-sm-3 col-md-6 col-lg-3">
                <?php echo $widget; ?>
            </article>
            <?php endforeach; ?>
        
            <!-- Fine Blocco 2 -->
        
            <!-- Blocco 3 -->
        
            
            <?php foreach($_blok_3 as $widget): ?>
            <article class="col-xs-12 col-sm-9 col-md-6 col-lg-9">
                <?php echo $widget; ?>
            </article>
            <?php endforeach; ?>
            
            <!-- Fine Blocco 3 -->
            
	</div>
	<!-- end row -->
    
</section>
