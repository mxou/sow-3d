document.addEventListener('DOMContentLoaded', function () {

    
    console.log("SCRIPT JS CHARGE");

    const processedWidgets = {}; 

    const models = document.querySelectorAll('.models');
    models.forEach(container => {
        container.addEventListener('click', function () {
            const modelLinkType = container.dataset.modelLinkType;
            const modelCustomUrl = container.dataset.modelLink;
        
            if (modelLinkType === 'custom' && modelCustomUrl) {
                window.location.href = modelCustomUrl;
            }
        });
        const widgetId = container.dataset.widgetId;

        // Check is widget already has been cooked
        if (!processedWidgets[widgetId]) {
            const modelUrl = container.querySelector('.renderer-3d').dataset.modelUrl;
            const backgroundColor = container.dataset.backgroundColor;
            const interaction = container.dataset.interaction;
            const rotation = parseFloat(container.dataset.modelRotation);
            const exposure = container.dataset.modelExposure;
            const cameracontrol = container.dataset.modelCameracontrol;
            const xcamera = container.dataset.modelXcamera;
            const ycamera = container.dataset.modelYcamera;
            const zcamera = container.dataset.modelZcamera;
            
            
            
            init(container, modelUrl, widgetId, backgroundColor, interaction, rotation, exposure, cameracontrol, xcamera, ycamera, zcamera); // Init 3d scene for each widget

            // Add the widget in the "already cooked" list
            processedWidgets[widgetId] = true;
        }
    });

    

    function init(container, modelUrl, widgetId, backgroundColor, interaction, rotation, exposure, cameracontrol, xcamera, ycamera, zcamera) {
        let scene, camera, renderer, controls, model;

        scene = new THREE.Scene();
        scene.background = new THREE.Color(parseInt(backgroundColor.replace("#", "0x")));
    
        const loader = new THREE.GLTFLoader();
        loader.load(modelUrl, function (gltf) {
            model = gltf.scene;
            scene.add(model);
    
            const boundingBox = new THREE.Box3().setFromObject(model);
            const size = new THREE.Vector3();
            boundingBox.getSize(size);
            const maxSize = Math.max(size.x, size.y, size.z); // Calc max size
    
            const containerWidth = container.clientWidth; // clientWidth to get real width of the container
            const containerHeight = container.clientHeight; // clientHeight to get real height of the container
    
            const cameraDistance = maxSize * 2; 
            const cameraPosition = new THREE.Vector3().copy(boundingBox.max).add(boundingBox.min).multiplyScalar(0.5); // Position de la caméra au centre de la boîte englobante
    
            camera = new THREE.PerspectiveCamera(45, containerWidth / containerHeight, 0.1, 1000); // FOV, aspect, near, far
            if (cameracontrol === 'auto') {
                camera.position.set(cameraPosition.x, cameraPosition.y, cameraPosition.z + cameraDistance); // Position de la caméra
            } else if (cameracontrol === 'manuel') {
                camera.position.set(xcamera,ycamera,zcamera);
            }
            
            camera.lookAt(cameraPosition); // Center the camera
    

            renderer = new THREE.WebGLRenderer({ antialias: true });
            renderer.setSize(containerWidth, containerHeight);
            container.appendChild(renderer.domElement);
    

            renderer.toneMappingExposure += exposure;
            renderer.toneMapping = THREE.NoToneMapping;
    
            // Ligths
            const ambientLight = new THREE.AmbientLight(0xffffff, 0.8);
            ambientLight.position.set(2, 1, 1);
            scene.add(ambientLight);
    
            const directionalLight = new THREE.DirectionalLight(0xffffff, 2);
            directionalLight.position.set(2, 1, 1).normalize();
            scene.add(directionalLight);
    
            const hemisphereLight = new THREE.HemisphereLight(0xffffbb, 0x080820, 0.4);
            hemisphereLight.position.set(3, 1, 3);
            scene.add(hemisphereLight);
    
            const pointLight = new THREE.PointLight(0xffffff, 1, 100);
            pointLight.position.set(15, 1, 20);
            scene.add(pointLight);
    
            controls = new THREE.OrbitControls(camera, renderer.domElement);
    
            window.addEventListener('resize', onWindowResize, false);
    
            animate();
        });
    
        function onWindowResize() {
            const containerWidth = container.clientWidth;
            const containerHeight = container.clientHeight;
    
            camera.aspect = containerWidth / containerHeight;
            camera.updateProjectionMatrix();
            renderer.setSize(containerWidth, containerHeight);
        }
    
        function animate() {
            requestAnimationFrame(animate);
            if (model) {
                model.rotation.y += rotation;
            }
    
            if (interaction === 'oui') {
                controls.update();
                renderer.render(scene, camera);
            } else {
                controls.dispose();
                renderer.render(scene, camera);
                window.removeEventListener('resize', onWindowResize);
            }
        }
    }
    
    
});

