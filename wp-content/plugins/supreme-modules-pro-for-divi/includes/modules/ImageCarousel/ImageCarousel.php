<?php

class DSM_ImageCarousel extends ET_Builder_Module {

	public $slug       = 'dsm_image_carousel';
	public $vb_support = 'on';

	protected $module_credits = array(
		'module_uri' => 'https://divisupreme.com',
		'author'     => 'Divi Supreme',
		'author_uri' => 'https://divisupreme.com',
	);

	public function init() {
		$this->name      = esc_html__( 'Supreme Image Carousel', 'dsm-supreme-modules-pro-for-divi' );
		$this->icon_path = plugin_dir_path( __FILE__ ) . 'icon.svg';

		$this->settings_modal_toggles = array(
			'general'    => array(
				'toggles' => array(
					'main_content'      => esc_html__( 'Image Carousel', 'dsm-supreme-modules-pro-for-divi' ),
					'carousel_settings' => esc_html__( 'Carousel Settings', 'dsm-supreme-modules-pro-for-divi' ),
					'lightbox_settings' => esc_html__( 'Lightbox Settings', 'dsm-supreme-modules-pro-for-divi' ),
				),
			),
			'advanced'   => array(
				'toggles' => array(
					'layout'             => esc_html__( 'Layout', 'dsm-supreme-modules-pro-for-divi' ),
					'arrow_element'      => esc_html__( 'Arrow Element', 'dsm-supreme-modules-pro-for-divi' ),
					'pagination_element' => esc_html__( 'Pagination Element', 'dsm-supreme-modules-pro-for-divi' ),
					'image'              => array(
						'title' => esc_html__( 'Image', 'dsm-supreme-modules-pro-for-divi' ),
					),
				),
			),
			'custom_css' => array(
				'toggles' => array(
					'animation' => array(
						'title'    => esc_html__( 'Animation', 'dsm-supreme-modules-pro-for-divi' ),
						'priority' => 90,
					),
				),
			),
		);

	}

	public function get_advanced_fields_config() {
		return array(
			'fonts'          => false,
			'text'           => false,
			'button'         => false,
			'link_options'   => false,
			'height'         => false,
			'background'     => array(
				'css'     => array(
					'main' => '%%order_class%%',
				),
				'options' => array(
					'parallax_method' => array(
						'default' => 'off',
					),
				),
			),
			'borders'        => array(
				'default' => array(
					'css' => array(
						'main' => array(
							'border_radii'  => '%%order_class%%',
							'border_styles' => '%%order_class%%',
						),
					),
				),
				'image'   => array(
					'css'          => array(
						'main' => array(
							'border_radii'  => '%%order_class%% .dsm_image_carousel_item img',
							'border_styles' => '%%order_class%% .dsm_image_carousel_item img',
						),
					),
					'label_prefix' => esc_html__( 'Image', 'dsm-supreme-modules-pro-for-divi' ),
					'tab_slug'     => 'advanced',
					'toggle_slug'  => 'image',
				),
			),
			'box_shadow'     => array(
				'default' => array(),
				'image'   => array(
					'label'             => esc_html__( 'Image Box Shadow', 'dsm-supreme-modules-pro-for-divi' ),
					'option_category'   => 'layout',
					'tab_slug'          => 'advanced',
					'toggle_slug'       => 'image',
					'css'               => array(
						'main'    => '%%order_class%% .dsm_image_carousel_item img',
						'overlay' => 'inset',
					),
					'default_on_fronts' => array(
						'color'    => '',
						'position' => '',
					),
				),
			),
			'margin_padding' => array(
				'css' => array(
					'main'      => '%%order_class%% .dsm_image_carousel_container',
					'important' => array( 'custom_margin' ), // needed to overwrite last module margin-bottom styling.
				),
			),
			'filters'        => array(
				'css'                  => array(
					'main' => '%%order_class%%',
				),
				'child_filters_target' => array(
					'tab_slug'    => 'advanced',
					'toggle_slug' => 'image',
				),
			),
			'image'          => array(
				'css' => array(
					'main' => '%%order_class%% .dsm_image_carousel_item img',
				),
			),
		);
	}

