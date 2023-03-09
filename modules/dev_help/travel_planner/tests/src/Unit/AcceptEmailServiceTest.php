<?php
namespace Drupal\Tests\travel_planner\Unit;

use Drupal\travel_planner\AcceptEmailService;
use Drupal\Tests\UnitTestCase;

class AcceptEmailServiceTest extends UnitTestCase  {
    /**
     * Modules to install.
     * @var array
     */
    public static $modules = ['node', 'block', 'travel_planner'];

    /**
     * A simple user with 'access content' permission
     */
    private $user;
    /**
     * Perform any initial set up tasks that run before every test method
     */
    public function setUp() {
        parent::setUp();
        $this->user = $this->drupalCreateUser(array('access content'));
    }

}
