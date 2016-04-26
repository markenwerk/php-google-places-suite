<?php

namespace GooglePlacesSuite;

use CommonException;

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
	 * @throws CommonException\ApiException\AuthenticationException
	 * @throws CommonException\ApiException\InvalidResponseException
	 * @throws CommonException\ApiException\NoResultException
	 * @throws CommonException\ApiException\RequestQuotaException
	 * @throws CommonException\NetworkException\CurlException
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
