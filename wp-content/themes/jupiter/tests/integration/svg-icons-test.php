<?php
use org\bovigo\vfs\vfsStream;

class Svg_Icons_Test extends WP_UnitTestCase {

	private $icon;
	public function setUp() {
		$this->empty_post_requests();
		$this->icon = new Mk_SVG_Icons( false );
	}
	public function test_class_is_defined() {
		$this->assertFalse( is_null( $this->icon ) );
	}

	public function test_it_can_validate_empty_start_point_in_get_icons_db() {
		try {
			$response = $this->icon->get_icons_db();
		} catch (Exception $e) {
		}
		$this->assertTrue(isset($e));
		$this->assertEquals( 'Pagination start point is not valued correctly.' , $e->getMessage() );
	}
	public function test_it_can_validate_int_start_point_in_get_icons_db() {
		try {
			$this->icon
			->set_pagination_start( '' )
			->get_icons_db();
		} catch (Exception $e) {
		}
		$this->assertTrue(isset($e));
		$this->assertEquals( 'Pagination start point is not valued correctly.' , $e->getMessage() );
	}
	public function test_it_can_validate_int_start_point2_in_get_icons_db() {
		try {
			$this->icon
			->set_pagination_start( 'blah blah' )
			->get_icons_db();
		} catch (Exception $e) {
		}

		$this->assertTrue(isset($e));
		$this->assertEquals( 'Pagination start point is not valued correctly.' , $e->getMessage() );
	}

	public function test_it_can_validate_empty_count_point_in_get_icons_db() {
		try {
			$this->icon
			->set_pagination_start( 0 )
			->get_icons_db();
		} catch (Exception $e) {
		}
		$this->assertTrue(isset($e));
		$this->assertEquals( 'Pagination count point is not valued correctly.' , $e->getMessage() );
	}
	public function test_it_can_validate_int_count_point_in_get_icons_db() {
		try {
			$this->icon
			->set_pagination_start( 0 )
			->set_pagination_count( '' )
			->get_icons_db();
		} catch (Exception $e) {
		}
		$this->assertTrue(isset($e));
		$this->assertEquals( 'Pagination count point is not valued correctly.' , $e->getMessage() );
	}
	public function test_it_can_validate_int_count_point2_in_get_icons_db() {
		try {
			$this->icon
			->set_pagination_start( 0 )
			->set_pagination_count( 'blah blah' )
			->get_icons_db();
		} catch (Exception $e) {
		}
		$this->assertTrue(isset($e));
		$this->assertEquals( 'Pagination count point is not valued correctly.' , $e->getMessage() );
	}

	public function test_it_can_validate_empty_icon_family_in_get_icons_db() {
		try {
			$this->icon
			->set_pagination_start( 0 )
			->set_pagination_count( 10 )
			->get_icons_db();
		} catch (Exception $e) {
		}
		$this->assertTrue(isset($e));
		$this->assertEquals( 'Please choose an icon family.' , $e->getMessage() );
	}
	public function test_it_can_validate_existence_icon_family_in_get_icons_db() {
		try {
			$this->icon
			->set_pagination_start( 0 )
			->set_pagination_count( 10 )
			->set_icon_family( 'reza' )
			->get_icons_db();
		} catch (Exception $e) {
		}
		$this->assertTrue(isset($e));
		$this->assertEquals( 'Icon family that you choosed is not exist.' , $e->getMessage() );
	}

	public function test_it_can_get_icon_family_list() {
		$response = $this->icon
		->set_icon_family( 'all' )
		->set_pagination_start( 0 )
		->set_pagination_count( 10 )
		->get_icons_db();

		$this->assertTrue( ($response !== false ));
		$this->assertTrue( count( $response ) > 0 );
		$this->assertTrue( count( $response ) == 10 );
	}

	public function test_it_accept_zero_one_get_icon_family_list() {
		$response = $this->icon
		->set_icon_family( 'pe-line-icons' )
		->set_pagination_start( 0 )
		->set_pagination_count( -1 )
		->get_icons_db();

		$this->assertTrue( ($response !== false ));
		$this->assertTrue( count( $response ) > 0 );
	}





