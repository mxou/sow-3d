<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Ne fonctionnera pas si le script est exécuté en dehors de Wordpress
}
//var_dump( plugin_dir_url(__FILE__) );


use \Elementor\Controls_Manager;


class sow3d_widget extends \Elementor\Widget_Base {

    public function __construct($data = [], $args = null) {
        parent::__construct($data, $args);
        add_action('elementor/frontend/after_register_scripts', [$this, 'register_scripts']);
    }
    

    //Widget title
    public function get_name() {
		return 'title';
	}


    //Widget title
    public function get_title() {
		return esc_html__( 'Sow3D', 'sow3d_widget' );
	}

    //Widget icon
    public function get_icon() {
		return 'eicon-shape';
	}

    //If help needed for the plugin
    public function get_custom_help_url() {
		return 'https://developers.elementor.com/docs/widgets/';
	}

    //Widget category
    public function get_categories() {
		return [ 'general' ];
	}

    //Keywords to find the widget
    public function get_keywords() {
		return [ '3d', 'sow', 'sw','Sow3d' ];
	}


    //Register controls for the widget 
    protected function register_controls() {

        //Content section
        $this->start_controls_section(
			'content_section',
			[
				'label' => esc_html__( 'Content', 'sow3d_widget' ),
				'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
			]
		);

        //3D File
        $this->add_control(
            'modele_3d',
            [
                'label' => esc_html__( 'Choisir un fichier 3D', 'sow3d_widget' ),
                'type' => \Elementor\Controls_Manager::MEDIA,
                'media_types' => [ 'file' ], 
                'mime_type' => 'model/gltf-binary,model/gltf+json', // Accepted files
                'default' => [
                    'url' => \Elementor\Utils::get_placeholder_image_src(),
                ],
            ]
        );

        //Model title
        $this->add_control(
			'model_title',
			[
				'label' => esc_html__( 'Titre du modèle', 'elementor-oembed-widget' ),
				'type' => \Elementor\Controls_Manager::TEXT,
				'input_type' => 'url',
				'placeholder' => esc_html__( 'Titre du modèle', 'elementor-oembed-widget' ),
                'label_block' => true,
                'separator' => 'before'
			]
		);

         //Model description
         $this->add_control(
			'model_description',
			[
				'label' => esc_html__( 'Description du modèle', 'elementor-oembed-widget' ),
				'type' => \Elementor\Controls_Manager::TEXTAREA,
				'input_type' => 'url',
				'placeholder' => esc_html__( 'Description du modèle', 'elementor-oembed-widget' ),
                'separator' => 'before'
			]
		);


        //Style - Background color
        $this->add_control(
            'model_background_color',
            [
                'label' => esc_html__( 'Couleur du background', 'elementor-oembed-widget' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '#ffffff', // Couleur de fond par défaut
                'selectors' => [
                    '{{WRAPPER}} .models .renderer-3d' => 'background-color: {{VALUE}};', // Sélecteur CSS pour appliquer la couleur de fond
                ],
                'separator' => 'before'
            ]
        );

        //Style - Margin (top and bottom only)
        $this->add_responsive_control(
            'model_margin',
            [
                'label' => esc_html__( 'Margin', 'elementor' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em', 'rem', 'custom' ],
                'selectors' => [
                    '{{WRAPPER}} .models' => 'margin-top: {{TOP}}{{UNIT}}; margin-bottom: {{BOTTOM}}{{UNIT}};',
                ],
                'separator' => 'before'
            ]
        );


        //Style - Height
           $this->add_responsive_control(
			'model_height',
			[
				'label' => esc_html__( 'Height', 'elementor' ),
				'type' => Controls_Manager::SLIDER,
                'default' => '400',
				'default' => [
					'unit' => 'px',
				],
				'tablet_default' => [
					'unit' => 'px',
				],
				'mobile_default' => [
					'unit' => 'px',
				],
				'size_units' => [ 'px', '%', 'em', 'rem', 'vw', 'custom' ],
				'range' => [
					'%' => [
						'min' => 1,
						'max' => 100,
					],
					'px' => [
						'min' => 1,
						'max' => 1000,
					],
					'vw' => [
						'min' => 1,
						'max' => 100,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .models' => 'height: {{SIZE}}{{UNIT}};',
				],
                'separator' => 'before'
			]
		);


        //Style - Width
        $this->add_responsive_control(
			'model_width',
			[
				'label' => esc_html__( 'Width', 'elementor' ),
				'type' => Controls_Manager::SLIDER,
                'default' => '400',
				'default' => [
					'unit' => 'px',
				],
				'tablet_default' => [
					'unit' => 'px',
				],
				'mobile_default' => [
					'unit' => 'px',
				],
				'size_units' => [ 'px', '%', 'em', 'rem', 'vw', 'custom' ],
				'range' => [
					'%' => [
						'min' => 1,
						'max' => 100,
					],
					'px' => [
						'min' => 1,
						'max' => 1000,
					],
					'vw' => [
						'min' => 1,
						'max' => 100,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .models' => 'width: {{SIZE}}{{UNIT}};',
				],
			]
		);


        //Custom URL
        $this->add_control(
            'model_link_to', 
            [
                'label' => esc_html__( 'Link', 'elementor' ),
                'type' => \Elementor\Controls_Manager::SELECT,
                'default' => 'none',
                'options' => [
                    'none' => esc_html__( 'None', 'elementor' ),
                    'custom' => esc_html__( 'Custom URL', 'elementor' ),
                ],
                'separator' => 'before'
            ]
        );

        //If custom URL
        $this->add_control(
            'model_custom_url',
            [
                'label' => esc_html__( 'Custom URL', 'elementor' ),
                'type' => \Elementor\Controls_Manager::URL,
                'default' => [
                    'url' => '',
                ],
                'placeholder' => esc_html__( 'https://your-custom-url.com', 'elementor' ),
                'condition' => [
                    'model_link_to' => 'custom',
                ],
            ]
        );


        $this->end_controls_section();



         //Model management   
         $this->start_controls_section(
            'model_section',
            [
                'label' => esc_html__( 'Modèle', 'sow3d_widget' ),
                'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
            ]
        );

        //Is the model draggable ?
        $this->add_control(
			'model_interaction',
			[
				'label' => esc_html__( 'Intéractivité', 'elementor-list-widget' ),
				'type' => \Elementor\Controls_Manager::CHOOSE,
				'options' => [
					'oui' => [
						'title' => esc_html__( 'Oui', 'elementor-list-widget' ),
						'icon' => 'eicon-check',
					],
					'non' => [
						'title' => esc_html__( 'Non', 'elementor-list-widget' ),
						'icon' => 'eicon-ban',
					],
				],
				'default' => 'oui',
				'toggle' => false,
                'separator' => 'before'
			]
		);

        //Is the model zoomable ?
        $this->add_control(
			'model_zoom',
			[
				'label' => esc_html__( 'Zoom', 'elementor-list-widget' ),
				'type' => \Elementor\Controls_Manager::CHOOSE,
				'options' => [
					'oui' => [
						'title' => esc_html__( 'Oui', 'elementor-list-widget' ),
						'icon' => 'eicon-check',
					],
					'non' => [
						'title' => esc_html__( 'Non', 'elementor-list-widget' ),
						'icon' => 'eicon-ban',
					],
				],
				'default' => 'oui',
				'toggle' => false,
			]
		);



        //Speed rotation
        $this->add_control(
            'model_rotation',
            [
                'label' => esc_html__('Vitesse de rotation', 'elementor-oembed-widget'),
                'type' => \Elementor\Controls_Manager::NUMBER,
                'default' => '0.005',
                'separator' => 'before'
            ]
            );
        
        //Exposure
        $this->add_control(
            'model_exposure',
            [
                'label' => esc_html__('Exposition', 'elementor-oembed-widget'),
                'type' => \Elementor\Controls_Manager::NUMBER,
                'default' => '0',
                'separator' => 'before'
            ]
            );

            $this->end_controls_section();


        //Camera management    
        $this->start_controls_section(
            'camera_section',
            [
                'label' => esc_html__( 'Caméra', 'sow3d_widget' ),
                'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
            ]
        );


         //Auto/manuel camera direction
         $this->add_control(
			'model_cameracontrol',
			[
				'label' => esc_html__( 'Camera Controle', 'elementor-list-widget' ),
				'type' => \Elementor\Controls_Manager::CHOOSE,
				'options' => [
					'auto' => [
						'title' => esc_html__( 'Auto', 'elementor-list-widget' ),
						'icon' => 'eicon-check',
					],
					'manuel' => [
						'title' => esc_html__( 'Manuel', 'elementor-list-widget' ),
						'icon' => 'eicon-ban',
					],
				],
				'default' => 'auto',
				'toggle' => false,
			]
		);
        

         //X - Left/Right
         $this->add_control(
            'model_xcamera',
            [
                'label' => esc_html__('Horizontal (X)', 'elementor-oembed-widget'),
                'type' => \Elementor\Controls_Manager::NUMBER,
                'default' => '5',
                'separator' => 'before'
            ]
            );

         //Y - Height 
         $this->add_control(
            'model_ycamera',
            [
                'label' => esc_html__('Hauteur (Y)', 'elementor-oembed-widget'),
                'type' => \Elementor\Controls_Manager::NUMBER,
                'default' => '5',
            ]
            );
        
         //Z - Distance
         $this->add_control(
            'model_zcamera',
            [
                'label' => esc_html__('Distance (Z)', 'elementor-oembed-widget'),
                'type' => \Elementor\Controls_Manager::NUMBER,
                'default' => '5',
            ]
            );

        
        
        

        $this->end_controls_section();

    }


    //Render
    protected function render() {
        add_action('elementor/frontend/after_register_scripts', [$this, 'register_scripts']);
        $settings = $this->get_settings_for_display();
        $model_model = $settings['modele_3d']['url'];
        $model_title = $settings['model_title'];
        $model_height = $settings['model_height']['size'];
        $model_width = $settings['model_width']['size'];
        $model_description = $settings['model_description'];
        $background_color = $settings['model_background_color'];
        $model_link_type = $settings['model_link_to'];
        $model_xcamera = $settings['model_xcamera'];
        $model_ycamera = $settings['model_ycamera'];
        $model_zcamera = $settings['model_zcamera'];
        $model_cameracontrol = $settings['model_cameracontrol'];
        $model_link = isset($settings['model_custom_url']) ? $settings['model_custom_url']['url'] : '';
        $interaction = '';
        $model_interaction = $settings['model_interaction'];
        if ($model_interaction === 'oui') {
            $interaction = 'oui';
        } else {
            $interaction = 'non';
        }
        $model_rotation = $settings['model_rotation']; 
        $model_exposure = $settings['model_exposure'];
        $model_zoom = $settings['model_zoom'];
        $widget_id = $this->get_id(); //Get widget id


    
        //Send the informations by the "data" attribute
        echo '<div class="models" data-model-cameracontrol="'.esc_attr($model_cameracontrol) .'" data-model-zoom="' . esc_attr($model_zoom) . '" data-model-xcamera="' . esc_attr($model_xcamera) . '" data-model-ycamera="' . esc_attr($model_ycamera) . '" data-model-zcamera="' . esc_attr($model_zcamera) . '" data-model-exposure="' . esc_attr($model_exposure) . '" data-model-width="' . esc_attr($model_width ) . '" data-model-height="' . esc_attr($model_height) . '" data-widget-id="' . esc_attr($widget_id) . '" data-background-color="' . esc_attr($background_color) . '" data-interaction="' . esc_attr($interaction) . '" data-model-link-type="' . esc_attr($model_link_type) . '" data-model-link="' . esc_attr($model_link) . '" data-model-rotation="' . esc_attr($model_rotation) . '">';
        echo '<div class="renderer-3d" data-model-url="' . esc_url( $model_model ) . '"></div>';
        if (isset($_GET['action']) && $_GET['action'] === 'elementor') {
        ?> 
        <script>
        document.querySelectorAll('.models').forEach(function(element) {
            element.classList.add('isPreviewClass'); //For each model, add a class who add background color and a border in the draft page
        });
        </script> 
        <?php
        }
        echo '<h4>' . esc_html( $model_title ) . '</h4>';
        echo '<p>' . esc_html( $model_description ) . '</p>';
        echo '</div>';
    }

    public static $is_script_registered = false;

    public function register_scripts() { 
        //Check if scripts already has been registered
        if (!self::$is_script_registered) {

            // Load online three js
            //wp_enqueue_script('three-js', 'https://cdn.jsdelivr.net/npm/three@0.137.5/build/three.min.js', array(), '0.137.5', false);
    
            // Load online OrbitControls.js
            //wp_enqueue_script('orbit-controls', 'https://cdn.jsdelivr.net/npm/three@0.137.5/examples/js/controls/OrbitControls.js', array(), '0.137.5', false);
    
            // Load online GLTFLoader.js
            //wp_enqueue_script('gltf-loader', 'https://cdn.jsdelivr.net/npm/three@0.137.5/examples/js/loaders/GLTFLoader.js', array(), '0.137.5', false);
        
            //Load local three.js
            wp_enqueue_script('threejs', plugin_dir_url(__FILE__) . './../assets/three.js', array(), '0.163.0', false);
            
            // Load local OrbitControls.js
            wp_enqueue_script('orbit-controls', plugin_dir_url(__FILE__) . './../assets/OrbitControls.js', array('threejs'), '0.163.0', false);

            // Load local GLTFLoader.js
            wp_enqueue_script('gltf-loader', plugin_dir_url(__FILE__) . './../assets/GTLFLoader.js', array('threejs'), '0.163.0', false);
            
            // Save js script with the widget id
            wp_enqueue_script('sow3d_script', plugins_url('script.js', __FILE__), array('jquery'), '1.0', true);

            
            self::$is_script_registered = true; // Mark the scripts as already saved

       }
        
    }
    
    
}

?>
<style>
.models {
    margin: 0 auto; 
    padding: 0;
    position: relative;
    width: 80%;
}

.models canvas {
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    margin: auto; 
}

/*If the current page is the draft page, the models will have this class*/
.isPreviewClass {
    border: 2px solid #f3bafd;
    /*background-color: #f3bafd;*/ 
    background-image: url(./../wp-content/plugins/sow_3d/assets/Elementor.gif);
    background-size: cover; 
    background-position: center;
}



</style>

<?php
