function createScene(element) {

	// Renderer
	var renderer = new THREE.WebGLRenderer({
		clearColor : 0x000000,
		clearAlpha : 1
	});
	renderer.setSize(element.width(), element.height());
	element.append(renderer.domElement);
	renderer.clear();
	/*
	// Scene
	var scene = new THREE.Scene();

	// Lights...
	[[0,0,1,  0xFFFFCC],
	[0,1,0,  0xFFCCFF],
	[1,0,0,  0xCCFFFF],
	[0,0,-1, 0xCCCCFF],
	[0,-1,0, 0xCCFFCC],
	[-1,0,0, 0xFFCCCC]].forEach(function(position) {
	var light = new THREE.DirectionalLight(position[3]);
	light.position.set(position[0], position[1], position[2]).normalize();
	scene.add(light);
	});
	*/
	// Camera...
	var fov = 45, aspect = element.width() / element.height(), near = 0.1, far = 10000;

	var camera = new THREE.PerspectiveCamera(fov, aspect, near, far);

	//camera.rotationAutoUpdate = true;
	//camera.position.x = 0;
	//camera.position.y = 500;
	camera.position.x = 200;
	camera.position.y = 100;
	camera.position.z = 200;
	//camera.lookAt(scene.position);

	controls = new THREE.TrackballControls(camera);

	controls.rotateSpeed = 1.0;
	controls.zoomSpeed = 1.2;
	controls.panSpeed = 0.8;

	controls.noZoom = false;
	controls.noPan = false;

	controls.staticMoving = true;
	controls.dynamicDampingFactor = 0.3;

	controls.keys = [65, 83, 68];

	scene = new THREE.Scene();

	ambientLight = new THREE.AmbientLight(0x202020);
	scene.add(ambientLight);

	directionalLight = new THREE.DirectionalLight(0xffffff, 0.75);
	directionalLight.position.x = 1;
	directionalLight.position.y = 1;
	directionalLight.position.z = 2;
	directionalLight.position.normalize();
	scene.add(directionalLight);

	pointLight = new THREE.PointLight(0xffffff, 0.3);
	pointLight.position.x = 0;
	pointLight.position.y = -25;
	pointLight.position.z = 10;
	scene.add(pointLight);

	scene.add(camera);

	// Action!
	function render() {
		controls.update();
		renderer.render(scene, camera);

		requestAnimationFrame(render);
		// And repeat...
	}

	render();

	// Fix coordinates up if window is resized.
	$(window).on('resize', function() {
		renderer.setSize(element.width(), element.height());
		camera.aspect = element.width() / element.height();
		camera.updateProjectionMatrix();
		controls.screen.width = window.innerWidth;
		controls.screen.height = window.innerHeight;
	});

	return scene;
}