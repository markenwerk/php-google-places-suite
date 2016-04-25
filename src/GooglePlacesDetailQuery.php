<?php

namespace GooglePlacesSuite;

/**
 * Class GooglePlacesDetail
 *
 * @package GooglePlacesSuite
 */
class GooglePlacesDetailQuery extends Base\BaseGooglePlacesQuery
{

	/**
	 * @param string $googlePlacesId
	 * @return $this
	 * @throws Exception\ApiException
	 * @throws Exception\ApiLimitException
	 * @throws Exception\ApiNoResultsException
	 * @throws Exception\NetworkException
	 */
	public function query($googlePlacesId)
	{
		$requestUrl = self::API_BASE_URL . $this->encodeUrlParameter($googlePlacesId);
		$requestUrl = $this->addApiKeyToUrl($requestUrl);
		$responseData = $this->request($requestUrl);
		$this
			->clearResult()
			->setResultFromResponse($responseData);
		return $this;
	}

	/**
	 * @param string $urlParameter
	 * @return string
	 */
	protected function encodeUrlParameter($urlParameter)
	{
		return '&placeid=' . parent::encodeUrlParameter($urlParameter);
	}

}
