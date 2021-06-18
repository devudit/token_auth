<?php

namespace Drupal\token_auth\Authentication;

use Drupal\Core\Authentication\AuthenticationProviderInterface;
use Drupal\Core\Entity\EntityTypeManagerInterface;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class TokenAuthenticationProvider
 *
 * @package Drupal\token_auth\Authentication\Provider
 */
class TokenAuthenticationProvider implements AuthenticationProviderInterface {

  /**
   * The entity type manager.
   *
   * @var \Drupal\Core\Entity\EntityTypeManagerInterface
   */
  protected $entityTypeManager;

  /**
   * {@inheritdoc}
   */
  public function __construct(EntityTypeManagerInterface $entity_type_manager) {
    $this->entityTypeManager = $entity_type_manager;
  }

  /**
   * {@inheritdoc}
   */
  public function applies(Request $request) {
    return $request->headers->has('X-Auth-Token');
  }

  /**
   * {@inheritdoc}
   */
  public function authenticate(Request $request) {
    $token = $request->headers->get('X-Auth-Token');

    $user = $this->entityTypeManager
      ->getStorage('user')
      ->loadByProperties([
        'auth_token' => $token,
      ]);

    if (!empty($user)) {
      return reset($user);
    }

    return NULL;
  }

}
