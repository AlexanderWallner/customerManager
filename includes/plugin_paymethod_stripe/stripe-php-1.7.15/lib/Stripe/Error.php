<?php 
/** 
  * Copyright: dtbaker 2012
  * Licence: Please check CodeCanyon.net for licence details. 
  * More licence clarification available here:  http://codecanyon.net/wiki/support/legal-terms/licensing-terms/ 
  * Deploy: 7736 9b9da523f8242d524bc0edab9f79a2e4
  * Envato: ef2d6ec4-fa40-41b6-9980-24587c1aaf55
  * Package Date: 2015-02-04 02:18:43 
  * IP Address: 185.17.207.18
  */

class Stripe_Error extends Exception
{
  public function __construct($message=null, $http_status=null, $http_body=null, $json_body=null)
  {
    parent::__construct($message);
    $this->http_status = $http_status;
    $this->http_body = $http_body;
    $this->json_body = $json_body;
  }

  public function getHttpStatus()
  {
    return $this->http_status;
  }

  public function getHttpBody()
  {
    return $this->http_body;
  }

  public function getJsonBody()
  {
    return $this->json_body;
  }
}
