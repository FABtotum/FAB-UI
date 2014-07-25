<div class="row">
	<div class="col-xs-12 col-sm-10 col-md-10 col-lg-10">
		<h1 class="page-title txt-color-blueDark">
             <i class="icon-fab-manager fab-fw"></i> Objectmanager
		</h1>
	</div>
	<div class="col-xs-12 col-sm-2 col-md-2 col-lg-2">
		<div class="page-title pull-right">
			<a href="<?php  echo site_url('objectmanager/add')?>"
				class="btn btn-default">Add new</a>
		</div>
	</div>
</div>
<section id="widget-grid">
    <div class="row">
        <article class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
            <?php echo $_table; ?>
        </article>
    </div>
</section>
