<?php

/**
 * SageOne PHP API Wrapper
 *
 * A simple API wrapper to integrate SageOne Accounting API with your project.
 * 
 * Official API Documentation: 
 *      https://accounting.sageone.co.za/Marketing/DeveloperProgram.aspx
 *
 * @copyright Tow.com.au
 * @license   GNU Lesser General Public License <http://www.gnu.org/licenses/lgpl.html>
 * @link      https://github.com/Tow-com-au/SageOne-Accounting-PHP
 * @author    S. White <sharnw@tow.com.au>
 */

class Sage {

    private $apiUrl, $apiKey, $authCode, $companyId, $debug = false;
    
    function __construct($apiUrl, $apiKey, $authCode, $companyId = '', $debug = false){
        $this->apiUrl = $apiUrl;
        $this->apiKey = $apiKey;
        $this->authCode = $authCode;
        $this->companyId = $companyId;
        $this->debug = $debug;
    }

    private function call($endpoint, $type, $post_data=false, $get_data=false){
        
        if($post_data){
            $post_data = json_encode($post_data);
        }
        
        $ch = curl_init();
        
        $curlURL = "{$this->apiUrl}/{$endpoint}?apikey={$this->apiKey}&companyId={$this->companyId}";
        if (!empty($get_data)) {
            foreach ($get_data as $key => $val) $curlURL .= "&{$key}={$val}";
        }

        curl_setopt($ch, CURLOPT_URL, $curlURL);
        
        $curl_options = array(
            CURLOPT_CONNECTTIMEOUT => 10,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_TIMEOUT        => 60,
            CURLOPT_HEADER         => true,
            CURLOPT_USERAGENT      => 'sage-one-accounting-api-wrapper-php'
        );

        switch($type){
            case 'post':
                $curl_options = $curl_options + array(
                    CURLOPT_HTTPHEADER => array(
                        'Content-Type:application/json',
                        'Authorization: Basic '. $this->authCode
                    ),
                    CURLOPT_POST        => 1,
                    CURLOPT_POSTFIELDS  => $post_data
                );
                break;
            case 'get':
                $curl_options = $curl_options + array(
                    CURLOPT_HTTPHEADER => array(
                        'Content-Type:application/json',
                        'Authorization: Basic '. $this->authCode
                    )
                );
                break;
            case 'delete':
                $curl_options = $curl_options + array(
                    CURLOPT_HTTPHEADER => array(
                        'Content-Type:application/json',
                        'Authorization: Basic '. $this->authCode
                    ),
                    CURLOPT_CUSTOMREQUEST => "DELETE"
                );
                break;
            break;
        }

        // Set curl options
        curl_setopt_array($ch, $curl_options);
        
        // Send the request
        $result = curl_exec($ch);
        $error = curl_errno($ch);
        $code = curl_getinfo($ch, CURLINFO_HTTP_CODE);

        $header_size = curl_getinfo($ch, CURLINFO_HEADER_SIZE);
        $header = substr($result, 0, $header_size);
        $body = substr($result, $header_size);

        if ($code == 429) { // request limit exceeded
            $headers = $this->get_headers_from_curl_response($header);
            if (isset($headers['X-RequestLimit-ExceededReason'])) {
                $this->error .= "\r\n\r\n Request Limit Exceeded: ".$headers['X-RequestLimit-ExceededReason']; 
            }
        }
        
        if($this->debug){
            var_dump($result);
            var_dump($error);
        }
        
        // Close the connection
        curl_close($ch);
        
        return json_decode($body, true);
    }

    function get_headers_from_curl_response($response) {
        $headers = array();
        $header_text = substr($response, 0, strpos($response, "\r\n\r\n"));
        foreach (explode("\r\n", $header_text) as $i => $line) {
            if ($i === 0) {
                $headers['http_code'] = $line;
            }
            else {
                list ($key, $value) = explode(': ', $line);
                $headers[$key] = $value;
            }
        }
        return $headers;
    }
    
    private function get($endpoint, $data=false){
        return $this->call($endpoint, 'get', false, $data);
    }
    
    private function post($endpoint, $data){
        return $this->call($endpoint, 'post', $data);
    }

    private function delete($endpoint){
        return $this->call($endpoint, 'delete');
    }

    public function getItem($item_type, $item_id) {
        return $this->get($item_type.'/Get/'.$item_id);
    }

    public function listItems($item_type, $data = false) {
        return $this->get($item_type.'/Get', $data);
    }

    public function saveItem($item_type, $data) {
        return $this->post($item_type.'/Save', $data);
    }

    public function deleteItem($item_type, $id) {
        return $this->delete($item_type.'/Delete/'.$id);
    }

}



?>