<?php
use org\bovigo\vfs\vfsStream;
class CompatibilityTest extends PHPUnit_Framework_TestCase {

	private $root;
	private $compat;

	public function setUp() {
		$this->compat = new Compatibility();
	}
	public function test_it_can_check_and_create_new_directory() {
		vfsStream::setup( 'upload-writable' );
		$path = vfsStream::url( 'upload-writable' );
		$this->compat->setTemplateDir( $path );
		$result = $this->compat->checkAndCreate( $this->compat->getTemplateDir() );
		$this->assertEquals( array( 'status' => true ), $result );
	}
	public function test_it_can_check_for_not_writable_directory() {
		vfsStream::setup( 'upload-notwritable', 0444 );
		$path = vfsStream::url( 'upload-notwritable' );
		$this->compat->setTemplateDir( $path );
		$result  = $this->compat->checkAndCreate( $this->compat->getTemplateDir() );
		$message = [
			'sys_msg'       => $path . ' directory is not writable, ',
			'sys_recommend' => 'Set read/write (0775) permission for this directory .',
			'link_href'     => '',
			'link_title'    => '',
			'type'          => 'error',
			'status'        => false,
		];
		$this->assertEquals( $message, $result );
	}
	public function test_it_can_check_writable_directories() {
		vfsStream::setup( 'upload-writable' );
		$path = vfsStream::url( 'upload-writable' );
		$this->compat->setTemplateDir( $path );
		$result = $this->compat->isWritable( $this->compat->getTemplateDir() );
		$this->assertTrue( $result );
	}
	public function test_it_can_check_unwritable_directories() {
		vfsStream::setup( 'upload-notwritable', 0444 );
		$path = vfsStream::url( 'upload-notwritable' );
		$this->compat->setTemplateDir( $path );
		$result = $this->compat->isWritable( $this->compat->getTemplateDir() );
		$this->assertFalse( $result );
	}
	public function test_it_can_check_max_execution_time_error() {
		ini_set( 'max_execution_time' , 20 );
		$response = $this->compat->phpIniCheck();
		$this->assertTrue( count( $response ) > 0 );
		foreach ( $response as $key => $value ) {
			if ( strpos( $value['sys_msg'] , 'Maximum Execution' ) !== false ) {
				break;
			}
		}

		$this->assertTrue( strpos( $value['sys_msg'],'20 seconds' ) !== false );
		$this->assertTrue( strpos( $value['sys_recommend'],'least 60 second' ) !== false );
		$this->assertTrue( $value['type'] == 'error' );
	}

	public function test_it_can_check_max_execution_time_warning() {
		ini_set( 'max_execution_time' , 45 );
		$response = $this->compat->phpIniCheck();
		$this->assertTrue( count( $response ) > 0 );
		foreach ( $response as $key => $value ) {
			if ( strpos( $value['sys_msg'] , 'Maximum Execution' ) !== false ) {
				break;
			}
		}

		$this->assertTrue( strpos( $value['sys_msg'],'45 seconds' ) !== false );
		$this->assertTrue( strpos( $value['sys_recommend'],'least 60 second' ) !== false );
		$this->assertTrue( $value['type'] == 'warning' );
	}

	public function test_it_can_check_max_input_time() {
		$max_input_time = ini_get( 'max_input_time' );
		if ( $max_input_time < 60 && $max_input_time > 0 ) {
			$response = $this->compat->phpIniCheck();
			$this->assertTrue( count( $response ) > 0 );
			foreach ( $response as $key => $value ) {
				if ( strpos( $value['sys_msg'] , 'Maximum Input Time' ) !== false ) {
					break;
				}
			}
			$this->assertTrue( strpos( $value['sys_msg'],$max_input_time . ' seconds' ) !== false );
			$this->assertTrue( strpos( $value['sys_recommend'],'least 60 second' ) !== false );
		} else {
			$this->markTestSkipped( 'This test dont have right situation to execute. please change max_input_time in php.ini' );
		}
	}
	public function test_it_can_check_upload_max_file_size() {
		$upload_max_file_size = $this->compat->let_to_num( ini_get( 'upload_max_filesize' ) );
		if ( $upload_max_file_size < 10485760 ) {
			$response = $this->compat->phpIniCheck();
			$this->assertTrue( count( $response ) > 0 );
			foreach ( $response as $key => $value ) {
				if ( strpos( $value['sys_msg'] , 'Maximum Post Size' ) !== false ) {
					break;
				}
			}
			$this->assertTrue( strpos( $value['sys_msg'],'Maximum Post Size' ) !== false );
			$this->assertTrue( strpos( $value['sys_recommend'],'be at least 30MB' ) !== false );
			$this->assertTrue( strpos( $value['type'],'error' ) !== false );
		} else {
			$this->markTestSkipped( 'This test dont have right situation to execute. please change max_input_time in php.ini' );
		}
	}
	public function test_it_can_check_max_input_vars() {
		$max_input_vars = $this->compat->let_to_num( ini_get( 'max_input_vars' ) );
		if ( $max_input_vars < 4000 ) {
			$response = $this->compat->phpIniCheck();
			$this->assertTrue( count( $response ) > 0 );
			foreach ( $response as $key => $value ) {
				if ( strpos( $value['sys_msg'] , 'Maximum Input Vars' ) !== false ) {
					break;
				}
			}
			$this->assertTrue( strpos( $value['sys_msg'],'Maximum Input Vars' ) !== false );
			$this->assertTrue( strpos( $value['sys_recommend'],'should be at least 4000' ) !== false );
			$this->assertTrue( strpos( $value['type'],'error' ) !== false );
		} else {
			$this->markTestSkipped( 'This test dont have right situation to execute. please change max_input_vars in php.ini' );
		}
	}

	public function test_it_can_check_suhosin_post_max_vars() {
		$suhosin_post_max_vars = ini_get( 'suhosin.post.max_vars' );
		if ( $suhosin_post_max_vars != '' && $suhosin_post_max_vars < 4000 ) {
			$response = $this->compat->phpIniCheck();
			$this->assertTrue( count( $response ) > 0 );
			foreach ( $response as $key => $value ) {
				if ( strpos( $value['sys_msg'] , 'Your are running Suhosin, and your current' ) !== false ) {
					break;
				}
			}
			$this->assertTrue( strpos( $value['sys_msg'],'Your are running Suhosin, and your current' ) !== false );
			$this->assertTrue( strpos( $value['sys_recommend'],'should be at least 4000.' ) !== false );
			$this->assertTrue( strpos( $value['type'],'warning' ) !== false );
		} else {
			$this->markTestSkipped( 'This test dont have right situation to execute. please change suhosin.post.max_vars in php.ini' );
		}
	}

	public function test_it_can_convert_character_to_number()
	{
		$this->assertTrue($this->compat->let_to_num('32M') == 33554432);
		$this->assertTrue($this->compat->let_to_num('64M') == 67108864);
	}
}
