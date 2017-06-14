<?php
class Gltr_Widget extends WP_Widget {

	static $instance;

	function __construct() {
		if ( ! $this->instance ) {
			$this->instance = true;
		} else {
			return;
		}
		// widget actual processes
		parent::__construct(
				'wppn_widget', // Base ID
				__( 'Global Translator', 'global-translator' ), // Name
				array( 'description' => __( 'Shows the flags for all the translations', 'global-translator' ), ) // Args
		);
	}

	public function widget( $args, $instance ) {
		echo $args['before_widget'];
		$title = apply_filters('widget_title', $instance['title'] );
		if ( $title )
			echo $args['before_title'] . $title . $args['after_title'];
		$options = get_option('gltr-options');
		echo gltr_get_flags_bar(gltr_get_slug());
		echo $args['after_widget'];
	}

	public function form( $instance ) {
?>		
		<p>
		<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e('Title:', 'Translations'); ?></label>
		    <input id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" value="<?php echo $instance['title']; ?>" style="width:100%;" />
		</p>
<?php 
	}

	public function update( $new_instance, $old_instance ) {
		$instance = $old_instance;
		$instance['title'] = strip_tags( $new_instance['title'] );
		
		return $instance;
	}

}
?>