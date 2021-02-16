<?php
/**
 * Bootstrap Conditional
 *
 * @since  1.0.0
 *
 * @category  WordPress_Plugin
 * @package   Bootstrap Conditional
 * @author    Neil Gowran
 * @link      https://wordpress.org/plugins/bootstrap-conditional/
 */

/**
 * Main plugin class
 *
 * @since  1.0.0
 */
class Bootstrap_Conditional{
	/**
	 * Bootstrap js version
	 *
	 * @var version
	 */
	public $bl_version = '1.0.0';
	/**
	 * Holds an instance of the object
	 *
	 * @var MeMeMe_Plugin
	 */
	protected static $instance = null;
	/**
	 * Returns the running object
	 *
	 * @return MeMeMe_Plugin
	 */
	public static function get_instance() {
		if ( null === self::$instance ) {
			self::$instance = new self();
			self::$instance->hooks();
		}
		return self::$instance;
	}

	/**
	 * Init plugin
	 */
	public function __construct() {
		// Nothing here.
	}

	/**
	 * Initiate hooks
	 */
	public function hooks() {
		// Plugin text domain.
		// add_action( 'plugins_loaded', array( $this, 'load_plugin_textdomain' ) );
		add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_scripts' ) );
		add_action( 'wp_print_styles', array( $this, 'print_styles' ) );
		// add_action( 'admin_menu', array( $this, 'plugin_page' ) );
		// add_action( 'admin_init', array( $this, 'plugin_settings' ) );

		// WP 3.0+.
		add_action( 'add_meta_boxes', array( $this, 'post_options_metabox' ) );
		add_action( 'save_post', array( $this, 'bcmeta_save' ) );
	}

	/**
	 * Add the bootstrap option metabox to all post types
	 */
	public function post_options_metabox() {
		add_meta_box( 'post_options_bl', __( 'Bootstrap 4', 'bootstrap-conditional' ), array( $this, 'bcmeta_create' ), get_post_types(), 'side', 'low' );
	}



	/**
	 * Setup JavaScript and CSS
	 */
	public function enqueue_scripts() {

		/* Get the current post ID. */
		$post_id = get_the_ID();
		$disable_basebootstrap = get_post_meta( $post_id, '_bootstrap_check', true );

		
		if( $disable_basebootstrap !=='' && is_singular() ){
			wp_enqueue_script( 'bootstrap-4', plugin_dir_url( dirname( __FILE__ ) ) . 'js/bootstrap-4.min.js', array(), $this->bl_version, true );
			wp_enqueue_style( 'bootstrap-4', plugin_dir_url( dirname( __FILE__ ) ) . 'css/bootstrap-4.min.css', array(), $this->bl_version, 'all' );

		}
	}

	/**
	 * Dequeue BeaverBuilder Bootstrap minimal CSS
	 */
	public function print_styles() {

		/* Get the current post ID. */
		$post_id = get_the_ID();
		$disable_basebootstrap = get_post_meta( $post_id, '_bootstrap_check', true );


		if($disable_basebootstrap !=='') {
			wp_dequeue_style( 'base-4' );
        	wp_deregister_style( 'base-4' );
		}
	}
	
	/**
	 * Create Bootstrap Meta
	 *
	 * @link https://gist.github.com/emilysnothere/943ea6274dc160cec271
	 */
	public function bcmeta_create() {
		$post_id = get_the_ID();
		$value = get_post_meta( $post_id, '_bootstrap_check', true );
		wp_nonce_field( 'bootstrap_nonce_' . $post_id, 'bootstrap_nonce' );
		?>
		<div class="misc-pub-section misc-pub-section-last">
			<label><input type="checkbox" value="1" <?php checked( $value, true, true ); ?> name="_bootstrap_check" /><?php esc_attr_e( 'Load Full Bootstrap', 'bootstrap-conditional' ); ?></label>
		</div>
		<?php
	}

	/**
	 * Save Bootstrap Conditional Meta
	 *
	 * @param int $post_id post ID.
	 */
	public function bcmeta_save( $post_id ) {

		if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
			return;
		}
		$bootstrap_nonce = filter_input( INPUT_POST, 'bootstrap_nonce', FILTER_SANITIZE_STRING );
		if ( ! $bootstrap_nonce || ! wp_verify_nonce( $bootstrap_nonce, 'bootstrap_nonce_' . $post_id ) ) {
			return;
		}
		if ( ! current_user_can( 'edit_post', $post_id ) ) {
			return;
		}

		$bootstrap_check = filter_input( INPUT_POST, '_bootstrap_check', FILTER_SANITIZE_STRING );
		if ( $bootstrap_check ) {
			update_post_meta( $post_id, '_bootstrap_check', $bootstrap_check );
		} else {
			delete_post_meta( $post_id, '_bootstrap_check' );
		}
	}

}

/**
 * Helper function to get/return the MeMeMe_Plugin object
 *
 * @return Bootstrap_Conditionalobject
 */
function bootstrap_conditional() {
	return Bootstrap_Conditional::get_instance();
}