	public function test_it_can_validate_empty_start_point_in_get_icons_list() {
		$response = $this->icon->get_icons_list();
		$this->assertFalse( $response );
		$response = $this->icon->get_message();
		$this->assertEquals( 'Pagination start point is not valued correctly.' , $response['message'] );
	}
	public function test_it_can_validate_int_start_point_in_get_icons_list() {
		$_POST['pagination_start'] = 'blah blah';
		$response = $this->icon->get_icons_list();
		$this->assertFalse( $response );
		$response = $this->icon->get_message();
		$this->assertEquals( 'Pagination start point is not valued correctly.' , $response['message'] );
	}
	public function test_it_can_validate_empty_count_point_in_get_icons_list() {
		$_POST['pagination_start'] = 0;
		$response = $this->icon->get_icons_list();
		$this->assertFalse( $response );
		$response = $this->icon->get_message();
		$this->assertEquals( 'Pagination count point is not valued correctly.' , $response['message'] );
	}
	public function test_it_can_validate_int_count_point_in_get_icons_list() {
		$_POST['pagination_start'] = 0;
		$_POST['pagination_count'] = 'blah blah';
		$response = $this->icon->get_icons_list();
		$this->assertFalse( $response );
		$response = $this->icon->get_message();
		$this->assertEquals( 'Pagination count point is not valued correctly.' , $response['message'] );
	}
	public function test_it_can_validate_not_valid_int_count_point_in_get_icons_list() {
		$_POST['pagination_start'] = 0;
		$_POST['pagination_count'] = 0;
		$response = $this->icon->get_icons_list();
		$this->assertFalse( $response );
		$response = $this->icon->get_message();
		$this->assertEquals( 'Pagination count point is not valued correctly.' , $response['message'] );
	}
	public function test_it_can_validate_null_icon_family_in_get_icons_list() {
		$_POST['pagination_start'] = 0;
		$_POST['pagination_count'] = 10;
		$response = $this->icon->get_icons_list();
		$this->assertFalse( $response );
		$response = $this->icon->get_message();
		$this->assertEquals( 'Icon family is not valued correctly.' , $response['message'] );
	}
	public function test_it_can_validate_empty_icon_family_in_get_icons_list() {
		$_POST['pagination_start'] = 0;
		$_POST['pagination_count'] = 10;
		$_POST['icon_family'] = '';
		$response = $this->icon->get_icons_list();
		$this->assertFalse( $response );
		$response = $this->icon->get_message();
		$this->assertEquals( 'Icon family is not valued correctly.' , $response['message'] );
	}
	public function test_it_can_validate_existence_icon_family_in_get_icons_list() {
		$_POST['pagination_start'] = 0;
		$_POST['pagination_count'] = 10;
		$_POST['icon_family'] = 'reza';
		$response = $this->icon->get_icons_list();
		$this->assertFalse( $response );
		$response = $this->icon->get_message();
		$this->assertEquals( 'Icon family that you choosed is not exist.' , $response['message'] );
	}
	public function test_it_can_get_icons_list() {
		$_POST['pagination_start'] = 0;
		$_POST['pagination_count'] = 10;
		$_POST['icon_family'] = 'all';
		$response = $this->icon->get_icons_list();
		$this->assertTrue( $response );
		$response = $this->icon->get_message();
		$this->assertEquals( 'Successful' , $response['message'] );
		foreach ( $response['data'] as $key => $value ) {
			$this->assertTrue( strpos( $value, '<svg  class="mk-svg-icon"' ) !== false );
			$this->assertTrue( strpos( $value, 'data-name="' . $key ) !== false );
			$this->assertTrue( strpos( $key , 'mk-' ) !== false );
		}
	}

	public function test_it_accept_zero_one_get_icons_list() {
		$_POST['pagination_start'] = 0;
		$_POST['pagination_count'] = -1;
		$_POST['icon_family'] = 'theme-icons';
		$response = $this->icon->get_icons_list();
		$this->assertTrue( $response );
		$response = $this->icon->get_message();
		$this->assertEquals( 'Successful' , $response['message'] );
		foreach ( $response['data'] as $key => $value ) {
			$this->assertTrue( strpos( $value, '<svg  class="mk-svg-icon"' ) !== false );
			$this->assertTrue( strpos( $value, 'data-name="' . $key ) !== false );
			$this->assertTrue( strpos( $key , 'mk-' ) !== false );
		}
	}


