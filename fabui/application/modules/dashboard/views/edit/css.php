<style>
.layout {
	width: 100%;
	background-color: white;
	-webkit-border-radius: 5px;
	-moz-border-radius: 5px;
	border-radius: 5px;
	-moz-border-radius: 5px;
	margin-bottom: 10px;
	cursor: pointer;
}
.layout:HOVER {
	background-color: #f1f6fc;
	-webkit-box-shadow: 3px 0px 10px rgba(50, 50, 50, 0.35);
	-moz-box-shadow: 3px 0px 12px rgba(50, 50, 50, 0.35);
	box-shadow: 3px 0px 12px rgba(50, 50, 50, 0.35);
	-webkit-border-radius: 5px;
	-moz-border-radius: 5px;
	border-radius: 5px;
	-moz-border-radius: 5px;
}

.my-selected {
	
}

.my-selected:before {
	display: block;
	position: absolute;
	content: "\f00c";
	color: #fff;
	right: 2px;
	top: 0px;
	font-family: FontAwesome;
	z-index: 1002;
}

.my-selected:after {
	width: 0;
	height: 0;
	border-top: 35px solid #0091d9;
	border-left: 35px solid rgba(0, 0, 0, 0);
	position: absolute;
	display: block;
	right: 0px;
	content: "";
	top: 0;
	z-index: 1001;
	font-size: 13px;
}

.white-popup {
	position: relative;
	background: #FFF;
	padding: 20px;
	width: auto;
	max-width: 500px;
	margin: 20px auto;
}

.dd {
    width: 100% !important;
    max-width: 100% !important;
}


.wid{
    min-height: 50px;
    position: relative;

    
    
    
}

ol {
    list-style-type: none !important;
}

.item{
display: block;
font-size: 15px;
margin: 5px 0;
padding: 7px 15px;
color: #333;
text-decoration: none;
border: 1px solid #cfcfcf;
background: #fbfbfb;
cursor:pointer;
margin-left:-40px;
}

.item:hover{
    background: #FDDFB3!important;
border: 1px solid #FAA937;
color: #333!important;
}



</style>
