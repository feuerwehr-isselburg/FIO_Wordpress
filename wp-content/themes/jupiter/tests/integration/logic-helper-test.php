<?php
use org\bovigo\vfs\vfsStream;
class logic_helper_test extends WP_Ajax_UnitTestCase {

	public static function setUpBeforeClass() {
		Abb_Logic_Helpers::deleteFileNDir( __DIR__ . '/sample' );
	}
	public function setUp() {
	}
	public function test_it_can_validate_zip_file_address() {
		try {
			Abb_Logic_Helpers::unZip( 'sample.zip', '/reza/' );
		} catch (Exception $e) {
			$this->assertEquals( 'Zip file that you are looking for is not exist', $e->getMessage() );
		}
	}
	public function test_it_can_validate_dest_file_address() {
		try {
			// Create a zip file
			$zip_path = $this->create_zip_file();

			// Create a sample Directory
			vfsStream::setup( 'upload-notwritable', 0444 );
			$des_path = vfsStream::url( 'upload-notwritable' );
			Abb_Logic_Helpers::unZip( $zip_path, $des_path );
		} catch (Exception $e) {
			$this->assertEquals( 'Destination path is not writable , Please resolve this issue first.', $e->getMessage() );
		}

		Abb_Logic_Helpers::deleteFileNDir( __DIR__ . '/sample' );
	}

	public function test_unzip_file() {
		$zip_path = $this->create_zip_file();
		// Create a zip file
		$this->assertTrue( Abb_Logic_Helpers::unZip( $zip_path, __DIR__ . '/sample' ) );
		Abb_Logic_Helpers::deleteFileNDir( __DIR__ . '/sample' );
	}
	public function test_check_n_create_for_exist_not_writable_directory() {
		try {
			vfsStream::setup( 'upload-notwritable', 0444 );
			$des_path = vfsStream::url( 'upload-notwritable' );
			Abb_Logic_Helpers::checkPermAndCreate( $des_path );
		} catch (Exception $e) {
			$this->assertEquals( $des_path . ' directory is not writable', $e->getMessage() );
		}
	}
	public function test_check_n_create_for_exist_writable_directory() {
		vfsStream::setup( 'upload-exist-writable' );
		$des_path = vfsStream::url( 'upload-exist-writable' );
		$this->assertTrue( Abb_Logic_Helpers::checkPermAndCreate( $des_path ) );
	}
	public function test_check_n_create_for_not_exist_not_writable_directory() {
		try {
			vfsStream::setup( 'upload-notwritable', 0444 );
			$des_path = vfsStream::url( 'upload-notwritable/reza/' );
			Abb_Logic_Helpers::checkPermAndCreate( $des_path );
		} catch (Exception $e) {
			$this->assertEquals( "Can't create $des_path directory", $e->getMessage() );
		}
	}
	public function test_check_n_create_for_not_exist_writable_directory() {
		vfsStream::setup( 'upload-not-exist-writable' );
		$des_path = vfsStream::url( 'upload-not-exist-writable/reza/' );
		$this->assertTrue( Abb_Logic_Helpers::checkPermAndCreate( $des_path ) );
	}
	public function test_upload_from_url_remote_file_check() {
		try {
			Abb_Logic_Helpers::uploadFromURL( 'http://google.com/reza.zip', 'testtest1.zip', __DIR__ . '/sample/' );
		} catch (Exception $e) {
			$this->assertEquals( "Can't download source file.", $e->getMessage() );
		}
	}
	public function test_upload_from_url_dest_upload_check() {
		try {
			vfsStream::setup( 'upload-notwritable', 0444 );
			$dest_directory = vfsStream::url( 'upload-notwritable/reza/' );
			Abb_Logic_Helpers::uploadFromURL( 'http://www.google.com', 'testtest2.zip', $dest_directory );
		} catch (Exception $e) {
			$this->assertEquals( sprintf( 'Destination directory is not ready for upload . {%s}', $dest_directory ), $e->getMessage() );
		}
	}
	public function test_writable_owner_check_for_writable_directory() {
		mkdir( __DIR__ . '/writable_check/', 0755 );
		$this->assertTrue( Abb_Logic_Helpers::writableOwnerCheck( __DIR__ . '/writable_check/' ) );
	}
	public function test_writable_owner_check_for_no_writable_directory() {
		mkdir( __DIR__ . '/nowritable_check/', 0444 );
		$this->assertFalse( Abb_Logic_Helpers::writableOwnerCheck( __DIR__ . '/nowritable_check/' ) );
	}
	public function test_delete_file_n_dir() {
		mkdir( __DIR__ . '/delete_check/' );
		mkdir( __DIR__ . '/delete_check/reza' );
		mkdir( __DIR__ . '/delete_check/reza/sample' );
		file_put_contents( __DIR__ . '/delete_check/reza/sample/reza.txt', 'blahasdasda' );
		file_put_contents( __DIR__ . '/delete_check/reza/reza.txt', 'blahasdasda' );
		file_put_contents( __DIR__ . '/delete_check/reza.txt', 'blahasdasda' );
		$this->assertTrue( Abb_Logic_Helpers::deleteFileNDir( __DIR__ . '/delete_check/' ) );
		$this->assertFalse( file_exists( __DIR__ . '/delete_check/' ) );
	}
	public function test_remote_url_header_check_for_not_exist_header() {
		$response = Abb_Logic_Helpers::remoteURLHeaderCheck( 'http://google.com/reza.zip' );
		$this->assertFalse( $response );
	}
	public function test_remote_url_header_check_for_exist_header() {
		$response = Abb_Logic_Helpers::remoteURLHeaderCheck( 'http://www.google.com' );
		$this->assertTrue( $response );
	}
	public function test_zip_function() {
		$this->create_zip_file();
		Abb_Logic_Helpers::deleteFileNDir( __DIR__ . '/sample' );
	}
	public function create_zip_file() {
		mkdir( __DIR__ . '/sample/', 0755 );
		$file_path = __DIR__ . '/sample/reza.txt';
		$zip_path  = __DIR__ . '/sample/reza.zip';
		$data      = 'Some random data that i dont care about';
		file_put_contents( $file_path, $data );
		$this->assertEquals( $data, file_get_contents( $file_path ) );
		$this->assertTrue( Abb_Logic_Helpers::zip( array( $file_path ), $zip_path ) );
		$this->assertTrue( unlink( $file_path ) );
		return $zip_path;
	}
	// Remove sample records that insert in database
	public static function tearDownAfterClass() {
		Abb_Logic_Helpers::deleteFileNDir( __DIR__ . '/writable_check/' );
		Abb_Logic_Helpers::deleteFileNDir( __DIR__ . '/nowritable_check/' );
		Abb_Logic_Helpers::deleteFileNDir( __DIR__ . '/sample' );
	}
}
