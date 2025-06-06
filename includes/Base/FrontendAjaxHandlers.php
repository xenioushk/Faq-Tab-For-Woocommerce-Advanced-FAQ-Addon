<?php
namespace FTFWCWP\Base;

use Xenioushk\BwlPluginApi\Api\AjaxHandlers\AjaxHandlersApi;

/**
 * Class for frontend ajax handlers.
 *
 * @package FTFWCWP
 * @since: 1.1.0
 * @author: Mahbub Alam Khan
 */
class FrontendAjaxHandlers {

	/**
	 * Register frontend ajax handlers.
	 */
	public function register() {

		$ajax_handlers_api = new AjaxHandlersApi();

		// Initalize Callbacks.

		// Do not change the tag.
		// If do so, you need to change in js file too.
		$ajax_requests = [];

		$ajax_handlers_api->add_ajax_handlers( $ajax_requests )->register();
	}
}
