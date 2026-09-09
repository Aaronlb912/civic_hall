<?php

declare(strict_types=1);

use Drupal\media\Entity\MediaType;
use Drupal\node\Entity\NodeType;
use Drupal\Tests\BrowserTestBase;
use PHPUnit\Framework\Attributes\Group;
use PHPUnit\Framework\Attributes\IgnoreDeprecations;
use PHPUnit\Framework\Attributes\RunTestsInSeparateProcesses;

/**
 * Tests that CivicHall can be used to install Drupal.
 */
#[Group('civic_hall')]
#[IgnoreDeprecations]
#[RunTestsInSeparateProcesses]
class InstallTest extends BrowserTestBase {

  /**
   * {@inheritdoc}
   */
  protected $defaultTheme = 'stark';

  /**
   * {@inheritdoc}
   */
  protected function installParameters(): array {
    $install = parent::installParameters();
    $install['parameters']['recipe'] = dirname(__FILE__, 4);
    return $install;
  }

  /**
   * Tests that the site template can be used to install Drupal.
   */
  public function testInstall(): void {
    $this->checkContent('node');
    $this->checkContent('canvas_page');

    $account = $this->createUser();
    $this->drupalLogin($account);
    $this->checkContent('node');
    $this->checkContent('canvas_page');

    $account->addRole('content_editor')->save();
    $session = $this->getSession();
    foreach (NodeType::loadMultiple() as $node_type) {
      $this->drupalGet('/node/add/' . $node_type->id());
      $this->assertSame(200, $session->getStatusCode(), 'Content editors cannot create ' . $node_type->label() . ' content.');
    }
    foreach (MediaType::loadMultiple() as $media_type) {
      $this->drupalGet('/media/add/' . $media_type->id());
      $this->assertSame(200, $session->getStatusCode(), 'Content editors cannot create ' . $media_type->label() . ' media.');
    }
  }

  /**
   * Checks that all content of a specific type can be visited successfully.
   */
  private function checkContent(string $entity_type_id): void {
    $items = \Drupal::entityTypeManager()
      ->getStorage($entity_type_id)
      ->loadMultiple();

    foreach ($items as $item) {
      if ($item->hasLinkTemplate('canonical')) {
        $url = $item->toUrl('canonical');
        if ($url->access()) {
          $this->drupalGet($url);
          $this->assertSame(200, $this->getSession()->getStatusCode(), $url->toString() . ' should be accessible to the current user.');
        }
      }
    }
  }

}
