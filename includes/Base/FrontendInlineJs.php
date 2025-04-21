<?php
namespace FTFWCWP\Base;

use FTFWCWP\Helpers\PluginConstants;

/**
 * Class for plucin frontend inline js.
 *
 * @package FTFWCWP
 * @since: 1.1.0
 * @auther: Mahbub Alam Khan
 */
class FrontendInlineJs {

	/**
	 * Register the methods.
	 */
	public function register() {
		add_action( 'wp_head', [ $this, 'set_inline_js' ] );
	}

	/**
	 * Set the inline js.
	 */
	public function set_inline_js() {

		$faqftw_options = PluginConstants::$addon_options;

        $faqftw_faq_counter = $faqftw_options['faqftw_faq_counter'] ?? 1;
        ?>
<script type="text/javascript">
var faqftw_faq_counter = '<?php echo $faqftw_faq_counter; ?>';
</script>

		<?php
	}
}
