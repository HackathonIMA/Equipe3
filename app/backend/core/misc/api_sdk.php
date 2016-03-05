<?php

class ImaApiClient
{
    public function __construct($clientId)
    {
        $this->clientId = $clientId;
    }
    // public function info_log($content)
    // {
    //     error_log('[info_log]'.print_r($content, true));
    // }

    private function request_api($path, $params)
    {
        $request = curl_init();
        $query_params = http_build_query($params);
        $request_url = IMA_API_BASE_URL."$path?$query_params";

        curl_setopt($request, CURLOPT_URL, $request_url);
        curl_setopt($request, CURLOPT_HTTPHEADER, array('client_id: '.$this->clientId));
        // debug
        curl_setopt($request, CURLOPT_VERBOSE, true);

        $response = curl_exec( $request);
        curl_close($request);

        return json_decode($response);
    }

    public function list_escolas($offset = 0, $limit = 50)
    {
        return $this->request_api('/educacao', ['offset' => $offset, 'limit' => $limit]);
    }

    public function get_escola($id)
    {
        return request_api("/educacao/$id", ['offset' => 0, 'limit' => 20]);
    }
}
