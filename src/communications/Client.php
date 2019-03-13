<?php
/**
 * Created by PhpStorm.
 * User: agutierrez
 * Date: 2019-02-15
 * Time: 18:44
 */

namespace Wakup;


class Client extends HttpClient
{

    /**
     * Obtains the paginated list of product attributes registered on Wakup catalogue manager
     *
     * @param int $page Page to request. First page is 0
     * @param int $perPage Number of results to obtain per request. Default is 25.
     * @return PaginatedAttributes Product attributes list with pagination information
     * @throws WakupException
     */
    public function getPaginatedAttributes($page = 0, $perPage = 25) : PaginatedAttributes
    {
        $responseObj = new PaginatedAttributes();
        $params = ['page' => $page, 'perPage' => $perPage];
        $this->launchGetRequest('catalog/attributes', $params, $this->getWakupHeaders(), $responseObj);
        return $responseObj;
    }

    /**
     * Obtains the paginated list of product categories registered on Wakup catalogue manager
     *
     * @param int $page Page to request. First page is 0
     * @param int $perPage Number of results to obtain per request. Default is 25.
     * @return PaginatedCategories Product categories list with pagination information
     * @throws WakupException
     */
    public function getPaginatedCategories($page = 0, $perPage = 25) : PaginatedCategories
    {
        $responseObj = new PaginatedCategories();
        $params = ['page' => $page, 'perPage' => $perPage];
        $this->launchGetRequest('catalog/categories', $params, $this->getWakupHeaders(), $responseObj);
        return $responseObj;
    }

    /**
     * Obtains the list of products that has changed from the last update time. The returning products will only contain
     * price or details info if it has changed since given date.
     *
     * @param \DateTime $lastUpdate Time of last update
     * @param int $page Page to request. First page is 0
     * @param int $perPage Number of results to obtain per request. Default is 25.
     * @return PaginatedProducts List of paginated products that has changed since last update time
     * @throws WakupException
     */
    public function getPaginatedProducts(\DateTime $lastUpdate = null, $page = 0, $perPage = 25) : PaginatedProducts
    {
        $responseObj = new PaginatedProducts();
        $params = ['page' => $page, 'perPage' => $perPage];
        if ($lastUpdate != null) $params['lastUpdate'] = $lastUpdate->format(\DateTime::ATOM);
        $this->launchGetRequest('catalog/products', $params, $this->getWakupHeaders(), $responseObj);
        return $responseObj;
    }

    /**
     * @param string $userIdentifier User VAT identifier
     * @return UserCreditInfo Credit information for given user. Null if user is not registered on credit system.
     * @throws WakupException
     */
    public function getUserCreditInfo(string $userIdentifier) : UserCreditInfo
    {
        $params = ['TipoIdentificacion' => 51, 'Identificacion' => $userIdentifier, 'pais' => 'CR'];
        $responseArray = $this->launchMongeRequest(96, 'Cliente/BuscarCliente', $params, UserCreditInfo::class);
        return count($responseArray) > 0 ? $responseArray[0] : null;
    }



}