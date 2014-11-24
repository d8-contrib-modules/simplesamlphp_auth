<?php

/**
 * @file
 * Contains \Drupal\simplesamlphp_auth\Plugin\Block\SimplesamlphpAuthBlock.
 */

namespace Drupal\simplesamlphp_auth\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use Drupal\Core\Cache\Cache;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Routing\LinkGeneratorTrait;
use Drupal\Core\Url;

/**
 * Provides a 'Most recent poll' block.
 *
 * @Block(
 *   id = "simplesamlphp_auth_block",
 *   admin_label = @Translation("SimpleSAMLphp Auth Status"),
 * )
 */
class SimplesamlphpAuthBlock extends BlockBase {

  use LinkGeneratorTrait;

  /**
   * {@inheritdoc}
   */
  public function build() {

    $activated = \Drupal::config('simplesamlphp_auth.settings')->get('activate');

    $simplesaml = \Drupal::service('simplesamlphp_auth.manager');
    $simplesaml->load();

    if ($activated) {
      if ($simplesaml->isAuthenticated()) {
        $content = $this->t('Logged in as %authname<br />!logout', array(
          '%authname' => $simplesaml->getAuthname(),
          '!logout' => $this->l('Log Out', new Url('user.logout')),
        ));
      }
      else {
        $content = $this->t('!login', array(
          '!login' => $this->l('Federated Log In', new Url('simplesamlphp_auth.saml_login'))
        ));
      }
    }
    else {
      $content = $this->t('SimpleSAML not enabled');
    }

    return array(
      '#title' => $this->t('SimpleSAMLphp Auth Status'),
      '#markup' => $content,
    );
  }
}
