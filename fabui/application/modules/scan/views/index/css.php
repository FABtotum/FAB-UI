<style>

.scan-mode {
    width:100%;
    background-color:white;
    -webkit-border-radius:5px;
    -moz-border-radius:5px;
    border-radius:5px;
    -moz-border-radius:5px;
    margin-bottom:10px;
    cursor:pointer;
}

.scan-mode:HOVER {
    background-color:#f1f6fc;
    -webkit-box-shadow:3px 0px 10px rgba(50,50,50,0.35);
    -moz-box-shadow:3px 0px 12px rgba(50,50,50,0.35);
    box-shadow:3px 0px 12px rgba(50,50,50,0.35);
    -webkit-border-radius:5px;
    -moz-border-radius:5px;
    border-radius:5px;
    -moz-border-radius:5px;
}

.my-selected {
}

.my-selected:before {
    display:block;
    position:absolute;
    content: " \f00c ";
    color:#fff;
    right:2px;
    top:0px;
    font-family:FontAwesome;
    z-index:1002;
}

.my-selected:after {
    width:0;
    height:0;
    border-top:35px solid #0091d9;
    border-left:35px solid rgba(0,0,0,0);
    position:absolute;
    display:block;
    right:0px;
    content: " ";
    top:0;
    z-index:1001;
    font-size:13px;
}

.mode-description {
    position:absolute; /* absolute position (so we can position it where we want)*/
    bottom:0px; /* position will be on bottom */
    left:0px;
    width:100%;
    /* styling bellow */
    background-color:black;
    color:white;
    opacity:0.6; /* transparency */
    filter:alpha(opacity=60); /* IE transparency */
}

.mode-description p {
    padding:10px;
    margin:0px;
}

.sfumatura {
    filter:alpha(opacity=20);
    -moz-opacity:.20;
    opacity:.20;
}

    
 .plane{
 	/*
    width: 223px;
    height: 235px;
    */
    width: 212px;
    height: 232px;
    background-image: url("<?php echo base_url()."application/modules/scan/assets/img/working_plane_simple.png "  ?>");  
 }

.jcrop-holder {
    margin: 0 auto !important;
    float: none !important;
}

.smart-form .input .icon-prepend + input {
  padding-left: 57px !important;
}

.smart-form .icon-prepend {
  left: 5px;
  padding-right: 20px;
  border-right-width: 1px;
  border-right-style: solid;
}


.error{
    background-color: #fff0f0 !important;
}


.fab-slider {
	background-color: #57889c!important;
}

.noUi-handle{
    background: #858585 !important;
    height: 30px ;
    width: 16px;
    border: 1px solid #fff;
    cursor: default;  
    box-shadow: none !important;
}

#probe-quality .noUi-handle{
	top: -5px !important;
}

</style>
