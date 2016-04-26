<?php

namespace GooglePlacesSuite\Base;

use CommonException;
use GooglePlacesSuite;
use GoogleDataStructure;

/**
 * Class BaseLookup
 *
 * @package GooglePlacesSuite\Base
 */
abstract class BaseGooglePlacesQuery
{

	const API_BASE_URL = 'https://maps.googleapis.com/maps/api/place/details/json?sensor=false';

	/**
	 * @var string
	 */
	private $apiKey;

	/**
	 * @var GooglePlacesSuite\GooglePlacesDetailResult
	 */
	private $result = array();

	/**
	 * @return string
	 */
	public function getApiKey()
	{
		return $this->apiKey;
	}

	/**
	 * @param string $apiKey
	 * @return $this
	 */
	public function setApiKey($apiKey)
	{
		$this->apiKey = $apiKey;
		return $this;
	}

	/**
	 * @return $this
	 */
	protected function clearResult()
	{
		$this->result = null;
		return $this;
	}

	/**
	 * @return GooglePlacesSuite\GooglePlacesDetailResult
	 */
	public function getResult()
	{
		return $this->result;
	}

	/**
	 * @param GooglePlacesSuite\GooglePlacesDetailResult $result
	 * @return $this
	 */
	protected function setResult($result)
	{
		$this->result = $result;
		return $this;
	}

	/**
	 * Processes the remote request
	 *
	 * @param string
	 * @return string
	 * @throws CommonException\ApiException\AuthenticationException
	 * @throws CommonException\ApiException\InvalidResponseException
	 * @throws CommonException\ApiException\NoResultException
	 * @throws CommonException\ApiException\RequestQuotaException
	 * @throws CommonException\NetworkException\CurlException
	 */
	protected function request($url)
	{
		$curl = curl_init();
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($curl, CURLOPT_URL, $url);
		$response = curl_exec($curl);
		curl_close($curl);
		if (!$response) {
			throw new CommonException\NetworkException\CurlException('Curling the API endpoint ' . $url . ' failed.');
		}
		$responseData = @json_decode($response, true);
		$this->validateResponse($response, $responseData);
		return $responseData;
	}

	/**
	 * @param string $rawResponse
	 * @param array|string $responseData
	 * @throws CommonException\ApiException\AuthenticationException
	 * @throws CommonException\ApiException\InvalidResponseException
	 * @throws CommonException\ApiException\NoResultException
	 * @throws CommonException\ApiException\RequestQuotaException
	 */
	private function validateResponse($rawResponse, $responseData)
	{
		if (is_null($responseData) || !is_array($responseData) || !isset($responseData['status'])) {
			throw new CommonException\ApiException\InvalidResponseException(
				'Parsing the API response from body failed: ' . $rawResponse
			);
		}

		$responseStatus = mb_strtoupper($responseData['status']);
		if ($responseStatus == 'OVER_QUERY_LIMIT') {
			$exceptionMessage = $this->buildExceptionMessage('Google Places request limit reached', $responseData);
			throw new CommonException\ApiException\RequestQuotaException($exceptionMessage);
		} else if ($responseStatus == 'REQUEST_DENIED') {
			$exceptionMessage = $this->buildExceptionMessage('Google Places request was denied', $responseData);
			throw new CommonException\ApiException\AuthenticationException($exceptionMessage);
		} else if ($responseStatus != 'OK') {
			$exceptionMessage = $this->buildExceptionMessage('Google Places no results', $responseData);
			throw new CommonException\ApiException\NoResultException($exceptionMessage);
		}
	}

	/**
	 * @param string $exceptionMessage
	 * @param array $responseData
	 * @return string
	 */
	private function buildExceptionMessage($exceptionMessage, array $responseData)
	{
		if (isset($responseData['error_message'])) {
			$exceptionMessage .= ': ' . $responseData['error_message'];
		}
		return $exceptionMessage;
	}

	/**
	 * @param $responseData
	 * @return $this
	 */
	protected function setResultFromResponse($responseData)
	{
		$address = $responseData['result']['address_components'];
		$geometry = $responseData['result']['geometry'];
		$placesId = $responseData['result']['place_id'];
		$locationAddress = new GoogleDataStructure\GeoLocation\GeoLocationAddress();
		$locationAddress->setFromServiceResult($address);
		$locationGeometry = new GoogleDataStructure\GeoLocation\GeoLocationGeometry();
		$locationGeometry->setFromServiceResult($geometry);
		$this->setResult(new GooglePlacesSuite\GooglePlacesDetailResult($locationAddress, $locationGeometry, $placesId));
		return $this;
	}

	/**
	 * Returns the address as pseudo url encoded utf8 string
	 *
	 * @param string $urlParameter
	 * @return string
	 */
	protected function encodeUrlParameter($urlParameter)
	{
		$urlParameter = str_replace(' ', '+', $urlParameter);
		return $urlParameter;
	}

	/**
	 * Adds the API key to the URL
	 *
	 * @param string $url
	 * @return string
	 */
	protected function addApiKeyToUrl($url)
	{
		return $url . '&key=' . $this->getApiKey();
	}

}
