<?php

namespace Model\ShtrafovnetClient;

class ShtrafovnetClient
{
    /**
     * @var array
     */
    private $params;

    public function __construct($params = [])
    {
        $this->params = $params;
    }

    /**
     * @return array
     */
    public function getParams()
    {
        return $this->params;
    }

    /**
     * @param array $params
     */
    public function setParams(array $params)
    {
        $this->params = $params;
    }

    private function sendGetRequest($url, $queryParameters = [], $headers = [])
    {
        if (!empty($queryParameters)) {
            $url .= (stripos($url, "?") === false ? "?" : "&").http_build_query($queryParameters);
        }

        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_HEADER, true);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_TIMEOUT, 15);

        if (!empty($headers)) {
            curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
        }

        if (($response = curl_exec($curl)) === false) {
            throw new \Exception(curl_error($curl));
        }

        $header_size = curl_getinfo($curl, CURLINFO_HEADER_SIZE);
        $responseHeader = trim(substr($response, 0, $header_size));
        $responseBody = trim(substr($response, $header_size));

        curl_close($curl);

        return [$responseHeader, $responseBody];
    }

    private function sendPostRequest($url, $body, $headers = [])
    {
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_HEADER, true);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_TIMEOUT, 15);
        curl_setopt($curl, CURLOPT_POST, true);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $body);

        if (!empty($headers)) {
            curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
        }

        if (($response = curl_exec($curl)) === false) {
            throw new \Exception(curl_error($curl));
        }

        $header_size = curl_getinfo($curl, CURLINFO_HEADER_SIZE);
        $responseHeader = trim(substr($response, 0, $header_size));
        $responseBody = trim(substr($response, $header_size));

        curl_close($curl);

        return [$responseHeader, $responseBody];
    }

    private function sendPatchRequest($url, $body, $headers = [])
    {
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_HEADER, true);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_TIMEOUT, 15);
        curl_setopt($curl, CURLOPT_CUSTOMREQUEST, 'PATCH');
        curl_setopt($curl, CURLOPT_POSTFIELDS, $body);

        if (!empty($headers)) {
            curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
        }

        if (($response = curl_exec($curl)) === false) {
            throw new \Exception(curl_error($curl));
        }

        $header_size = curl_getinfo($curl, CURLINFO_HEADER_SIZE);
        $responseHeader = trim(substr($response, 0, $header_size));
        $responseBody = trim(substr($response, $header_size));

        curl_close($curl);

        return [$responseHeader, $responseBody];
    }

    private function sendDeleteRequest($url, $body, $headers = [])
    {
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_HEADER, true);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_TIMEOUT, 15);
        curl_setopt($curl, CURLOPT_CUSTOMREQUEST, 'DELETE');
        curl_setopt($curl, CURLOPT_POSTFIELDS, $body);

        if (!empty($headers)) {
            curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
        }

        if (($response = curl_exec($curl)) === false) {
            throw new \Exception(curl_error($curl));
        }

        $header_size = curl_getinfo($curl, CURLINFO_HEADER_SIZE);
        $responseHeader = trim(substr($response, 0, $header_size));
        $responseBody = trim(substr($response, $header_size));

        curl_close($curl);

        return [$responseHeader, $responseBody];
    }

    /**
     * @return string
     */
    public function getApiBaseUrl()
    {
        return !empty($this->params['host']) ? $this->params['host'] : 'http://example.com';
    }

    /**
     * @param string $api_base_url
     */
    public function setApiBaseUrl(string $api_base_url)
    {
        $this->api_base_url = $api_base_url;
    }


    public function getBasicAuthHeader()
    {
        $username = !empty($this->getParams()['account']['login']) ?
            $this->getParams()['account']['login'] : 'username';

        $password = !empty($this->getParams()['account']['password']) ?
            $this->getParams()['account']['password'] : 'password';

        return 'Authorization: Basic '.base64_encode($username.":".$password);
    }

    public function getBearerAuthHeader()
    {
        $token = !empty($this->getParams()['token']) ? $this->getParams()['token'] : 'fail_token';

        return 'Authorization: Bearer '.$token;
    }

    /**
     * ===============================================================
     * ACCOUNT
     * ===============================================================
     */
    /**
     * Создание нового аккаунта
     * POST /account
     */
    public function createAccount($email, $password, $name, $contactName, $contactPhone, $companyInn, $extraData = [])
    {
        $url = $this->getApiBaseUrl()."/account";

        $headers = [
            'Content-Type: application/json',
        ];

        $data = [
            'email'                  => $email,
            'plainPassword'          => $password,
            'name'                   => $name,
            'companyContactFullname' => $contactName,
            'companyContactPhone'    => $contactPhone,
            'companyInn'             => $companyInn,
        ];

        $data = array_merge($data, $extraData);

        return $this->sendPostRequest($url, json_encode($data), $headers);
    }

    /**
     * Обновление информации аккаунта
     * PATCH /account
     */
    public function updateAccount($data = [])
    {
        $url = $this->getApiBaseUrl()."/account";

        $headers = [
            'Content-Type: application/json',
            $this->getBasicAuthHeader(),
        ];

        return $this->sendPatchRequest($url, json_encode($data), $headers);
    }

    /**
     * Сброс и отправка нового пароля от аккаунта
     * POST /account/reset-password
     */
    public function resetPasswordAccount()
    {
        $url = $this->getApiBaseUrl()."/account/reset-password";

        $headers = [
            'Content-Type: application/json',
        ];

        $data = [
            'email' => !empty($this->getParams()['account']['login']) ?
                $this->getParams()['account']['login'] : 'user@example.com',
        ];

        return $this->sendPostRequest($url, json_encode($data), $headers);
    }

    /**
     * Получение информации об аккаунте
     * GET /account
     */
    public function getAccount()
    {
        $url = $this->getApiBaseUrl()."/account";

        $headers = [
            $this->getBasicAuthHeader(),
        ];

        return $this->sendGetRequest($url, [], $headers);
    }

    /**
     * ===============================================================
     * TOKENS
     * ===============================================================
     */
    /**
     * Создание токена доступа к ресурсам ШтрафовНЕТ
     * POST /tokens
     */
    public function createToken()
    {
        $url = $this->getApiBaseUrl()."/tokens";

        $headers = [
            $this->getBasicAuthHeader(),
        ];

        return $this->sendPostRequest($url, [], $headers);
    }

    /**
     * ===============================================================
     * TARIFFS
     * ===============================================================
     */
    /**
     * Получение списка тарифов информационного обслуживания
     * GET /tariffs
     */
    public function getTariffs()
    {
        $url = $this->getApiBaseUrl()."/tariffs";

        $headers = [
            'Content-Type: application/json',
            $this->getBearerAuthHeader(),
        ];

        return $this->sendGetRequest($url, null, $headers);
    }

    /**
     * ===============================================================
     * CARS
     * ===============================================================
     */
    /**
     * Добавление ТС
     * POST /cars
     */
    public function createCar($data = [])
    {
        $url = $this->getApiBaseUrl()."/cars";

        $headers = [
            'Content-Type: application/json',
            $this->getBearerAuthHeader(),
        ];

        return $this->sendPostRequest($url, json_encode($data), $headers);
    }

    /**
     * Информация о ТС
     * POST /cars/{car_id}
     */
    public function getCar($car_id)
    {
        $url = $this->getApiBaseUrl()."/cars/".$car_id;

        $headers = [
            $this->getBearerAuthHeader(),
        ];

        return $this->sendGetRequest($url, null, $headers);
    }

    /**
     * Обновление ТС
     * PATCH /cars/{car_id}
     */
    public function updateCar($car_id, $data = [])
    {
        $url = $this->getApiBaseUrl()."/cars/".$car_id;

        $headers = [
            'Content-Type: application/json',
            $this->getBearerAuthHeader(),
        ];

        return $this->sendPatchRequest($url, json_encode($data), $headers);
    }

    /**
     * Удаление ТС
     * DELETE /cars/{id}
     */
    public function deleteCar($id)
    {
        $url = $this->getApiBaseUrl()."/cars/".$id;

        $headers = [
            $this->getBearerAuthHeader(),
        ];

        return $this->sendDeleteRequest($url, null, $headers);
    }

    /**
     * Получение списка ТС
     * GET /cars
     */
    public function getCars()
    {
        $url = $this->getApiBaseUrl()."/cars";

        $headers = [
            $this->getBearerAuthHeader(),
        ];

        return $this->sendGetRequest($url, null, $headers);
    }

    /**
     * ===============================================================
     * FINES
     * ===============================================================
     */
    /**
     * Получение списка штрафов от всех ТС
     * POST /fines
     */
    public function getFines($queryParams = [])
    {
        $url = $this->getApiBaseUrl()."/fines";

        if (!empty($queryParams)) {
            $url .= "?".http_build_query($queryParams);
        }

        $headers = [
            $this->getBearerAuthHeader(),
        ];

        return $this->sendGetRequest($url, null, $headers);
    }

    /**
     * Получение информации о штрафе
     * POST /fines/{id}
     */
    public function getFine($id)
    {
        $url = $this->getApiBaseUrl()."/fines/".$id;

        $headers = [
            $this->getBearerAuthHeader(),
        ];

        return $this->sendGetRequest($url, null, $headers);
    }

    /**
     * ===============================================================
     * SERVICES
     * ===============================================================
     */
    /**
     * Получение списка услуг по информационному обслуживанию
     * POST /services
     */
    public function getServices()
    {
        $url = $this->getApiBaseUrl()."/services";

        $headers = [
            $this->getBearerAuthHeader(),
        ];

        return $this->sendGetRequest($url, null, $headers);
    }

    /**
     * Информация об услуге по информационному обслуживанию
     * POST /services/{id}
     */
    public function getService($id)
    {
        $url = $this->getApiBaseUrl()."/services/".$id;

        $headers = [
            $this->getBearerAuthHeader(),
        ];

        return $this->sendGetRequest($url, null, $headers);
    }

    /**
     * Создание новой услуги по информационному обслуживанию (выставление счета)
     * POST /services
     */
    public function createService($tariff_id, $quantity)
    {
        $url = $this->getApiBaseUrl()."/services";

        $headers = [
            'Content-Type: application/json',
            $this->getBearerAuthHeader(),
        ];

        $data = [
            'tariff'   => $tariff_id,
            'quantity' => $quantity,
        ];

        return $this->sendPostRequest($url, json_encode($data), $headers);
    }

    /**
     * ===============================================================
     * INVOICES
     * ===============================================================
     */
    /**
     * Получение списка счетов на оплату услуг
     * GET /invoices
     */
    public function getInvoices()
    {
        $url = $this->getApiBaseUrl()."/invoices";

        $headers = [
            $this->getBearerAuthHeader(),
        ];

        return $this->sendGetRequest($url, null, $headers);
    }

    /**
     * Получение счета
     * GET /invoices
     */
    public function getInvoice($id)
    {
        $url = $this->getApiBaseUrl()."/invoices/".$id;

        $headers = [
            $this->getBearerAuthHeader(),
        ];

        return $this->sendGetRequest($url, null, $headers);
    }

    /**
     * ===============================================================
     * REGISTRIES (оплата штрафов)
     * ===============================================================
     */
    /**
     * Создание реестра штрафов для оплаты
     * POST /registries
     */
    public function createRegistry($data = [])
    {
        $url = $this->getApiBaseUrl()."/registries";

        $headers = [
            'Content-Type: application/json',
            $this->getBearerAuthHeader(),
        ];

        return $this->sendPostRequest($url, json_encode($data), $headers);
    }

    /**
     * Получение списка реестров
     * GET /registries
     */
    public function getRegistries()
    {
        $url = $this->getApiBaseUrl()."/registries";

        $headers = [
            $this->getBearerAuthHeader(),
        ];

        return $this->sendGetRequest($url, null, $headers);
    }

    /**
     * Получение реестра
     * GET /registries/{id}
     */
    public function getRegistry($id)
    {
        $url = $this->getApiBaseUrl()."/registries/".$id;

        $headers = [
            $this->getBearerAuthHeader(),
        ];

        return $this->sendGetRequest($url, null, $headers);
    }

    /**
     * Удаление реестра
     * GET /registries/{id}
     */
    public function deleteRegistry($id)
    {
        $url = $this->getApiBaseUrl()."/registries/".$id;

        $headers = [
            $this->getBearerAuthHeader(),
        ];

        return $this->sendDeleteRequest($url, null, $headers);
    }

    /**
     * Выставление счета на оплату штрафов
     * GET /registries/{id}/actions/bill
     */
    public function billRegistry($id)
    {
        $url = $this->getApiBaseUrl()."/registries/".$id."/actions/bill";

        $headers = [
            $this->getBearerAuthHeader(),
        ];

        return $this->sendPostRequest($url, null, $headers);
    }
}