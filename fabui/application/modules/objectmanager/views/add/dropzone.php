<ul id="myTab1" class="nav nav-tabs bordered">
    <li class="active"><a href="#remote" data-toggle="tab">  Local Disk</a></li>
    <li><a href="#usb" class="check-usb" data-toggle="tab"> Usb Disk</a></li>
</ul>
<div id="myTabContent1" class="tab-content padding-10">
    <!-- dropzone -->
    <div class="tab-pane fade in active" id="remote">
         <div>
			<form enctype="multipart/form-data"
				action="<?php echo site_url('filemanager/upload'); ?>"
				class="dropzone" id="mydropzone"></form>
		</div>
    </div>
    <!-- usb disk -->
    <div class="tab-pane fade in" id="usb">
    </div>

</div>