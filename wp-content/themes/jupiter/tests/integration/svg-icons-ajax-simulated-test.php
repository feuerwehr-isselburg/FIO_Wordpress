<?php
class Svg_Icons_Ajax_Simulated_Test extends WP_Ajax_UnitTestCase {
	public function test_it_can_validate_empty_start_point_in_get_icons_list() {
		try {
			$this->_handleAjax( 'mk_get_icons_list' );
		} catch ( WPAjaxDieStopException $e ) {
		}
		$this->assertTrue( isset( $e ) );
		$response = json_decode( $e->getMessage() , true );
		$this->assertTrue( count( $response ) > 0 );
		$this->assertEquals( 'Pagination start point is not valued correctly.' , $response['message'] );
		$this->assertFalse( $response['status'] );
		$this->assertTrue( (bool) count( $response['data'] == 0 ) );
	}

	public function test_it_can_validate_int_start_point_in_get_icons_list() {
		$_POST['pagination_start'] = 'blah blah';
		try {
			$this->_handleAjax( 'mk_get_icons_list' );
		} catch ( WPAjaxDieStopException $e ) {
		}
		$this->assertTrue( isset( $e ) );
		$response = json_decode( $e->getMessage() , true );
		$this->assertTrue( count( $response ) > 0 );
		$this->assertEquals( 'Pagination start point is not valued correctly.' , $response['message'] );
		$this->assertFalse( $response['status'] );
		$this->assertTrue( (bool) count( $response['data'] == 0 ) );
	}
	public function test_it_can_validate_empty_count_point_in_get_icons_list() {
		$_POST['pagination_start'] = 0;
		try {
			$this->_handleAjax( 'mk_get_icons_list' );
		} catch ( WPAjaxDieStopException $e ) {
		}
		$this->assertTrue( isset( $e ) );
		$response = json_decode( $e->getMessage() , true );
		$this->assertTrue( count( $response ) > 0 );
		$this->assertEquals( 'Pagination count point is not valued correctly.' , $response['message'] );
		$this->assertFalse( $response['status'] );
		$this->assertTrue( (bool) count( $response['data'] == 0 ) );
	}

	public function test_it_can_validate_int_count_point_in_get_icons_list() {
		$_POST['pagination_start'] = 0;
		$_POST['pagination_count'] = 'blah blah';
		try {
			$this->_handleAjax( 'mk_get_icons_list' );
		} catch ( WPAjaxDieStopException $e ) {
		}
		$this->assertTrue( isset( $e ) );
		$response = json_decode( $e->getMessage() , true );
		$this->assertTrue( count( $response ) > 0 );
		$this->assertEquals( 'Pagination count point is not valued correctly.' , $response['message'] );
		$this->assertFalse( $response['status'] );
		$this->assertTrue( (bool) count( $response['data'] == 0 ) );
	}
	public function test_it_can_validate_not_valid_int_count_point_in_get_icons_list() {
		$_POST['pagination_start'] = 0;
		$_POST['pagination_count'] = 0;
		try {
			$this->_handleAjax( 'mk_get_icons_list' );
		} catch ( WPAjaxDieStopException $e ) {
		}
		$this->assertTrue( isset( $e ) );
		$response = json_decode( $e->getMessage() , true );
		$this->assertTrue( count( $response ) > 0 );
		$this->assertEquals( 'Pagination count point is not valued correctly.' , $response['message'] );
		$this->assertFalse( $response['status'] );
		$this->assertTrue( (bool) count( $response['data'] == 0 ) );
	}
	public function test_it_can_validate_null_icon_family_in_get_icons_list() {
		$_POST['pagination_start'] = 0;
		$_POST['pagination_count'] = 10;
		try {
			$this->_handleAjax( 'mk_get_icons_list' );
		} catch ( WPAjaxDieStopException $e ) {
		}
		$this->assertTrue( isset( $e ) );
		$response = json_decode( $e->getMessage() , true );
		$this->assertTrue( count( $response ) > 0 );
		$this->assertEquals( 'Icon family is not valued correctly.' , $response['message'] );
		$this->assertFalse( $response['status'] );
		$this->assertTrue( (bool) count( $response['data'] == 0 ) );
	}
	public function test_it_can_validate_empty_icon_family_in_get_icons_list() {
		$_POST['pagination_start'] = 0;
		$_POST['pagination_count'] = 10;
		$_POST['icon_family'] = '';
		try {
			$this->_handleAjax( 'mk_get_icons_list' );
		} catch ( WPAjaxDieStopException $e ) {
		}
		$this->assertTrue( isset( $e ) );
		$response = json_decode( $e->getMessage() , true );
		$this->assertTrue( count( $response ) > 0 );
		$this->assertEquals( 'Icon family is not valued correctly.' , $response['message'] );
		$this->assertFalse( $response['status'] );
		$this->assertTrue( (bool) count( $response['data'] == 0 ) );
	}
	public function test_it_can_validate_existence_icon_family_in_get_icons_list() {
		$_POST['pagination_start'] = 0;
		$_POST['pagination_count'] = 10;
		$_POST['icon_family'] = 'reza';
		try {
			$this->_handleAjax( 'mk_get_icons_list' );
		} catch ( WPAjaxDieStopException $e ) {
		}

		$this->assertTrue( isset( $e ) );
		$response = json_decode( $e->getMessage() , true );
		$this->assertTrue( count( $response ) > 0 );
		$this->assertEquals( 'Icon family that you choosed is not exist.' , $response['message'] );
		$this->assertFalse( $response['status'] );
		$this->assertTrue( (bool) count( $response['data'] == 0 ) );
	}