	public function test_it_can_validate_empty_start_point_in_search_icon_by_name() {
		$response = $this->icon->search_icon_by_name();
		$this->assertFalse( $response );
		$response = $this->icon->get_message();
		$this->assertEquals( 'Pagination start point is not valued correctly.' , $response['message'] );
	}
	public function test_it_can_validate_int_start_point_in_search_icon_by_name() {
		$_POST['pagination_start'] = 'blah blah';
		$response = $this->icon->search_icon_by_name();
		$this->assertFalse( $response );
		$response = $this->icon->get_message();
		$this->assertEquals( 'Pagination start point is not valued correctly.' , $response['message'] );
	}
	public function test_it_can_validate_empty_count_point_in_search_icon_by_name() {
		$_POST['pagination_start'] = 0;
		$response = $this->icon->search_icon_by_name();
		$this->assertFalse( $response );
		$response = $this->icon->get_message();
		$this->assertEquals( 'Pagination count point is not valued correctly.' , $response['message'] );
	}
	public function test_it_can_validate_int_count_point_in_search_icon_by_name() {
		$_POST['pagination_start'] = 0;
		$_POST['pagination_count'] = 'blah blah';
		$response = $this->icon->search_icon_by_name();
		$this->assertFalse( $response );
		$response = $this->icon->get_message();
		$this->assertEquals( 'Pagination count point is not valued correctly.' , $response['message'] );
	}
	public function test_it_can_validate_not_valid_int_count_point_in_search_icon_by_name() {
		$_POST['pagination_start'] = 0;
		$_POST['pagination_count'] = 0;
		$response = $this->icon->search_icon_by_name();
		$this->assertFalse( $response );
		$response = $this->icon->get_message();
		$this->assertEquals( 'Pagination count point is not valued correctly.' , $response['message'] );
	}
	public function test_it_can_validate_null_icon_family_in_search_icon_by_name() {
		$_POST['pagination_start'] = 0;
		$_POST['pagination_count'] = 10;
		$response = $this->icon->search_icon_by_name();
		$this->assertFalse( $response );
		$response = $this->icon->get_message();
		$this->assertEquals( 'Icon name is not valued correctly.' , $response['message'] );
	}
	public function test_it_can_validate_empty_icon_family_in_search_icon_by_name() {
		$_POST['pagination_start'] = 0;
		$_POST['pagination_count'] = 10;
		$_POST['icon_name'] = '';
		$response = $this->icon->search_icon_by_name();
		$this->assertFalse( $response );
		$response = $this->icon->get_message();
		$this->assertEquals( 'Icon name is not valued correctly.' , $response['message'] );
	}
	public function test_it_can_validate_existence_icon_family_in_search_icon_by_name() {
		$_POST['pagination_start'] = 0;
		$_POST['pagination_count'] = 10;
		$_POST['icon_name'] = 'someblahblahthatisnotexist';
		$response = $this->icon->search_icon_by_name();
		$this->assertTrue( $response );
		$response = $this->icon->get_message();
		$this->assertEquals( 'Successful' , $response['message'] );
		$this->assertTrue( count( $response['data'] ) === 0 );
	}

	public function test_it_can_search_icon_by_name() {
		$_POST['pagination_start'] = 0;
		$_POST['pagination_count'] = 10;
		$_POST['icon_name'] = 'search';
		$response = $this->icon->search_icon_by_name();
		$response = $this->icon->get_message();
		$this->assertEquals( 'Successful' , $response['message'] );
		$this->assertTrue( count( $response['data'] ) > 1 );
		foreach ( $response['data'] as $key => $value ) {
			$this->assertTrue( strpos( $value, '<svg  class="mk-svg-icon"' ) !== false );
			$this->assertTrue( strpos( $value, 'data-name="' . $key ) !== false );
			$this->assertTrue( strpos( $key , 'mk-' ) !== false );
		}
	}
	
	public function test_it_accept_zero_one_in_search_icon_by_name() {
		$_POST['pagination_start'] = 0;
		$_POST['pagination_count'] = -1;
		$_POST['icon_name'] = 'search';
		$response = $this->icon->search_icon_by_name();
		$response = $this->icon->get_message();
		$this->assertEquals( 'Successful' , $response['message'] );
		$this->assertTrue( count( $response['data'] ) > 1 );
		foreach ( $response['data'] as $key => $value ) {
			$this->assertTrue( strpos( $value, '<svg  class="mk-svg-icon"' ) !== false );
			$this->assertTrue( strpos( $value, 'data-name="' . $key ) !== false );
			$this->assertTrue( strpos( $key , 'mk-' ) !== false );
		}
	}

	public function test_it_can_search_from_different_point_icon_by_name() {
		$_POST['pagination_start'] = 2;
		$_POST['pagination_count'] = 1;
		$_POST['icon_name'] = 'search';
		$response = $this->icon->search_icon_by_name();
		$response = $this->icon->get_message();
		$this->assertEquals( 'Successful' , $response['message'] );
		$this->assertTrue( count( $response['data'] ) > 0 );
		foreach ( $response['data'] as $key => $value ) {
			$this->assertTrue( strpos( $value, '<svg  class="mk-svg-icon"' ) !== false );
			$this->assertTrue( strpos( $value, 'data-name="' . $key ) !== false );
			$this->assertTrue( strpos( $key , 'mk-' ) !== false );
		}
	}
	public function empty_post_requests() {
		$_POST = array();
	}
}