	public function get_fields() {
		return array(
			'gallery_ids'                    => array(
				'label'            => esc_html__( 'Image Carousel', 'dsm-supreme-modules-pro-for-divi' ),
				'type'             => 'upload-gallery',
				'computed_affects' => array(
					'__gallery',
				),
				'option_category'  => 'basic_option',
				'toggle_slug'      => 'main_content',
			),
			'gallery_orderby'                => array(
				'label'            => esc_html__( 'Order By', 'dsm-supreme-modules-pro-for-divi' ),
				'type'             => $this->is_loading_bb_data() ? 'hidden' : 'select',
				'options'          => array(
					''     => esc_html__( 'Default', 'dsm-supreme-modules-pro-for-divi' ),
					'rand' => esc_html__( 'Random', 'dsm-supreme-modules-pro-for-divi' ),
				),
				'default'          => 'off',
				'class'            => array( 'et-pb-gallery-ids-field' ),
				'computed_affects' => array(
					'__gallery',
				),
				'toggle_slug'      => 'main_content',
			),
			'sizes'                          => array(
				'label'            => esc_html__( 'Image Size', 'dsm-supreme-modules-pro-for-divi' ),
				'type'             => 'select',
				'option_category'  => 'layout',
				'toggle_slug'      => 'main_content',
				'default'          => 'full',
				'default_on_front' => 'full',
				'computed_affects' => array(
					'__gallery',
				),
				'options'          => self::dsm_get_all_image_sizes(),
			),
			'slider_effect'                  => array(
				'label'            => esc_html__( 'Carousel Effect', 'dsm-supreme-modules-pro-for-divi' ),
				'type'             => 'select',
				'option_category'  => 'layout',
				'options'          => array(
					'default'   => esc_html__( 'Slide', 'dsm-supreme-modules-pro-for-divi' ),
					'coverflow' => esc_html__( 'Coverflow', 'dsm-supreme-modules-pro-for-divi' ),
					'flip'      => esc_html__( 'Flip', 'dsm-supreme-modules-pro-for-divi' ),
					'cube'      => esc_html__( 'Cube', 'dsm-supreme-modules-pro-for-divi' ),
				),
				'default'          => 'default',
				'default_on_front' => 'default',
				'toggle_slug'      => 'carousel_settings',
			),
			'slider_effect_shadows'          => array(
				'label'           => esc_html__( 'Show Shadow', 'dsm-supreme-modules-pro-for-divi' ),
				'type'            => 'yes_no_button',
				'option_category' => 'configuration',
				'options'         => array(
					'off' => esc_html__( 'No', 'dsm-supreme-modules-pro-for-divi' ),
					'on'  => esc_html__( 'Yes', 'dsm-supreme-modules-pro-for-divi' ),
				),
				'default'         => 'off',
				'show_if_not'     => array(
					'slider_effect' => 'default',
				),
				'toggle_slug'     => 'carousel_settings',
			),
			'slider_effect_coverflow_rotate' => array(
				'label'            => esc_html__( 'Coverflow Rotate', 'dsm-supreme-modules-pro-for-divi' ),
				'type'             => 'range',
				'option_category'  => 'configuration',
				'default'          => '30',
				'default_on_front' => '30',
				'default_unit'     => '',
				'validate_unit'    => false,
				'mobile_options'   => false,
				'unitless'         => true,
				'responsive'       => false,
				'range_settings'   => array(
					'min'  => '30',
					'max'  => '100',
					'step' => '1',
				),
				'toggle_slug'      => 'carousel_settings',
				'show_if'          => array(
					'slider_effect' => 'coverflow',
				),
			),
			'slider_effect_coverflow_depth'  => array(
				'label'            => esc_html__( 'Coverflow Depth', 'dsm-supreme-modules-pro-for-divi' ),
				'type'             => 'range',
				'option_category'  => 'configuration',
				'default'          => '0',
				'default_on_front' => '0',
				'default_unit'     => '',
				'validate_unit'    => false,
				'mobile_options'   => false,
				'unitless'         => true,
				'responsive'       => false,
				'range_settings'   => array(
					'min'  => '0',
					'max'  => '500',
					'step' => '1',
				),
				'toggle_slug'      => 'carousel_settings',
				'show_if'          => array(
					'slider_effect' => 'coverflow',
				),
			),
			'slider_direction'               => array(
				'label'            => esc_html__( 'Carousel Direction', 'dsm-supreme-modules-pro-for-divi' ),
				'type'             => 'select',
				'option_category'  => 'layout',
				'options'          => array(
					'ltr' => esc_html__( 'Left to Right', 'dsm-supreme-modules-pro-for-divi' ),
					'rtl' => esc_html__( 'Right to Left', 'dsm-supreme-modules-pro-for-divi' ),
				),
				'default'          => 'ltr',
				'default_on_front' => 'ltr',
				'toggle_slug'      => 'carousel_settings',
			),
			'centered_slides'                => array(
				'label'            => esc_html__( 'Centered Mode', 'dsm-supreme-modules-pro-for-divi' ),
				'type'             => 'yes_no_button',
				'option_category'  => 'configuration',
				'options'          => array(
					'off' => esc_html__( 'No', 'dsm-supreme-modules-pro-for-divi' ),
					'on'  => esc_html__( 'Yes', 'dsm-supreme-modules-pro-for-divi' ),
				),
				'toggle_slug'      => 'carousel_settings',
				'description'      => esc_html__( 'If enable, then active slide will be centered, not always on the left side.', 'dsm-supreme-modules-pro-for-divi' ),
				'default'          => 'off',
				'default_on_front' => 'off',
			),
			'slide_to_show'                  => array(
				'label'            => esc_html__( 'Slides To Show', 'dsm-supreme-modules-pro-for-divi' ),
				'type'             => 'range',
				'option_category'  => 'configuration',
				'default'          => '4',
				'default_on_front' => '4',
				'default_unit'     => '',
				'validate_unit'    => false,
				'mobile_options'   => true,
				'responsive'       => true,
				'range_settings'   => array(
					'min'  => '1',
					'max'  => '9',
					'step' => '1',
				),
				'toggle_slug'      => 'carousel_settings',
			),
			'slide_to_scroll'                => array(
				'label'            => esc_html__( 'Slides To Scroll', 'dsm-supreme-modules-pro-for-divi' ),
				'type'             => 'range',
				'option_category'  => 'configuration',
				'default'          => '1',
				'default_on_front' => '1',
				'default_unit'     => '',
				'validate_unit'    => false,
				'unitless'         => true,
				'mobile_options'   => true,
				'responsive'       => true,
				'range_settings'   => array(
					'min'  => '1',
					'max'  => '9',
					'step' => '1',
				),
				'toggle_slug'      => 'carousel_settings',
				'show_if_not'      => array(
					'infinite_smooth_scrolling' => 'on',
				),
			),
			'multiple_slide_row'             => array(
				'label'            => esc_html__( 'Use Multiple Row', 'dsm-supreme-modules-pro-for-divi' ),
				'type'             => 'yes_no_button',
				'option_category'  => 'configuration',
				'options'          => array(
					'off' => esc_html__( 'No', 'dsm-supreme-modules-pro-for-divi' ),
					'on'  => esc_html__( 'Yes', 'dsm-supreme-modules-pro-for-divi' ),
				),
				'toggle_slug'      => 'carousel_settings',
				'description'      => esc_html__( 'To use multirow layout.', 'dsm-supreme-modules-pro-for-divi' ),
				'default'          => 'off',
				'default_on_front' => 'off',
			),
			'slide_row'                      => array(
				'label'            => esc_html__( 'Row Per Slide', 'dsm-supreme-modules-pro-for-divi' ),
				'type'             => 'range',
				'option_category'  => 'configuration',
				'default'          => '1',
				'default_on_front' => '1',
				'default_unit'     => '',
				'validate_unit'    => false,
				'mobile_options'   => true,
				'responsive'       => true,
				'range_settings'   => array(
					'min'  => '1',
					'max'  => '5',
					'step' => '1',
				),
				'show_if'          => array(
					'multiple_slide_row' => 'on',
				),
				'toggle_slug'      => 'carousel_settings',
			),
			'speed'                          => array(
				'label'            => esc_html__( 'Slider Speed', 'dsm-supreme-modules-pro-for-divi' ),
				'type'             => 'range',
				'option_category'  => 'configuration',
				'default'          => '300',
				'default_on_front' => '300',
				'default_unit'     => '',
				'validate_unit'    => false,
				'unitless'         => true,
				'range_settings'   => array(
					'min'  => '100',
					'max'  => '5000',
					'step' => '1',
				),
				'toggle_slug'      => 'carousel_settings',
			),
			'autoplay'                       => array(
				'label'            => esc_html__( 'Autoplay', 'dsm-supreme-modules-pro-for-divi' ),
				'type'             => 'yes_no_button',
				'option_category'  => 'configuration',
				'options'          => array(
					'off' => esc_html__( 'No', 'dsm-supreme-modules-pro-for-divi' ),
					'on'  => esc_html__( 'Yes', 'dsm-supreme-modules-pro-for-divi' ),
				),
				'toggle_slug'      => 'carousel_settings',
				'description'      => esc_html__( 'If enable, then an arrow will be added to the tooltip.', 'dsm-supreme-modules-pro-for-divi' ),
				'default'          => 'on',
				'default_on_front' => 'on',
			),
			'autoplay_speed'                 => array(
				'label'            => esc_html__( 'Autoplay Change Interval', 'dsm-supreme-modules-pro-for-divi' ),
				'type'             => 'range',
				'option_category'  => 'configuration',
				'default'          => '3000',
				'default_on_front' => '3000',
				'default_unit'     => '',
				'validate_unit'    => false,
				'unitless'         => true,
				'range_settings'   => array(
					'min'  => '100',
					'max'  => '5000',
					'step' => '1',
				),
				'toggle_slug'      => 'carousel_settings',
				'show_if'          => array(
					'autoplay' => 'on',
				),
			),
			'autoplay_viewport'               => array(
				'label'            => esc_html__( 'Autoplay Only On Viewport', 'dsm-supreme-modules-pro-for-divi' ),
				'description'      => esc_html__( 'Autoplay when in viewport.', 'dsm-supreme-modules-pro-for-divi' ),
				'type'             => 'range',
				'option_category'  => 'layout',
				'toggle_slug'      => 'carousel_settings',
				'default'          => '50%',
				'default_on_front' => '50%',
				'unitless'         => false,
				'allow_empty'      => false,
				'range_settings'   => array(
					'min'  => '0',
					'max'  => '100',
					'step' => '1',
				),
				'responsive'       => false,
				'mobile_options'   => false,
				'show_if'          => array(
					'autoplay' => 'on',
				),
			),
			'pause_on_hover'                 => array(
				'label'            => esc_html__( 'Pause on Hover', 'dsm-supreme-modules-pro-for-divi' ),
				'type'             => 'yes_no_button',
				'option_category'  => 'configuration',
				'options'          => array(
					'off' => esc_html__( 'No', 'dsm-supreme-modules-pro-for-divi' ),
					'on'  => esc_html__( 'Yes', 'dsm-supreme-modules-pro-for-divi' ),
				),
				'toggle_slug'      => 'carousel_settings',
				'default'          => 'off',
				'default_on_front' => 'off',
				'show_if'          => array(
					'autoplay' => 'on',
				),
				'description'      => esc_html__( 'If enable, slider will pause on hover.', 'dsm-supreme-modules-pro-for-divi' ),
			),
			'space_between'                  => array(
				'label'            => esc_html__( 'Spacing', 'dsm-supreme-modules-pro-for-divi' ),
				'type'             => 'range',
				'option_category'  => 'configuration',
				'default'          => '15',
				'default_on_front' => '15',
				'default_unit'     => '',
				'validate_unit'    => false,
				'unitless'         => true,
				'mobile_options'   => true,
				'responsive'       => true,
				'range_settings'   => array(
					'min'  => '0',
					'max'  => '100',
					'step' => '1',
				),
				'toggle_slug'      => 'carousel_settings',
			),
			'infinite'                       => array(
				'label'            => esc_html__( 'Loop', 'dsm-supreme-modules-pro-for-divi' ),
				'type'             => 'yes_no_button',
				'option_category'  => 'configuration',
				'options'          => array(
					'off' => esc_html__( 'No', 'dsm-supreme-modules-pro-for-divi' ),
					'on'  => esc_html__( 'Yes', 'dsm-supreme-modules-pro-for-divi' ),
				),
				'toggle_slug'      => 'carousel_settings',
				'default'          => 'on',
				'default_on_front' => 'on',
				'show_if_not'      => array(
					'multiple_slide_row' => 'on',
				),
			),
			'infinite_smooth_scrolling'      => array(
				'label'            => esc_html__( 'Infinite Smooth Scrolling', 'dsm-supreme-modules-pro-for-divi' ),
				'type'             => 'yes_no_button',
				'option_category'  => 'configuration',
				'options'          => array(
					'off' => esc_html__( 'No', 'dsm-supreme-modules-pro-for-divi' ),
					'on'  => esc_html__( 'Yes', 'dsm-supreme-modules-pro-for-divi' ),
				),
				'toggle_slug'      => 'carousel_settings',
				'default'          => 'off',
				'default_on_front' => 'off',
				'show_if'          => array(
					'infinite' => 'on',
				),
				'show_if_not'      => array(
					'multiple_slide_row' => 'on',
				),
			),
			'horizontal_alignment'           => array(
				'label'           => esc_html__( 'Vertical Alignment', 'dsm-supreme-modules-pro-for-divi' ),
				'type'            => 'select',
				'option_category' => 'layout',
				'options'         => array(
					'flex-start' => esc_html__( 'Top', 'dsm-supreme-modules-pro-for-divi' ),
					'center'     => esc_html__( 'Center', 'dsm-supreme-modules-pro-for-divi' ),
					'flex-end'   => esc_html__( 'Bottom', 'dsm-supreme-modules-pro-for-divi' ),
				),
				'default'         => 'center',
				'tab_slug'        => 'advanced',
				'toggle_slug'     => 'layout',
			),
			'arrows'                         => array(
				'label'            => esc_html__( 'Show Arrow', 'dsm-supreme-modules-pro-for-divi' ),
				'type'             => 'yes_no_button',
				'option_category'  => 'configuration',
				'options'          => array(
					'off' => esc_html__( 'No', 'dsm-supreme-modules-pro-for-divi' ),
					'on'  => esc_html__( 'Yes', 'dsm-supreme-modules-pro-for-divi' ),
				),
				'toggle_slug'      => 'carousel_settings',
				'default'          => 'on',
				'default_on_front' => 'on',
				'show_if_not'      => array(
					'infinite_smooth_scrolling' => 'on',
				),
			),
			'arrow_position'                 => array(
				'label'           => esc_html__( 'Arrow Position', 'dsm-supreme-modules-pro-for-divi' ),
				'type'            => 'select',
				'option_category' => 'layout',
				'options'         => array(
					'inside'  => esc_html__( 'Inside', 'dsm-supreme-modules-pro-for-divi' ),
					'outside' => esc_html__( 'Outside', 'dsm-supreme-modules-pro-for-divi' ),
				),
				'default'         => 'outside',
				'tab_slug'        => 'advanced',
				'toggle_slug'     => 'arrow_element',
				'show_if'         => array(
					'arrows' => 'on',
				),
				'show_if_not'     => array(
					'use_arrow_custom_position' => 'on',
				),
			),
			'arrow_position_mobile'          => array(
				'label'           => esc_html__( 'Mobile Arrow Position', 'dsm-supreme-modules-pro-for-divi' ),
				'type'            => 'select',
				'option_category' => 'layout',
				'options'         => array(
					'inside'  => esc_html__( 'Inside', 'dsm-supreme-modules-pro-for-divi' ),
					'outside' => esc_html__( 'Outside', 'dsm-supreme-modules-pro-for-divi' ),
				),
				'default'         => 'inside',
				'tab_slug'        => 'advanced',
				'toggle_slug'     => 'arrow_element',
				'show_if'         => array(
					'arrows'         => 'on',
					'arrow_position' => 'outside',
				),
				'show_if_not'     => array(
					'use_arrow_custom_position' => 'on',
				),
			),
			'use_arrow_custom_position'      => array(
				'label'           => esc_html__( 'Use Arrow Custom Position', 'dsm-supreme-modules-pro-for-divi' ),
				'type'            => 'yes_no_button',
				'option_category' => 'configuration',
				'options'         => array(
					'off' => esc_html__( 'No', 'dsm-supreme-modules-pro-for-divi' ),
					'on'  => esc_html__( 'Yes', 'dsm-supreme-modules-pro-for-divi' ),
				),
				'default'         => 'off',
				'tab_slug'        => 'advanced',
				'toggle_slug'     => 'arrow_element',
			),
			'arrow_custom_position'          => array(
				'label'            => esc_html__( 'Arrow Custom Position', 'dsm-supreme-modules-pro-for-divi' ),
				'type'             => 'range',
				'option_category'  => 'configuration',
				'default_unit'     => 'px',
				'validate_unit'    => true,
				'mobile_options'   => true,
				'unitless'         => false,
				'responsive'       => true,
				'default'          => '-60px',
				'default_on_front' => '-60px',
				'range_settings'   => array(
					'min'  => '-100',
					'max'  => '100',
					'step' => '1',
				),
				'tab_slug'         => 'advanced',
				'toggle_slug'      => 'arrow_element',
				'show_if'          => array(
					'use_arrow_custom_position' => 'on',
				),
			),
			'arrow_prev'                     => array(
				'label'           => esc_html__( 'Use Custom Previous Arrow Icon', 'dsm-supreme-modules-pro-for-divi' ),
				'type'            => 'yes_no_button',
				'option_category' => 'configuration',
				'options'         => array(
					'off' => esc_html__( 'No', 'dsm-supreme-modules-pro-for-divi' ),
					'on'  => esc_html__( 'Yes', 'dsm-supreme-modules-pro-for-divi' ),
				),
				'tab_slug'        => 'advanced',
				'toggle_slug'     => 'arrow_element',
				'description'     => esc_html__( 'Here you can choose to use a custom icon on the previous arrow.', 'dsm-supreme-modules-pro-for-divi' ),
				'default'         => 'off',
			),
			'arrow_prev_font_icon'           => array(
				'label'            => esc_html__( 'Previous Arrow Icon', 'dsm-supreme-modules-pro-for-divi' ),
				'type'             => 'select_icon',
				'option_category'  => 'basic_option',
				'class'            => array( 'et-pb-font-icon' ),
				'default'          => '4',
				'default_on_front' => '4',
				'tab_slug'         => 'advanced',
				'toggle_slug'      => 'arrow_element',
				'show_if'          => array(
					'arrow_prev' => 'on',
				),
			),
			'arrow_next'                     => array(
				'label'           => esc_html__( 'Use Custom Next Arrow Icon', 'dsm-supreme-modules-pro-for-divi' ),
				'type'            => 'yes_no_button',
				'option_category' => 'configuration',
				'options'         => array(
					'off' => esc_html__( 'No', 'dsm-supreme-modules-pro-for-divi' ),
					'on'  => esc_html__( 'Yes', 'dsm-supreme-modules-pro-for-divi' ),
				),
				'tab_slug'        => 'advanced',
				'toggle_slug'     => 'arrow_element',
				'description'     => esc_html__( 'Here you can choose to use a custom icon on the next arrow.', 'dsm-supreme-modules-pro-for-divi' ),
				'default'         => 'off',
			),
			'arrow_next_font_icon'           => array(
				'label'            => esc_html__( 'Next Arrow Icon', 'dsm-supreme-modules-pro-for-divi' ),
				'type'             => 'select_icon',
				'option_category'  => 'basic_option',
				'class'            => array( 'et-pb-font-icon' ),
				'default'          => '5',
				'default_on_front' => '5',
				'tab_slug'         => 'advanced',
				'toggle_slug'      => 'arrow_element',
				'show_if'          => array(
					'arrow_next' => 'on',
				),
			),
			'arrow_horizontal_position'      => array(
				'label'          => esc_html__( 'Arrow Horizontal Position', 'dsm-supreme-modules-pro-for-divi' ),
				'type'           => 'range',
				'default'        => '50%',
				'range_settings' => array(
					'min'  => '0',
					'max'  => '100',
					'step' => '1',
				),
				'show_if'        => array(
					'arrows' => 'on',
				),
				'mobile_options' => true,
				'tab_slug'       => 'advanced',
				'toggle_slug'    => 'arrow_element',
			),
			'arrow_size'                     => array(
				'label'            => esc_html__( 'Arrow Size', 'dsm-supreme-modules-pro-for-divi' ),
				'type'             => 'range',
				'option_category'  => 'configuration',
				'default'          => '40px',
				'default_on_front' => '40px',
				'default_unit'     => 'px',
				'mobile_options'   => true,
				'responsive'       => true,
				'range_settings'   => array(
					'min'  => '20',
					'max'  => '60',
					'step' => '1',
				),
				'tab_slug'         => 'advanced',
				'toggle_slug'      => 'arrow_element',
				'show_if'          => array(
					'arrows' => 'on',
				),
			),
			'dots'                           => array(
				'label'            => esc_html__( 'Show Pagination', 'dsm-supreme-modules-pro-for-divi' ),
				'type'             => 'yes_no_button',
				'option_category'  => 'configuration',
				'options'          => array(
					'off' => esc_html__( 'No', 'dsm-supreme-modules-pro-for-divi' ),
					'on'  => esc_html__( 'Yes', 'dsm-supreme-modules-pro-for-divi' ),
				),
				'toggle_slug'      => 'carousel_settings',
				'default'          => 'on',
				'default_on_front' => 'on',
				'show_if_not'      => array(
					'infinite_smooth_scrolling' => 'on',
				),
			),
			'touch_move'                     => array(
				'label'            => esc_html__( 'Disable Touch/Dragging', 'dsm-supreme-modules-pro-for-divi' ),
				'type'             => 'yes_no_button',
				'option_category'  => 'configuration',
				'options'          => array(
					'off' => esc_html__( 'No', 'dsm-supreme-modules-pro-for-divi' ),
					'on'  => esc_html__( 'Yes', 'dsm-supreme-modules-pro-for-divi' ),
				),
				'toggle_slug'      => 'carousel_settings',
				'default'          => 'off',
				'default_on_front' => 'off',
				'description'      => esc_html__( 'This option will prevent user to touch/drag the slide.', 'dsm-supreme-modules-pro-for-divi' ),
			),
			'grab'                           => array(
				'label'            => esc_html__( 'Use Grab Cursor', 'dsm-supreme-modules-pro-for-divi' ),
				'type'             => 'yes_no_button',
				'option_category'  => 'configuration',
				'options'          => array(
					'off' => esc_html__( 'No', 'dsm-supreme-modules-pro-for-divi' ),
					'on'  => esc_html__( 'Yes', 'dsm-supreme-modules-pro-for-divi' ),
				),
				'toggle_slug'      => 'carousel_settings',
				'default'          => 'on',
				'default_on_front' => 'on',
				'show_if'          => array(
					'touch_move' => 'off',
				),
				'description'      => esc_html__( 'This option may a little improve desktop usability. If true, user will see the "grab" cursor when hover on Carousel.', 'dsm-supreme-modules-pro-for-divi' ),
			),
			'show_lightbox'                  => array(
				'label'            => esc_html__( 'Open Image in Lightbox', 'dsm-supreme-modules-pro-for-divi' ),
				'type'             => 'yes_no_button',
				'option_category'  => 'configuration',
				'options'          => array(
					'off' => esc_html__( 'No', 'dsm-supreme-modules-pro-for-divi' ),
					'on'  => esc_html__( 'Yes', 'dsm-supreme-modules-pro-for-divi' ),
				),
				'toggle_slug'      => 'lightbox_settings',
				'description'      => esc_html__( 'Here you can choose whether or not the image should open in Lightbox.', 'dsm-supreme-modules-pro-for-divi' ),
				'default'          => 'off',
				'default_on_front' => 'off',
			),
			'lightbox_img_sizes'             => array(
				'label'            => esc_html__( 'Image Size', 'dsm-supreme-modules-pro-for-divi' ),
				'type'             => 'select',
				'option_category'  => 'layout',
				'toggle_slug'      => 'lightbox_settings',
				'default'          => 'full',
				'default_on_front' => 'full',
				'computed_affects' => array(
					'__gallery',
				),
				'options'          => self::dsm_get_all_image_sizes(),
				'show_if'          => array(
					'show_lightbox' => 'on',
				),
			),
			'show_lightbox_gallery'          => array(
				'label'            => esc_html__( 'Lightbox in Gallery Mode', 'dsm-supreme-modules-pro-for-divi' ),
				'type'             => 'yes_no_button',
				'option_category'  => 'configuration',
				'options'          => array(
					'off' => esc_html__( 'No', 'dsm-supreme-modules-pro-for-divi' ),
					'on'  => esc_html__( 'Yes', 'dsm-supreme-modules-pro-for-divi' ),
				),
				'toggle_slug'      => 'lightbox_settings',
				'description'      => esc_html__( 'Here you can choose whether or not the lightbox should in Gallery Mode.', 'dsm-supreme-modules-pro-for-divi' ),
				'default'          => 'off',
				'default_on_front' => 'off',
				'show_if'          => array(
					'show_lightbox' => 'on',
				),
			),
			'show_lightbox_caption'          => array(
				'label'            => esc_html__( 'Display Caption', 'dsm-supreme-modules-pro-for-divi' ),
				'type'             => 'yes_no_button',
				'option_category'  => 'configuration',
				'options'          => array(
					'off' => esc_html__( 'No', 'dsm-supreme-modules-pro-for-divi' ),
					'on'  => esc_html__( 'Yes', 'dsm-supreme-modules-pro-for-divi' ),
				),
				'toggle_slug'      => 'lightbox_settings',
				'description'      => esc_html__( 'Here you can choose whether or not the lightbox should in have Title or Description.', 'dsm-supreme-modules-pro-for-divi' ),
				'default'          => 'off',
				'default_on_front' => 'off',
				'show_if'          => array(
					'show_lightbox' => 'on',
				),
			),
			/*
			'dots_position' => array(
				'label'             => esc_html__( 'Pagination Position', 'dsm-supreme-modules-pro-for-divi' ),
				'type'              => 'select',
				'option_category'   => 'layout',
				'options'           => array(
					'inside' => esc_html__( 'Inside', 'dsm-supreme-modules-pro-for-divi' ),
					'outside'  => esc_html__( 'Outside', 'dsm-supreme-modules-pro-for-divi' ),
				),
				'default' => 'inside',
				'tab_slug'          => 'advanced',
				'toggle_slug'       => 'pagination_element',
				'show_if' => array(
					'dots' => 'on',
				),
			),*/
			'arrow_color'                    => array(
				'label'          => esc_html__( 'Arrow Color', 'dsm-supreme-modules-pro-for-divi' ),
				'type'           => 'color-alpha',
				'custom_color'   => true,
				'default'        => '#666',
				'mobile_options' => true,
				'responsive'     => true,
				'hover'          => 'tabs',
				'tab_slug'       => 'advanced',
				'toggle_slug'    => 'arrow_element',
				'show_if'        => array(
					'arrows' => 'on',
				),
			),
			'arrow_background_color'         => array(
				'label'          => esc_html__( 'Arrow Background Color', 'dsm-supreme-modules-pro-for-divi' ),
				'type'           => 'color-alpha',
				'custom_color'   => true,
				'mobile_options' => true,
				'responsive'     => true,
				'hover'          => 'tabs',
				'tab_slug'       => 'advanced',
				'toggle_slug'    => 'arrow_element',
				'show_if'        => array(
					'arrows' => 'on',
				),
			),
			'dots_horizontal_placement'      => array(
				'label'            => esc_html__( 'Pagination Vertical Placement', 'dsm-supreme-modules-pro-for-divi' ),
				'type'             => 'range',
				'option_category'  => 'configuration',
				'default'          => '-30px',
				'default_on_front' => '-30px',
				'default_unit'     => 'px',
				'range_settings'   => array(
					'min'  => '-100',
					'max'  => '100',
					'step' => '1',
				),
				'tab_slug'         => 'advanced',
				'toggle_slug'      => 'pagination_element',
				'show_if'          => array(
					'dots' => 'on',
				),
			),
			'dots_active_color'              => array(
				'label'        => esc_html__( 'Pagination Active Color', 'dsm-supreme-modules-pro-for-divi' ),
				'type'         => 'color-alpha',
				'custom_color' => true,
				'default'      => 'rgba(0,0,0,0.75)',
				'tab_slug'     => 'advanced',
				'toggle_slug'  => 'pagination_element',
				'show_if'      => array(
					'dots' => 'on',
				),
			),
			'dots_inactive_color'            => array(
				'label'        => esc_html__( 'Pagination In-Active Color', 'dsm-supreme-modules-pro-for-divi' ),
				'type'         => 'color-alpha',
				'custom_color' => true,
				'default'      => 'rgba(0,0,0,0.2)',
				'tab_slug'     => 'advanced',
				'toggle_slug'  => 'pagination_element',
				'show_if'      => array(
					'dots' => 'on',
				),
			),
			/*
			'height'                    => array(
				'label'           => esc_html__( 'Height', 'dsm-supreme-modules-pro-for-divi' ),
				'type'            => 'range',
				'option_category' => 'layout',
				'tab_slug'        => 'advanced',
				'toggle_slug'     => 'width',
				'mobile_options'  => true,
				'default_unit'    => 'px',
				'default'         => '',
				'range_settings'  => array(
					'min'  => '100',
					'max'  => '1000',
					'step' => '1',
				),
				'responsive'      => true,
			),*/
			'__gallery'                      => array(
				'type'                => 'computed',
				'computed_callback'   => array( 'DSM_ImageCarousel', 'get_gallery' ),
				'computed_depends_on' => array(
					'gallery_ids',
					'gallery_orderby',
					'sizes',
					'lightbox_img_sizes',
				),
			),
		);
	}

