<?php

namespace GooglePlacesSuite;

use CommonException;

/**
 * Class GooglePlacesDetailQueryTest
 *
 * @package GooglePlacesSuite
 */
class GooglePlacesDetailQueryTest extends \PHPUnit_Framework_TestCase
{

	/**
	 * @var string
	 */
	private $googlePlacesApiKey;

	/**
	 * GooglePlacesLookupTest constructor.
	 */
	public function __construct()
	{
		parent::__construct();
		// Receive the Google Places API key from env
		$this->googlePlacesApiKey = getenv('GOOGLE_PLACES_API_KEY');
	}

	public function testQuerySuccess()
	{
		// Perform lookup
		$googlePlacesDetailQuery = new GooglePlacesDetailQuery();
		$googlePlacesDetailQuery
			->setApiKey($this->googlePlacesApiKey)
			->query('ChIJ_zNzWmpWskcRP8DWT5eX5jQ');

		// Validate results
		$queryResult = $googlePlacesDetailQuery->getResult();
		$this->assertInstanceOf('GooglePlacesSuite\\GooglePlacesDetailResult', $queryResult);

		// Address result
		$this->assertTrue($queryResult->hasAddress());
		$addressResult = $queryResult->getAddress();
		$this->assertTrue($addressResult->hasStreetNumber());
		$this->assertEquals('43', $addressResult->getStreetNumber()->getShortName());
		$this->assertEquals('43', $addressResult->getStreetNumber()->getLongName());
		$this->assertTrue($addressResult->hasStreetName());
		$this->assertEquals('Lornsenstraße', $addressResult->getStreetName()->getShortName());
		$this->assertEquals('Lornsenstraße', $addressResult->getStreetName()->getLongName());
		$this->assertTrue($addressResult->hasCity());
		$this->assertEquals('KI', $addressResult->getCity()->getShortName());
		$this->assertEquals('Kiel', $addressResult->getCity()->getLongName());
		$this->assertTrue($addressResult->hasPostalCode());
		$this->assertEquals('24105', $addressResult->getPostalCode()->getShortName());
		$this->assertEquals('24105', $addressResult->getPostalCode()->getLongName());
		$this->assertTrue($addressResult->hasArea());
		$this->assertEquals('Ravensberg - Brunswik - Düsternbrook', $addressResult->getArea()->getShortName());
		$this->assertEquals('Ravensberg - Brunswik - Düsternbrook', $addressResult->getArea()->getLongName());
		$this->assertTrue($addressResult->hasProvince());
		$this->assertEquals('SH', $addressResult->getProvince()->getShortName());
		$this->assertEquals('Schleswig-Holstein', $addressResult->getProvince()->getLongName());
		$this->assertTrue($addressResult->hasCountry());
		$this->assertEquals('DE', $addressResult->getCountry()->getShortName());
		$this->assertEquals('Germany', $addressResult->getCountry()->getLongName());

		// Geometry result
		$this->assertTrue($queryResult->hasGeometry());
		$geometryResult = $queryResult->getGeometry();
		$this->assertTrue($geometryResult->hasLocation());
		$this->assertGreaterThanOrEqual(54.3, $geometryResult->getLocation()->getLatitude());
		$this->assertLessThanOrEqual(54.4, $geometryResult->getLocation()->getLatitude());
		$this->assertGreaterThanOrEqual(10.1, $geometryResult->getLocation()->getLongitude());
		$this->assertLessThanOrEqual(10.2, $geometryResult->getLocation()->getLongitude());
		$this->assertFalse($geometryResult->hasViewport());
		$this->assertTrue($geometryResult->hasAccessPoints());

		// Google Places ID
		$this->assertTrue($queryResult->hasGooglePlacesId());
		$this->assertEquals('ChIJ_zNzWmpWskcRP8DWT5eX5jQ', $queryResult->getGooglePlacesId());
	}

	public function testQueryNoResults()
	{
		$this->setExpectedException(get_class(new CommonException\ApiException\NoResultException()));
		$googlePlacesDetailQuery = new GooglePlacesDetailQuery();
		$googlePlacesDetailQuery
			->setApiKey($this->googlePlacesApiKey)
			->query('NO_VALID_PLACES_ID');
	}

	public function testQueryApiKey()
	{
		$this->setExpectedException(get_class(new CommonException\ApiException\AuthenticationException()));
		$googlePlacesDetailQuery = new GooglePlacesDetailQuery();
		$googlePlacesDetailQuery
			->setApiKey('INVALID_API_KEY')
			->query('ChIJ_zNzWmpWskcRP8DWT5eX5jQ');
	}

}
