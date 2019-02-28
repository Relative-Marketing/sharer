<?php
/**
 * Registers a widget that when uses will display the 
 * active social network profile links
 */
namespace RelativeMarketing\Sharer;

class Profile_Widget extends \WP_Widget {
	public function __construct() {
		parent::__construct(
			'relative_sharer_profile_links_widget',
			__( 'Social Profile Links - Relative Sharer', 'relative-sharer' ),
			array(
				'decription' => __( 'Will display the links to your active social network profiles' ),
			)
		);

		add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_scripts' ) );
		add_action( 'admin_footer-widgets.php', array( $this, 'print_scripts' ), 9999 );
	}

	/**
	 * Enqueue scripts
	 * 
	 * @since 0.0.1
	 * 
	 * @param string $hook_suffix
	 */
	public function enqueue_scripts( $hook_suffix ) {
		if('widgets.php' !== $hook_suffix ) {
			return;
		}

		wp_enqueue_style( 'wp-color-picker' );
		wp_enqueue_script( 'wp-color-picker' );
		wp_enqueue_script( 'underscore' );
	}

	/**
	 * Print scripts
	 * 
	 * @since 0.0.1
	 */
	public function print_scripts() {
		?>
		<script>
			( function( $ ){
				function initColorPicker( widget ) {
					widget.find( '.color-picker' ).wpColorPicker( {
						change: _.throttle( function() { // For Customizer
							$(this).trigger( 'change' );
						}, 3000 )
					});
				}

				function onFormUpdate( event, widget ) {
					initColorPicker( widget );
				}

				$( document ).on( 'widget-added widget-updated', onFormUpdate );

				$( document ).ready( function() {
					$( '#widgets-right .widget:has(.color-picker)' ).each( function () {
						initColorPicker( $( this ) );
					} );
				} );

				$('.use-global-settings-checkbox').each( function() {

					if($( this ).is(':checked')) {
						$( this).parent().parent().find('.single-share-settings-wrap').hide();
					} else {
						$( this).parent().parent().find('.single-share-settings-wrap').show();
					}

					$( this ).change(
						function() {
							if($(this).is(':checked')) {
								$( this).parent().parent().find('.single-share-settings-wrap').hide();
							} else {
								$( this).parent().parent().find('.single-share-settings-wrap').show();
							}
						}
					)
				});

			}( jQuery ) );
		</script>
		<?php
	}
	function widget($args, $instance) {
		$outer_color = empty( $instance['icon_outer_color'] ) ? false : $instance['icon_outer_color'];
		$inner_color = empty( $instance['icon_inner_color'] ) ? false : $instance['icon_inner_color'];
		$icon_size = empty( $instance['icon_size'] ) ? false : $instance['icon_size'];
		$using_custom_settings = $instance['use_global_settings'] ? false : true; 
		$style = '';

		if ( $using_custom_settings && ($outer_color || $inner_color || $icon_size) ) {

			/**
			 * Set the css variables to the selected values for this widget
			 * 
			 * Set the variables on the id so that each widget can be styled
			 * individually 
			 */
			$style = '<style>';
			$style .= '#' . $args['widget_id'] . ' {';
				
			if ( $outer_color ) {
				$style .= '--icon-color-outer: ' . $outer_color . ';';
				$style .= '--icon-color-main--hover: r' . $outer_color . ';';
			}

			if ( $inner_color ) {
				$style .= '--icon-color-main: ' . $inner_color . ';';
				$style .= '--icon-color-outer--hover: ' . $inner_color . ';';
			}

			if ( $icon_size ) {
				$style .= '--icon-size: ' . $icon_size . ';';
			}

			$style .= '}';
			$style .= '</style>';
		}
		
		echo $style . get_page_profile_links($args['widget_id']);
	}

	/**
	 * Back-end widget form.
	 *
	 * @see WP_Widget::form()
	 *
	 * @param array $instance Previously saved values from database.
	 */
	public function form( $instance ) {
		// Defaults
		$instance = wp_parse_args(
			$instance,
			array(
				'use_global_settings' => true,
				'icon_outer_color' => '',
				'icon_inner_color' => '',
				'icon_size' => ''
			)
		);
		$use_global_settings =  $instance['use_global_settings'] ? 'checked' : ''; 
		?>
		<p>
			<label for="<?php echo $this->get_field_id( 'use_global_settings' ); ?>" style="display:inline-block;"><?php _e( 'Use global settings:' ); ?></label>
			<input class="widefat use-global-settings-checkbox" id="<?php echo $this->get_field_id( 'use_global_settings' ); ?>" name="<?php echo $this->get_field_name( 'use_global_settings' ); ?>" type="checkbox" <?php echo $use_global_settings; ?> />
		</p>
		<div class="single-share-settings-wrap">
		<p>
			<label for="<?php echo $this->get_field_id( 'icon_outer_color' ); ?>" style="display:block;"><?php _e( 'Icon outer color:' ); ?></label> 
			<input class="widefat color-picker" id="<?php echo $this->get_field_id( 'icon_outer_color' ); ?>" name="<?php echo $this->get_field_name( 'icon_outer_color' ); ?>" type="text" value="<?php echo $instance['icon_outer_color']; ?>" />
		</p>
		<p>
			<label for="<?php echo $this->get_field_id( 'icon_inner_color' ); ?>" style="display:block;"><?php _e( 'Icon inner color:' ); ?></label> 
			<input class="widefat color-picker" id="<?php echo $this->get_field_id( 'icon_inner_color' ); ?>" name="<?php echo $this->get_field_name( 'icon_inner_color' ); ?>" type="text" value="<?php echo $instance['icon_inner_color']; ?>" />
		</p>
		<p>
			<label for="<?php echo $this->get_field_id( 'icon_size' ); ?>" style="display:block;"><?php _e( 'Icon Size:' ); ?></label> 
			<span>Acceptable css format e.g 15px, 1rem etc</span>
			<input class="widefat" id="<?php echo $this->get_field_id( 'icon_size' ); ?>" name="<?php echo $this->get_field_name( 'icon_size' ); ?>" type="text" value="<?php echo $instance['icon_size']; ?>" />
		</p>
		</div>
		<?php
	}

	/**
	 * Sanitize widget form values as they are saved.
	 *
	 * @see WP_Widget::update()
	 *
	 * @param array $new_instance Values just sent to be saved.
	 * @param array $old_instance Previously saved values from database.
	 *
	 * @return array Updated safe values to be saved.
	 */
	public function update( $new_instance, $old_instance ) {
		$instance = array();
		$instance['icon_outer_color'] = ( ! empty( $new_instance['icon_outer_color'] ) ) ? sanitize_text_field( $new_instance['icon_outer_color'] ) : '';
		$instance['icon_inner_color'] = ( ! empty( $new_instance['icon_inner_color'] ) ) ? sanitize_text_field( $new_instance['icon_inner_color'] ) : '';
		$instance['icon_size'] = ( ! empty( $new_instance['icon_size'] ) ) ? sanitize_text_field( $new_instance['icon_size'] ) : '';
		$instance['use_global_settings'] = ! empty( $new_instance['use_global_settings'] ) ? true : false;
		
		return $instance;
	}


}

add_action( 'widgets_init', __NAMESPACE__ . '\\register_profile_widget' );

function register_profile_widget() {
	register_widget( __NAMESPACE__ . '\\Profile_Widget' );
}