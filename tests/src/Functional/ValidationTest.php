<?php

declare(strict_types=1);

use Drupal\Core\Config\FileStorage;
use Drupal\canvas\Entity\AssetLibrary;
use Drupal\canvas\Entity\ComponentTreeEntityInterface;
use Drupal\canvas\JsonSchemaDefinitionsStreamwrapper;
use Drupal\FunctionalTests\Core\Recipe\RecipeTestTrait;
use Drupal\node\Entity\NodeType;
use Drupal\Tests\BrowserTestBase;
use PHPUnit\Framework\Attributes\RunTestsInSeparateProcesses;

/**
 * Tests that this site template can be applied without errors.
 *
 * All deprecation notices triggered by the recipe's dependencies will be
 * displayed. To suppress them, add the
 * \PHPUnit\Framework\Attributes\IgnoreDeprecations attribute to this class.
 */
#[RunTestsInSeparateProcesses]
class ValidationTest extends BrowserTestBase {

  use RecipeTestTrait;

  /**
   * {@inheritdoc}
   */
  protected $defaultTheme = 'stark';

  /**
   * Returns the absolute path of the recipe this test is for.
   *
   * @return string
   *   The absolute path of the recipe.
   */
  protected static function getRecipePath(): string {
    return dirname(__FILE__, 4);
  }

  /**
   * Tests that the site template can be applied without errors.
   *
   * At the very least, this test ensures that this site template can be applied
   * against an empty site with the `drupal recipe` command-line tool. You
   * should customize this test to also confirm that the site template sets up
   * everything as you expect.
   *
   * If you need to test JavaScript interactions, you can convert this test to
   * a functional JavaScript test instead.
   *
   * Documentation on how to write functional (non-JavaScript) tests can be
   * found at https://www.drupal.org/docs/develop/automated-testing/phpunit-in-drupal/creating-functional-tests-simulated-browser.
   *
   * Documentation on how to write functional JavaScript tests can be found at
   * https://www.drupal.org/docs/develop/automated-testing/phpunit-in-drupal/creating-functionaljavascript-tests-real-browser.
   *
   * Further documentation on writing PHPUnit tests for Drupal can be found at
   * https://www.drupal.org/docs/develop/automated-testing/phpunit-in-drupal.
   */
  public function testApply(): void {
    $this->applyRecipe(self::getRecipePath());

    foreach ([
      'department',
      'official',
      'meeting',
      'resident_task',
      'news',
      'city_alert',
    ] as $type) {
      $this->assertNotNull(
        NodeType::load($type),
        "The $type content type should be installed.",
      );
    }

    $session = $this->getSession();
    foreach ([
      '/how-do-i',
      '/departments',
      '/officials',
      '/meetings',
      '/news',
      '/government',
      '/form/contact-form',
      '/form/report-pothole',
      '/form/public-records-request',
    ] as $path) {
      $this->drupalGet($path);
      $this->assertSame(200, $session->getStatusCode(), "$path should be publicly reachable.");
    }

    $this->drupalGet('/how-do-i');
    $this->assertSession()->pageTextContains('Pay a water bill');
    $this->assertSession()->elementExists('css', '.view-civic-hall .views-view-grid h2 a');
    $this->assertSession()->elementNotExists('css', '.view-civic-hall .views-view-grid h3');
    $this->drupalGet('/departments');
    $this->assertSession()->pageTextContains('City Clerk');
    $this->assertSession()->elementExists('css', '.civic-hall-alerts');
    $this->assertSession()->elementExists('css', '.region-header .views-exposed-form input[name="keywords"]');
    $this->drupalGet('/meetings');
    $this->assertSession()->pageTextContains('City Council');
    $this->drupalGet('/form/contact-form');
    $this->assertSession()->pageTextContains('100 Main');
    $this->assertSession()->pageTextContains('What do you need?');
    $this->assertSession()->pageTextContains('If the errand is already on How do I');
    $this->drupalGet('/form/report-pothole');
    $this->assertSession()->pageTextContains('Street or intersection');
    $this->assertSession()->pageTextContains('Send report');
    $this->drupalGet('/form/public-records-request');
    $this->assertSession()->pageTextContains('What records');
    $this->assertSession()->pageTextContains('Send request');

    $library = AssetLibrary::load('global');
    $this->assertNotNull($library);
    $this->assertStringContainsString('.views-view-grid.cols-3', $library->getCss());
    $this->assertFileExists($library->getCssPath());

    // If this site template uses Canvas, it is a best practice for it to ship
    // `canvas.component.*.yml` files for every component that is actually using
    // in content templates, page regions, patterns, landing pages, etc. This
    // method checks for that.
    $this->assertCanvasComponentsAreIncluded();
  }

  /**
   * Checks that the site template includes all Canvas components that it uses.
   */
  protected function assertCanvasComponentsAreIncluded(): void {
    // Examine all entities that implement
    // \Drupal\canvas\Entity\ComponentTreeEntityInterface.
    $entity_types = array_filter(
      \Drupal::entityTypeManager()->getDefinitions(),
      fn ($entity_type): bool => $entity_type->entityClassImplements(ComponentTreeEntityInterface::class),
    );

    $included_components = (new FileStorage(self::getRecipePath() . '/config'))
      ->listAll('canvas.component.');

    foreach ($entity_types as $entity_type) {
      $entities = \Drupal::entityTypeManager()
        ->getStorage($entity_type->id())
        ->loadMultiple();

      foreach ($entities as $entity) {
        $this->assertInstanceOf(ComponentTreeEntityInterface::class, $entity);
        /** @var \Drupal\canvas\Plugin\Field\FieldType\ComponentTreeItem $item */
        foreach ($entity->getComponentTree() as $item) {
          $component = $item->getComponent()?->getConfigDependencyName();
          if ($component) {
            $this->assertContains($component, $included_components, 'The site template should include this component in its configuration.');
          }
        }
      }
    }
  }

  /**
   * {@inheritdoc}
   */
  protected function rebuildAll(): void {
    // The rebuild won't succeed without the `json-schema-definitions` stream
    // wrapper. This would normally happen automatically whenever a module is
    // installed, but in this case, all of that has taken place in a separate
    // process, so we need to refresh *this* process manually.
    // @see canvas_module_preinstall()
    \Drupal::service('stream_wrapper_manager')->registerWrapper(
      'json-schema-definitions',
      JsonSchemaDefinitionsStreamwrapper::class,
      JsonSchemaDefinitionsStreamwrapper::getType(),
    );
    parent::rebuildAll();
  }

}
