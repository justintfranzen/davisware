<?php

/**
 * The dashboard-specific functionality of the plugin.
 *
 * @link       http://mythemeshop.com
 * @since      1.0
 *
 * @package    MTSNBF
 * @subpackage MTSNBF/admin
 */

/**
 * The dashboard-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the dashboard-specific stylesheet and JavaScript.
 *
 * @package    MTSNBF
 * @subpackage MTSNBF/admin
 * @author     MyThemeShop
 */
// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

if ( ! class_exists( 'MTSNBF_Admin' ) ) {
	class MTSNBF_Admin {

		/**
		 * The ID of this plugin.
		 *
		 * @since    1.0
		 * @access   private
		 * @var      string    $plugin_name    The ID of this plugin.
		 */
		private $plugin_name;

		/**
		 * The version of this plugin.
		 *
		 * @since    1.0
		 * @access   private
		 * @var      string    $version    The current version of this plugin.
		 */
		private $version;

		/**
		 * Post types where user can override bar on single view.
		 *
		 * @var [type]
		 */
		private $force_bar_post_types;

		/**
		 * Initialize the class and set its properties.
		 *
		 * @since 1.0
		 * @param string $plugin_name       The name of this plugin.
		 * @param string $version    The version of this plugin.
		 */
		public function __construct( $plugin_name, $version ) {
			$this->plugin_name = $plugin_name;
			$this->version     = $version;
		}

		/**
		 * Register the stylesheets for the Dashboard.
		 *
		 * @since 1.0
		 */
		public function enqueue_styles() {
			$screen    = get_current_screen();
			$screen_id = $screen->id;

			$force_bar_post_types = $this->force_bar_post_types;

			if ( 'mts_notification_bar' === $screen_id || in_array( $screen_id, $force_bar_post_types, true ) ) {

				wp_enqueue_style( 'wp-color-picker' );
				wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/wp-notification-bars-admin.css', array(), $this->version, 'all' );
				if ( 'mts_notification_bar' !== $screen_id ) {
					wp_enqueue_style( $this->plugin_name . '_select2', plugin_dir_url( __FILE__ ) . 'css/select2.min.css', array(), $this->version, 'all' );
				}
			}
		}

		/**
		 * Register the JavaScript for the dashboard.
		 *
		 * @since 1.0
		 */
		public function enqueue_scripts() {

			$screen    = get_current_screen();
			$screen_id = $screen->id;

			$force_bar_post_types = $this->force_bar_post_types;

			if ( 'mts_notification_bar' === $screen_id || in_array( $screen_id, $force_bar_post_types, true ) ) {

				wp_enqueue_script( 'wp-color-picker' );

				if ( 'mts_notification_bar' !== $screen_id ) {

					wp_enqueue_script(
						$this->plugin_name . '_select2',
						plugin_dir_url( __FILE__ ) . 'js/select2.full.min.js',
						array( 'jquery' ),
						$this->version,
						false
					);
				}

				wp_enqueue_script(
					$this->plugin_name,
					plugin_dir_url( __FILE__ ) . 'js/wp-notification-bars-admin.js',
					array(
						'jquery',
						'wp-color-picker',
					),
					$this->version,
					false
				);

				wp_localize_script(
					$this->plugin_name,
					'mtsnb_locale',
					array(
						'select_placeholder' => __( 'Enter Notification Bar Title', 'wp-notification-bars' ),
					)
				);
			}

			wp_enqueue_script(
				$this->plugin_name,
				plugin_dir_url( __FILE__ ) . 'js/wp-nb-admin-notices.js',
				array(
					'jquery',
				),
				$this->version,
				false
			);
		}

		//
		// CPT /////////
		//

		/**
		 * Register MTS Notification Bar Post Type, attached to 'init'
		 *
		 * @since    1.0
		 */
		public function mts_notification_cpt() {
			$labels = array(
				'name'               => _x( 'Notification Bars', 'post type general name', 'wp-notification-bars' ),
				'singular_name'      => _x( 'Notification Bar', 'post type singular name', 'wp-notification-bars' ),
				'menu_name'          => _x( 'Notification Bars', 'admin menu', 'wp-notification-bars' ),
				'name_admin_bar'     => _x( 'Notification Bar', 'add new on admin bar', 'wp-notification-bars' ),
				'add_new'            => _x( 'Add New', 'notification bar', 'wp-notification-bars' ),
				'add_new_item'       => __( 'Add New Notification Bar', 'wp-notification-bars' ),
				'new_item'           => __( 'New Notification Bar', 'wp-notification-bars' ),
				'edit_item'          => __( 'Edit Notification Bar', 'wp-notification-bars' ),
				'view_item'          => __( 'View Notification Bar', 'wp-notification-bars' ),
				'all_items'          => __( 'All Notification Bars', 'wp-notification-bars' ),
				'search_items'       => __( 'Search Notification Bars', 'wp-notification-bars' ),
				'parent_item_colon'  => __( 'Parent Notification Bars:', 'wp-notification-bars' ),
				'not_found'          => __( 'No notification bars found.', 'wp-notification-bars' ),
				'not_found_in_trash' => __( 'No notification bars found in Trash.', 'wp-notification-bars' ),
			);

			$args = array(
				'labels'             => $labels,
				'public'             => false,
				'show_ui'            => true,
				'capability_type'    => 'post',
				'hierarchical'       => false,
				'rewrite'            => false,
				'publicly_queryable' => false,
				'menu_position'      => 100,
				'menu_icon'          => 'dashicons-info',
				'has_archive'        => false,
				'supports'           => array( 'title' ),
			);

			register_post_type( 'mts_notification_bar', $args );

			// Filter supported post types where user can override bar on single view.
			$force_bar_supported_post_types = apply_filters( 'mtsnb_force_bar_post_types', array( 'post', 'page' ) );

			$key = array_search( 'mts_notification_bar', $force_bar_supported_post_types, true );
			if ( false !== $key ) {
				unset( $force_bar_supported_post_types[ $key ] );
			}

			$this->force_bar_post_types = $force_bar_supported_post_types;
		}

		/**
		 * Add preview button to edit bar page.
		 *
		 * @since    1.0
		 */
		public function add_preview_button() {
			global $post;
			if ( 'mts_notification_bar' === $post->post_type ) {
				echo '<div class="misc-pub-section">';
					echo '<a href="#" class="button" id="preview-bar"><i class="dashicons dashicons-visibility"></i> ' . esc_html__( 'Preview Bar', 'wp-notification-bars' ) . '</a>';
				echo '</div>';
			}
		}

		/**
		 * Add the Meta Box.
		 *
		 * @since    1.0
		 */
		public function add_custom_meta_box() {
			add_meta_box(
				'custom_meta_box',
				__( 'Settings', 'wp-notification-bars' ),
				array( $this, 'show_custom_meta_box' ),
				'mts_notification_bar',
				'normal',
				'high'
			);
		}

		/**
		 * The Callback, Meta Box Content.
		 *
		 * @since    1.0
		 */
		public function show_custom_meta_box( $post ) {

			$general_options = array(
				array(
					'type'    => 'select',
					'name'    => 'button',
					'label'   => __( 'Hide/Close Button', 'wp-notification-bars' ),
					'default' => 'no_button',
					'options' => array(
						'no_button'     => __( 'No Button', 'wp-notification-bars' ),
						'toggle_button' => __( 'Toggle Button', 'wp-notification-bars' ),
						'close_button'  => __( 'Close Button', 'wp-notification-bars' ),
					),
					'class'   => 'mtsnb-has-child-opt',
				),
				array(
					'type'    => 'number',
					'name'    => 'content_width',
					'label'   => __( 'Content Width (px)', 'wp-notification-bars' ),
					'default' => '960',
				),
				array(
					'type'    => 'select',
					'name'    => 'css_position',
					'label'   => __( 'Notification bar CSS position', 'wp-notification-bars' ),
					'default' => 'fixed',
					'options' => array(
						'fixed'    => __( 'Fixed', 'wp-notification-bars' ),
						'absolute' => __( 'Absolute', 'wp-notification-bars' ),
					),
				),
			);

			$style_options = array(
				array(
					'type'    => 'color',
					'name'    => 'bg_color',
					'label'   => __( 'Background Color', 'wp-notification-bars' ),
					'default' => '#d35151',
				),
				array(
					'type'    => 'color',
					'name'    => 'txt_color',
					'label'   => __( 'Text Color', 'wp-notification-bars' ),
					'default' => '#ffffff',
				),
				array(
					'type'    => 'color',
					'name'    => 'link_color',
					'label'   => __( 'Link Color/Button Color', 'wp-notification-bars' ),
					'default' => '#f4a700',
				),
				array(
					'type'    => 'number',
					'name'    => 'font_size',
					'label'   => __( 'Font size (px)', 'wp-notification-bars' ),
					'default' => '15',
				),
			);

			$button_content_type_options = array(
				array(
					'type'    => 'select',
					'name'    => 'basic_link_style',
					'label'   => __( 'Link Style', 'wp-notification-bars' ),
					'default' => 'link',
					'options' => array(
						'link'   => __( 'Link', 'wp-notification-bars' ),
						'button' => __( 'Button', 'wp-notification-bars' ),
					),
				),
				array(
					'type'    => 'text',
					'name'    => 'basic_text',
					'label'   => __( 'Text', 'wp-notification-bars' ),
					'default' => '',
				),
				array(
					'type'    => 'text',
					'name'    => 'basic_link_text',
					'label'   => __( 'Link/Button Text', 'wp-notification-bars' ),
					'default' => '',
				),
				array(
					'type'    => 'text',
					'name'    => 'basic_link_url',
					'label'   => __( 'Link/Button Url', 'wp-notification-bars' ),
					'default' => '',
				),
			);

			$custom_content_type_options = array(
				array(
					'type'    => 'textarea',
					'name'    => 'custom_content',
					'label'   => __( 'Add custom content, shortcodes allowed', 'wp-notification-bars' ),
					'default' => '',
				),
			);

			// Add an nonce field so we can check for it later.
			wp_nonce_field( 'mtsnb_meta_box', 'mtsnb_meta_box_nonce' );
			// Use get_post_meta to retrieve an existing value from the database.
			$value = get_post_meta( $post->ID, '_mtsnb_data', true );
			?>
			<div class="mtsnb-tabs clearfix">
				<div class="mtsnb-tabs-inner clearfix">
					<?php $active_tab = ( isset( $value['active_tab'] ) && ! empty( $value['active_tab'] ) ) ? $value['active_tab'] : 'general'; ?>
					<input type="hidden" class="mtsnb-tab-option" name="mtsnb_fields[active_tab]" id="mtsnb_fields_active_tab" value="<?php echo esc_attr( $active_tab ); ?>" />
					<ul class="mtsnb-tabs-nav" id="main-tabs-nav">
						<li>
							<a href="#tab-general" <?php $this->active_class( $active_tab, 'general', true ); ?>>
								<span class="mtsnb-tab-title"><i class="dashicons dashicons-admin-generic"></i><?php esc_html_e( 'General', 'wp-notification-bars' ); ?></span>
							</a>
						</li>
						<li>
							<a href="#tab-type" <?php $this->active_class( $active_tab, 'type', true ); ?>>
								<span class="mtsnb-tab-title"><i class="dashicons dashicons-edit"></i><?php esc_html_e( 'Content', 'wp-notification-bars' ); ?></span>
							</a>
						</li>
						<li>
							<a href="#tab-style" <?php $this->active_class( $active_tab, 'style', true ); ?>>
								<span class="mtsnb-tab-title"><i class="dashicons dashicons-admin-appearance"></i><?php esc_html_e( 'Style', 'wp-notification-bars' ); ?></span>
							</a>
						</li>
						<li>
							<a href="#tab-conditions" <?php $this->active_class( $active_tab, 'conditions', true ); ?>>
								<span class="mtsnb-tab-title"><i class="dashicons dashicons-admin-settings"></i><?php esc_html_e( 'Conditions', 'wp-notification-bars' ); ?></span>
							</a>
						</li>
					</ul>
					<div class="mtsnb-tabs-wrap" id="main-tabs-wrap">
						<div id="tab-general" class="mtsnb-tabs-content <?php $this->active_class( $active_tab, 'general', false ); ?>">
							<div class="mtsnb-tab-desc"><?php esc_html_e( 'Select basic settings like close button type and CSS position of the bar.', 'wp-notification-bars' ); ?></div>
							<div class="mtsnb-tab-options clearfix">
								<?php
								foreach ( $general_options as $option_args ) {
									$this->custom_meta_field( $option_args, $value );
								}
								?>
							</div>
						</div>
						<div id="tab-type" class="mtsnb-tabs-content <?php $this->active_class( $active_tab, 'type', false ); ?>">
							<div class="mtsnb-tab-desc"><?php esc_html_e( 'Set up notification bar content. Select content type and fill in the fields.', 'wp-notification-bars' ); ?></div>
							<div class="mtsnb-tab-options clearfix">
								<?php $content_type = ( isset( $value['content_type'] ) && ! empty( $value['content_type'] ) ) ? $value['content_type'] : 'button'; ?>
								<input type="hidden" class="mtsnb-tab-option" name="mtsnb_fields[content_type]" id="mtsnb_fields_content_type" value="<?php echo esc_attr( $content_type ); ?>" />
								<ul class="mtsnb-tabs-nav" id="sub-tabs-nav">
									<li><a href="#tab-button" <?php $this->active_class( $content_type, 'button', true ); ?>><?php esc_html_e( 'Text and Link/Button', 'wp-notification-bars' ); ?></a></li>
									<li><a href="#tab-custom" <?php $this->active_class( $content_type, 'custom', true ); ?>><?php esc_html_e( 'Custom', 'wp-notification-bars' ); ?></a></li>
								</ul>
								<div class="meta-tabs-wrap" id="sub-tabs-wrap">
									<div id="tab-button" class="mtsnb-tabs-content <?php $this->active_class( $content_type, 'button', false ); ?>">
										<?php
										foreach ( $button_content_type_options as $option_args ) {
											$this->custom_meta_field( $option_args, $value );
										}
										?>
									</div>
									<div id="tab-custom" class="mtsnb-tabs-content <?php $this->active_class( $content_type, 'custom', false ); ?>">
										<?php
										foreach ( $custom_content_type_options as $option_args ) {
											$this->custom_meta_field( $option_args, $value );
										}
										?>
									</div>
								</div>
							</div>
						</div>
						<div id="tab-style" class="mtsnb-tabs-content <?php $this->active_class( $active_tab, 'style', false ); ?>">
							<div class="mtsnb-tab-desc"><?php esc_html_e( 'Change the appearance of the notification bar.', 'wp-notification-bars' ); ?></div>
							<div class="mtsnb-tab-options clearfix">
							<?php
							foreach ( $style_options as $option_args ) {
								$this->custom_meta_field( $option_args, $value );
							}
							?>
							</div>
						</div>
						<div id="tab-conditions" class="mtsnb-tabs-content <?php $this->active_class( $active_tab, 'conditions', false ); ?>">
							<div class="mtsnb-tab-desc"><?php esc_html_e( 'Choose when and where to display the notification bar.', 'wp-notification-bars' ); ?></div>
							<div id="conditions-selector-wrap" class="clearfix">
								<div id="conditions-selector">
									<ul>
										<?php $condition_location_state = isset( $value['conditions'] ) && isset( $value['conditions']['location'] ) && ( isset( $value['conditions']['location']['state'] ) && ! empty( $value['conditions']['location']['state'] ) ) ? $value['conditions']['location']['state'] : ''; ?>
										<?php $condition_notlocation_state = isset( $value['conditions'] ) && isset( $value['conditions']['notlocation'] ) && ( isset( $value['conditions']['notlocation']['state'] ) && ! empty( $value['conditions']['notlocation']['state'] ) ) ? $value['conditions']['notlocation']['state'] : ''; ?>
										<?php $condition_location_disabled = empty( $condition_notlocation_state ) ? '' : ' disabled'; ?>
										<?php $condition_notlocation_disabled = empty( $condition_location_state ) ? '' : ' disabled'; ?>
										<li id="condition-location" data-disable="notlocation" class="condition-checkbox <?php echo esc_attr( $condition_location_state . $condition_location_disabled ); ?>">
											<?php esc_html_e( 'On specific locations', 'wp-notification-bars' ); ?>
											<div class="mtsnb-check"></div>
											<input type="hidden" class="mtsnb-condition-checkbox-input" id="mtsnb_fields_conditions_location_state" name="mtsnb_fields[conditions][location][state]" value="<?php echo esc_attr( $condition_location_state ); ?>">
										</li>
										<li id="condition-notlocation" data-disable="location" class="condition-checkbox <?php echo esc_attr( $condition_notlocation_state . $condition_notlocation_disabled ); ?>">
											<?php esc_html_e( 'Not on specific locations', 'wp-notification-bars' ); ?>
											<div class="mtsnb-check"></div>
											<input type="hidden" class="mtsnb-condition-checkbox-input" id="mtsnb_fields_conditions_notlocation_state" name="mtsnb_fields[conditions][notlocation][state]" value="<?php echo esc_attr( $condition_notlocation_state ); ?>">
										</li>
										<?php $condition_google_state = isset( $value['conditions'] ) && isset( $value['conditions']['google'] ) && ( isset( $value['conditions']['google']['state'] ) && ! empty( $value['conditions']['google']['state'] ) ) ? $value['conditions']['google']['state'] : ''; ?>
										<?php $condition_notgoogle_state = isset( $value['conditions'] ) && isset( $value['conditions']['notgoogle'] ) && ( isset( $value['conditions']['notgoogle']['state'] ) && ! empty( $value['conditions']['notgoogle']['state'] ) ) ? $value['conditions']['notgoogle']['state'] : ''; ?>
										<?php $condition_google_disabled = empty( $condition_notgoogle_state ) ? '' : ' disabled'; ?>
										<?php $condition_notgoogle_disabled = empty( $condition_google_state ) ? '' : ' disabled'; ?>
										<li id="condition-google" data-disable="notgoogle" class="condition-checkbox <?php echo esc_attr( $condition_google_state . $condition_google_disabled ); ?>">
											<?php esc_html_e( 'From Google', 'wp-notification-bars' ); ?>
											<div class="mtsnb-check"></div>
											<input type="hidden" class="mtsnb-condition-checkbox-input" id="mtsnb_fields_conditions_google_state" name="mtsnb_fields[conditions][google][state]" value="<?php echo esc_attr( $condition_google_state ); ?>">
										</li>
										<li id="condition-notgoogle" data-disable="google" class="condition-checkbox <?php echo esc_attr( $condition_notgoogle_state . $condition_notgoogle_disabled ); ?>">
											<?php esc_html_e( 'Not from Google', 'wp-notification-bars' ); ?>
											<div class="mtsnb-check"></div>
											<input type="hidden" class="mtsnb-condition-checkbox-input" id="mtsnb_fields_conditions_notgoogle_state" name="mtsnb_fields[conditions][notgoogle][state]" value="<?php echo esc_attr( $condition_notgoogle_state ); ?>">
										</li>
										<?php $condition_facebook_state = isset( $value['conditions'] ) && isset( $value['conditions']['facebook'] ) && ( isset( $value['conditions']['facebook']['state'] ) && ! empty( $value['conditions']['facebook']['state'] ) ) ? $value['conditions']['facebook']['state'] : ''; ?>
										<?php $condition_notfacebook_state = isset( $value['conditions'] ) && isset( $value['conditions']['notfacebook'] ) && ( isset( $value['conditions']['notfacebook']['state'] ) && ! empty( $value['conditions']['notfacebook']['state'] ) ) ? $value['conditions']['notfacebook']['state'] : ''; ?>
										<?php $condition_facebook_disabled = empty( $condition_notfacebook_state ) ? '' : ' disabled'; ?>
										<?php $condition_notfacebook_disabled = empty( $condition_facebook_state ) ? '' : ' disabled'; ?>
										<li id="condition-facebook" data-disable="notfacebook" class="condition-checkbox <?php echo esc_attr( $condition_facebook_state . $condition_facebook_disabled ); ?>">
											<?php esc_html_e( 'From Facebook', 'wp-notification-bars' ); ?>
											<div class="mtsnb-check"></div>
											<input type="hidden" class="mtsnb-condition-checkbox-input" id="mtsnb_fields_conditions_facebook_state" name="mtsnb_fields[conditions][facebook][state]" value="<?php echo esc_attr( $condition_facebook_state ); ?>">
										</li>
										<li id="condition-notfacebook" data-disable="facebook" class="condition-checkbox <?php echo esc_attr( $condition_notfacebook_state . $condition_notfacebook_disabled ); ?>">
											<?php esc_html_e( 'Not from Facebook', 'wp-notification-bars' ); ?>
											<div class="mtsnb-check"></div>
											<input type="hidden" class="mtsnb-condition-checkbox-input" id="mtsnb_fields_conditions_notfacebook_state" name="mtsnb_fields[conditions][notfacebook][state]" value="<?php echo esc_attr( $condition_notfacebook_state ); ?>">
										</li>
									</ul>
								</div>
								<div id="conditions-panels">
									<div id="condition-location-panel" class="mtsnb-conditions-panel <?php echo esc_attr( $condition_location_state ); ?>">
										<div class="mtsnb-conditions-panel-title"><?php esc_html_e( 'On specific locations', 'wp-notification-bars' ); ?></div>
										<div class="mtsnb-conditions-panel-content">
											<div class="mtsnb-conditions-panel-desc"><?php esc_html_e( 'Show Notification Bar on the following locations', 'wp-notification-bars' ); ?></div>
											<div class="mtsnb-conditions-panel-opt">
												<?php $location_home = isset( $value['conditions'] ) && isset( $value['conditions']['location'] ) && ( isset( $value['conditions']['location']['home'] ) && ! empty( $value['conditions']['location']['home'] ) ) ? $value['conditions']['location']['home'] : '0'; ?>
												<?php $location_blog_home = isset( $value['conditions'] ) && isset( $value['conditions']['location'] ) && ( isset( $value['conditions']['location']['blog_home'] ) && ! empty( $value['conditions']['location']['blog_home'] ) ) ? $value['conditions']['location']['blog_home'] : '0'; ?>
												<?php $location_pages = isset( $value['conditions'] ) && isset( $value['conditions']['location'] ) && ( isset( $value['conditions']['location']['pages'] ) && ! empty( $value['conditions']['location']['pages'] ) ) ? $value['conditions']['location']['pages'] : '0'; ?>
												<?php $location_posts = isset( $value['conditions'] ) && isset( $value['conditions']['location'] ) && ( isset( $value['conditions']['location']['posts'] ) && ! empty( $value['conditions']['location']['posts'] ) ) ? $value['conditions']['location']['posts'] : '0'; ?>
												<p>
													<label>
														<input type="checkbox" class="mtsnb-checkbox" name="mtsnb_fields[conditions][location][home]" id="mtsnb_fields_conditions_location_home" value="1" <?php checked( $location_home, '1', true ); ?> />
														<?php esc_html_e( 'Homepage.', 'wp-notification-bars' ); ?>
													</label>
												</p>
												<?php if ( 'page' === get_option( 'show_on_front' ) && '0' !== get_option( 'page_for_posts' ) && '0' !== get_option( 'page_on_front' ) ) { ?>
													<p>
														<label>
															<input type="checkbox" class="mtsnb-checkbox" name="mtsnb_fields[conditions][location][blog_home]" id="mtsnb_fields_conditions_location_blog_home" value="1" <?php checked( $location_blog_home, '1', true ); ?> />
															<?php esc_html_e( 'Blog Homepage.', 'wp-notification-bars' ); ?>
														</label>
													</p>
												<?php } ?>
												<p>
													<label>
														<input type="checkbox" class="mtsnb-checkbox" name="mtsnb_fields[conditions][location][pages]" id="mtsnb_fields_conditions_location_pages" value="1" <?php checked( $location_pages, '1', true ); ?> />
														<?php esc_html_e( 'Pages.', 'wp-notification-bars' ); ?>
													</label>
												</p>
												<p>
													<label>
														<input type="checkbox" class="mtsnb-checkbox" name="mtsnb_fields[conditions][location][posts]" id="mtsnb_fields_conditions_location_posts" value="1" <?php checked( $location_posts, '1', true ); ?> />
														<?php esc_html_e( 'Posts.', 'wp-notification-bars' ); ?>
													</label>
												</p>
											</div>
										</div>
									</div>
									<div id="condition-notlocation-panel" class="mtsnb-conditions-panel <?php echo esc_attr( $condition_notlocation_state ); ?>">
										<div class="mtsnb-conditions-panel-title"><?php esc_html_e( 'Not on specific locations', 'wp-notification-bars' ); ?></div>
										<div class="mtsnb-conditions-panel-content">
											<div class="mtsnb-conditions-panel-desc"><?php esc_html_e( 'Hide Notification Bar on the following locations', 'wp-notification-bars' ); ?></div>
											<div class="mtsnb-conditions-panel-opt">
												<?php $notlocation_home = isset( $value['conditions'] ) && isset( $value['conditions']['notlocation'] ) && ( isset( $value['conditions']['notlocation']['home'] ) && ! empty( $value['conditions']['notlocation']['home'] ) ) ? $value['conditions']['notlocation']['home'] : '0'; ?>
												<?php $notlocation_blog_home = isset( $value['conditions'] ) && isset( $value['conditions']['notlocation'] ) && ( isset( $value['conditions']['notlocation']['blog_home'] ) && ! empty( $value['conditions']['notlocation']['blog_home'] ) ) ? $value['conditions']['notlocation']['blog_home'] : '0'; ?>
												<?php $notlocation_pages = isset( $value['conditions'] ) && isset( $value['conditions']['notlocation'] ) && ( isset( $value['conditions']['notlocation']['pages'] ) && ! empty( $value['conditions']['notlocation']['pages'] ) ) ? $value['conditions']['notlocation']['pages'] : '0'; ?>
												<?php $notlocation_posts = isset( $value['conditions'] ) && isset( $value['conditions']['notlocation'] ) && ( isset( $value['conditions']['notlocation']['posts'] ) && ! empty( $value['conditions']['notlocation']['posts'] ) ) ? $value['conditions']['notlocation']['posts'] : '0'; ?>
												<p>
													<label>
														<input type="checkbox" class="mtsnb-checkbox" name="mtsnb_fields[conditions][notlocation][home]" id="mtsnb_fields_conditions_notlocation_home" value="1" <?php checked( $notlocation_home, '1', true ); ?> />
														<?php esc_html_e( 'Homepage.', 'wp-notification-bars' ); ?>
													</label>
												</p>
												<?php if ( 'page' === get_option( 'show_on_front' ) && '0' !== get_option( 'page_for_posts' ) && '0' !== get_option( 'page_on_front' ) ) { ?>
													<p>
														<label>
															<input type="checkbox" class="mtsnb-checkbox" name="mtsnb_fields[conditions][notlocation][blog_home]" id="mtsnb_fields_conditions_notlocation_blog_home" value="1" <?php checked( $notlocation_blog_home, '1', true ); ?> />
															<?php esc_html_e( 'Blog Homepage.', 'wp-notification-bars' ); ?>
														</label>
													</p>
												<?php } ?>
												<p>
													<label>
														<input type="checkbox" class="mtsnb-checkbox" name="mtsnb_fields[conditions][notlocation][pages]" id="mtsnb_fields_conditions_notlocation_pages" value="1" <?php checked( $notlocation_pages, '1', true ); ?> />
														<?php esc_html_e( 'Pages.', 'wp-notification-bars' ); ?>
													</label>
												</p>
												<p>
													<label>
														<input type="checkbox" class="mtsnb-checkbox" name="mtsnb_fields[conditions][notlocation][posts]" id="mtsnb_fields_conditions_notlocation_posts" value="1" <?php checked( $notlocation_posts, '1', true ); ?> />
														<?php esc_html_e( 'Posts.', 'wp-notification-bars' ); ?>
													</label>
												</p>
											</div>
										</div>
									</div>
									<div id="condition-google-panel" class="mtsnb-conditions-panel <?php echo esc_attr( $condition_google_state ); ?>">
										<div class="mtsnb-conditions-panel-title"><?php esc_html_e( 'From Google', 'wp-notification-bars' ); ?></div>
										<div class="mtsnb-conditions-panel-content">
											<div class="mtsnb-conditions-panel-desc">
												<p>
													<label>
														<?php esc_html_e( 'Show Notification Bar if visitor arrived via Google search engine.', 'wp-notification-bars' ); ?>
													</label>
												</p>
											</div>
										</div>
									</div>
									<div id="condition-notgoogle-panel" class="mtsnb-conditions-panel <?php echo esc_attr( $condition_notgoogle_state ); ?>">
										<div class="mtsnb-conditions-panel-title"><?php esc_html_e( 'Not from Google', 'wp-notification-bars' ); ?></div>
										<div class="mtsnb-conditions-panel-content">
											<div class="mtsnb-conditions-panel-desc">
												<p>
													<label>
														<?php esc_html_e( 'Hide Notification Bar if visitor arrived via Google search engine.', 'wp-notification-bars' ); ?>
													</label>
												</p>
											</div>
										</div>
									</div>
									<div id="condition-facebook-panel" class="mtsnb-conditions-panel <?php echo esc_attr( $condition_facebook_state ); ?>">
										<div class="mtsnb-conditions-panel-title"><?php esc_html_e( 'From Facebook', 'wp-notification-bars' ); ?></div>
										<div class="mtsnb-conditions-panel-content">
											<div class="mtsnb-conditions-panel-desc">
												<p>
													<label>
														<?php esc_html_e( 'Show Notification Bar if visitor arrived from Facebook.', 'wp-notification-bars' ); ?>
													</label>
												</p>
											</div>
										</div>
									</div>
									<div id="condition-notfacebook-panel" class="mtsnb-conditions-panel <?php echo esc_attr( $condition_notfacebook_state ); ?>">
										<div class="mtsnb-conditions-panel-title"><?php esc_html_e( 'Not from Facebook', 'wp-notification-bars' ); ?></div>
										<div class="mtsnb-conditions-panel-content">
											<div class="mtsnb-conditions-panel-desc">
												<p>
													<label>
														<?php esc_html_e( 'Hide Notification Bar if visitor arrived from Facebook.', 'wp-notification-bars' ); ?>
													</label>
												</p>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
			<?php
		}

		/**
		 * Echo class="active" if the condition is met.
		 *
		 * @param mixed   $variable      Variable.
		 * @param mixed   $match         Matching value.
		 * @param boolean $add_attribute Add class attribute name too, or just its value.
		 * @return void
		 */
		private function active_class( $variable, $match, $add_attribute = false ) {
			$class = '';
			if ( $variable === $match ) {
				$class = ' active';
			}
			if ( $add_attribute ) {
				$class = ' class="' . $class . '"';
			}

			echo wp_kses_post( $class );
		}

		/**
		 * Helper function for common fields
		 *
		 * @since    1.0
		 */
		public function custom_meta_field( $args, $value, $b = false ) {

			$type    = isset( $args['type'] ) ? $args['type'] : '';
			$name    = isset( $args['name'] ) ? $args['name'] : '';
			$name    = $b ? 'b_' . $name : $name;
			$label   = isset( $args['label'] ) ? $args['label'] : '';
			$options = isset( $args['options'] ) ? $args['options'] : array();
			$default = isset( $args['default'] ) ? $args['default'] : '';
			$min     = isset( $args['min'] ) ? $args['min'] : '0';

			$class = isset( $args['class'] ) ? $args['class'] : '';

			// Option value
			$opt_val = isset( $value[ $name ] ) ? $value[ $name ] : $default;

			?>
			<div id="mtsnb_fields_<?php echo esc_attr( $name ); ?>_row" class="form-row">
				<label class="form-label" for="mtsnb_fields_<?php echo esc_attr( $name ); ?>"><?php echo esc_html( $label ); ?></label>
				<div class="form-option <?php echo esc_attr( $class ); ?>">
				<?php
				switch ( $type ) {

					case 'text':
						?>
						<input type="text" name="mtsnb_fields[<?php echo esc_attr( $name ); ?>]" id="mtsnb_fields_<?php echo esc_attr( $name ); ?>" value="<?php echo esc_attr( $opt_val ); ?>" />
						<?php
						break;
					case 'select':
						?>
						<select name="mtsnb_fields[<?php echo esc_attr( $name ); ?>]" id="mtsnb_fields_<?php echo esc_attr( $name ); ?>">
						<?php foreach ( $options as $val => $label ) { ?>
							<option value="<?php echo esc_attr( $val ); ?>" <?php selected( $opt_val, $val, true ); ?>><?php echo esc_html( $label ); ?></option>
						<?php } ?>
						</select>
						<?php
						break;
					case 'number':
						?>
						<input type="number" step="1" min="<?php echo (int) $min; ?>" name="mtsnb_fields[<?php echo esc_attr( $name ); ?>]" id="mtsnb_fields_<?php echo esc_attr( $name ); ?>" value="<?php echo esc_attr( $opt_val ); ?>" class="small-text"/>
						<?php
						break;
					case 'color':
						?>
						<input type="text" name="mtsnb_fields[<?php echo esc_attr( $name ); ?>]" id="mtsnb_fields_<?php echo esc_attr( $name ); ?>" value="<?php echo esc_attr( $opt_val ); ?>" class="mtsnb-color-picker" />
						<?php
						break;
					case 'textarea':
						?>
						<textarea name="mtsnb_fields[<?php echo esc_attr( $name ); ?>]" id="mtsnb_fields_<?php echo esc_attr( $name ); ?>" class="mtsnb-textarea"><?php echo esc_textarea( $opt_val ); ?></textarea>
						<?php
						break;
					case 'checkbox':
						?>
						<input type="checkbox" name="mtsnb_fields[<?php echo esc_attr( $name ); ?>]" id="mtsnb_fields_<?php echo esc_attr( $name ); ?>" value="1" <?php checked( $opt_val, '1', true ); ?> />
						<?php
						break;
					case 'info':
						?>
						<small class="mtsnb-option-info">
							<?php echo wp_kses_post( $default ); ?>
						</small>
						<?php
						break;
				}
				?>
				</div>
			</div>
			<?php
		}

		/**
		 * Save the Data
		 *
		 * @since    1.0
		 */
		public function save_custom_meta( $post_id ) {
			// Check if our nonce is set.
			if ( ! isset( $_POST['mtsnb_meta_box_nonce'] ) ) {
				return;
			}
			// Verify that the nonce is valid.
			if ( ! wp_verify_nonce( $_POST['mtsnb_meta_box_nonce'], 'mtsnb_meta_box' ) ) {
				return;
			}
			// If this is an autosave, our form has not been submitted, so we don't want to do anything.
			if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
				return;
			}
			// Check the user's permissions.
			if ( isset( $_POST['post_type'] ) && 'mts_notification_bar' === $_POST['post_type'] ) {

				if ( ! current_user_can( 'edit_page', $post_id ) ) {
					return;
				}
			} else {

				if ( ! current_user_can( 'edit_post', $post_id ) ) {
					return;
				}
			}

			/* OK, it's safe for us to save the data now. */
			if ( ! isset( $_POST['mtsnb_fields'] ) ) {
				return;
			}

			// Sanitize fields.
			$my_data = MTSNBF_Shared::sanitize_data( $_POST['mtsnb_fields'] );

			// Update the meta field in the database.
			update_post_meta( $post_id, '_mtsnb_data', $my_data );
		}

		/**
		 * Deactivate plugin if pro is active.
		 *
		 * @since    1.0
		 */
		public function check_version() {

			if ( defined( 'MTSNB_PLUGIN_BASE' ) ) {

				if ( is_plugin_active( MTSNB_PLUGIN_BASE ) || class_exists( 'MTSNB' ) ) {

					deactivate_plugins( MTSNBF_PLUGIN_BASE );
					add_action( 'admin_notices', array( $this, 'disabled_notice' ) );
					if ( isset( $_GET['activate'] ) ) {
						unset( $_GET['activate'] );
					}
				}
			}
		}

		/**
		 * Show admin notice.
		 *
		 * @return void
		 */
		public function wp_notification_bar_admin_notice() {
			global $current_user;
			$user_id = $current_user->ID;
			/*
			 Check that the user hasn't already clicked to ignore the message */
			/* Only show the notice 2 days after plugin activation */
			if ( ! get_user_meta( $user_id, 'wp_notification_bar_ignore_notice' ) && time() >= ( get_option( 'wp_notification_bar_activated', 0 ) + ( 2 * 24 * 60 * 60 ) ) ) {
				echo '<div class="updated notice-info wp-notification-bar-notice" id="wpnotificationbar-notice" style="position:relative;">';
				echo '<p>';
				// Translators: placeholder is a link to the plugin home page, with "WP Notification Bar Pro" as the anchor text.
				printf( esc_html__( 'Like WP Notification Bar plugin? You will LOVE %s!', 'wp-notification-bars' ), '<a target="_blank" href="https://mythemeshop.com/plugins/wp-notification-bar/?utm_source=WP+Notification+Bars&utm_medium=Notification+Link&utm_content=WP+Notification+Bar+Pro+LP&utm_campaign=WordPressOrg"><strong>WP Notification Bar Pro</strong></a>' );
				echo '</p>';
				echo '<a class="notice-dismiss mtsnb-notice-dismiss" data-ignore="0" data-nonce="' . esc_attr( wp_create_nonce( 'mtsnb_dismiss_notice' ) ) . '" href="#"></a>';
				echo '</div>';
			}
			/*
			 Other notice appears right after activating */
			/* And it gets hidden after showing 3 times */
			if ( ! get_user_meta( $user_id, 'wp_notification_bar_ignore_notice_2' ) && get_option( 'wp_notification_bar_notice_views', 0 ) < 3 && get_option( 'wp_notification_bar_activated', 0 ) ) {
				$views = get_option( 'wp_notification_bar_notice_views', 0 );
				update_option( 'wp_notification_bar_notice_views', ( $views + 1 ) );
				echo '<div class="updated notice-info wp-notification-bar-notice" id="wpnotificationbar-notice2" style="position:relative;">';
				echo '<p>';
				esc_html_e( 'Thank you for trying WP Notification Bar. We hope you will like it.', 'wp-notification-bars' );
				echo '</p>';
				echo '<a class="notice-dismiss mtsnb-notice-dismiss" data-ignore="1" data-nonce="' . esc_attr( wp_create_nonce( 'mtsnb_dismiss_notice' ) ) . '" href="#"></a>';
				echo '</div>';
			}
		}

		/**
		 * Ajax handler to ignore an admin notice.
		 *
		 * @return void
		 */
		public function wp_notification_bar_admin_notice_ignore() {
			global $current_user;
			$user_id = $current_user->ID;
			/* If user clicks to ignore the notice, add that to their user meta */
			if ( isset( $_POST['dismiss'] ) ) {
				// Check if our nonce is set.
				if ( ! isset( $_POST['mtsnb_notice_nonce'] ) ) {
					return;
				}
				// Verify that the nonce is valid.
				if ( ! wp_verify_nonce( $_POST['mtsnb_notice_nonce'], 'mtsnb_dismiss_notice' ) ) {
					return;
				}

				if ( '0' == $_POST['dismiss'] ) {
					add_user_meta( $user_id, 'wp_notification_bar_ignore_notice', '1', true );
				} elseif ( '1' == $_POST['dismiss'] ) {
					add_user_meta( $user_id, 'wp_notification_bar_ignore_notice_2', '1', true );
				}
			}
		}

		/**
		 * Deactivation notice.
		 *
		 * @since    1.0
		 */
		public function disabled_notice() {
			?>
			<div class="updated">
				<p><?php esc_html_e( 'Free version of WP Notification Bars plugin disabled. Pro version is active!', 'wp-notification-bars' ); ?></p>
			</div>
			<?php
		}

		/**
		 * Notification Bar update messages.
		 *
		 * @since    1.0
		 *
		 * @param array $messages
		 * @return array   $messages
		 */
		public function mtsnb_update_messages( $messages ) {

			global $post;

			$post_ID   = $post->ID;
			$post_type = get_post_type( $post_ID );

			if ( 'mts_notification_bar' === $post_type ) {

				$messages['mts_notification_bar'] = array(
					0  => '', // Unused. Messages start at index 1.
					1  => __( 'Notification Bar updated.', 'wp-notification-bars' ),
					2  => __( 'Custom field updated.', 'wp-notification-bars' ),
					3  => __( 'Custom field deleted.', 'wp-notification-bars' ),
					4  => __( 'Notification Bar updated.', 'wp-notification-bars' ),
					// Translators: %s: date and time of the revision.
					5  => isset( $_GET['revision'] ) ? sprintf( __( 'Notification Bar restored to revision from %s' ), wp_post_revision_title( (int) $_GET['revision'], false ) ) : false, // phpcs:ignore WordPress.Security.NonceVerification
					6  => __( 'Notification Bar published.', 'wp-notification-bars' ),
					7  => __( 'Notification Bar saved.', 'wp-notification-bars' ),
					8  => __( 'Notification Bar submitted.', 'wp-notification-bars' ),
					// Translators: %s: date and time of the scheduled publication.
					9  => sprintf( esc_html__( 'Notification Bar  scheduled for: %1$s.', 'wp-notification-bars' ), '<strong>' . date_i18n( __( 'M j, Y @ H:i' ), strtotime( $post->post_date ) ) . '</strong>' ),
					10 => __( 'Notification Bar draft updated.', 'wp-notification-bars' ),
				);
			}

			return $messages;
		}

		/**
		 * Single post view bar select.
		 *
		 * @since    1.0.1
		 */
		public function mtsnb_select_metabox_insert() {

			$force_bar_post_types = $this->force_bar_post_types;

			if ( $force_bar_post_types && is_array( $force_bar_post_types ) ) {

				foreach ( $force_bar_post_types as $screen ) {

					add_meta_box(
						'mtsnb_single_bar_metabox',
						__( 'Notification Bar', 'wp-notification-bars' ),
						array( $this, 'mtsnb_select_metabox_content' ),
						$screen,
						'side',
						'default'
					);
				}
			}
		}

		/**
		 * Post Meta box contents: select notification bar.
		 *
		 * @param WP_Post $post Post object.
		 */
		public function mtsnb_select_metabox_content( $post ) {

			// Add a nonce field so we can check for it later.
			wp_nonce_field( 'mtsnb_select_metabox_save', 'mtsnb_select_metabox_nonce' );

			/*
			* Use get_post_meta() to retrieve an existing value
			* from the database and use the value for the form.
			*/
			$bar = get_post_meta( $post->ID, '_mtsnb_override_bar', true );

			$processed_item_ids = '';
			if ( ! empty( $bar ) ) {
				// Some entries may be arrays themselves!
				$processed_item_ids = array();
				foreach ( $bar as $this_id ) {
					if ( is_array( $this_id ) ) {
						$processed_item_ids = array_merge( $processed_item_ids, $this_id );
					} else {
						$processed_item_ids[] = $this_id;
					}
				}

				if ( is_array( $processed_item_ids ) && ! empty( $processed_item_ids ) ) {
					$processed_item_ids = implode( ',', $processed_item_ids );
				} else {
					$processed_item_ids = '';
				}
			}
			?>
			<p>
				<label for="mtsnb_override_bar_field"><?php esc_html_e( 'Select Notification Bar (optional):', 'wp-notification-bars' ); ?></label><br />
				<input style="width: 400px;" type="hidden" id="mtsnb_override_bar_field" name="mtsnb_override_bar_field" class="mtsnb-bar-select"  value="<?php echo esc_attr( $processed_item_ids ); ?>" />
			</p>
			<p>
				<i><?php esc_html_e( 'Selected notification bar will override any other bar.', 'wp-notification-bars' ); ?></i>
			</p>
			<?php
		}

		/**
		 * Save meta box data.
		 *
		 * @param int $post_id Post ID.
		 */
		public function mtsnb_select_metabox_save( $post_id ) {

			// Check if our nonce is set.
			if ( ! isset( $_POST['mtsnb_select_metabox_nonce'] ) ) {
				return;
			}
			// Verify that the nonce is valid.
			if ( ! wp_verify_nonce( $_POST['mtsnb_select_metabox_nonce'], 'mtsnb_select_metabox_save' ) ) {
				return;
			}
			// If this is an autosave, our form has not been submitted, so we don't want to do anything.
			if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
				return;
			}

			// Check the user's permissions.
			if ( 'page' === $_POST['post_type'] ) {

				if ( ! current_user_can( 'edit_page', $post_id ) ) {
					return;
				}
			} else {

				if ( ! current_user_can( 'edit_post', $post_id ) ) {
					return;
				}
			}

			/* OK, its safe for us to save the data now. */
			if ( ! isset( $_POST['mtsnb_override_bar_field'] ) ) {
				return;
			}

			$val = sanitize_text_field( $_POST['mtsnb_override_bar_field'] );

			if ( strpos( $val, ',' ) === false ) {
				// No comma, must be single value - still needs to be in an array for now
				$post_ids = array( $val );
			} else {
				// There is a comma so it's explodable
				$post_ids = explode( ',', $val );
			}

			$post_ids = array_map( 'absint', $post_ids );

			// Update the meta field in the database.
			update_post_meta( $post_id, '_mtsnb_override_bar', $post_ids );
		}

		/**
		 * Bar select ajax function.
		 *
		 * @since    1.0.1
		 */
		public function mtsnb_get_bars() {

			$result = array();

			$search = sanitize_text_field( $_REQUEST['q'] ); // phpcs:ignore WordPress.Security.NonceVerification

			$ads_query = array(
				'posts_per_page'   => -1,
				'post_status'      => array( 'publish' ),
				'post_type'        => 'mts_notification_bar',
				'order'            => 'ASC',
				'orderby'          => 'title',
				'suppress_filters' => false,
				's'                => $search,
			);
			$posts     = get_posts( $ads_query );

			// We'll return a JSON-encoded result.
			foreach ( $posts as $this_post ) {
				$post_title = $this_post->post_title;
				$id         = $this_post->ID;

				$result[] = array(
					'id'    => $id,
					'title' => $post_title,
				);
			}

			echo wp_json_encode( $result );

			die();
		}

		/**
		 * Bar titles ajax function.
		 *
		 * @since    1.0.1
		 */
		public function mtsnb_get_bar_titles() {
			if ( ! current_user_can( 'edit_posts' ) ) {
				die( '0' );
			}

			$result = array();

			if ( isset( $_REQUEST['post_ids'] ) ) { // phpcs:ignore WordPress.Security.NonceVerification
				$post_ids = sanitize_text_field( $_REQUEST['post_ids'] ); // phpcs:ignore WordPress.Security.NonceVerification
				if ( strpos( $post_ids, ',' ) === false ) {
					// There is no comma, so we can't explode, but we still want an array
					$post_ids = array( $post_ids );
				} else {
					// There is a comma, so it must be explodable
					$post_ids = explode( ',', $post_ids );
				}
			} else {
				$post_ids = array();
			}

			$post_ids = array_map( 'absint', $post_ids );

			if ( is_array( $post_ids ) && ! empty( $post_ids ) ) {

				$posts = get_posts(
					array(
						'posts_per_page' => -1,
						'post_status'    => array( 'publish' ),
						'post__in'       => $post_ids,
						'post_type'      => 'mts_notification_bar',
					)
				);
				foreach ( $posts as $this_post ) {
					$result[] = array(
						'id'    => $this_post->ID,
						'title' => $this_post->post_title,
					);
				}
			}

			echo wp_json_encode( $result );

			die();
		}
	}
}
