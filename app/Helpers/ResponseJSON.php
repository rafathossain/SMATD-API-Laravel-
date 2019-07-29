<?php

namespace App\Helpers;

class ResponseJSON{

    private $response = array();
    private $data;

    /*
    * Default constructor
    * 
    */
    public function __construct(){
        $this->response = array();
        $this->data = array();
        $this->response['Status'] = "";
    }
    
    /*
    * Prepare is for creating data response output
    * 
    */
    public function prepare($status_code){
        $this->data['Status_Code'] = $status_code;
        $GetMessage = "statuscodes." . $status_code;
        $this->data['Message'] = config($GetMessage);
        $this->response['Status'] = $this->data;
    }

    /*
    * Make is for creating general response output
    * 
    */
    public function make($data_array){
        $this->response['Data'] = $data_array;
    }

    /*
    * Showing response output
    * 
    */
    public function show(){
        $this->response['API Version'] = env('APP_VERSION', 'API Version');
        $this->response['Author'] = env('API_AUTHOR', 'API Author');
        $this->response['Copyright'] = date('Y') . env('API_COPYRIGHT', 'API Copyright');
        return $this->response;
    }
}

?>