	/**
	 * Get attachment data for gallery module
	 *
	 * @param array $args {
	 *     Gallery Options
	 *
	 *     @type array  $gallery_ids     Attachment Ids of images to be included in gallery.
	 *     @type string $gallery_orderby `orderby` arg for query. Optional.
	 *     @type string $fullwidth       on|off to determine grid / slider layout
	 *     @type string $orientation     Orientation of thumbnails (landscape|portrait).
	 * }
	 * @param array $conditional_tags
	 * @param array $current_page
	 *
	 * @return array Attachments data
	 */
	static function get_gallery( $args = array(), $conditional_tags = array(), $current_page = array() ) {

		$attachments = array();

		$defaults = array(
			'gallery_ids'        => array(),
			'gallery_orderby'    => '',
			'gallery_captions'   => array(),
			'sizes'              => 'full',
			'lightbox_img_sizes' => 'full',
		);

		$args = wp_parse_args( $args, $defaults );

		$attachments_args = array(
			'include'        => $args['gallery_ids'],
			'post_status'    => 'inherit',
			'post_type'      => 'attachment',
			'post_mime_type' => 'image',
			'order'          => 'ASC',
			'orderby'        => 'post__in',
		);

		if ( 'rand' === $args['gallery_orderby'] ) {
			$attachments_args['orderby'] = 'rand';
		}

		$_attachments = get_posts( $attachments_args );

		foreach ( $_attachments as $key => $val ) {
			$attachments[ $key ]                          = $_attachments[ $key ];
			$attachments[ $key ]->image_src_full          = wp_get_attachment_image_src( $val->ID, $args['sizes'] );
			$attachments[ $key ]->lightbox_image_src_full = wp_get_attachment_image_src( $val->ID, $args['lightbox_img_sizes'] );
		}
		return $attachments;
	}

