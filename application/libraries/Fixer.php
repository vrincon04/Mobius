<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Fixer {
    /**
     * The public API KEY
     * 
     * @var string 
     */
    protected $apiKey = NULL;

    /**
     * The url for the API
     * 
     * @var string
     */
    protected $apiURL = 'http://data.fixer.io/api';

    /**
     * The base rate.
     * 
     * @var string
     */
    protected $baseRate = NULL;

    /**
	 * Constructor
	 */
    public function __construct() {
        $ci =& get_instance();
        $ci->load->config('fixer',true);

        $this->apiKey = $ci->config->item('fixer_api_key', 'fixer');

        if (empty($this->apiKey))
            throw new Exception('Fixer: Needed API KEY', 1);
        
        $this->baseRate = $ci->config->item('fixer_base_rate', 'fixer');

        if (empty($this->baseRate))
            throw new Exception('Fixer: needed the base rate at which operations are performed', 1);
        
    }

    /**
     * Endpoint will return real-time exchange rate.
     * 
     * @param array $simbols Enter a list of currency codes to limit output currencies.
     * 
     * @return array
     */
    public function latest($simbols = NULL) {
        $data = [
            'access_key' => $this->apiKey,
            'base' => $this->baseRate,
        ];

        if (is_array($simbols))
            $data['symbols'] = implode(',', $simbols);
            
        return $this->request($this->apiURL . '/latest', $data);
    }


    protected function request($url, $params)
	{
        $curl = curl_init();
        $url .= ($params ? '?'.http_build_query($params, NULL, '&') : '');

        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_CUSTOMREQUEST, 'GET');
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

		$responseData = curl_exec($curl);
		$responseInfo = curl_getinfo($curl);

		curl_close($curl);

		return $this->parseResponse($responseInfo, $responseData);
    }
    
    protected function parseResponse($responseInfo, $responseData)
	{
		if ($responseInfo['http_code'] == 200) {
            $response['body'] = json_decode($responseData);
            $response['message'] = 'Done';
            return [
                'header' => $responseInfo,
                'body' => json_decode($responseData),
                'message' => 'Done',
                'error' => FALSE
            ];
		}
		elseif ($this->responseInfo['http_code'] == 400)
		{
			return [
				'error' => TRUE,
				'message' => 'Request could not be parsed as JSON'
			];
		}
		elseif ($this->responseInfo['http_code'] == 401)
		{
			return [
				'error' => TRUE,
				'message' => 'There was an error authenticating the sender account'
			];
		}
		elseif ($this->responseInfo['http_code'] == 500)
		{
			return [
				'error' => 1,
				'message' => 'There was an internal error in the Fixer server while trying to process the request'
			];
		}
		elseif ($this->responseInfo['http_code'] == 503)
		{
			return [
				'error' => 1,
				'message' => 'Server is temporarily unavailable'
            ];
		}
		else
		{
			return [
				'error' => 1,
				'message' => 'Status undefined'
            ];
		}
    }
}