/***
 * 
 *  Simple class for getting information of gcodes files
 * 
 * 
 * 
 * 
 */
var GCode_Analizer = function(url) {

	this.url = url;
	this.g_code = [];
	this.model = [];
	

	this.firstReport;
	this.z_heights = {};

	this.gCodeOptions = {
		sortLayers : false,
		purgeEmptyLayers : true,
		analyzeModel : false
	};
	this.max = {
		x : undefined,
		y : undefined,
		z : undefined
	};
	this.min = {
		x : undefined,
		y : undefined,
		z : undefined
	};
	this.modelSize = {
		x : undefined,
		y : undefined,
		z : undefined
	};

	this.filamentByLayer = {};
	
	this.filamentByExtruder = {};
	
	this.totalFilament = 0;
	
	this.printTime = 0;
	
	this.printTimeByLayer = {};
	
	this.layerHeight = 0;
	
	this.layerCnt = 0;
	
	this.speeds = {
		extrude : [],
		retract : [],
		move : []
	};
	this.speedsByLayer = {
		extrude : {},
		retract : {},
		move : {}
	};
	this.volSpeeds = [];
	this.volSpeedsByLayer = {};
	this.extrusionSpeeds = [];
	this.extrusionSpeedsByLayer = {};
	
	
	
	//var get_file_call  = $.ajax({ url : this.url, dataType : 'text' });
	var get_file_call = $.get( url );
	
	
	
	/**
	 * LOAD FILE FROM URL
	 */
	
	this.load_file = function() {
		
		
		console.log('load file');

		var _temp = null

		$.ajax({
			url : this.url,
			async : false
		}).done(function(data) {
			_temp = data;	
		});
		
		this.g_code = _temp.split(/\n/);

		
	}

	/**
	 * PARSE GCODE FILE
	 */
	this.do_parse = function() {
		
	//function do_parse(){

		var argChar, numSlice;
		this.model = [];
		var sendLayer = undefined;
		var sendLayerZ = 0;
		var sendMultiLayer = [];
		var sendMultiLayerZ = [];
		var lastSend = 0;
		// console.time("parseGCode timer");
		var reg = new RegExp(/^(?:G0|G1)\s/i);
		var comment = new RegExp()
		var j, layer = 0, extrude = false, prevRetract = {
			e : 0,
			a : 0,
			b : 0,
			c : 0
		}, retract = 0, x, y, z = 0, f, prevZ = 0, prevX, prevY, lastF = 4000, prev_extrude = {
			a : undefined,
			b : undefined,
			c : undefined,
			e : undefined,
			abs : undefined
		}, extrudeRelative = false, volPerMM, extruder;
		var dcExtrude = false;
		var assumeNonDC = false;

		for ( var i = 0; i < this.g_code.length; i++) {
			// for(var len = gcode.length- 1, i=0;i!=len;i++){
			x = undefined;
			y = undefined;
			z = undefined;

			volPerMM = undefined;
			retract = 0;

			extrude = false;
			extruder = null;
			prev_extrude["abs"] = 0;
			this.g_code[i] = this.g_code[i].split(/[\(;]/)[0];

			// prevRetract=0;
			// retract=0;
			// if(gcode[i].match(/^(?:G0|G1)\s+/i)){
			if (reg.test(this.g_code[i])) {
				var args = this.g_code[i].split(/\s/);
				for (j = 0; j < args.length; j++) {
					// console.log(args);
					// if(!args[j])continue;
					switch (argChar = args[j].charAt(0).toLowerCase()) {
					case 'x':
						x = args[j].slice(1);
						// if(x === prevX){
						// x=undefined;
						// }
						break;
					case 'y':
						y = args[j].slice(1);
						// if(y===prevY){
						// y=undefined;
						// }
						break;
					case 'z':
						z = args[j].slice(1);
						z = Number(z);
						if (z == prevZ)
							continue;
						// z = Number(z);
						if (this.z_heights.hasOwnProperty(z)) {
							layer = this.z_heights[z];
						} else {
							layer = this.model.length;
							this.z_heights[z] = layer;
						}
						sendLayer = layer;
						sendLayerZ = z;
						// if(parseFloat(prevZ) < )
						// if(args[j].charAt(1) === "-")layer--;
						// else layer++;
						prevZ = z;
						break;
					case 'e':
					case 'a':
					case 'b':
					case 'c':
						assumeNonDC = true;
						extruder = argChar;
						numSlice = parseFloat(args[j].slice(1)).toFixed(3);

						if (!extrudeRelative) {
							// absolute extrusion positioning
							prev_extrude["abs"] = parseFloat(numSlice)
									- parseFloat(prev_extrude[argChar]);

						} else {
							prev_extrude["abs"] = parseFloat(numSlice);
						}
						extrude = prev_extrude["abs"] > 0;
						if (prev_extrude["abs"] < 0) {
							prevRetract[extruder] = -1;
							retract = -1;
						} else if (prev_extrude["abs"] == 0) {
							// if(prevRetract <0 )prevRetract=retract;
							retract = 0;
						} else if (prev_extrude["abs"] > 0
								&& prevRetract[extruder] < 0) {
							prevRetract[extruder] = 0;
							retract = 1;
						} else {
							// prevRetract = retract;
							retract = 0;
						}
						prev_extrude[argChar] = numSlice;

						break;
					case 'f':
						numSlice = args[j].slice(1);
						lastF = numSlice;
						break;
					default:
						break;
					}
				}
				if (dcExtrude && !assumeNonDC) {
					extrude = true;
					prev_extrude["abs"] = Math.sqrt((prevX - x) * (prevX - x)
							+ (prevY - y) * (prevY - y));
				}
				if (extrude && retract == 0) {
					volPerMM = Number(prev_extrude['abs']
							/ Math.sqrt((prevX - x) * (prevX - x) + (prevY - y)
									* (prevY - y)));
				}
				if (!this.model[layer])
					this.model[layer] = [];
				// if(typeof(x) !== 'undefined' || typeof(y) !== 'undefined'
				// ||typeof(z) !== 'undefined'||retract!=0)
				this.model[layer][this.model[layer].length] = {
					x : Number(x),
					y : Number(y),
					z : Number(z),
					extrude : extrude,
					retract : Number(retract),
					noMove : false,
					extrusion : (extrude || retract) ? Number(prev_extrude["abs"])
							: 0,
					extruder : extruder,
					prevX : Number(prevX),
					prevY : Number(prevY),
					prevZ : Number(prevZ),
					speed : Number(lastF),
					gcodeLine : Number(i),
					volPerMM : typeof (volPerMM) === 'undefined' ? -1
							: volPerMM.toFixed(3)
				};
				// {x: x, y: y, z: z, extrude: extrude, retract: retract,
				// noMove:
				// false, extrusion: (extrude||retract)?prev_extrude["abs"]:0,
				// prevX: prevX, prevY: prevY, prevZ: prevZ, speed: lastF,
				// gcodeLine: i};
				if (typeof (x) !== 'undefined')
					prevX = x;
				if (typeof (y) !== 'undefined')
					prevY = y;
			} else if (this.g_code[i].match(/^(?:M82)/i)) {
				extrudeRelative = false;
			} else if (this.g_code[i].match(/^(?:G91)/i)) {
				extrudeRelative = true;
			} else if (this.g_code[i].match(/^(?:G90)/i)) {
				extrudeRelative = false;
			} else if (this.g_code[i].match(/^(?:M83)/i)) {
				extrudeRelative = true;
			} else if (this.g_code[i].match(/^(?:M101)/i)) {
				dcExtrude = true;
			} else if (this.g_code[i].match(/^(?:M103)/i)) {
				dcExtrude = false;
			} else if (this.g_code[i].match(/^(?:G92)/i)) {
				var args = this.g_code[i].split(/\s/);
				for (j = 0; j < args.length; j++) {
					switch (argChar = args[j].charAt(0).toLowerCase()) {
					case 'x':
						x = args[j].slice(1);
						break;
					case 'y':
						y = args[j].slice(1);
						break;
					case 'z':
						z = args[j].slice(1);
						prevZ = z;
						break;
					case 'e':
					case 'a':
					case 'b':
					case 'c':
						numSlice = parseFloat(args[j].slice(1)).toFixed(3);
						extruder = argChar;
						if (!extrudeRelative)
							prev_extrude[argChar] = 0;
						else {
							prev_extrude[argChar] = numSlice;
						}
						// prevZ = z;
						break;
					default:
						break;
					}
				}
				if (!this.model[layer])
					this.model[layer] = [];
				if (typeof (x) !== 'undefined' || typeof (y) !== 'undefined'
						|| typeof (z) !== 'undefined')
					this.model[this.layer][this.model[layer].length] = {
						x : parseFloat(x),
						y : parseFloat(y),
						z : parseFloat(z),
						extrude : extrude,
						retract : parseFloat(retract),
						noMove : true,
						extrusion : 0,
						extruder : extruder,
						prevX : parseFloat(prevX),
						prevY : parseFloat(prevY),
						prevZ : parseFloat(prevZ),
						speed : parseFloat(lastF),
						gcodeLine : parseFloat(i)
					};
			} else if (this.g_code[i].match(/^(?:G28)/i)) {
				var args = this.g_code[i].split(/\s/);
				for (j = 0; j < args.length; j++) {
					switch (argChar = args[j].charAt(0).toLowerCase()) {
					case 'x':
						x = args[j].slice(1);
						break;
					case 'y':
						y = args[j].slice(1);
						break;
					case 'z':
						z = args[j].slice(1);
						z = Number(z);
						if (z === prevZ)
							continue;
						sendLayer = layer;
						sendLayerZ = z;// }
						if (this.z_heights.hasOwnProperty(z)) {
							layer = this.z_heights[z];
						} else {
							layer = model.length;
							this.z_heights[z] = layer;
						}
						prevZ = z;
						break;
					default:
						break;
					}
				}
				// G28 with no arguments
				if (args.length == 1) {
					// need to init values to default here
				}
				// if it's the first layer and G28 was without
				if (layer == 0 && typeof (z) === 'undefined') {
					z = 0;
					if (this.z_heights.hasOwnProperty(z)) {
						layer = this.z_heights[z];
					} else {
						layer = this.model.length;
						this.z_heights[z] = layer;
					}
					prevZ = z;
				}
				// x=0, y=0,z=0,prevZ=0, extrude=false;
				// if(typeof(prevX) === 'undefined'){prevX=0;}
				// if(typeof(prevY) === 'undefined'){prevY=0;}

				if (!this.model[layer])
					this.model[layer] = [];
				// if(typeof(x) !== 'undefined' || typeof(y) !== 'undefined'
				// ||typeof(z) !== 'undefined'||retract!=0)
				this.model[layer][this.model[layer].length] = {
					x : Number(x),
					y : Number(y),
					z : Number(z),
					extrude : extrude,
					retract : Number(retract),
					noMove : false,
					extrusion : (extrude || retract) ? Number(prev_extrude["abs"])
							: 0,
					extruder : extruder,
					prevX : Number(prevX),
					prevY : Number(prevY),
					prevZ : Number(prevZ),
					speed : Number(lastF),
					gcodeLine : Number(i)
				};
				// if(typeof(x) !== 'undefined' || typeof(y) !== 'undefined'
				// ||typeof(z) !== 'undefined')
				// model[layer][model[layer].length] =
				// {x: x, y: y, z: z, extrude: extrude, retract: retract,
				// noMove:false, extrusion:
				// (extrude||retract)?prev_extrude["abs"]:0, prevX: prevX,
				// prevY:
				// prevY, prevZ: prevZ, speed: lastF, gcodeLine: parseFloat(i)};
			}
			if (typeof (sendLayer) !== "undefined") {
				// sendLayerToParent(sendLayer, sendLayerZ, i/gcode.length*100);
				// sendLayer = undefined;

				if (i - lastSend > this.g_code.length * 0.02
						&& sendMultiLayer.length != 0) {
					lastSend = i;
					// sendMultiLayerToParent(sendMultiLayer, sendMultiLayerZ, i
					// / gcode.length * 100);
					sendMultiLayer = [];
					sendMultiLayerZ = [];
				}
				sendMultiLayer[sendMultiLayer.length] = sendLayer;
				sendMultiLayerZ[sendMultiLayerZ.length] = sendLayerZ;
				sendLayer = undefined;
				sendLayerZ = undefined;
			}
		}
		// sendMultiLayer[sendMultiLayer.length] = layer;
		// sendMultiLayerZ[sendMultiLayerZ.length] = z;
		// sendMultiLayerToParent(sendMultiLayer, sendMultiLayerZ,
		// i/gcode.length*100);

		// if(gCodeOptions["sortLayers"])sortLayers();
		// if(gCodeOptions["purgeEmptyLayers"])purgeLayers();

		// console.log(model);

	}
	
	
	

	this.analyze_model = function() {
	
	//function analyze_model(){

		var i, j;
		var x_ok = false, y_ok = false;
		var cmds;
		var tmp1 = 0, tmp2 = 0;
		var speedIndex = 0;
		var type;
		var printTimeAdd = 0;
		// var moveTime=0;

		for (i = 0; i < this.model.length; i++) {
			cmds = this.model[i];

			if (!cmds)
				continue;
			for (j = 0; j < cmds.length; j++) {
				x_ok = false;
				y_ok = false;
				if (typeof (cmds[j].x) !== 'undefined'
						&& typeof (cmds[j].prevX) !== 'undefined'
						&& typeof (cmds[j].extrude) !== 'undefined'
						&& cmds[j].extrude && !isNaN(cmds[j].x)) {
					this.max.x = parseFloat(this.max.x) > parseFloat(cmds[j].x) ? parseFloat(this.max.x)
							: parseFloat(cmds[j].x);
					this.max.x = parseFloat(this.max.x) > parseFloat(cmds[j].prevX) ? parseFloat(this.max.x)
							: parseFloat(cmds[j].prevX);
					this.min.x = parseFloat(this.min.x) < parseFloat(cmds[j].x) ? parseFloat(this.min.x)
							: parseFloat(cmds[j].x);
					this.min.x = parseFloat(this.min.x) < parseFloat(cmds[j].prevX) ? parseFloat(this.min.x)
							: parseFloat(cmds[j].prevX);
					x_ok = true;
				}

				if (typeof (cmds[j].y) !== 'undefined'
						&& typeof (cmds[j].prevY) !== 'undefined'
						&& typeof (cmds[j].extrude) !== 'undefined'
						&& cmds[j].extrude && !isNaN(cmds[j].y)) {
					this.max.y = parseFloat(this.max.y) > parseFloat(cmds[j].y) ? parseFloat(this.max.y)
							: parseFloat(cmds[j].y);
					this.max.y = parseFloat(this.max.y) > parseFloat(cmds[j].prevY) ? parseFloat(this.max.y)
							: parseFloat(cmds[j].prevY);
					this.min.y = parseFloat(this.min.y) < parseFloat(cmds[j].y) ? parseFloat(this.min.y)
							: parseFloat(cmds[j].y);
					this.min.y = parseFloat(this.min.y) < parseFloat(cmds[j].prevY) ? parseFloat(this.min.y)
							: parseFloat(cmds[j].prevY);
					y_ok = true;
				}

				if (typeof (cmds[j].prevZ) !== 'undefined'
						&& typeof (cmds[j].extrude) !== 'undefined'
						&& cmds[j].extrude && !isNaN(cmds[j].prevZ)) {
					this.max.z = parseFloat(this.max.z) > parseFloat(cmds[j].prevZ) ? parseFloat(this.max.z)
							: parseFloat(cmds[j].prevZ);
					this.min.z = parseFloat(this.min.z) < parseFloat(cmds[j].prevZ) ? parseFloat(this.min.z)
							: parseFloat(cmds[j].prevZ);
				}

				if ((typeof (cmds[j].extrude) !== 'undefined' && cmds[j].extrude == true)
						|| cmds[j].retract != 0) {
					this.totalFilament += cmds[j].extrusion;
					if (!this.filamentByLayer[cmds[j].prevZ])
						this.filamentByLayer[cmds[j].prevZ] = 0;
					this.filamentByLayer[cmds[j].prevZ] += cmds[j].extrusion;
					if (cmds[j].extruder != null) {
						if (!this.filamentByExtruder[cmds[j].extruder])
							this.filamentByExtruder[cmds[j].extruder] = 0;
						this.filamentByExtruder[cmds[j].extruder] += cmds[j].extrusion;
					}
				}

				if (x_ok && y_ok) {
					printTimeAdd = Math.sqrt(Math.pow(parseFloat(cmds[j].x)
							- parseFloat(cmds[j].prevX), 2)
							+ Math.pow(parseFloat(cmds[j].y)
									- parseFloat(cmds[j].prevY), 2))
							/ (cmds[j].speed / 60);
				} else if (cmds[j].retract === 0 && cmds[j].extrusion !== 0) {
					tmp1 = Math.sqrt(Math.pow(parseFloat(cmds[j].x)
							- parseFloat(cmds[j].prevX), 2)
							+ Math.pow(parseFloat(cmds[j].y)
									- parseFloat(cmds[j].prevY), 2))
							/ (cmds[j].speed / 60);
					tmp2 = Math.abs(parseFloat(cmds[j].extrusion)
							/ (cmds[j].speed / 60));
					printTimeAdd = tmp1 >= tmp2 ? tmp1 : tmp2;
				} else if (cmds[j].retract !== 0) {
					printTimeAdd = Math.abs(parseFloat(cmds[j].extrusion)
							/ (cmds[j].speed / 60));
				}

				this.printTime += printTimeAdd;
				if (typeof (this.printTimeByLayer[cmds[j].prevZ]) === 'undefined') {
					this.printTimeByLayer[cmds[j].prevZ] = 0;
				}
				this.printTimeByLayer[cmds[j].prevZ] += printTimeAdd;

				if (cmds[j].extrude && cmds[j].retract === 0) {
					type = 'extrude';
				} else if (cmds[j].retract !== 0) {
					type = 'retract';
				} else if (!cmds[j].extrude && cmds[j].retract === 0) {
					type = 'move';
				} else {
					self.postMessage({
						cmd : 'unknown type of move'
					});
					type = 'unknown';
				}
				speedIndex = this.speeds[type].indexOf(cmds[j].speed);
				if (speedIndex === -1) {
					this.speeds[type].push(cmds[j].speed);
					speedIndex = this.speeds[type].indexOf(cmds[j].speed);
				}
				if (typeof (this.speedsByLayer[type][cmds[j].prevZ]) === 'undefined') {
					this.speedsByLayer[type][cmds[j].prevZ] = [];
				}
				if (this.speedsByLayer[type][cmds[j].prevZ].indexOf(cmds[j].speed) === -1) {
					this.speedsByLayer[type][cmds[j].prevZ][speedIndex] = cmds[j].speed;
				}

				if (cmds[j].extrude && cmds[j].retract === 0 && x_ok && y_ok) {
					// we are extruding
					var volPerMM = cmds[j].volPerMM;
					volPerMM = parseFloat(volPerMM).toFixed(3);
					var volIndex = this.volSpeeds.indexOf(volPerMM);
					if (volIndex === -1) {
						this.volSpeeds.push(volPerMM);
						volIndex = this.volSpeeds.indexOf(volPerMM);
					}
					if (typeof (this.volSpeedsByLayer[cmds[j].prevZ]) === 'undefined') {
						this.volSpeedsByLayer[cmds[j].prevZ] = [];
					}
					if (this.volSpeedsByLayer[cmds[j].prevZ].indexOf(volPerMM) === -1) {
						this.volSpeedsByLayer[cmds[j].prevZ][volIndex] = volPerMM;
					}

					var extrusionSpeed = volPerMM * cmds[j].speed;
					extrusionSpeed = parseFloat(extrusionSpeed).toFixed(3);
					var volIndex = this.extrusionSpeeds.indexOf(extrusionSpeed);
					if (volIndex === -1) {
						this.extrusionSpeeds.push(extrusionSpeed);
						volIndex = this.extrusionSpeeds.indexOf(extrusionSpeed);
					}
					if (typeof (this.extrusionSpeedsByLayer[cmds[j].prevZ]) === 'undefined') {
						this.extrusionSpeedsByLayer[cmds[j].prevZ] = [];
					}
					if (this.extrusionSpeedsByLayer[cmds[j].prevZ]
							.indexOf(extrusionSpeed) === -1) {
						this.extrusionSpeedsByLayer[cmds[j].prevZ][volIndex] = extrusionSpeed;
					}
				}

			}
			
			
			//console.log(i/this.model.length*100);
			
		}
		
		
	
		
		
		//$('#file-progress').width('100%');
		
		this.purge_layers();

		this.modelSize.x = Math.abs(this.max.x - this.min.x);
		this.modelSize.y = Math.abs(this.max.y - this.min.y);
		this.modelSize.z = Math.abs(this.max.z - this.min.z);
		this.layerHeight = (this.max.z - this.min.z) / (this.layerCnt - 1);

	}
	
	
	
	this.purge_layers = function () {
		var purge = true;
		for ( var i = 0; i < this.model.length; i++) {
			purge = true;
			if (!this.model[i])
				purge = true;
			else {
				for ( var j = 0; j < this.model[i].length; j++) {
					if (this.model[i][j].extrude)
						purge = false;
				}
			}
			if (!purge) {
				this.layerCnt += 1;
			}
		}
	};
	
	

	/**
	 * INITIALIZE CLASS
	 */
	this.init = function() {
		
		
		
		this.load_file();
		
		
		this.do_parse();
		
		
		this.analyze_model();
		
		/*
		var obj = this;
		
		$.when(get_file_call).done( function(a){
			
			//
			
			obj.g_code = a.split(/\n/);
			
			//console.log('initialized');
			
			
			//do_parse();
			
			//analyze_model();
			
		});
		
		
		//console.log(this.g_code);
		*/
	
	};

	this.init();

}


/**
 * 
 */
GCode_Analizer.prototype.get_url = function () {
	return this.url;
};


/**
 * 
 */
GCode_Analizer.prototype.get_gcode = function() {
	return this.g_code;
};


/**
 * 
 */
GCode_Analizer.prototype.get_size = function (){
	return this.modelSize;
}


/**
 * 
 */
GCode_Analizer.prototype.get_total_filament = function (){
	return this.totalFilament;
}


/**
 * 
 */
GCode_Analizer.prototype.get_layer_height = function (){
	return this.layerHeight;
}

/**
 * 
 */
GCode_Analizer.prototype.get_print_time = function (){
	return this.printTime;
}


/**
 * 
 */
GCode_Analizer.prototype.get_layer_count = function (){
	return this.layerCnt;
}


/**
 * 
 */
GCode_Analizer.prototype.get_speeds = function (){
	return this.speeds;
}




