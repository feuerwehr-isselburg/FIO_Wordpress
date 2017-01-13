<?php
class validation_test extends WP_UnitTestCase {

	private $validation;
	public function setUp() {
		$this->validation = new Mk_Validator;
	}
	public function test_it_can_validate_not_passing_feild_name() {
		$response = $this->validation->setValue( 'reza' )->run( 'required:true' );
		$this->assertFalse( $response );
		$this->assertEquals( 'You must pass a field name for executing validation', $this->validation->getMessage() );
	}
	public function test_it_can_validate_required_value() {
		$response = $this->validation->setValue()->setFieldName( 'Name' )->run( 'required:true' );
		$this->assertFalse( $response );
		$this->assertEquals( 'The Name field is required.', $this->validation->getMessage() );
	}
	public function test_it_can_validate_string_check() {
		$response = $this->validation->setValue( 2 )->setFieldName( 'Name' )->run( 'required:true,string:true' );
		$this->assertFalse( $response );
		$this->assertEquals( 'The Name field must have an string value.', $this->validation->getMessage() );

		$response = $this->validation->setValue( 'reza' )->setFieldName( 'Name' )->run( 'required:true,string:true' );
		$this->assertTrue( $response );
		$this->assertEquals( '', $this->validation->getMessage() );
	}
	public function test_it_can_validate_array_check() {
		$response = $this->validation->setValue( 2 )->setFieldName( 'Name' )->run( 'array:true' );
		$this->assertFalse( $response );
		$this->assertEquals( 'The Name field must be an array with at least one element.', $this->validation->getMessage() );

		$response = $this->validation->setValue( 'reza' )->setFieldName( 'Name' )->run( 'array:true' );
		$this->assertFalse( $response );
		$this->assertEquals( 'The Name field must be an array with at least one element.', $this->validation->getMessage() );

		$response = $this->validation->setValue( array() )->setFieldName( 'Name' )->run( 'array:true' );
		$this->assertFalse( $response );
		$this->assertEquals( 'The Name field must be an array with at least one element.', $this->validation->getMessage() );

		$response = $this->validation->setValue( array( 'reza' ) )->setFieldName( 'Name' )->run( 'array:true' );
		$this->assertTrue( $response );
		$this->assertEquals( '', $this->validation->getMessage() );
	}

	public function test_it_can_check_min_len() {
		$response = $this->validation->setValue( 'reza' )->setFieldName( 'Name' )->run( 'required:true,string:true,min_len:5' );
		$this->assertFalse( $response );
		$this->assertEquals( 'The Name field must be at least 5 characters in length.', $this->validation->getMessage() );

		$response = $this->validation->setValue( 'reza' )->setFieldName( 'Name' )->run( 'required:true,string:true,min_len:3' );
		$this->assertTrue( $response );
		$this->assertEquals( '', $this->validation->getMessage() );
	}

	public function test_it_can_check_max_len() {
		$response = $this->validation->setValue( 'reza' )->setFieldName( 'Name' )->run( 'required:true,string:true,max_len:2' );
		$this->assertFalse( $response );
		$this->assertEquals( 'The Name field cannot exceed 2 characters in length.', $this->validation->getMessage() );

		$response = $this->validation->setValue( 'reza' )->setFieldName( 'Name' )->run( 'required:true,string:true,max_len:4' );
		$this->assertTrue( $response );
		$this->assertEquals( '', $this->validation->getMessage() );
	}

	public function test_it_can_check_exact_len_check() {
		$response = $this->validation->setValue( 'reza' )->setFieldName( 'Name' )->run( 'required:true,string:true,exact_len:5' );
		$this->assertFalse( $response );
		$this->assertEquals( 'The Name field must be exactly 5 characters in length.', $this->validation->getMessage() );

		$response = $this->validation->setValue( 'reza' )->setFieldName( 'Name' )->run( 'required:true,string:true,exact_len:4' );
		$this->assertTrue( $response );
		$this->assertEquals( '', $this->validation->getMessage() );
	}

	public function test_it_can_check_int_check() {
		$response = $this->validation->setValue( 'reza' )->setFieldName( 'Name' )->run( 'required:true,int:true' );
		$this->assertFalse( $response );
		$this->assertEquals( 'The Name field must contain an integer.', $this->validation->getMessage() );

		$response = $this->validation->setValue( 2 )->setFieldName( 'Name' )->run( 'required:true,int:true' );
		$this->assertTrue( $response );
		$this->assertEquals( '', $this->validation->getMessage() );
	}

	public function test_it_can_check_mixup_validation() {
		$response = $this->validation->setValue( 'reza@artbees' )->setFieldName( 'Name' )->run( 'required:true,string:true,min_len:13,max_len:20' );
		$this->assertFalse( $response );
		$this->assertEquals( 'The Name field must be at least 13 characters in length.', $this->validation->getMessage() );

		$response = $this->validation->setValue( 'reza@artbees' )->setFieldName( 'Name' )->run( 'required:true,string:true,min_len:11,max_len:11' );
		$this->assertFalse( $response );
		$this->assertEquals( 'The Name field cannot exceed 11 characters in length.', $this->validation->getMessage() );

		$response = $this->validation->setValue( 'reza@artbees' )->setFieldName( 'Name' )->run( 'required:true,string:true,min_len:13,max_len:11' );
		$this->assertFalse( $response );
		$this->assertEquals( 'The Name field must be at least 13 characters in length.The Name field cannot exceed 11 characters in length.', $this->validation->getMessage() );

		$response = $this->validation->setValue( 'reza@artbees' )->setFieldName( 'Name' )->run( 'required:true,string:true,min_len:10,max_len:20' );
		$this->assertTrue( $response );
		$this->assertEquals( '', $this->validation->getMessage() );
	}

	// Remove sample records that insert in database
	public static function tearDownAfterClass() {
	}
}