	public function test_it_can_validate_empty_start_point_in_search_icon_by_name() {
		try {
			$this->_handleAjax( 'mk_search_icons_by_name' );
		} catch ( WPAjaxDieStopException $e ) {
		}

		$this->assertTrue( isset( $e ) );
		$response = json_decode( $e->getMessage() , true );
		$this->assertTrue( count( $response ) > 0 );
		$this->assertEquals( 'Pagination start point is not valued correctly.' , $response['message'] );
		$this->assertFalse( $response['status'] );
		$this->assertTrue( (bool) count( $response['data'] == 0 ) );
	}
	public function test_it_can_validate_int_start_point_in_search_icon_by_name() {
		$_POST['pagination_start'] = 'blah blah';
		try {
			$this->_handleAjax( 'mk_search_icons_by_name' );
		} catch ( WPAjaxDieStopException $e ) {
		}

		$this->assertTrue( isset( $e ) );
		$response = json_decode( $e->getMessage() , true );
		$this->assertTrue( count( $response ) > 0 );
		$this->assertEquals( 'Pagination start point is not valued correctly.' , $response['message'] );
		$this->assertFalse( $response['status'] );
		$this->assertTrue( (bool) count( $response['data'] == 0 ) );
	}
	public function test_it_can_validate_empty_count_point_in_search_icon_by_name() {
		$_POST['pagination_start'] = 0;
		try {
			$this->_handleAjax( 'mk_search_icons_by_name' );
		} catch ( WPAjaxDieStopException $e ) {
		}

		$this->assertTrue( isset( $e ) );
		$response = json_decode( $e->getMessage() , true );
		$this->assertTrue( count( $response ) > 0 );
		$this->assertEquals( 'Pagination count point is not valued correctly.' , $response['message'] );
		$this->assertFalse( $response['status'] );
		$this->assertTrue( (bool) count( $response['data'] == 0 ) );
	}
	public function test_it_can_validate_int_count_point_in_search_icon_by_name() {
		$_POST['pagination_start'] = 0;
		$_POST['pagination_count'] = 'blah blah';
		try {
			$this->_handleAjax( 'mk_search_icons_by_name' );
		} catch ( WPAjaxDieStopException $e ) {
		}

		$this->assertTrue( isset( $e ) );
		$response = json_decode( $e->getMessage() , true );
		$this->assertTrue( count( $response ) > 0 );
		$this->assertEquals( 'Pagination count point is not valued correctly.' , $response['message'] );
		$this->assertFalse( $response['status'] );
		$this->assertTrue( (bool) count( $response['data'] == 0 ) );
	}
	public function test_it_can_validate_not_valid_int_count_point_in_search_icon_by_name() {
		$_POST['pagination_start'] = 0;
		$_POST['pagination_count'] = 0;
		try {
			$this->_handleAjax( 'mk_search_icons_by_name' );
		} catch ( WPAjaxDieStopException $e ) {
		}

		$this->assertTrue( isset( $e ) );
		$response = json_decode( $e->getMessage() , true );
		$this->assertTrue( count( $response ) > 0 );
		$this->assertEquals( 'Pagination count point is not valued correctly.' , $response['message'] );
		$this->assertFalse( $response['status'] );
		$this->assertTrue( (bool) count( $response['data'] == 0 ) );
	}
	public function test_it_can_validate_null_icon_family_in_search_icon_by_name() {
		$_POST['pagination_start'] = 0;
		$_POST['pagination_count'] = 10;
		try {
			$this->_handleAjax( 'mk_search_icons_by_name' );
		} catch ( WPAjaxDieStopException $e ) {
		}

		$this->assertTrue( isset( $e ) );
		$response = json_decode( $e->getMessage() , true );
		$this->assertTrue( count( $response ) > 0 );
		$this->assertEquals( 'Icon name is not valued correctly.' , $response['message'] );
		$this->assertFalse( $response['status'] );
		$this->assertTrue( (bool) count( $response['data'] == 0 ) );
	}
	public function test_it_can_validate_empty_icon_family_in_search_icon_by_name() {
		$_POST['pagination_start'] = 0;
		$_POST['pagination_count'] = 10;
		$_POST['icon_name'] = '';
		try {
			$this->_handleAjax( 'mk_search_icons_by_name' );
		} catch ( WPAjaxDieStopException $e ) {
		}

		$this->assertTrue( isset( $e ) );
		$response = json_decode( $e->getMessage() , true );
		$this->assertTrue( count( $response ) > 0 );
		$this->assertEquals( 'Icon name is not valued correctly.' , $response['message'] );
		$this->assertFalse( $response['status'] );
		$this->assertTrue( (bool) count( $response['data'] == 0 ) );
	}
	public function test_it_can_validate_existence_icon_family_in_search_icon_by_name() {
		// $_POST['pagination_start'] = 0;
		// $_POST['pagination_count'] = 10;
		// $_POST['icon_name'] = 'someblahblahthatisnotexist';
		// try {
		// 	$this->_handleAjax( 'mk_search_icons_by_name' );
		// } catch ( WPAjaxDieStopException $e ) {
		// }

		// $this->assertTrue( isset( $e ) );
		// $response = json_decode( $e->getMessage() , true );
		// $this->assertTrue( count( $response ) > 0 );
		// $this->assertEquals( 'Successful' , $response['message'] );
		// $this->assertTrue( (bool) $response['status'] );
		// $this->assertTrue( (bool) count( $response['data'] == 0 ) );
	}
}