	public function get_transition_fields_css_props() {
		$fields = parent::get_transition_fields_css_props();

		$fields['arrow_color'] = array(
			'color' => '%%order_class%% .swiper-button-prev:before, %%order_class%% .swiper-button-next:before',
		);

		$fields['arrow_background_color'] = array(
			'background-color' => '%%order_class%% .swiper-button-prev, %%order_class%% .swiper-button-next',
		);

		return $fields;

	}

	public function render( $attrs, $content, $render_slug ) {
		$multi_view                                  = et_pb_multi_view_options( $this );
		$gallery_ids                                 = $this->props['gallery_ids'];
		$gallery_orderby                             = $this->props['gallery_orderby'];
		$sizes                                       = $this->props['sizes'];
		$slider_effect                               = $this->props['slider_effect'];
		$slider_effect_shadows                       = $this->props['slider_effect_shadows'];
		$slider_effect_coverflow_rotate              = $this->props['slider_effect_coverflow_rotate'];
		$slider_effect_coverflow_depth               = $this->props['slider_effect_coverflow_depth'];
		$slider_direction                            = $this->props['slider_direction'];
		$slide_to_show                               = $this->props['slide_to_show'];
		$slide_to_show_tablet                        = $this->props['slide_to_show_tablet'];
		$slide_to_show_phone                         = $this->props['slide_to_show_phone'];
		$slide_to_show_last_edited                   = $this->props['slide_to_show_last_edited'];
		$slide_to_scroll                             = $this->props['slide_to_scroll'];
		$slide_to_scroll_tablet                      = $this->props['slide_to_scroll_tablet'];
		$slide_to_scroll_phone                       = $this->props['slide_to_scroll_phone'];
		$slide_to_scroll_last_edited                 = $this->props['slide_to_scroll_last_edited'];
		$multiple_slide_row                          = $this->props['multiple_slide_row'];
		$slide_row                                   = $this->props['slide_row'];
		$slide_row_tablet                            = $this->props['slide_row_tablet'] ? $this->props['slide_row_tablet'] : $slide_row;
		$slide_row_phone                             = $this->props['slide_row_phone'] ? $this->props['slide_row_phone'] : $slide_row_tablet;
		$centered_slides                             = $this->props['centered_slides'];
		$speed                                       = $this->props['speed'];
		$space_between                               = $this->props['space_between'];
		$space_between_values                        = et_pb_responsive_options()->get_property_values( $this->props, 'space_between' );
		$space_between_tablet                        = isset( $space_between_values['tablet'] ) === true && '' !== $space_between_values['tablet'] ? $space_between_values['tablet'] : $space_between;
		$space_between_phone                         = isset( $space_between_values['phone'] ) === true && '' !== $space_between_values['phone'] ? $space_between_values['phone'] : $space_between_tablet;
		$autoplay                                    = $this->props['autoplay'];
		$autoplay_speed                              = $this->props['autoplay_speed'];
		$autoplay_viewport                            = $this->props['autoplay_viewport'];
		$pause_on_hover                              = $this->props['pause_on_hover'];
		$infinite                                    = $this->props['infinite'];
		$infinite_smooth_scrolling                   = $this->props['infinite_smooth_scrolling'];
		$arrows                                      = $this->props['arrows'];
		$dots                                        = $this->props['dots'];
		$touch_move                                  = $this->props['touch_move'];
		$grab                                        = $this->props['grab'];
		$show_lightbox                               = $this->props['show_lightbox'];
		$show_lightbox_gallery                       = $this->props['show_lightbox_gallery'];
		$show_lightbox_caption                       = $this->props['show_lightbox_caption'];
		$lightbox_img_sizes                          = $this->props['lightbox_img_sizes'];
		$dots_horizontal_placement                   = $this->props['dots_horizontal_placement'];
		$arrow_color                                 = $this->props['arrow_color'];
		$arrow_color_hover                           = $this->get_hover_value( 'arrow_color' );
		$arrow_background_color                      = $this->props['arrow_background_color'];
		$arrow_background_color_hover                = $this->get_hover_value( 'arrow_background_color' );
		$arrow_position                              = $this->props['arrow_position'];
		$arrow_position_mobile                       = $this->props['arrow_position_mobile'];
		$use_arrow_custom_position                   = $this->props['use_arrow_custom_position'];
		$arrow_custom_position                       = $this->props['arrow_custom_position'];
		$arrow_custom_position_tablet                = $this->props['arrow_custom_position_tablet'];
		$arrow_custom_position_phone                 = $this->props['arrow_custom_position_phone'];
		$arrow_custom_position_last_edited           = $this->props['arrow_custom_position_last_edited'];
		$arrow_prev_font_icon                        = $this->props['arrow_prev_font_icon'];
		$arrow_next_font_icon                        = $this->props['arrow_next_font_icon'];
		$arrow_size                                  = $this->props['arrow_size'];
		$arrow_size_tablet                           = $this->props['arrow_size_tablet'];
		$arrow_size_phone                            = $this->props['arrow_size_phone'];
		$arrow_size_last_edited                      = $this->props['arrow_size_last_edited'];
		$arrow_horizontal_position                   = $this->props['arrow_horizontal_position'];
		$arrow_horizontal_position_tablet            = $this->props['arrow_horizontal_position_tablet'];
		$arrow_horizontal_position_phone             = $this->props['arrow_horizontal_position_phone'];
		$arrow_horizontal_position_last_edited       = $this->props['arrow_horizontal_position_last_edited'];
		$arrow_horizontal_position_responsive_status = et_pb_get_responsive_status( $arrow_horizontal_position_last_edited );
		$dots_active_color                           = $this->props['dots_active_color'];
		$dots_inactive_color                         = $this->props['dots_inactive_color'];

		/*
		$height                      = $this->props['height'];
		$height_tablet               = $this->props['height_tablet'];
		$height_phone                = $this->props['height_phone'];
		$height_last_edited          = $this->props['height_last_edited'];*/
		$horizontal_alignment = $this->props['horizontal_alignment'];

		if ( 'on' === $show_lightbox ) {
			wp_enqueue_script( 'magnific-popup' );
		}
		wp_enqueue_script( 'dsm-image-carousel' );

		// Get gallery item data.
		$attachments = self::get_gallery(
			array(
				'gallery_ids'        => $gallery_ids,
				'gallery_orderby'    => $gallery_orderby,
				'sizes'              => $sizes,
				'lightbox_img_sizes' => $lightbox_img_sizes,
			)
		);

		if ( empty( $attachments ) ) {
			return;
		}

		// $background_class          = "et_pb_bg_layout_{$background_layout}";
		$video_background          = $this->video_background();
		$parallax_image_background = $this->get_parallax_image_background();

		if ( '' !== $slide_to_show_tablet || '' !== $slide_to_show_phone || '' !== $slide_to_show ) {
			$slide_to_show_responsive_active = et_pb_get_responsive_status( $slide_to_show_last_edited );

			$slide_to_show_values = array(
				'desktop' => $slide_to_show,
				'tablet'  => $slide_to_show_responsive_active ? $slide_to_show_tablet : '',
				'phone'   => $slide_to_show_responsive_active ? $slide_to_show_phone : '',
			);
		}

		if ( '' !== $slide_to_scroll_tablet || '' !== $slide_to_scroll_phone || '' !== $slide_to_scroll ) {
			$slide_to_scroll_responsive_active = et_pb_get_responsive_status( $slide_to_scroll_last_edited );

			$slide_to_scroll_values = array(
				'desktop' => $slide_to_scroll,
				'tablet'  => $slide_to_scroll_responsive_active ? $slide_to_scroll_tablet : '',
				'phone'   => $slide_to_scroll_responsive_active ? $slide_to_scroll_phone : '',
			);
		}

		// Arrow Font Icon Style.
		$this->generate_styles(
			array(
				'utility_arg'    => 'icon_font_family',
				'render_slug'    => $render_slug,
				'base_attr_name' => 'arrow_prev_font_icon',
				'important'      => true,
				'selector'       => '%%order_class%% .swiper-button-prev::before',
				'processor'      => array(
					'ET_Builder_Module_Helper_Style_Processor',
					'process_extended_icon',
				),
			)
		);
		$this->generate_styles(
			array(
				'utility_arg'    => 'icon_font_family',
				'render_slug'    => $render_slug,
				'base_attr_name' => 'arrow_next_font_icon',
				'important'      => true,
				'selector'       => '%%order_class%% .swiper-button-next::before',
				'processor'      => array(
					'ET_Builder_Module_Helper_Style_Processor',
					'process_extended_icon',
				),
			)
		);

		// Arrow Color & BG Color.
		$arrow_color_style_hover = '';
		$this->generate_styles(
			array(
				'base_attr_name' => 'arrow_color',
				'selector'       => '%%order_class%% .swiper-button-prev:before, %%order_class%% .swiper-button-next:before',
				'css_property'   => 'color',
				'render_slug'    => $render_slug,
				'type'           => 'color',
			)
		);

		if ( et_builder_is_hover_enabled( 'arrow_color', $this->props ) ) {
			$arrow_color_style_hover = sprintf( 'color: %1$s;', esc_html( $arrow_color_hover ) );
		}

		if ( '' !== $arrow_color_style_hover ) {
			ET_Builder_Element::set_style(
				$render_slug,
				array(
					'selector'    => '%%order_class%% .swiper-button-prev:hover:before, %%order_class%% .swiper-button-next:hover:before',
					'declaration' => $arrow_color_style_hover,
				)
			);
		}

		$arrow_background_color_style_hover = '';
		$this->generate_styles(
			array(
				'base_attr_name' => 'arrow_background_color',
				'selector'       => '%%order_class%% .swiper-button-prev, %%order_class%% .swiper-button-next',
				'css_property'   => 'background-color',
				'render_slug'    => $render_slug,
				'type'           => 'background-color',
			)
		);

		if ( et_builder_is_hover_enabled( 'arrow_background_color', $this->props ) ) {
			$arrow_background_color_style_hover = sprintf( 'background-color: %1$s;', esc_html( $arrow_background_color_hover ) );
		}

		if ( '' !== $arrow_background_color_style_hover ) {
			ET_Builder_Element::set_style(
				$render_slug,
				array(
					'selector'    => '%%order_class%% .swiper-button-prev:hover, %%order_class%% .swiper-button-next:hover',
					'declaration' => $arrow_background_color_style_hover,
				)
			);
		}

		// Dots.
		if ( '-30px' !== $dots_horizontal_placement ) {
			ET_Builder_Element::set_style(
				$render_slug,
				array(
					'selector'    => '%%order_class%% .swiper-pagination-bullets, %%order_class%% .swiper-pagination-custom, %%order_class%% .swiper-pagination-fraction',
					'declaration' => sprintf(
						'bottom: %1$s;',
						esc_attr( $dots_horizontal_placement )
					),
				)
			);
		}

		if ( '' !== $dots_active_color ) {
			ET_Builder_Element::set_style(
				$render_slug,
				array(
					'selector'    => '%%order_class%% .swiper-pagination-bullet.swiper-pagination-bullet-active',
					'declaration' => sprintf(
						'background: %1$s; opacity: 1;',
						esc_html( $dots_active_color )
					),
				)
			);
		}

		if ( '' !== $dots_inactive_color ) {
			ET_Builder_Element::set_style(
				$render_slug,
				array(
					'selector'    => '%%order_class%% .swiper-pagination-bullet',
					'declaration' => sprintf(
						'background: %1$s; opacity: 1;',
						esc_html( $dots_inactive_color )
					),
				)
			);
		}

		if ( 'inside' === $arrow_position_mobile ) {
			ET_Builder_Element::set_style(
				$render_slug,
				array(
					'selector'    => '%%order_class%%.dsm_image_carousel_arrow_outside.dsm_image_carousel_arrow_mobile_inside .swiper-button-prev',
					'declaration' => 'left: 0;',
					'media_query' => ET_Builder_Element::get_media_query( 'max_width_767' ),
				)
			);
			ET_Builder_Element::set_style(
				$render_slug,
				array(
					'selector'    => '%%order_class%%.dsm_image_carousel_arrow_outside.dsm_image_carousel_arrow_mobile_inside .swiper-button-next',
					'declaration' => 'right: 0;',
					'media_query' => ET_Builder_Element::get_media_query( 'max_width_767' ),
				)
			);
		}

		if ( 'off' !== $use_arrow_custom_position ) {

			if ( '' !== $arrow_custom_position_tablet || '' !== $arrow_custom_position_phone || '' !== $arrow_custom_position ) {
				$arrow_custom_position_responsive_active = et_pb_get_responsive_status( $arrow_custom_position_last_edited );

				$arrow_custom_position_values = array(
					'desktop' => $arrow_custom_position,
					'tablet'  => $arrow_custom_position_responsive_active ? $arrow_custom_position_tablet : '',
					'phone'   => $arrow_custom_position_responsive_active ? $arrow_custom_position_phone : '',
				);

				et_pb_responsive_options()->generate_responsive_css( $arrow_custom_position_values, '%%order_class%% .swiper-button-prev', 'left', $render_slug );
				et_pb_responsive_options()->generate_responsive_css( $arrow_custom_position_values, '%%order_class%% .swiper-button-next', 'right', $render_slug );
			}
		} else {
			$arrow_size_responsive_active = et_pb_get_responsive_status( $arrow_size_last_edited );

			$arrow_size_values = array(
				'desktop' => $arrow_size,
				'tablet'  => $arrow_size_responsive_active ? $arrow_size_tablet : '',
				'phone'   => $arrow_size_responsive_active ? $arrow_size_phone : '',
			);

			$arrow_size_height_width_values = array(
				'desktop' => ( floatval( $arrow_size ) + 20 ) . 'px',
				'tablet'  => $arrow_size_responsive_active ? ( floatval( $arrow_size_tablet ) + 20 ) . 'px' : '',
				'phone'   => $arrow_size_responsive_active ? ( floatval( $arrow_size_phone ) + 20 ) . 'px' : '',
			);

			$arrow_size_margin_values = array(
				'desktop' => '-' . ( floatval( $arrow_size ) + 20 ) / 2 . 'px',
				'tablet'  => $arrow_size_responsive_active ? '-' . ( floatval( $arrow_size_tablet ) + 20 ) / 2 . 'px' : '',
				'phone'   => $arrow_size_responsive_active ? '-' . ( floatval( $arrow_size_phone ) + 20 ) / 2 . 'px' : '',
			);

			$arrow_size_left_right_values = array(
				'desktop' => '-' . ( floatval( $arrow_size ) + 20 ) . 'px',
				'tablet'  => $arrow_size_responsive_active ? '-' . ( floatval( $arrow_size_tablet ) + 20 ) . 'px' : '',
				'phone'   => $arrow_size_responsive_active ? '-' . ( floatval( $arrow_size_phone ) + 20 ) . 'px' : '',
			);

			if ( '' !== $arrow_size_tablet || '' !== $arrow_size_phone || '40px' !== $arrow_size ) {
				et_pb_responsive_options()->generate_responsive_css( $arrow_size_values, '%%order_class%% .swiper-button-prev:before, %%order_class%% .swiper-button-next:before', 'font-size', $render_slug );
				et_pb_responsive_options()->generate_responsive_css( $arrow_size_height_width_values, '%%order_class%% .swiper-button-prev, %%order_class%% .swiper-button-next', 'height', $render_slug );
				et_pb_responsive_options()->generate_responsive_css( $arrow_size_height_width_values, '%%order_class%% .swiper-button-prev, %%order_class%% .swiper-button-next', 'width', $render_slug );
				et_pb_responsive_options()->generate_responsive_css( $arrow_size_margin_values, '%%order_class%% .swiper-button-prev, %%order_class%% .swiper-button-next', 'margin-top', $render_slug );
				if ( 'outside' === $arrow_position ) {
					et_pb_responsive_options()->generate_responsive_css( $arrow_size_left_right_values, '%%order_class%%.dsm_image_carousel_arrow_outside .swiper-button-prev', 'left', $render_slug );
					et_pb_responsive_options()->generate_responsive_css( $arrow_size_left_right_values, '%%order_class%%.dsm_image_carousel_arrow_outside .swiper-button-next', 'right', $render_slug );
				}
			}
		}

		// Horizontal.
		ET_Builder_Element::set_style(
			$render_slug,
			array(
				'selector'    => '%%order_class%% .swiper-arrow-button',
				'declaration' => sprintf( 'top: %1$s;', $arrow_horizontal_position ),
			)
		);

		if ( '' !== $arrow_horizontal_position_tablet && $arrow_horizontal_position_responsive_status ) {
			ET_Builder_Element::set_style(
				$render_slug,
				array(
					'selector'    => '%%order_class%% .swiper-arrow-button',
					'declaration' => sprintf( 'top: %1$s;', $arrow_horizontal_position_tablet ),
					'media_query' => ET_Builder_Element::get_media_query( 'max_width_980' ),
				)
			);
		}

		if ( '' !== $arrow_horizontal_position_phone && $arrow_horizontal_position_responsive_status ) {
			ET_Builder_Element::set_style(
				$render_slug,
				array(
					'selector'    => '%%order_class%% .swiper-arrow-button',
					'declaration' => sprintf( 'top: %1$s;', $arrow_horizontal_position_phone ),
					'media_query' => ET_Builder_Element::get_media_query( 'max_width_767' ),
				)
			);
		}

		if ( '' !== $horizontal_alignment ) {
			ET_Builder_Element::set_style(
				$render_slug,
				array(
					'selector'    => '%%order_class%% .dsm_image_carousel_container .swiper-wrapper',
					'declaration' => sprintf(
						'align-items: %1$s;',
						esc_attr( $horizontal_alignment )
					),
				)
			);
		}

		$this->add_classname(
			array(
				'on' !== $use_arrow_custom_position ? "dsm_image_carousel_arrow_{$arrow_position} dsm_image_carousel_arrow_mobile_{$arrow_position_mobile}" : '',
				'on' === $infinite_smooth_scrolling ? 'dsm_image_carousel_infinite_scroll' : '',
			)
		);

		/*
		if ( 'outside' === $dots_position ) {
			$this->add_classname( array(
				'dsm_image_carousel_pagination_outside',
			) );
		}

		$lightbox_gallery_src = array();
		foreach ( $attachments as $id => $attachment ) {
			$asd = array(
				'src' => esc_url( $attachment->image_src_full[0] ),
			);
			array_push($lightbox_gallery_src, $asd);

		}*/
		$loop_check = 'off' !== $infinite ? 'true' : 'false';

		$image_carousel_data_attr = '';
		$image_carousel_data_attr = sprintf(
			' data-effect="%1$s"
			data-slider-effect-shadows="%2$s"
			data-slider-effect-coverflow-rotate="%3$s"
			data-slider-effect-coverflow-depth="%4$s"
			data-loop="%5$s"
			data-slide-to-show="%6$s"
			data-slide-to-show-tablet="%7$s"
			data-slide-to-show-phone="%8$s"
			data-slide-to-scroll="%9$s"
			data-slide-to-scroll-tablet="%10$s"
			data-slide-to-scroll-phone="%11$s"
			data-space-between="%12$s"
			data-space-between-tablet="%13$s"
			data-space-between-phone="%14$s"
			data-slide-row="%15$s"
			data-centered-slides="%16$s"
			data-speed="%17$s"
			data-autoplay="%18$s"
			data-autoplay-speed="%19$s"
			data-touch-move="%20$s"
			data-grab="%21$s"
			data-pause-on-hover="%22$s"
			data-show-lightbox="%23$s"
			data-lightbox-gallery="%24$s"
			data-lightbox-caption="%25$s"
			data-infinite-scrolling="%26$s"
			data-slide-row-tablet="%27$s"
			data-slide-row-phone="%28$s"
			%29$s
			',
			esc_attr( $slider_effect ),
			'off' !== $slider_effect_shadows ? 'true' : 'false',
			esc_attr( $slider_effect_coverflow_rotate ),
			esc_attr( $slider_effect_coverflow_depth ),
			'off' !== $multiple_slide_row ? 'false' : $loop_check,
			esc_attr( $slide_to_show ),
			et_pb_get_responsive_status( $slide_to_show_last_edited ) && '' !== $slide_to_show_tablet ? $slide_to_show_tablet : '1',
			et_pb_get_responsive_status( $slide_to_show_last_edited ) && '' !== $slide_to_show_phone ? $slide_to_show_phone : '1',
			esc_attr( $slide_to_scroll ),
			et_pb_get_responsive_status( $slide_to_scroll_last_edited ) && '' !== $slide_to_scroll_tablet ? $slide_to_scroll_tablet : '1',
			et_pb_get_responsive_status( $slide_to_scroll_last_edited ) && '' !== $slide_to_scroll_phone ? $slide_to_scroll_phone : '1',
			'cube' !== $slider_effect ? $space_between : '0',
			'cube' !== $slider_effect ? $space_between_tablet : '0',
			'cube' !== $slider_effect ? $space_between_phone : '0',
			'off' !== $multiple_slide_row ? $slide_row : '1',
			'off' !== $centered_slides ? 'true' : 'false',
			esc_attr( $speed ),
			'off' !== $autoplay ? 'true' : 'false',
			esc_attr( $autoplay_speed ),
			'off' !== $touch_move ? 'false' : 'true',
			'off' !== $grab ? 'true' : 'false',
			'off' !== $pause_on_hover ? 'true' : 'false',
			'off' !== $show_lightbox ? 'true' : 'false',
			'off' !== $show_lightbox_gallery ? 'true' : 'false',
			'off' !== $show_lightbox_caption ? 'true' : 'false',
			'on' === $infinite_smooth_scrolling ? 'true' : 'false',
			esc_attr( $slide_row_tablet ),
			esc_attr( $slide_row_phone ),
			'off' !== $autoplay && '' !== $autoplay_viewport ? esc_attr( 'data-autoplay-viewport=' . $autoplay_viewport ) : ''
		);

		$output = sprintf(
			'<div class="swiper-container dsm_image_carousel_container" dir="%3$s"%2$s><div class="swiper-wrapper">%1$s',
			et_core_sanitized_previously( $this->content ),
			$image_carousel_data_attr,
			esc_attr( $slider_direction )
		);

		$output .= $video_background;
		$output .= $parallax_image_background;

		// Images: Add CSS Filters and Mix Blend Mode rules (if set).
		if ( array_key_exists( 'image', $this->advanced_fields ) && array_key_exists( 'css', $this->advanced_fields['image'] ) ) {
			$generate_css_filters_item = $this->generate_css_filters(
				$render_slug,
				'child_',
				self::$data_utils->array_get( $this->advanced_fields['image']['css'], 'main', '%%order_class%% .dsm_image_carousel_item img' )
			);
		}

		foreach ( $attachments as $id => $attachment ) {
			$dsm_upload_gallery_custom_link_url       = get_post_meta( $attachment->ID, 'dsm_upload_gallery_custom_link_url', true );
			$dsm_upload_gallery_link_url_target       = get_post_meta( $attachment->ID, 'dsm_upload_gallery_link_url_target', true );
			$dsm_upload_gallery_link_as_download_file = get_post_meta( $attachment->ID, 'dsm_upload_gallery_link_as_download_file', true );
			$dsm_image_alt_text                       = get_post_meta( $attachment->ID, '_wp_attachment_image_alt', true );

			$image_classes[] = 'swiper-lazy';

			$image = $multi_view->render_element(
				array(
					'tag'   => 'img',
					'attrs' => array(
						'src'                        => esc_url( $attachment->image_src_full[0] ),
						'class'                      => implode( ' ', $image_classes ),
						'alt'                        => esc_attr( $dsm_image_alt_text ),
						'title'                      => esc_attr( $attachment->post_title ),
						'width'                      => esc_attr( $attachment->image_src_full[1] ),
						'height'                     => esc_attr( $attachment->image_src_full[2] ),
						'data-dsm-image-description' => esc_attr( $attachment->post_excerpt ),
					),
				)
			);

			$image_url_condition = '' !== $dsm_upload_gallery_custom_link_url ? esc_url( $dsm_upload_gallery_custom_link_url ) : esc_url( $attachment->image_src_full[0] );
			$image_output        = 'on' === $show_lightbox || '' !== $dsm_upload_gallery_custom_link_url ? sprintf(
				'<a class="%2$s%4$s" href="%1$s"%3$s%5$s%6$s>%7$s</a>',
				esc_url( $image_url_condition ),
				'on' === $show_lightbox && '' === $dsm_upload_gallery_custom_link_url ? 'dsm_image_carousel_lightbox' : '',
				'on' === $show_lightbox && '' === $dsm_upload_gallery_custom_link_url ? sprintf(
					' data-mfp-src="%1$s"',
					esc_url( $attachment->lightbox_image_src_full[0] )
				) : '',
				'' !== $dsm_upload_gallery_custom_link_url && 'on' === $show_lightbox ? ' dsm_image_carousel_link' : '',
				'_blank' === $dsm_upload_gallery_link_url_target ? esc_attr( " target=$dsm_upload_gallery_link_url_target " ) : '',
				( '1' === $dsm_upload_gallery_link_as_download_file ? ' download' : '' ),
				$image
			) : $image;

			$output .= sprintf(
				'<div class="swiper-slide dsm_image_carousel_item%2$s">%1$s</div>',
				$image_output,
				$generate_css_filters_item
			);

		}

		$output      .= '</div>';
		$output      .= '</div>';
		$swiper_arrow = sprintf(
			'<div class="swiper-button-prev swiper-arrow-button" data-icon="%1$s"></div><div class="swiper-button-next swiper-arrow-button" data-icon="%2$s"></div>',
			esc_attr( et_pb_process_font_icon( $arrow_prev_font_icon ) ),
			esc_attr( et_pb_process_font_icon( $arrow_next_font_icon ) )
		);

		'off' !== $arrows && 'off' === $infinite_smooth_scrolling ? $output .= $swiper_arrow : '';
		'off' !== $dots && 'off' === $infinite_smooth_scrolling ? $output   .= '<div class="swiper-pagination"></div>' : '';

		if ( isset( get_option( 'dsm_settings_misc' )['dsm_dynamic_assets'] ) && ! empty( get_option( 'dsm_settings_misc' )['dsm_dynamic_assets'] ) && 'on' === get_option( 'dsm_settings_misc' )['dsm_dynamic_assets'] ) {
			if ( isset( get_option( 'dsm_settings_misc' )['dsm_dynamic_assets_compatibility'] ) && ! empty( get_option( 'dsm_settings_misc' )['dsm_dynamic_assets'] ) && 'on' === get_option( 'dsm_settings_misc' )['dsm_dynamic_assets_compatibility'] ) {
				wp_enqueue_style( 'dsm-swiper' );
				wp_enqueue_style( 'dsm-image-carousel', plugin_dir_url( __DIR__ ) . 'ImageCarousel/style.css', array(), DSM_PRO_VERSION, 'all' );
			} else {
				add_filter( 'et_global_assets_list', array( $this, 'dsm_load_required_divi_assets' ), 10, 3 );
				add_filter( 'et_late_global_assets_list', array( $this, 'dsm_load_required_divi_assets' ), 10, 3 );
			}
		}

		return $output;

	}
	static function dsm_get_all_image_sizes() {
		global $_wp_additional_image_sizes;

		$sizes = array();

		foreach ( get_intermediate_image_sizes() as $_size ) {
			if ( in_array( $_size, array( 'thumbnail', 'medium', 'medium_large', 'large' ) ) ) {
				$sizes[ $_size ]['width']  = get_option( "{$_size}_size_w" );
				$sizes[ $_size ]['height'] = get_option( "{$_size}_size_h" );
				$sizes[ $_size ]['crop']   = (bool) get_option( "{$_size}_crop" );
			} elseif ( isset( $_wp_additional_image_sizes[ $_size ] ) ) {
				$sizes[ $_size ] = array(
					'width'  => $_wp_additional_image_sizes[ $_size ]['width'],
					'height' => $_wp_additional_image_sizes[ $_size ]['height'],
					'crop'   => $_wp_additional_image_sizes[ $_size ]['crop'],
				);
			}
		}

		$image_sizes = array(
			'full' => esc_html__( 'Full Size', 'dsm-supreme-modules-pro-for-divi' ),
		);

		foreach ( $sizes as $size_key => $size_value ) {
			$size_key_title           = str_replace( '_', ' ', $size_key );
			$size_key_title           = str_replace( '-', ' ', $size_key_title );
			$image_sizes[ $size_key ] = sprintf(
				'%1$s (W: %2$s x H: %3$s,%4$s Cropped)',
				ucfirst( $size_key_title ),
				$size_value['width'],
				$size_value['height'],
				( false === $size_value['crop'] ? ' Not' : '' )
			);
		}

		return $image_sizes;
	}

