<?php
use org\bovigo\vfs\vfsStream;
class template_management_test extends WP_Ajax_UnitTestCase {

	protected $tm;
	protected $stub;
	protected $is_test_env_ready;
	protected $template_name = 'adrastea';
	public static function setUpBeforeClass() {
		$api_key = 'f1b7ac652a000e43dfcaa2b5435fab26f786ed842f52c625ca57f716835ad72e';
		update_option( 'artbees_api_key', $api_key );
	}
	public function setUp() {
		$csc = $this->getMockBuilder( 'mk_template_managememnt' )
			->setConstructorArgs( array( true, false ) )
			->setMethods( array( 'getTemplateDownloadLink' ) )
			->getMock();

		// Tell the `handleValue` method to return 'bla'
		//
		$map = array(
			array( $this->template_name, 'download', 'http://static-cdn.artbees.net/phpunit/' . $this->template_name . '.zip' ),
			array( $this->template_name, 'filename', $this->template_name . '.zip' ),
			array( 'blahblah', 'download', 'http://static-cdn.artbees.net/phpunit/blahblah.zip' ),
			array( 'blahblah', 'filename', $this->template_name . 'blahblah.zip' ),
		);
		$csc->method( 'getTemplateDownloadLink' )
			->will( $this->returnValueMap( $map ) );
		// $this->tm = new mk_template_managememnt(true, false);
		$this->tm = $csc;
	}
	public function test_it_can_validate_data_on_lazy_load_both_field() {
		$this->tm->abbTemplatelazyLoadFromApi();
		$response = $this->tm->getMessage();
		$this->assertEquals( 'System problem , please contact support', $response['message'] );
	}
	public function test_it_can_validate_data_on_lazy_load_to_field() {
		$_POST['from'] = 0;
		$this->tm->abbTemplatelazyLoadFromApi();
		$response = $this->tm->getMessage();
		$this->assertEquals( 'System problem , please contact support', $response['message'] );
	}
	public function test_it_can_validate_data_on_lazy_load_from_field() {
		$_POST['count'] = 10;
		$this->tm->abbTemplatelazyLoadFromApi();
		$response = $this->tm->getMessage();
		$this->assertEquals( 'System problem , please contact support', $response['message'] );
	}
	public function test_it_can_validate_data_on_lazy_load() {
		$_POST['from']  = 0;
		$_POST['count'] = 10;
		$this->tm->abbTemplatelazyLoadFromApi();
		$response = $this->tm->getMessage();
		$this->assertTrue( is_array( $response['data'] ) );
		if ( count( $response['data'] ) > 0 ) {
			foreach ( $response['data'] as $key => $value ) {
				$this->assertTrue( isset( $value->name ) );
				$this->assertTrue( isset( $value->slug ) );
				$this->assertTrue( isset( $value->last_update ) );
				$this->assertTrue( isset( $value->category ) );
				$this->assertTrue( isset( $value->url ) );
				$this->assertTrue( isset( $value->img_url ) );
				$this->assertTrue( isset( $value->installed ) );
			}
		}
	}
	public function test_it_can_validate_not_writable_directory() {
		vfsStream::setup( 'upload-notwritable', 0444 );
		$path = vfsStream::url( 'upload-notwritable' );

		$this->tm->setUploadDir( $path );
		$this->tm->setBasePath( $path . '/mk_templates/' );

		$response = $this->tm->isWritable( $this->tm->getBasePath() );
		$this->assertFalse( $response );
	}
	public function test_it_can_validate_data_on_upload_to_server() {
		$this->tm->uploadTemplateToServer( '' );
		$response = $this->tm->getMessage();
		$this->assertEquals( 'Choose one template first', $response['message'] );
	}

