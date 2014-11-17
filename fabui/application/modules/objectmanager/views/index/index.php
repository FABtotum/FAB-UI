<div class="row">
	<div class="col-xs-12 col-sm-10 col-md-10 col-lg-10">
		<h1 class="page-title txt-color-blueDark">
             <i class="icon-fab-manager fab-fw"></i> Objectmanager
		</h1>
	</div>
	<div class="col-xs-12 col-sm-2 col-md-2 col-lg-2">
		<div class="page-title pull-right">
			<a href="<?php  echo site_url('objectmanager/add')?>"
				class="btn btn-primary">Add new</a>
		</div>
	</div>
</div>
<!--
 <div class="row no-space"></div>
    <div class="row">
        <div class="col-sm-12">
            <div class="well">
            
                <div class="row">
                    
                    <div class="col-sm-2 pull-right">
                        <span class="easy-pie-title pull-right">Free disk space </span> 
                        <div class="easy-pie-chart txt-color-red easyPieChart pull-right" data-percent="<?php echo (100 * ($_disk_free_space / $_disk_total_space)) ?>" data-size="45" data-pie-size="30">
    				        <span class="percent percent-sign txt-color-red"><?php echo number_format((100 * ($_disk_free_space / $_disk_total_space)), 2, ',', '.') ?></span>
        				</div>
                         
                    </div>
                </div>
            </div>  
        </div>
    </div>
-->
<section id="widget-grid">
    <div class="row">
        <article class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
            <?php echo $_table; ?>
        </article>
    </div>
</section>