	/**
	 * Force load global styles.
	 *
	 * @param array $assets_list Current global assets on the list.
	 *
	 * @return array
	 */
	public function dsm_load_required_divi_assets( $assets_list, $assets_args, $instance ) {
		$assets_prefix     = et_get_dynamic_assets_path();
		$all_shortcodes    = $instance->get_saved_page_shortcodes();
		$this->_cpt_suffix = et_builder_should_wrap_styles() && ! et_is_builder_plugin_active() ? '_cpt' : '';

		if ( ! isset( $assets_list['et_pb_overlay'] ) ) {
			$assets_list['et_pb_overlay'] = array(
				'css' => "{$assets_prefix}/css/overlay{$this->_cpt_suffix}.css",
			);
		}

		// ImageCarousel & Swiper.
		if ( ! isset( $assets_list['dsm_swiper'] ) ) {
			$assets_list['dsm_swiper'] = array(
				'css' => DSM_DIR_PATH . 'public/css/swiper.css',
			);
		}
		if ( ! isset( $assets_list['dsm_image_carousel'] ) ) {
			$assets_list['dsm_image_carousel'] = array(
				'css' => plugin_dir_url( __DIR__ ) . 'ImageCarousel/style.css',
			);
		}
		if ( ! isset( $assets_list['et_jquery_magnific_popup'] ) ) {
			$assets_list['et_jquery_magnific_popup'] = array(
				'css' => "{$assets_prefix}/css/magnific_popup.css",
			);
		}
		if ( ! isset( $assets_list['et_icons_all'] ) ) {
			$assets_list['et_icons_all'] = array(
				'css' => "{$assets_prefix}/css/icons_all.css",
			);
		}

		if ( ! isset( $assets_list['et_icons_fa'] ) ) {
			$assets_list['et_icons_fa'] = array(
				'css' => "{$assets_prefix}/css/icons_fa_all.css",
			);
		}

		return $assets_list;
	}
}

new DSM_ImageCarousel();
