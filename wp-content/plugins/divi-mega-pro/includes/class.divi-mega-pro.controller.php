<?php

	class DiviMegaPro_Controller extends DiviMegaPro {
		
		protected static $_show_errors = FALSE;
		
		/**
		 * @var \WP_Filesystem_Base|null
		 */
		public static $wpfs;
		
		/**
		 * @var ET_Core_Data_Utils
		 */
		public static $data_utils;
		
		private static $slug = 'DiviMegaPro-divi-custom-styles';
		
		private static $post_id;
		
		private static $current_post_id;
		
		private static $filename;
		
		private static $file_extension;
		
		private static $cache_dir;
		
		private static $module_index = - 1;
		
		public function __construct() {
			
		}
		
		
		public static function addDiviMegaPro( $the_content, $render = true ) {
			
			if ( !class_exists( 'DiviExtension' ) ) {
				
				return;
			}
			
			// Settings
			self::$helper = new DiviMegaPro_Helper;
			
			$divimegapros_in_current = array();
			
			try {
				
					
				// Singleton feature
				$divimegapro_singleton = get_option( 'divimegapro_singleton' );
				
				$header = 'false';
				$content = 'false';
				$footer = 'false';
				
				if ( isset( $divimegapro_singleton[0] ) ) {
					
					$header = in_array( 'header', $divimegapro_singleton ) ? 'true' : 'false';
					$content = in_array( 'content', $divimegapro_singleton ) ? 'true' : 'false';
					$footer = in_array( 'footer', $divimegapro_singleton ) ? 'true' : 'false';
				}
				?>
				<script>
				var divimegapro_singleton = [];
				divimegapro_singleton['header'] = <?php print et_core_intentionally_unescaped( $header, 'fixed_string' ) ?>;
				divimegapro_singleton['content'] = <?php print et_core_intentionally_unescaped( $content, 'fixed_string' ) ?>;
				divimegapro_singleton['footer'] = <?php print et_core_intentionally_unescaped( $footer, 'fixed_string' ) ?>;
				var divimegapro_singleton_enabled = ( divimegapro_singleton['header'] || divimegapro_singleton['content'] || divimegapro_singleton['footer'] ) ? true : false;
				</script>
				
				<?php
				
				// phpcs:ignore WordPress.Security.NonceVerification.Recommended
				if ( isset( $_GET['et_fb'] ) && $render ) {
					
					// phpcs:ignore WordPress.Security.NonceVerification.Recommended
					$divi_builder_enabled = sanitize_text_field( wp_unslash( $_GET['et_fb'] ) );
					
					// is divi theme builder ?
					if ( $divi_builder_enabled === '1' ) {
						
						return;
					}
				}
				
				if ( $render ) {
					
					$the_content = $the_content . '<div class="divimegapro-wrapper">';
				
				}
				
				
				/* Search CSS Triggers in all Divi divimegapros */
				if ( isset( DiviMegaPro::$divimegaproList['css_trigger'] ) ) {
					
					$posts = DiviMegaPro::$divimegaproList['css_trigger'];
				}
				else {
					
					$posts = '';
				}
				
				if ( !empty( $posts ) && $render ) {
						
					print '<script>var divimegapros_with_css_trigger = {';
					
					foreach( $posts as $post_id => $css_selector ) {
						
						print '\'' . et_core_esc_previously( $post_id ) . '\': \'' . et_core_esc_previously( $css_selector ) . '\',';
					}
					
					print '};</script>';
				}
				
				
				/* Search Divi divimegapros with Custom Close Buttons */
				if ( $render ) {
					
					$posts = DiviMegaPro_Model::getDiviMegaPros('customizeclosebtn');
					
					if ( isset( $posts[0] ) ) {
						
						print '<style type="text/css">';
						
						foreach( $posts as $dmm_post ) {
							
							$post_id = $dmm_post->ID;
							
							$cbc_textcolor = get_post_meta( $post_id, 'dmp_closebtn_text_color', true );
							$cbc_bgcolor = get_post_meta( $post_id, 'dmp_closebtn_bg_color', true );
							$cbc_fontsize = get_post_meta( $post_id, 'dmp_closebtn_fontsize', true );
							$cbc_borderradius = get_post_meta( $post_id, 'dmp_closebtn_borderradius', true );
							$cbc_padding = get_post_meta( $post_id, 'dmp_closebtn_padding', true );
							
							$customizeclosebtn = get_post_meta( $post_id, 'dmp_customizeclosebtn' );
							if ( isset( $customizeclosebtn[0] ) ) {
								
								$customizeclosebtn = $customizeclosebtn[0];
								
							} else {
								
								continue;
							}
							
							if ( $customizeclosebtn ) {
								
								print '
								.divimegapro-customclose-btn-' . et_core_esc_previously( $post_id ) . ' {
									top:5px !important;
									color:' . esc_attr( $cbc_textcolor ) . ' !important;
									background-color:' . esc_attr( $cbc_bgcolor ) . ' !important;
									font-size:' . esc_attr( $cbc_fontsize ) . 'px !important;
									padding:' . esc_attr( $cbc_padding ) . 'px !important;
									-moz-border-radius:' . esc_attr( $cbc_borderradius ) . '% !important;
									-webkit-border-radius:' . esc_attr( $cbc_borderradius ) . '% !important;
									-khtml-border-radius:' . esc_attr( $cbc_borderradius ) . '% !important;
									border-radius:' . esc_attr( $cbc_borderradius ) . '% !important;
								}
								';
							}
						}
						
						print '</style>';
					}
				}
				
				
				/* Search Divi divimegapros with Arrow Features */
				if ( $render ) {
					
					$posts = DiviMegaPro_Model::getDiviMegaPros('enable_arrow');
					
					if ( isset( $posts[0] ) ) {
						
						print '<style type="text/css">';
						
						foreach( $posts as $dmm_post ) {
							
							$post_id = $dmm_post->ID;
							
							$dmp_arrowfeature_color = esc_attr( get_post_meta( $post_id, 'dmp_arrowfeature_color', true ) );
							
							$dmp_enable_arrow = get_post_meta( $post_id, 'dmp_enable_arrow' );
							if ( isset( $dmp_enable_arrow[0] ) ) {
								
								$dmp_enable_arrow = $dmp_enable_arrow[0];
								
							} else {
								
								continue;
							}
							
							if ( $dmp_enable_arrow !== 0 ) {
								
								$dmp_arrow_width = esc_attr( get_post_meta( $post_id, 'dmp_arrowfeature_width', true ) );
								if ( !isset( $dmp_arrow_width ) ) {
									
									$dmp_arrow_width = 0;
								}
								
								$dmp_arrow_height = esc_attr( get_post_meta( $post_id, 'dmp_arrowfeature_height', true ) );
								if ( !isset( $dmp_arrow_height ) ) {
									
									$dmp_arrow_height = 0;
								}
								
								$dmp_arrow_width = $dmp_arrow_width * 0.1;
								$dmp_arrow_height = $dmp_arrow_height * 0.1;
								
								print et_core_esc_previously( '
								.dmp-' . $post_id . ' > .tippy-tooltip > .tippy-svg-arrow {
									fill:' . $dmp_arrowfeature_color . ' !important;
								}
								.dmp-' . $post_id . ' .tippy-arrow:before,
								.dmp-' . $post_id . ' .tippy-svg-arrow {
									-webkit-transform: scale( ' . $dmp_arrow_width . ', ' . $dmp_arrow_height . ');  /* Saf3.1+, Chrome */
									 -moz-transform: scale( ' . $dmp_arrow_width . ', ' . $dmp_arrow_height . ');  /* FF3.5+ */
									  -ms-transform: scale( ' . $dmp_arrow_width . ', ' . $dmp_arrow_height . ');  /* IE9 */
									   -o-transform: scale( ' . $dmp_arrow_width . ', ' . $dmp_arrow_height . ');  /* Opera 10.5+ */
										  transform: scale( ' . $dmp_arrow_width . ', ' . $dmp_arrow_height . ');
								}
								.tippy-popper.dmp-' . $post_id . '[x-placement^=top] > .tippy-tooltip > .tippy-arrow:before {
									border-top-color:' . $dmp_arrowfeature_color . ' !important;
								}
								.tippy-popper.dmp-' . $post_id . '[x-placement^=bottom] > .tippy-tooltip > .tippy-arrow:before {
									border-bottom-color:' . $dmp_arrowfeature_color . ' !important;
								}
								.tippy-popper.dmp-' . $post_id . '[x-placement^=left] > .tippy-tooltip > .tippy-arrow:before {
									border-left-color:' . $dmp_arrowfeature_color . ' !important;
								}
								.tippy-popper.dmp-' . $post_id . '[x-placement^=right] > .tippy-tooltip > .tippy-arrow:before {
									border-right-color:' . $dmp_arrowfeature_color . ' !important;
								}
								' );
							}
						}
						
						print '</style>';
					}
				}
				
				
				if ( isset( DiviMegaPro::$divimegaproList['ids'] ) ) {
					
					$divimegapros = DiviMegaPro::$divimegaproList['ids'];
				}
				else {
					
					$divimegapros = '';
				}
				
				if ( is_array( $divimegapros ) && count( $divimegapros ) > 0 ) {
					
					global $post;
					
					$ref_id = 0;
					
					$current_post_id = 0;
					
					if ( function_exists( 'get_queried_object_id' ) && get_queried_object_id() > 0 ) {
						
						$current_post_id = get_queried_object_id();
					
					} else {
					
						$current_home_post_id = (int) get_option( 'page_on_front' );
						
						$is_home = is_home();
						
						if ( $current_home_post_id == 0 && !$is_home ) {
							
							$current_post_id = get_the_ID();
						}
					}
					
					self::$current_post_id = $current_post_id;
					
					if ( $current_post_id > 0 ) {
						
						$ref_id = $current_post_id;
					}
					
					$post_id = $current_post_id;
					$is_preview          = is_preview() || is_et_pb_preview();
					$forced_in_footer    = $post_id && et_builder_setting_is_on( 'et_pb_css_in_footer', $post_id );
					$forced_inline       = ! $post_id || $is_preview || $forced_in_footer || et_builder_setting_is_off( 'et_pb_static_css_file', $post_id ) || et_core_is_safe_mode_active() || ET_GB_Block_Layout::is_layout_block_preview();
					
					foreach( $divimegapros as $divimegapro_id => $idx ) {
						
						if ( get_post_status ( $divimegapro_id ) == 'publish' ) {
							
							$display_in_current = false;
						
							$at_pages = get_post_meta( $divimegapro_id, 'dmp_css_selector_at_pages' );
							
							$display_on_archive = get_post_meta( $divimegapro_id, 'dmp_displaylocations_archive', true );
							
							if ( isset( $display_on_archive[0] ) ) {
								
								$display_on_archive = (int) $display_on_archive[0];
								
							} else {
								
								$display_on_archive = 1;
							}
							
							
							$display_on_author = get_post_meta( $divimegapro_id, 'dmp_displaylocations_author', true );
							
							if ( isset( $display_on_author[0] ) ) {
								
								$display_on_author = (int) $display_on_author[0];
								
							} else {
								
								$display_on_author = 1;
							}
							
							$display_in_posts = ( !isset( $at_pages[0] ) ) ? 'all' : $at_pages[0];
							
							if ( $display_in_posts == 'specific' ) {
								
								$display_in_current = false;
								
								$in_posts = get_post_meta( $divimegapro_id, 'dmp_css_selector_at_pages_selected' );
								
								if ( isset( $in_posts[0] ) && $in_posts[0] != '' ) {
								
									foreach( $in_posts[0] as $in_post => $the_id ) {
										
										if ( $the_id == $current_post_id ) {
											
											$display_in_current = true;
											
											break;
										}
									}
								}
							}
							
							if ( $display_in_posts == 'all' ) {
								
								$display_in_current = true;
								
								$except_in_posts = get_post_meta( $divimegapro_id, 'dmp_css_selector_at_pagesexception_selected' );
								
								if ( isset( $except_in_posts[0] ) && $except_in_posts[0] != '' ) {
									
									foreach( $except_in_posts[0] as $in_post => $the_id ) {
										
										if ( $the_id == $current_post_id ) {
											
											$display_in_current = false;
											
											break;
										}
									}
								}
							}
							
							if ( is_archive() && $display_on_archive ) {
								
								$display_in_current = true;
							}
							
							if ( ( is_404() || is_search() ) && $display_in_posts === 'all' ) {
								
								$display_in_current = true;
							}
							
							if ( is_author() && $display_on_author ) {
								
								$display_in_current = true;
							}
							
							if ( is_page() && $display_in_posts === 'pages' ) {
								
								$display_in_current = true;
							}
							
							if ( is_single() && $display_in_posts === 'posts' ) {
								
								$display_in_current = true;
							}
							
							if ( is_singular() && ( $display_in_posts === 'posts' || $display_in_posts === 'pages' ) ) {
								
								$display_in_current = true;
							}
							
							if ( $display_in_current ) {
								
								$disablemobile = get_post_meta( $divimegapro_id, 'dmp_mpa_disablemobile' );
								$disabletablet = get_post_meta( $divimegapro_id, 'dmp_mpa_disabletablet' );
								$disabledesktop = get_post_meta( $divimegapro_id, 'dmp_mpa_disabledesktop' );
								
								if ( isset( $disablemobile[0] ) ) {
									
									$disablemobile = $disablemobile[0];
									
								} else {
									
									$disablemobile = 0;
								}
								
								if ( isset( $disabletablet[0] ) ) {
									
									$disabletablet = $disabletablet[0];
									
								} else {
									
									$disabletablet = 0;
								}
								
								if ( isset( $disabledesktop[0] ) ) {
									
									$disabledesktop = $disabledesktop[0];
									
								} else {
									
									$disabledesktop = 0;
								}
								
								$renderDiviMegaPro = 1;
								if ( $disablemobile && self::$isMobileDevice ) {
									
									$renderDiviMegaPro = 0;
								}
								
								if ( $disabletablet && self::$isTabletDevice ) {
									
									$renderDiviMegaPro = 0;
								}
								
								if ( $disabledesktop && !self::$isMobileDevice && !self::$isTabletDevice ) {
									
									$renderDiviMegaPro = 0;
								}
								
								// condition for some iPads
								if ( $disablemobile && !$disabletablet && self::$isMobileDevice && self::$isTabletDevice ) {
									
									$renderDiviMegaPro = 1;
								}
								
								if ( $renderDiviMegaPro ) {
									
									$divimegapros_in_current[ $divimegapro_id ] = $divimegapro_id;
									
									if ( $render ) {
										
										if ( is_singular() && is_main_query() ) {
											
											self::$post_id = (int) $divimegapro_id;
												
											$divimegapro_content = et_core_esc_previously( self::render( self::$post_id ) );
											
											$the_content = $the_content . $divimegapro_content;
										}
									}
									
									$dmpswithindmp = self::searchForDMPsWithinDMPs( $divimegapro_id );
									
									if ( count( $dmpswithindmp ) > 0 ) {
										
										foreach( $dmpswithindmp as $dmp_id => $dmp_idx ) {
											
											$divimegapros_in_current[ $dmp_id ] = $dmp_id;
										
											if ( !isset( $divimegapros[$dmp_id] ) && $render ) {
												
													if ( is_singular() && is_main_query() ) {
															
														$divimegapro_content = et_core_esc_previously( self::render( $dmp_id ) );
														
														$the_content = $the_content . $divimegapro_content;
													}
											}
										}
									}
								}
							}
						}
					}
				}
				
				if ( $render ) {
					
					$the_content = $the_content . '</div>';
					
					?>
					<script>
					var ajaxurl = "<?php echo et_core_intentionally_unescaped( admin_url( 'admin-ajax.php' ), 'fixed_string' ); ?>"
					, diviLifeisMobileDevice = "<?php print et_core_intentionally_unescaped( DiviMegaPro::isMobileDevice(), 'fixed_string' ); ?>"
					, diviLifeisTabletDevice = "<?php print et_core_intentionally_unescaped( DiviMegaPro::isTabletDevice(), 'fixed_string' ); ?>";
					</script>
					<?php
				}
			
			} catch (Exception $e) {
			
				DiviMegaPro::log( $e );
			}
			
			if ( !$render ) {
				
				return $divimegapros_in_current;
			}
			
			return $the_content;
		}
		
		
		private static function searchForDMPsWithinDMPs( $divimegapro_id = NULL ) {
			
			$post = get_post( $divimegapro_id );
			
			/* Search divimegapros within divimegapros */
			if ( $post ) {
				
				$content = $post->post_content;
				
				$divimegapros_in_post = DiviMegaPro_Helper::searchDMPs( $content );
				
				if ( is_array( $divimegapros_in_post ) ) {
					
					return $divimegapros_in_post;
				}
				else {
					
					return array();
				}
			}
		}
		
		
		public static function getRender( $post_id = NULL, $avoidRenderTags = 0, $divilifepost = false ) {
			
			try {
				
				if ( !is_numeric( $post_id ) ) {
					
					throw new InvalidArgumentException( 'divimegapro_render > $post_id is not numeric');
				}
				
			} catch (Exception $e) {
			
				DiviMegaPro::log( $e );
			}
			
			$render = array();
			
			$output = '';
			
			if ( $divilifepost === false ) {
				
				global $post;
				
				$post_data = $post;
				
				if ( !isset( $post_data->post_content ) ) {
					
					return false;
				}
			}
			else {
				
				$post_data = get_post( self::$post_id );
			}
			
			$post_content = $post_data->post_content;
			
			$output = $post_content;
			
			$render['post_data'] = $post_data;
			$render['output'] = $output;
			
			return $render;
		}
		
		
		public static function start_module_index_override() {
			ET_Builder_Element::begin_theme_builder_layout( self::$post_id );
			
			add_filter(
				'et_pb_module_shortcode_attributes',
				array( 'DiviMegaPro_Controller', 'module_index_override' ),
				1
			);
		}
		
		public static function end_module_index_override() {
			
			ET_Builder_Element::end_theme_builder_layout();
			
			remove_filter( 'et_pb_module_shortcode_attributes', array( 'DiviMegaPro_Controller', 'module_index_override' ), 2 );
			
			global $et_pb_predefined_module_index;
			
			unset( $et_pb_predefined_module_index );
		}
		
		public static function module_index_override( $value = '' ) {
			global $et_pb_predefined_module_index;
			
			self::$module_index ++;
			$et_pb_predefined_module_index = sprintf(
				'dmp_%1$s_%2$s',
				self::$post_id,
				self::$module_index
			);
			
			return $value;
		}
		
		
		protected static function updateRevSliders( $post_content, $divimegapro_id ) {
			
			if ( !class_exists( 'RevSliderSlider' ) ) {
				
				return;
			}
			
			if ( has_shortcode( $post_content, 'rev_slider' ) ) {
				
				preg_match_all('/\\[rev_slider.*?\\]/', $post_content, $fullshortcodes);
				
				if ( isset($fullshortcodes[0]) && $fullshortcodes[0] !== '') {
					
					foreach( $fullshortcodes[0] as $shortcode ) {
						
						preg_match_all('/\[rev_slider.*alias=.(.*)"\]/', $shortcode, $shortcodes);
						
						if ( isset($shortcodes[1]) && isset($shortcodes[1][0]) && $shortcodes[1][0] !== '') {
							
							$alias = $shortcodes[1][0];
							
							if ( !RevSliderSlider::alias_exists( $alias ) ) continue;
							
							$slider = new RevSliderSlider();
							$slider->init_by_alias( $alias );
							$params = $slider->get_params();
							
							if ( $params['general']['slideshow']['waitForInit'] === false ) {
								
								$slider->set_val($params, array('general', 'slideshow', 'waitForInit'), true);
								
								$slider->set_params($params);
								
								$slider->update_params($params, true);
							}
						}
					}
				}
			}
		}
		
		public static function render( $divimegapro_id ) {
			
			ob_start();
			
			$divilifepost = true;
			
			if ( ! isset ( $divimegapro_id ) ) {
				
				$divimegapro_id = self::$post_id;
			}
			
			$render = self::getRender( $divimegapro_id, 0, $divilifepost );
			
			$post_data = $render['post_data'];
			
			$output = $render['output'];
			
			// Support RevolutionSlider
			self::updateRevSliders( $post_data->post_content, $divimegapro_id );
			
			$is_mobile = self::$isMobileDevice;
			
			if ( !$is_mobile ) {
				
				$is_mobile = 0;
			}
			
			
			/* Close Button Customizations */
			$dmp_enabledesktop = get_post_meta( $post_data->ID, 'dmp_enabledesktop', true );
			if ( !isset( $dmp_enabledesktop ) ) {
				
				$dmp_enabledesktop = 0;
			}
			
			$dmp_enablemobile = get_post_meta( $post_data->ID, 'dmp_enablemobile', true );
			if ( !isset( $dmp_enablemobile ) ) {
				
				$dmp_enablemobile = 0;
			}
			
			$dmp_customizeclosebtn = get_post_meta( $post_data->ID, 'dmp_customizeclosebtn' );
			if( !isset( $dmp_customizeclosebtn[0] ) ) {
				
				$dmp_customizeclosebtn[0] = '0';
			}
			
			
			/* Arrow Feature */
			$dmp_enable_arrow = get_post_meta( $post_data->ID, 'dmp_enable_arrow', true );
			if ( !isset( $dmp_enable_arrow ) ) {
				
				$dmp_enable_arrow = 0;
			}
			
			$dmp_arrowfeature_type = get_post_meta( $post_data->ID, 'dmp_arrowfeature_type', true );
			if ( !isset( $dmp_arrowfeature_type ) ) {
				
				$dmp_arrowfeature_type = 0;
			}
			
			
			/* Mega Menu Settings */
			$dmp_animation = get_post_meta( $post_data->ID, 'dmp_animation', true );
			if ( !isset( $dmp_animation ) ) {
				
				$dmp_animation = 'shift-away';
			}
			
			$dmp_placement = get_post_meta( $post_data->ID, 'dmp_placement', true );
			if ( !isset( $dmp_placement ) ) {
				
				$dmp_placement = 'down';
			}
			
			$dmp_margintopbottom = get_post_meta( $post_data->ID, 'dmp_margintopbottom', true );
			if ( !isset( $dmp_margintopbottom ) ) {
				
				$dmp_margintopbottom = 0;
			}
			
			$dmp_megaprowidth = get_post_meta( $post_data->ID, 'dmp_megaprowidth', true );
			if ( !isset( $dmp_megaprowidth ) ) {
				
				$dmp_megaprowidth = '100';
			}
			
			$dmp_megaprowidth_custom = get_post_meta( $post_data->ID, 'dmp_megaprowidth_custom', true );
			if ( !isset( $dmp_megaprowidth_custom ) ) {
				
				$dmp_megaprowidth_custom = '100';
			}
			
			$dmp_megaprofixedheight = get_post_meta( $post_data->ID, 'dmp_megaprofixedheight', true );
			if ( !isset( $dmp_megaprofixedheight ) ) {
				
				$dmp_megaprofixedheight = 0;
			}
			
			$dmp_cssposition = get_post_meta( $post_data->ID, 'dmp_cssposition', true );
			if ( !isset( $dmp_cssposition ) ) {
				
				$dmp_cssposition = '100';
			}
			
			$dmp_enablecenterhorizontal = get_post_meta( $post_data->ID, 'dmp_enablecenterhorizontal', true );
			if ( !isset( $dmp_enablecenterhorizontal ) ) {
				
				$dmp_enablecenterhorizontal = 0;
			}
			
			$dmp_triggertype = get_post_meta( $post_data->ID, 'dmp_triggertype', true );
			if ( !isset( $dmp_triggertype ) ) {
				
				$dmp_triggertype = 'hover';
			}
			
			$dmp_exittype = get_post_meta( $post_data->ID, 'dmp_exittype', true );
			if ( !isset( $dmp_exittype ) ) {
				
				$dmp_exittype = 'hover';
			}
			
			$dmp_exitdelay = get_post_meta( $post_data->ID, 'dmp_exitdelay', true );
			if ( !isset( $dmp_exitdelay ) ) {
				
				$dmp_exitdelay = 0;
			}
			
			$dmp_bg_color = get_post_meta( $post_data->ID, 'dmp_bg_color', true );
			$dmp_font_color = get_post_meta( $post_data->ID, 'dmp_font_color', true );
			
			$body = $output;
			
			require( DIVI_MEGA_PRO_PLUGIN_DIR . '/templates/divimegapro.php');
			
			$divimegapro_content = ob_get_contents();
			ob_end_clean();
			
			return $divimegapro_content;
		}
		
	} // end DiviMegaPro_Controller