	public function test_it_can_upload_to_server() {
		vfsStream::setup( 'upload-writable', 0755 );
		$path = vfsStream::url( 'upload-writable' );
		if (
			$this->tm->checkRemoteFileExistence( $this->tm->getTemplateDownloadLink( $this->template_name, 'download' ) ) == false ||
			! is_writeable( $path )
		) {
			$this->markTestSkipped( 'This test dont have right situation to execute.' );
		}

		$this->tm->setUploadDir( $path );
		$this->tm->setBasePath( $path . '/mk_templates/' );

		$this->tm->uploadTemplateToServer( $this->template_name );
		$response = $this->tm->getMessage();
		$this->assertEquals( 'Uploaded to server', $response['message'] );
		$zip_file_addr = $this->tm->getBasePath() . $this->tm->getTemplateDownloadLink( $this->template_name, 'filename' );
		$this->assertTrue( file_exists( $zip_file_addr ) );
	}
	public function test_it_can_validate_template_url() {
		$this->tm->uploadTemplateToServer( 'blah' );
		$response = $this->tm->getMessage();
		$this->assertEquals( 'Template source URL is not validate', $response['message'] );
	}
	public function test_it_can_check_destination_file() {
		$this->tm->uploadTemplateToServer( 'blahblah' );
		$response = $this->tm->getMessage();
		$this->assertEquals( "Can't download template source file.", $response['message'] );
	}
	public function test_it_can_unzip_template() {
		$this->test_it_can_upload_to_server();

		// Unzip
		$zip_file_addr = $this->tm->getBasePath() . $this->tm->getTemplateDownloadLink( $this->template_name, 'filename' );
		$this->tm->unzipTemplateInServer( $this->template_name );
		$response = $this->tm->getMessage();
		$this->assertEquals( 'Compeleted', $response['message'] );
		$this->assertFalse( file_exists( $zip_file_addr ) );
		$this->assertTrue( file_exists( $this->tm->getBasePath() . $this->template_name ) );
	}
	public function test_it_can_get_error_on_unzip_problem() {
		$this->test_it_can_upload_to_server();

		// Unzip
		$zip_file_addr = $this->tm->getBasePath() . $this->tm->getTemplateDownloadLink( $this->template_name, 'filename' );
		file_put_contents( $zip_file_addr, 'blahblahblah' );
		$this->tm->unzipTemplateInServer( $this->template_name );
		$response = $this->tm->getMessage();
		$this->assertEquals( 'Incompatible Archive.', $response['message'] );
		$this->assertTrue( file_exists( $zip_file_addr ) );
		$this->assertFalse( file_exists( $this->tm->getBasePath() . $this->template_name ) );
	}
	public function test_it_can_validate_template_files() {
		$this->test_it_can_unzip_template();
		$this->tm->validateTemplateFiles( $this->template_name );
		$response = $this->tm->getMessage();
		$this->assertEquals( 'Compeleted', $response['message'] );
	}
	public function test_it_can_validate_failed_template_content_path() {
		$this->test_it_can_unzip_template();
		unlink( $this->tm->getAssetsAddress( 'template_content_path', $this->template_name ) );
		$this->tm->validateTemplateFiles( $this->template_name );
		$response = $this->tm->getMessage();
		$this->assertEquals( 'Template assets are not completely exist - p2, Contact support.', $response['message'] );
	}
	public function test_it_can_validate_failed_template_widget_path() {
		$this->test_it_can_unzip_template();
		unlink( $this->tm->getAssetsAddress( 'widget_path', $this->template_name ) );
		$this->tm->validateTemplateFiles( $this->template_name );
		$response = $this->tm->getMessage();
		$this->assertEquals( 'Template assets are not completely exist - p2, Contact support.', $response['message'] );
	}
	public function test_it_can_validate_failed_template_options_path() {
		$this->test_it_can_unzip_template();
		unlink( $this->tm->getAssetsAddress( 'options_path', $this->template_name ) );
		$this->tm->validateTemplateFiles( $this->template_name );
		$response = $this->tm->getMessage();
		$this->assertEquals( 'Template assets are not completely exist - p2, Contact support.', $response['message'] );
	}
	public function test_it_can_validate_failed_template_json_path() {
		$this->test_it_can_unzip_template();
		unlink( $this->tm->getAssetsAddress( 'json_path', $this->template_name ) );
		$this->tm->validateTemplateFiles( $this->template_name );
		$response = $this->tm->getMessage();
		$this->assertEquals( 'Template assets are not completely exist - p2, Contact support.', $response['message'] );
	}
	public function test_it_can_import_menu_locations() {
		$this->test_it_can_validate_template_files();
		$this->tm->importMenuLocations( $this->template_name );
		$response = $this->tm->getMessage();
		$this->assertEquals( 'Navigation locations is configured.', $response['message'] );
	}
	public function test_it_can_set_up_pages() {
		$this->test_it_can_validate_template_files();
		$this->tm->setUpPages( $this->template_name );
		$response = $this->tm->getMessage();
		$this->assertEquals( 'Setup pages completed.', $response['message'] );
	}
	public function test_it_import_theme_options() {
		$this->test_it_can_validate_template_files();
		$this->tm->importThemeOptions( $this->template_name );
		$response = $this->tm->getMessage();
		$this->assertEquals( 'Theme options are imported.', $response['message'] );
	}
	public function test_it_import_theme_widgets() {
		$this->test_it_can_validate_template_files();
		$this->tm->importThemeWidgets( $this->template_name );
		$response = $this->tm->getMessage();
		$this->assertEquals( 'Widgets are imported.', $response['message'] );
	}
	public function test_it_finilize_importing() {
		$this->test_it_can_validate_template_files();
		$this->tm->finilizeImporting( $this->template_name );
		$response = $this->tm->getMessage();
		$this->assertEquals( 'Data imported successfully', $response['message'] );
		$this->assertEquals( get_option( 'jupiter_template_installed' ), $this->template_name );
	}
	// Remove sample records that insert in database
	public static function tearDownAfterClass() {
		update_option( 'artbees_api_key', '' );
	}
}
