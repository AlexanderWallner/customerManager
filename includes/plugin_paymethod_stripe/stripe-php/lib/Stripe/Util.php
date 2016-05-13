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

abstract class Stripe_Util
{
  public static function isList($array)
  {
    if (!is_array($array))
      return false;

    // TODO: this isn't actually correct in general, but it's correct given Stripe's responses
    foreach (array_keys($array) as $k) {
      if (!is_numeric($k))
        return false;
    }
    return true;
  }

  public static function convertStripeObjectToArray($values)
  {
    $results = array();
    foreach ($values as $k => $v) {
      // FIXME: this is an encapsulation violation
      if ($k[0] == '_') {
        continue;
      }
      if ($v instanceof Stripe_Object) {
        $results[$k] = $v->__toArray(true);
      }
      else if (is_array($v)) {
        $results[$k] = self::convertStripeObjectToArray($v);
      }
      else {
        $results[$k] = $v;
      }
    }
    return $results;
  }

  public static function convertToStripeObject($resp, $apiKey)
  {
    $types = array(
      'card' => 'Stripe_Card',
      'charge' => 'Stripe_Charge',
      'customer' => 'Stripe_Customer',
      'list' => 'Stripe_List',
      'invoice' => 'Stripe_Invoice',
      'invoiceitem' => 'Stripe_InvoiceItem',
      'event' => 'Stripe_Event',
      'transfer' => 'Stripe_Transfer',
      'plan' => 'Stripe_Plan',
      'recipient' => 'Stripe_Recipient',
      'subscription' => 'Stripe_Subscription'
    );
    if (self::isList($resp)) {
      $mapped = array();
      foreach ($resp as $i)
        array_push($mapped, self::convertToStripeObject($i, $apiKey));
      return $mapped;
    } else if (is_array($resp)) {
      if (isset($resp['object']) && is_string($resp['object']) && isset($types[$resp['object']]))
        $class = $types[$resp['object']];
      else
        $class = 'Stripe_Object';
      return Stripe_Object::scopedConstructFrom($class, $resp, $apiKey);
    } else {
      return $resp;
    }
  }
}
