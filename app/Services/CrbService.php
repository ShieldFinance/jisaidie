<?php

namespace App\Services;

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

use App\Helpers\AfricasTalkingGateway;
use App\Http\Models\Message;
use App\Setting;
use App\Mail\AppEmail;
use Edujugon\PushNotification\PushNotification;
use App\Http\Models\CustomerDevice;
use App\Http\Models\Customer;
class CrbService {

    /**
     * Send money to a single customer
     * @param type $payload
     */
    public function checkID($payload){
      
    //contact crb for detail verification
		
		$response=array();
		$username = Setting::where('setting_name', 'crb_username')->first()->setting_value;
        $password = Setting::where('setting_name', 'crb_password')->first()->setting_value;
		$endpoint=Setting::where('setting_name', 'crb_endpoint')->first()->setting_value;
		$headers = array(
		   'SOAPAction:urn:getProduct102',
		   'Content-Type:text/xml;charset=UTF-8',
		   
		);
		
		$curl_req='<soapenv:Envelope xmlns:soapenv="http://schemas.xmlsoap.org/soap/envelope/" xmlns:q0="http://ws.crbws.transunion.ke.co/" xmlns:xsd="http://www.w3.org/2001/XMLSchema" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance">
			  <soapenv:Body>
				<q0:getProduct102>
				<username>WS_SIC1</username>
					<password>Tuvwxz</password>
					<code>2151</code>
					<infinityCode>1328KE46406</infinityCode>
					<name1>'.$payload["first_name"].'</name1>
					<name2>'.$payload["middle_name"].' '.$payload["last_name"].'</name2>
					<nationalID>'.$payload["id_number"].'</nationalID>
					<reportSector>1</reportSector>
					<reportReason>2</reportReason>
				</q0:getProduct102>
			  </soapenv:Body>
			</soapenv:Envelope>';
			
			
		$ch = curl_init($endpoint);
		curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
		curl_setopt($ch, CURLOPT_VERBOSE, 0);       
		curl_setopt($ch, CURLOPT_USERPWD, "$username:$password");
		curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $curl_req);
		
		// execute!
	 	$curl_response = curl_exec($ch);
		//echo '<pre>';print_r($curl_response);print_r($payload);exit;
		// close the connection, release resources used
		curl_close($ch);
		
		
	    $dom = new \DOMDocument();
		$res=str_replace("<?xml version='1.0' encoding='UTF-8'?>","",$curl_response);
		$dom->loadXML( $res, LIBXML_NOBLANKS|LIBXML_PARSEHUGE );
	
		$crb_meta_data=array();
		$crb_meta_data["crb_responsecode"]= $dom->getElementsbyTagName( 'responseCode' )->item(0)->nodeValue;
		
		$response["code"]=$crb_meta_data["crb_responsecode"];
		
		
		if($crb_meta_data["crb_responsecode"]==200){
				$crb_meta_data["crb_full_name"]= $dom->getElementsbyTagName( 'fullName' )->item(0)->nodeValue;
				$crb_meta_data["crb_surname_names"]= $dom->getElementsbyTagName( 'surname' )->item(0)->nodeValue;
				$crb_meta_data["crb_other_names"]= $dom->getElementsbyTagName( 'otherNames' )->item(0)->nodeValue;
				$crb_meta_data["crb_dob"]= $dom->getElementsbyTagName( 'dateOfBirth' )->item(0)->nodeValue;
				$crb_meta_data["crb_nationalID"]= $dom->getElementsbyTagName( 'nationalID' )->item(0)->nodeValue;
				
				$crbNames=explode(" ",$crb_meta_data["crb_other_names"]);
			   
			  
				
			   
				
					
					$k=0;
					if (strpos($crb_meta_data["crb_full_name"],strtoupper($payload["first_name"] )) !== false) {
						$response["first_name"]=1;
						$k=$k+1;
					}
					if (strpos($crb_meta_data["crb_full_name"],strtoupper($payload["middle_name"] )) !== false) {
						$response["middle_name"]=1;
						 $k=$k+1;
					}
					if (strpos($crb_meta_data["crb_full_name"],strtoupper($payload["last_name"] )) !== false) {
						$response["last_name"]=1;
						 $k=$k+1;
					}
				   
					if($k>=1 ){
					  $response["match"]=1;
					}else{
					  $response["match"]=2;
					}
					
					
					 $response["message"]="successful_query";
		}else{
			$response["match"]=3;
            $response["message"]="query_failed";
        }
		$response["updated"] = Customer::find($payload["customer_id"])->update(['crb_data' => json_encode($crb_meta_data),'id_verified'=>$response["match"]]);
      
