# PHP Google Places Suite

[![Build Status](https://travis-ci.org/markenwerk/php-google-places-suite.svg?branch=master)](https://travis-ci.org/markenwerk/php-google-places-suite)
[![Test Coverage](https://codeclimate.com/github/markenwerk/php-google-places-suite/badges/coverage.svg)](https://codeclimate.com/github/markenwerk/php-google-places-suite/coverage)
[![Code Climate](https://codeclimate.com/github/markenwerk/php-google-places-suite/badges/gpa.svg)](https://codeclimate.com/github/markenwerk/php-google-places-suite)
[![Latest Stable Version](https://poser.pugx.org/markenwerk/google-places-suite/v/stable)](https://packagist.org/packages/markenwerk/google-places-suite)
[![Total Downloads](https://poser.pugx.org/markenwerk/google-places-suite/downloads)](https://packagist.org/packages/markenwerk/google-places-suite)
[![License](https://poser.pugx.org/markenwerk/google-places-suite/license)](https://packagist.org/packages/markenwerk/google-places-suite)

A PHP library to query Google's Places service for querying locations and addresses and getting details by Places ID.

## Installation

```{json}
{
   	"require": {
        "markenwerk/google-places-suite": "~1.0"
    }
}
```

## Usage

### Autoloading and namesapce

```{php}  
require_once('path/to/vendor/autoload.php');
```

---

### Performing a Google Places Query

#### Getting detail information about a known Google Places ID

```{php}
try{
	// Perform query
	$googlePlacesDetailQuery = new GooglePlacesDetailQuery();
	$googlePlacesDetailQuery
		->setApiKey($this->googlePlacesApiKey)
		->query('GOOGLE_PLACES_ID');

	// Retrieving the query result as GooglePlacesSuite\GooglePlacesDetailResult instance
	$queryResult = $googlePlacesDetailQuery->getResult();

} catch (GoogleGeocode\Exception\NetworkException $exception) {
	// Google Geocode API is not reachable or curl failed
} catch (GoogleGeocode\Exception\ApiException $exception) {
	// Google Geocode API unexpected result
} catch (GoogleGeocode\Exception\ApiLimitException $exception) {
	// Google Geocode API requests over the allowed limit
} catch (GoogleGeocode\Exception\ApiNoResultsException $exception) {
	// Google Geocode API request had no result
}

```

---

### Reading from a GooglePlacesDetailResult

**Attention:** Plaese note that all getter methods on the `GeoLocationAddress` return a `GeoLocationAddressComponent` instance or `null`. For preventing calls on non-objects the `GeoLocationAddress` class provides methods to check whether the address components exists. 

```{php}
// Retrieving the query result as GooglePlacesSuite\GooglePlacesDetailResult instance
$queryResult = $googlePlacesDetailQuery->getResult();

// Retieving address information as GoogleDataStructure\GeoLocation\GeoLocationAddress
if($queryResult->hasAddress()) {

	if ($queryResult->getAddress()->hasStreetName()) {
		// Returns 'Lornsenstraße'
		$addressStreetShort = $queryResult->getAddress()->getStreetName()->getShortName();
		// Returns 'Lornsenstraße'
		$addressStreetLong = $queryResult->getAddress()->getStreetName()->getLongName();
	}

	if ($queryResult->getAddress()->hasStreetNumber()) {
		// Returns '43'
		$addressStreetNumberShort = $queryResult->getAddress()->getStreetNumber()->getShortName();
		// Returns '43'
		$addressStreetNumberLong = $queryResult->getAddress()->getStreetNumber()->getLongName();
	}

	if ($queryResult->getAddress()->hasPostalCode()) {
		// Returns '24105'
		$addressPostalCodeShort = $queryResult->getAddress()->getPostalCode()->getShortName();
		// Returns '24105'
		$addressPostalCodeLong = $queryResult->getAddress()->getPostalCode()->getLongName();
	}

	if ($queryResult->getAddress()->hasCity()) {
		// Returns 'KI'
		$addressCityShort = $queryResult->getAddress()->getCity()->getShortName();
		// Returns 'Kiel'
		$addressCityLong = $queryResult->getAddress()->getCity()->getLongName();
	}

	if ($queryResult->getAddress()->hasArea()) {
		// Returns 'Ravensberg - Brunswik - Düsternbrook'
		$addressAreaShort = $queryResult->getAddress()->getArea()->getShortName();
		// Returns 'Ravensberg - Brunswik - Düsternbrook'
		$addressAreaLong = $queryResult->getAddress()->getArea()->getLongName();
	}

	if ($queryResult->getAddress()->hasProvince()) {
		// Returns 'SH'
		$addressProvinceShort = $queryResult->getAddress()->getProvince()->getShortName();
		// Returns 'Schleswig-Holstein'
		$addressProvinceLong = $queryResult->getAddress()->getProvince()->getLongName();
	}

	if ($queryResult->getAddress()->hasCountry()) {
		// Returns 'DE'
		$addressCountryShort = $queryResult->getAddress()->getCountry()->getShortName();
		// Returns 'Germany'
		$addressCountryLong = $queryResult->getAddress()->getCountry()->getLongName();
	}

}

// Retieving address information as GoogleDataStructure\GeoLocation\GeoLocationGeometry
if ($queryResult->hasGeometry()) {

	if ($queryResult->getGeometry()->hasLocation()) {
		// Returns 54.334123
		$geometryLocationLatitude = $queryResult->getGeometry()->getLocation()->getLatitude();
		// Returns 10.1364007
		$geometryLocationLatitude = $queryResult->getGeometry()->getLocation()->getLongitude();
	}

	if ($queryResult->getGeometry()->hasViewport()) {
		// Returns 54.335471980291
		$geometryLocationLatitude = $queryResult->getGeometry()->getViewport()->getNortheast()->getLatitude();
		// Returns 10.137749680292
		$geometryLocationLatitude = $queryResult->getGeometry()->getViewport()->getNortheast()->getLongitude();
		// Returns 54.332774019708
		$geometryLocationLatitude = $queryResult->getGeometry()->getViewport()->getSouthwest()->getLatitude();
		// Returns 10.135051719708
		$geometryLocationLatitude = $queryResult->getGeometry()->getViewport()->getSouthwest()->getLongitude();
	}

	if ($queryResult->getGeometry()->hasAccessPoints()) {
		for ($i = 0; $i < $queryResult->getGeometry()->countAccessPoints(); $i++) {
			// Returns 54.335471980291
			$geometryAccessPointLatitude = $queryResult->getGeometry()->getAccessPointAt($i)->getLatitude();
			// Returns 10.137749680292
			$geometryAccessPointLatitude = $queryResult->getGeometry()->getAccessPointAt($i)->getLongitude();
		}
	}

}

if ($queryResult->hasGooglePlacesId()) {
	// Retrieving the Google Places information from the query result
	// Returns 'ChIJ_zNzWmpWskcRP8DWT5eX5jQ'
	$googlePlacesId = $queryResult->getGooglePlacesId();
}
```

## Contribution

Contributing to our projects is always very appreciated.  
**But: please follow the contribution guidelines written down in the [CONTRIBUTING.md](https://github.com/markenwerk/php-google-places-suite/blob/master/CONTRIBUTING.md) document.**

## License

PHP Google Places Suite is under the MIT license.