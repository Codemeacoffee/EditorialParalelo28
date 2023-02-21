<?php

namespace Paralelo28\Http\Controllers;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;
use Paralelo28\User;
use Exception;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    /*---------------------------------------- U T I L S ----------------------------------------*/

    function generateRandomString($length = 10)
    {
        return substr(str_shuffle(str_repeat($x = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ', ceil($length / strlen($x)))), 1, $length);
    }

    function getValidatedDirection(Request $request)
    {
        $response = $this->validateDirection(htmlspecialchars($request['direction']), false);

        if(!$response) return abort(400);

        return json_encode($response);
    }
    
    function validateDirection($direction, $includeUrl){
        try{
            if($includeUrl){
                $mapsBaseUrl = "https://maps.google.es/maps?q=";
                $direction = htmlspecialchars_decode($mapsBaseUrl.$direction);
            }
            $url = preg_replace('!\s+!', '+', $direction);
            
            $content = file_get_contents($url, true);
             
            $needle = "/preview/place/";
            $initIndex = strpos($content, $needle) + strlen($needle);
            $endIndex = strpos($content, '/', $initIndex);
            $direction = str_replace('+', ' ', substr($content, $initIndex, $endIndex - $initIndex));

            $directionParts = explode(',', $direction);
            
            switch (Count($directionParts)){
                case 6:
                    $postalCode = $this->postalCodeParse($directionParts[3]);

                    if(intval($postalCode) == 0) return false;
                    if(strtolower(urldecode($directionParts[5])) != ' spain') return false;

                    $response = array(
                        'direction' => urldecode($directionParts[0]).', '.urldecode($directionParts[1]),
                        'extraInfo' => urldecode($directionParts[2]),
                        'postalCode' => $postalCode,
                        'township' => urldecode($directionParts[4])
                    );
                    break;
                case 5:
                    $postalCode = $this->postalCodeParse($directionParts[2]);

                    if(intval($postalCode) == 0) return false;
                    if(strtolower(urldecode($directionParts[4])) != ' spain') return false;

                    $response = array(
                        'direction' => urldecode($directionParts[0]),
                        'extraInfo' => urldecode($directionParts[1]),
                        'postalCode' => $postalCode,
                        'township' => urldecode($directionParts[3])
                    );
                    break;
                case 4:
                    $postalCode = $this->postalCodeParse($directionParts[1]);

                    if(intval($postalCode) == 0) return false;
                    if(strtolower(urldecode($directionParts[3])) != ' spain') return false;

                    $response = array(
                        'direction' => urldecode($directionParts[0]),
                        'extraInfo' => null,
                        'postalCode' => $postalCode,
                        'township' => urldecode($directionParts[2])
                    );
                    break;
                default:
                    return false;
            }
            return $response;
        }catch (Exception $e){
            return false;
        }
    }
    
    function deepValidateDirection($direction){
        try{
            $url = 'http://i18napis.appspot.com/address/data/ES/';
       
            $direction = explode(',', $direction);
            $direction = $str = ltrim($direction[Count($direction)-1], ' ');
        
            $url = $url.$direction;
            $url = preg_replace('!\s+!', '%20', $url);
           
            $response = json_decode(file_get_contents($url), true);
          
            if($response['id'] == 'data/ES/'.$direction)return true;
            else return false;
        }catch (Exception $e){
            return false;
        }
    }

    function validateUser($userCookie, $userSession){
        $userCookie = htmlspecialchars($userCookie);
        $sessionCookie = htmlspecialchars($userSession);

        if(!$userCookie || !$sessionCookie){
            return false;
        }else{
            $user = User::where('email', $userCookie)->first();

            if(!$user){
                return false;
            }else{
                if($user->session_token != $sessionCookie){
                    return false;
                }else{
                    return $user;
                }
            }
        }
    }
    
     protected function  getIP(){
        if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
            $ip = $_SERVER['HTTP_CLIENT_IP'];
        } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
        } else {
            $ip = $_SERVER['REMOTE_ADDR'];
        }
        $localIP = getHostByName(getHostName());
        $ip = $ip.'_'.$localIP;
        return $ip;
    }

    function postalCodeParse($postalCode){
        $postalCode = strval(intval($postalCode));

        while(strlen($postalCode) < 5){
            $postalCode = '0'.$postalCode;
        }

        return $postalCode;
    }

    function validateStringForFileName($string){
        return str_replace(array('/',  '\\', ':', '*', '?', '"', '<', '>','|', '.', "'", ',', '-', ' '),'', $string);
    }

    function getTaxesByDirection($direction){
        if (strpos($direction, 'Las Palmas') !== false ||
            strpos($direction, 'Santa Cruz de Tenerife') !== false ){
            return 'IGIC';
        }else{
            return 'IVA';
        }
    }
    
      function revertDate($date, $divider = '-'){
        $dateParts = explode('-', $date);

        return $dateParts[2].$divider.$dateParts[1].$divider.$dateParts[0];
    }
    
    function getNonReturnableUrls(){
        //---------- Urls tha cannot be directly returned to the user ----------//
        return [
            'emails.confirmationemail',
            'emails.newsletterpromotion',
            'emails.emaillayout',
            'emails.customerservice',
            'emails.workwithus',
            'emails.faq',
            'emails.passwordreset',
            'emails.accesswarning',
            'emails.surveyemail',
            'emails.refundsolicitude',
            'emails.refundresponse',
            'errors.404',
            'errors.403',
            'errors.errorlayout',
            'admineditpage',
            'admineditsurvey',
            'adminlayout',
            'layout',
            'manuals',
            'pdfviewer',
            'searchresults',
            'statisticgraphics',
            'shipmentinfo',
            'blogentry',
            'newsletterpromotion',
            'expediteCoupons',
            'passwordreset',
            'temporaryblock',
            'personalsurvey',
            'administrateshipment',
            'administrateshipments'
        ];
    }
}