	    return $response;
	}//
    
   
    public function checkCreditScore($payload) {
		$response=array();
		$username = Setting::where('setting_name', 'crb_username')->first()->setting_value;
        $password = Setting::where('setting_name', 'crb_password')->first()->setting_value;
		$endpoint=Setting::where('setting_name', 'crb_endpoint')->first()->setting_value;
              $headers = array(
                  'SOAPAction:urn:getProduct115',
                  'Content-Type:text/xml;charset=UTF-8',
                  
                );
                               
                               // set post fields
               $curl_req='
                     <soapenv:Envelope xmlns:soapenv="http://schemas.xmlsoap.org/soap/envelope/" xmlns:q0="http://ws.crbws.transunion.ke.co/"    xmlns:xsd="http://www.w3.org/2001/XMLSchema" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance">
                       <soapenv:Body>
                         <q0:getProduct115>
                           <username>WS_SIC1</username>
                           <password>Tuvwxz</password>
                           <code>2151</code>
                           <infinityCode>1328KE46406</infinityCode>
                            <name1>'.$payload["first_name"].'</name1>
							<name2>'.$payload["middle_name"].' '.$payload["last_name"].'</name2>
							<nationalID>'.$payload["id_number"].'</nationalID>
                           <reportSector>1</reportSector>
                           <reportReason>2</reportReason>
                         </q0:getProduct115>
                       </soapenv:Body>
                     </soapenv:Envelope>
                     ';
               
               $ch = curl_init($endpoint);
               curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
               curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
               curl_setopt($ch, CURLOPT_VERBOSE, 0);
			   curl_setopt($ch, CURLOPT_CONNECTTIMEOUT ,0); 
               curl_setopt($ch, CURLOPT_TIMEOUT, 60); //timeout in seconds
               curl_setopt($ch, CURLOPT_USERPWD, "$username:$password");
               curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
               curl_setopt($ch, CURLOPT_POSTFIELDS, $curl_req);
			   // execute!
				$curl_response = curl_exec($ch);
				
				// close the connection, release resources used
				curl_close($ch);
				
				// do anything you want with your response
			   
						
				$dom = new \DOMDocument();
				$res=str_replace("<?xml version='1.0' encoding='UTF-8'?>","",$curl_response);
				$dom->loadXML( $res, LIBXML_NOBLANKS|LIBXML_PARSEHUGE );
                                
				$response["credit_grade"]= $dom->getElementsbyTagName( 'grade' )->item(0)->nodeValue;
				$response["credit_score"]= $dom->getElementsbyTagName( 'positiveScore' )->item(0)->nodeValue;
				
				$reasons=array();
				$reasons[]=$dom->getElementsbyTagName( 'reasonCodeAARC1' )->item(0)->nodeValue;
				$reasons[]=$dom->getElementsbyTagName( 'reasonCodeAARC2' )->item(0)->nodeValue;
				$reasons[]=$dom->getElementsbyTagName( 'reasonCodeAARC3' )->item(0)->nodeValue;
				$reasons[]=$dom->getElementsbyTagName( 'reasonCodeAARC4' )->item(0)->nodeValue;
				$response["existing_reasons"]= implode(',',$reasons);
				
				  $loan_status=array();
				  foreach( $dom->getElementsbyTagName( 'accountStatus' ) as $status){
					  
					  $loan_status[]=$status->nodeValue;
				  }
				if(in_array('DEFAULT',$loan_status)){
				  $response["has_defaulted"]=1;
				  
				}
					
        return $response;
    }
    public function checkBalance(){
		$username = Setting::where('setting_name', 'crb_username')->first()->setting_value;
        $password = Setting::where('setting_name', 'crb_password')->first()->setting_value;
		$endpoint=Setting::where('setting_name', 'crb_endpoint')->first()->setting_value;
		
		$headers = array(
		   'SOAPAction:urn:getProduct102',
		   'Content-Type:text/xml;charset=UTF-8',
		   
		);
						
						// set post fields
		$curl_req='
			  <soapenv:Envelope xmlns:soapenv="http://schemas.xmlsoap.org/soap/envelope/" xmlns:q0="http://ws.crbws.transunion.ke.co/" xmlns:xsd="http://www.w3.org/2001/XMLSchema" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance">
				<soapenv:Body>
				  <q0:getCreditBal>
					<username>WS_SIC1</username>
					<password>Tuvwxz</password>
					<code>2151</code>
					<infinityCode>1328KE46406</infinityCode>
				  </q0:getCreditBal>
				</soapenv:Body>
			  </soapenv:Envelope>
			  ';
		
		$ch = curl_init($endpoint);
		curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
		curl_setopt($ch, CURLOPT_VERBOSE, 0);       
		curl_setopt($ch, CURLOPT_USERPWD, "$username:$password");
		curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $curl_req);
		
		// execute!
		$curl_response = curl_exec($ch);
		
		// close the connection, release resources used
		curl_close($ch);
		$dom = new DOMDocument();
	    $res=str_replace("<?xml version='1.0' encoding='UTF-8'?>","",$curl_response);
	    $dom->loadXML( $curl_response, LIBXML_NOBLANKS );
	    $credit="";
	    $credit= $dom->getElementsbyTagName('credit' )->item(0)->nodeValue;
		
		return $credit;
    }
   
   
}
