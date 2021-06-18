<?php


namespace Drupal\token_auth\Plugin\rest\resource;

use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\node\NodeInterface;
use Drupal\rest\Plugin\ResourceBase;
use Drupal\rest\ResourceResponse;
use Psr\Log\LoggerInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;

/**
 * Provides json_page_resource endpoint.
 *
 * @RestResource(
 *   id = "json_page_resource",
 *   label = @Translation("Json Page Resource"),
 *   uri_paths = {
 *     "create" = "/api/v1/page/{id}"
 *   }
 * )
 */
class JsonPageResources extends ResourceBase {

  /**
   * Entity storage object.
   *
   * @var \Drupal\Core\Entity\EntityStorageInterface
   */
  protected $nodeStorage;

  /**
   * Constructs a Drupal\token_auth\Plugin\rest\resource\JsonPageResources
   * object.
   *
   * @param array $configuration
   *   A configuration array containing information about the plugin instance.
   * @param string $plugin_id
   *   The plugin_id for the plugin instance.
   * @param mixed $plugin_definition
   *   The plugin implementation definition.
   * @param array $serializer_formats
   *   The available serialization formats.
   * @param \Psr\Log\LoggerInterface $logger
   *   A logger instance.
   * @param EntityTypeManagerInterface $entityTypeManager
   *   EntityTypeManagerInterface object.
   */
  public function __construct(array $configuration, $plugin_id, $plugin_definition, array $serializer_formats,
                              LoggerInterface $logger,
                              EntityTypeManagerInterface $entityTypeManager) {
    // Call parent constructor.
    parent::__construct($configuration, $plugin_id, $plugin_definition, $serializer_formats, $logger);
    $this->nodeStorage = $entityTypeManager->getStorage('node');
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container, array $configuration, $plugin_id, $plugin_definition) {
    return new static(
      $configuration,
      $plugin_id,
      $plugin_definition,
      $container->getParameter('serializer.formats'),
      $container->get('logger.factory')->get('token_auth'),
      $container->get('entity_type.manager')
    );
  }

  /**
   * @param $id
   *
   * @return \Drupal\rest\ResourceResponse|\Symfony\Component\HttpFoundation\JsonResponse
   */
  public function post($id) {

    // A default response.
    $accessDeniedResponse = new JsonResponse([
      'success' => FALSE,
      'message' => 'Access Denied',
    ], 403);

    // If id exist.
    if ($id) {

      // Load node object.
      $node = $this->nodeStorage->load($id);

      // Check if we have node object and bundle is page.
      if ($node instanceof NodeInterface && $node->bundle() == 'page') {

        // Prepare response data.
        $response_data['success'] = TRUE;
        $response_data['data'] = $node->toArray();

        // Prepare response.
        $response = new ResourceResponse($response_data);

        // Send response.
        return $response;
      }

    }

    // Send default response.
    return $accessDeniedResponse;

  }

}
