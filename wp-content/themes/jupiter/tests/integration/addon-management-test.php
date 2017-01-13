<?php
class add_on_management_test extends PHPUnit_Framework_TestCase {

	private $addon;
	public static function setUpBeforeClass() {
	}
	public function setUp() {

		$this->addon = $this->getMockBuilder( 'Mk_Addon_Management' )
			->setConstructorArgs( array( true, false ) )
			->setMethods( array( 'getAddOnDownloadLink', 'getAddOnFileName', 'getAddonVersionFromAPI' ) )
			->getMock();

		$download_map = array(
			array( 'add-on-number-9', 'http://static-cdn.artbees.net/phpunit/add-on-number-9.zip' ),
			array( 'add-on-number-8', 'http://static-cdn.artbees.net/phpunit/add-on-number-8.zip' ),
			array( 'add-on-number-6', 'http://static-cdn.artbees.net/phpunit/add-on-number-6.zip' ),
			array( 'add-on-number-4', $this->throwException( new Exception( 'Add-On you choose is not exist.' ) ) ),
		);
		$filename_map = array(
			array( 'add-on-number-9', 'add-on-number-9.zip' ),
			array( 'add-on-number-8', 'add-on-number-8.zip' ),
			array( 'add-on-number-6', 'add-on-number-6.zip' ),
			array( 'add-on-number-4', $this->throwException( new Exception( 'Add-On you choose is not exist.' ) ) ),
		);

		$version_map = array(
			array( array( 'add-on-number-9' ), array( 'add-on-number-9' => 3.1 ) ),
			array( array( 'add-on-number-6' ), array( 'add-on-number-6' => 1.1 ) ),
			array( array( 'add-on-number-4' ), array() ),
		);
		$this->addon->method( 'getAddOnDownloadLink' )
			->will( $this->returnValueMap( $download_map ) );
		$this->addon->method( 'getAddOnFileName' )
			->will( $this->returnValueMap( $filename_map ) );
		$this->addon->method( 'getAddonVersionFromAPI' )
			->will( $this->returnValueMap( $version_map ) );
	}
	public function test_it_can_validate_none_exist_addon() {
		// $this->check_environment();
		// $response = $this->addon->setAddonSlug('add-on-number-4')->install();
		// $this->assertFalse($response);
		// $this->assertEquals('Add-On you choose is not exist.', $this->addon->getMessage()['message']);
	}

	public function test_it_can_validate_addon_slug_value_on_install() {
		$this->check_environment();
		$response = $this->addon->install();
		$this->assertFalse( $response );
		$this->assertEquals( 'The Add-on Slug field is required.The Add-on Slug field must have an string value.The Add-on Slug field must be at least 3 characters in length.', $this->addon->getMessage()['message'] );
	}
	public function test_it_can_validate_less_character_name_on_install() {
		$this->check_environment();
		$response = $this->addon->setAddonSlug( 're' )->install();
		$this->assertFalse( $response );
		$this->assertEquals( 'The Add-on Slug field must be at least 3 characters in length.', $this->addon->getMessage()['message'] );
	}
	public function test_it_can_download_and_activate_addon_on_install() {
		$this->check_environment();
		$response = $this->addon->setAddonSlug( 'add-on-number-9' )->install();
		$this->assertTrue( $response );
		$this->assertEquals( 'Add-on activated successfully', $this->addon->getMessage()['message'] );
		$this->assertEquals( 1, count( $this->addon->getAddon( 'add-on-number-9' ) ) );
	}
	public function test_it_can_validate_corrupt_addons_on_install() {
		$this->check_environment();
		$response = $this->addon->setAddonSlug( 'add-on-number-8' )->install();
		$this->assertFalse( $response );
		$this->assertEquals( 'Add-On structure have problem , Please download it again', $this->addon->getMessage()['message'] );
		$this->assertEquals( 0, count( $this->addon->getAddon( 'add-on-number-8' ) ) );
	}
	public function test_it_can_check_not_exists_addons_on_uninstall() {
		$this->check_environment();
		$response = $this->addon->setAddonSlug( 'add-on-number-11' )->uninstall();
		$this->assertFalse( $response );
		$this->assertEquals( 'The Add-On you are looking for is not exist.', $this->addon->getMessage()['message'] );
	}
	public function test_it_can_remove_an_addons_on_uninstall() {
		$this->check_environment();
		$this->test_it_can_download_and_activate_addon_on_install();
		$response = $this->addon->setAddonSlug( 'add-on-number-9' )->uninstall();
		$this->assertTrue( $response );
		$this->assertEquals( 'Add-on removed successfully', $this->addon->getMessage()['message'] );
		$this->assertEquals( 0, count( $this->addon->getAddon( 'add-on-number-9' ) ) );
	}
	public function test_it_can_get_addon_file_name() {
		$this->check_environment();
		$response = $this->addon->getAddOnFileName( 'add-on-number-9' );
		$this->assertEquals( 'add-on-number-9.zip', $response );
	}
	public function test_it_can_get_addon_full_path() {
		$this->check_environment();
		$this->test_it_can_download_and_activate_addon_on_install();
		$response = $this->addon->getAddonFullPath( 'add-on-number-9' );
		$this->assertEquals( $this->addon->getAddonDirectory() . 'add-on-number-9', $response );

		$response = $this->addon->getAddonFullPath( 'add-on-number-a' );
		$this->assertFalse( $response );

	}
	public function test_it_can_get_addon_data() {
		$this->check_environment();
		$this->test_it_can_download_and_activate_addon_on_install();
		$local = $this->addon->getAddon( 'add-on-number-9' );
		$this->assertTrue( array_key_exists( 'Version', $local['add-on-number-9'] ) );

		$local = $this->addon->getAddon( 'add-on-number-a' );
		$this->assertTrue( count( $local ) == 0 );
	}
	public function test_it_can_validate_data_on_update_addon() {
		$this->check_environment();
		$response = $this->addon->setAddonSlug( 'add-on-number-4' )->update();
		$this->assertFalse( $response );
		$this->assertEquals( 'Add-on you are looking for is not exist in API side', $this->addon->getMessage()['message'] );

		$response = $this->addon->setAddonSlug( 'add-on-number-6' )->install();
		$this->assertTrue( $response );

		$response = $this->addon->setAddonSlug( 'add-on-number-6' )->update();
		$this->assertFalse( $response );
		$this->assertEquals( 'You have latest version of this add-on.', $this->addon->getMessage()['message'] );
		$response = $this->addon->setAddonSlug( 'add-on-number-6' )->uninstall();
		$this->assertTrue( $response );
	}
	public function test_it_can_update_addon() {
		$this->check_environment();
		$response = $this->addon->setAddonSlug( 'add-on-number-9' )->install();
		$this->assertTrue( $response );
		$response = $this->addon->setAddonSlug( 'add-on-number-9' )->update();
		$this->assertTrue( $response );
		$this->assertEquals( 'Add-on updated successfully', $this->addon->getMessage()['message'] );
		$response = $this->addon->setAddonSlug( 'add-on-number-9' )->uninstall();
		$this->assertTrue( $response );
	}
	public function check_environment() {
		Abb_Logic_Helpers::checkPermAndCreate($this->addon->getAddonDirectory() , 0775);
		if ( ! is_writeable( $this->addon->getAddonDirectory() ) ) {
			$this->markTestSkipped( 'This test dont have right situation to execute.' );
		}
	}

	// Remove sample records that insert in database
	public static function tearDownAfterClass() {
	}
}
