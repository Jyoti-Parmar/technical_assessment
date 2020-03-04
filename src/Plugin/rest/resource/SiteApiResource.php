<?php 

namespace Drupal\site_api\Plugin\rest\resource;

use Drupal\rest\Plugin\ResourceBase;
use Drupal\rest\ResourceResponse;
use Drupal\node\Entity\Node;
use Drupal\Core\Cache\CacheableMetadata;

/**
 * Provides a Site Resource
 *
 * @RestResource(
 *   id = "site_api_resource",
 *   label = @Translation("Site API Resource"),
 *   uri_paths = {
 *     "canonical" = "/site_api/site_api_resource/{id}"
 *   }
 * )
 */

class SiteApiResource extends ResourceBase {

	//set permission to get access to resource 
	protected function getBaseRouteRequirements($method) {
		$requirements = parent::getBaseRouteRequirements($method);
		if ($method === 'GET') {
			$requirements['_permission'] = 'access content';
		}
		return $requirements;
	}

  /**
   * Responds to entity GET requests.
   * @return \Drupal\rest\ResourceResponse
   */
  	public function get($node_id) {
  		$config = \Drupal::config('system.siteapikey');
  	 	$node = Node::load($node_id);
		
  	 	if(!empty($config->get('siteapikey')) && is_object($node) && $node->getType() == 'page'){

			$response = $node;
	 	}else {
	 		$response = "Access Denied";
	 	}
	 	$res_response = new ResourceResponse($response);
	 	$disable_cache = new CacheableMetadata();
		$disable_cache->setCacheMaxAge(0);

		$res_response->addCacheableDependency($disable_cache);

    	return $res_response;
  	}
}