<?php

/**
 * @file
 * Definition of Drupal\commerce\Tests\StoreTest
 */

namespace Drupal\commerce\Tests;

use Drupal\commerce\Entity\Store;
use Drupal\commerce\Entity\StoreType;

/**
 * Create, view, edit, delete, and change store entities.
 *
 * @group commerce
 */
class StoreTest extends CommerceTestBase {

  /**
   * A store type entity to use in the tests.
   *
   * @var StoreType
   */
  protected $type;

  /**
   * {@inheritdoc}
   */
  protected function setUp() {
    parent::setUp();

    $this->type = $this->createEntity('commerce_store_type', array(
        'id' => 'foo',
        'label' => 'Label of foo',
      )
    );
  }

  /**
   * Tests creating a store programaticaly and through the create form.
   */
  public function testCreateStore() {
    $name = strtolower($this->randomMachineName(8));
    // Create a store programmaticaly.
    $store = $this->createEntity('commerce_store', array(
        'type' => $this->type->id(),
        'name' => $name,
        'mail' => \Drupal::currentUser()->getEmail(),
        'default_currency' => 'EUR',
      )
    );
    $storeExist = (bool) Store::load($store->id());
    $this->assertTrue($storeExist, 'The new store has been created in the database.');

    // Create a store through the form.
    $this->drupalGet('admin/commerce/config/store');
    $this->clickLink('Add a new store');
    $this->clickLink($this->type->label());
    $edit = array(
      'name[0][value]' => 'Foo Store',
      'mail[0][value]' => \Drupal::currentUser()->getEmail(),
      'default_currency' => 'EUR',
    );
    $this->drupalPostForm(NULL, $edit, t('Save'));
  }

  /**
   * Tests updating a store through the edit form.
   */
  public function testUpdateStore() {
    // Create a new store.
    $store = $this->createEntity('commerce_store', array(
        'type' => $this->type->id(),
        'name' => $this->randomMachineName(8),
        'email' => \Drupal::currentUser()->getEmail(),
      )
    );

    $this->drupalGet('admin/commerce/config/store');
    $this->clickLink(t('Edit'));
    // Only change the name.
    $edit = array(
      'name[0][value]' => $this->randomMachineName(8),
    );
    $this->drupalPostForm(NULL, $edit, 'Save');
    $storeChanged = Store::load($store->id());
    $this->assertEqual($store->getName(), $storeChanged->getName(), 'The name of the store has been changed.');
  }

  /**
   * Tests deleting a store.
   */
  public function testDeleteStore() {
    // Create a new store.
    $store = $this->createEntity('commerce_store', array(
        'type' => $this->type->id(),
        'name' => $this->randomMachineName(8),
        'email' => \Drupal::currentUser()->getEmail(),
      )
    );
    $storeExist = (bool) Store::load($store->id());
    $this->assertTrue($storeExist, 'The new store has been created in the database.');

    // Delete the Store and verify deletion.
    $store->delete();
    $storeExist = (bool) Store::load($store->id());
    $this->assertFalse($storeExist, 'The new store has been deleted from the database.');
  }
}
