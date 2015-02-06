<?php

namespace FlorProject\FrontendBundle\Controller;

use FlorProject\BackendBundle\Entity\Give;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

use FlorProject\BackendBundle\Form\Type\UserFormType;
use Symfony\Component\HttpFoundation\JsonResponse;

class FrontendCatalogController extends Controller
{
    public function showCatalogAction()
    {
        return $this->render('FlorProjectFrontendBundle:Catalog:catalog.html.twig');
    }

    public function downloadAttachAction($codeGive)
    {
        $give = $this->getDoctrine()->getManager()->getRepository("FlorProjectBackendBundle:Give")->findOneByCodeGive($codeGive);

        return new BinaryFileResponse($give->getAbsolutePath());

    }




    public function FamiliesAction($giftType)
    {
        $typeCode = 1;
        if ($giftType == 'flores') {
            $typeCode = 2;
        } else if ($giftType == 'cajas') {
            $typeCode = 3;
        }


        $em = $this->getDoctrine()->getManager();

            $query = "SELECT g FROM FlorProjectBackendBundle:Gift g JOIN g.family f WHERE f.giftType = :typeCode GROUP BY f.id";

            $queryObject = $em->createQuery($query)->setParameter('typeCode', $typeCode);
            $gifts = $queryObject->getResult();

        return $this->render('FlorProjectFrontendBundle:Catalog:families.html.twig', array(
            'gifts' => $gifts,
            'giftType' => $giftType
        ));
    }


    public function step1Action($giftType, $family)
    {
        $typeCode = 1;
        $giftElige = 'Elige un candado';
        if ($giftType == 'flores') {
            $typeCode = 2;
            $giftElige = 'Elige una flor';

        } else if ($giftType == 'cajas') {
            $typeCode = 3;
            $giftElige = 'Elige una caja';

        }

        $gifts = array();
        $firstFamily = null;

        $em = $this->getDoctrine()->getManager();
        $families = $em->createQuery("SELECT f FROM FlorProjectBackendBundle:Family f WHERE f.giftType = :typeCode")->setParameter('typeCode', $typeCode)->getResult();

        $error = false;
        $errorMsg = "";

        if ($family == 0) {
            if (count($families) != 0)
                $firstFamily = $families[0];
            else {
                $error = true;
                $errorMsg = "No existen familias de regalos creadas. Consulte con el equipo de administración.";
            }
        } else {
            $firstFamily = $em->getRepository("FlorProjectBackendBundle:Family")->find($family);
        }


        if ($firstFamily == null) {
            if ($error == false) {
                $error = true;
                $errorMsg = "No existe la familia solicitada. Esta acción es propia de atacantes, se registrará para ser verificada.";
            }
        } else {
            $query = "SELECT g FROM FlorProjectBackendBundle:Gift g JOIN g.family f WHERE f.giftType = :typeCode AND f.id = :idFirstFamily";
            $queryObject = $em->createQuery($query)->setParameter('typeCode', $typeCode)->setParameter('idFirstFamily', $firstFamily->getId());
            $gifts = $queryObject->getResult();
        }



        return $this->render('FlorProjectFrontendBundle:Catalog:send_gift.html.twig', array(
            'firstFamily' => $firstFamily,
            'gifts' => $gifts,
            'families' => $families,
            'error' => $error,
            'errorMsg' => $errorMsg,
            'giftType' => $giftType,
            'giftElige'=> $giftElige,
        ));
    }


    public function step2Action($codeGift)
    {
        $em = $this->getDoctrine()->getManager();
        $gift = $em->getRepository('FlorProjectBackendBundle:Gift')->find($codeGift);

        $typeCode = $gift->getFamily()->getGiftType();

            $giftType = 'candados';
        if ($typeCode == 2) {
            $giftType = 'flores';
        } else if ($typeCode == 3) {            ;
            $giftType = 'cajas';
        }



        return $this->render('FlorProjectFrontendBundle:Catalog:step2.html.twig', array(
            'gift' => $gift,
            'giftType' => $giftType,
        ));
    }


    public function step2FreeAction($codeGift)
    {
        $em = $this->getDoctrine()->getManager();
        $gift = $em->getRepository('FlorProjectBackendBundle:Gift')->find($codeGift);

        $typeCode = $gift->getFamily()->getGiftType();

        $give = new Give();
        $give->setGift($gift);
        $give->setLatitude('');
        $give->setLongitude('');
        $give->setLatitude2('');
        $give->setLongitude2('');
        $give->setZoom('');
        $give->setLatitude3('');
        $give->setLongitude3('');
        $give->setZoom2('');
        $give->setHeading('');
        $give->setPitch('');
        $give->setSenderEmail('');
        $give->setReceptorEmail('');
        $give->setMessage('Free');
        $give->setTransactionId('');
        $give->setSended('Sent');
        $give->setPayed('2');

        $securityContext = $this->get('security.context');

        if ($securityContext->isGranted('IS_AUTHENTICATED_REMEMBERED')) {
            $user = $securityContext->getToken()->getUser();
            $give->setUser($user);
        }


        $em->persist($give);
        $em->flush();

        return $this->redirect($this->generateUrl('step3', array(
            'codeGive' => $give->getCodeGive()
        )));

    }



    public function paypalAction(Request $request)
    {
        $from = $request->get('senderEmail');
        $to = $request->get('receptorEmail');
        $mess =  preg_replace( "/\r|\n/", " ", $request->get('message') ).' ';
        $file = $request->files->get('file');
        $codeGift = $request->get('codeGift');


        $em = $this->getDoctrine()->getManager();
        $gift = $em->getRepository('FlorProjectBackendBundle:Gift')->find($codeGift);

        $typeCode = $gift->getFamily()->getGiftType();

        $giftType = 'Candado ';
        if ($typeCode == 2) {
            $giftType = 'Flor ';
        } else if ($typeCode == 3) {            ;
            $giftType = 'Caja ';
        }



        $give = new Give();
        $give->setGift($gift);
        $give->setLatitude('');
        $give->setLongitude('');
        $give->setLatitude2('');
        $give->setLongitude2('');
        $give->setZoom('');
        $give->setLatitude3('');
        $give->setLongitude3('');
        $give->setZoom2('');
        $give->setHeading('');
        $give->setPitch('');
        $give->setSenderEmail($from);
        $give->setReceptorEmail($to);
        $give->setMessage($mess);
        $give->setFile($file);
        $give->setTransactionId('');
        $give->setSended('');

        $securityContext = $this->get('security.context');

        if ($securityContext->isGranted('IS_AUTHENTICATED_REMEMBERED')) {
            $user = $securityContext->getToken()->getUser();
            $give->setUser($user);
        }


        $em->persist($give);
        $em->flush();


        $_SESSION['codeGive'] = $give->getCodeGive();


        /********************************************
        PayPal API Module

        Defines all the global variables and the wrapper functions
         ********************************************/
        $PROXY_HOST = '127.0.0.1';
        $PROXY_PORT = '808';

        //$SandboxFlag = true;
        //Si no es prueba
        $SandboxFlag = true;



        if ($SandboxFlag == true)
        {
            //  CREDENCIALES DE PRUEBAtrue
            $API_UserName="seller1_api1.email.com";
            $API_Password="1403562005";
            $API_Signature="AFcWxV21C7fd0v3bYYYRCpSSRl31APx7ti4FHc02U-dlUn4v1H32hOTT";
            $merchant_mail_0 = 'seller3@email.com';
            $_SESSION['merchant_mail_0'] = $merchant_mail_0;
        }
        else
        {
            // ESTAS SON LAS CREDENCIALES DE VERDAD //


        }
        // BN Code 	is only applicable for partners
        $sBNCode = "PP-ECWizard";


        if ($SandboxFlag == true)
        {
            $API_Endpoint = "https://api-3t.sandbox.paypal.com/nvp";
            $PAYPAL_URL = "https://www.sandbox.paypal.com/webscr?cmd=_express-checkout&token=";
        }
        else
        {
            $API_Endpoint = "https://api-3t.paypal.com/nvp";
            $PAYPAL_URL = "https://www.paypal.com/cgi-bin/webscr?cmd=_express-checkout&token=";
        }

        $USE_PROXY = false;
        $version="64";

        $currencyCodeType = "EUR";
        $paymentType = "Sale";

        $_SESSION['params']['API_UserName'] = $API_UserName;
        $_SESSION['params']['API_Password'] = $API_Password;
        $_SESSION['params']['API_Signature'] = $API_Signature;
        $_SESSION['params']['USE_PROXY'] = $USE_PROXY;
        $_SESSION['params']['PROXY_HOST'] = $PROXY_HOST;
        $_SESSION['params']['PROXY_PORT'] = $PROXY_PORT;
        $_SESSION['params']['sBNCode'] = $sBNCode;
        $_SESSION['params']['version'] = $version;
        $_SESSION['params']['API_Endpoint'] = $API_Endpoint;


        //$order = '?order='.$_SESSION["order"];
 $url = $this->generateUrl('send_give');  // es /web/app_dev.php/give/send. no e sid ara problemas el /web
        $returnURL = "http://".$_SERVER['HTTP_HOST']."$url";


        $cancelURL = "http://".$_SERVER['HTTP_HOST']."/";


        $paymentAmount_0 = $gift->getPrice();
        $desc_0 = 'LovenLock '.$giftType.$gift->getName();
        $cartId = '1234';


        $_SESSION['params']["Payment_Amount_0"] = $paymentAmount_0;
        $_SESSION['params']["desc_0"] = $desc_0;
        $_SESSION['params']["cartId_paypal"] = $cartId;


        $resArray = self::CallShortcutExpressCheckout ($paymentAmount_0,$desc_0,$cartId, $currencyCodeType, $paymentType, $returnURL, $cancelURL, $merchant_mail_0, $API_Endpoint, $version, $API_UserName, $API_Password, $API_Signature, $USE_PROXY, $PROXY_HOST, $PROXY_PORT,$sBNCode);
        $ack = strtoupper($resArray["ACK"]);
        if($ack=="SUCCESS" || $ack=="SUCCESSWITHWARNING")
        {
           // self::RedirectToPayPal ( $resArray["TOKEN"], $PAYPAL_URL );

            $payPalURL = $PAYPAL_URL . $resArray["TOKEN"];

            return $this->redirect($payPalURL);
        }
        else
        {
            //Display a user friendly Error on the page using any of the following error information returned by PayPal
            $ErrorCode = urldecode($resArray["L_ERRORCODE0"]);
            $ErrorShortMsg = urldecode($resArray["L_SHORTMESSAGE0"]);
            $ErrorLongMsg = urldecode($resArray["L_LONGMESSAGE0"]);
            $ErrorSeverityCode = urldecode($resArray["L_SEVERITYCODE0"]);

            echo "SetExpressCheckout API call failed. ";
            echo "Detailed Error Message: " . $ErrorLongMsg;
            echo "Short Error Message: " . $ErrorShortMsg;
            echo "Error Code: " . $ErrorCode;
            echo "Error Severity Code: " . $ErrorSeverityCode;

            return $this->redirect($cancelURL);
        }

     }


    public function sendGiveAction()
    {
        $cancelURL = "http://".$_SERVER['HTTP_HOST']."/";


        $codeGive = $_SESSION['codeGive'];

        $give = $this->getDoctrine()->getManager()->getRepository("FlorProjectBackendBundle:Give")->findOneByCodeGive($codeGive);


        $tx_method = 'PayPal';

        $token = "";
        if (isset($_REQUEST['token']))
        {
            $token = $_REQUEST['token'];

        }


        // If the Request object contains the variable 'token' then it means that the user is coming from PayPal site.
        if ( $token != "" )
        {


            $resArray = self::GetShippingDetails( $token );
            $ack = strtoupper($resArray["ACK"]);
            if( $ack == "SUCCESS" || $ack == "SUCESSWITHWARNING")
            {

                $payerId 			= $resArray["PAYERID"]; // ' Unique PayPal customer account identification number.
                $payerStatus		= $resArray["PAYERSTATUS"]; // ' Status of payer. Character length and limitations: 10 single-byte alphabetic characters.
            }
            else
            {
                //Display a user friendly Error on the page using any of the following error information returned by PayPal
                $ErrorCode = urldecode($resArray["L_ERRORCODE0"]);
                $ErrorShortMsg = urldecode($resArray["L_SHORTMESSAGE0"]);
                $ErrorLongMsg = urldecode($resArray["L_LONGMESSAGE0"]);
                $ErrorSeverityCode = urldecode($resArray["L_SEVERITYCODE0"]);

                echo "GetExpressCheckoutDetails API call failed. ";
                echo "Detailed Error Message: " . $ErrorLongMsg;
                echo "Short Error Message: " . $ErrorShortMsg;
                echo "Error Code: " . $ErrorCode;
                echo "Error Severity Code: " . $ErrorSeverityCode;

                return $this->redirect($cancelURL);
            }
        }



        $finalPaymentAmount_0 =  $_SESSION['params']["Payment_Amount_0"];
        $desc_0 = $_SESSION['params']["desc_0"];
        $cartId = $_SESSION['params']["cartId_paypal"];
        $merchant_mail_0 = $_SESSION['merchant_mail_0'];



        $resArray = self::ConfirmPayment ( $finalPaymentAmount_0, $desc_0, $cartId, $merchant_mail_0  );
        $ack = strtoupper($resArray["ACK"]);
        if( $ack == "SUCCESS" || $ack == "SUCCESSWITHWARNING" )
        {

            $transactionId_0    = $resArray["PAYMENTINFO_0_TRANSACTIONID"]; // ' Unique transaction ID of the payment. Note:  If the PaymentAction of the request was Authorization or Order, this value is your AuthorizationID for use with the Authorization & Capture APIs.

            $paymentStatus_0	= $resArray["PAYMENTINFO_0_PAYMENTSTATUS"];


        }
        else
        {
            //Display a user friendly Error on the page using any of the following error information returned by PayPal
            $ErrorCode = urldecode($resArray["L_ERRORCODE0"]);
            $ErrorShortMsg = urldecode($resArray["L_SHORTMESSAGE0"]);
            $ErrorLongMsg = urldecode($resArray["L_LONGMESSAGE0"]);
            $ErrorSeverityCode = urldecode($resArray["L_SEVERITYCODE0"]);

            echo "GetExpressCheckoutDetails API call failed on transaction 0. ";
            echo "Detailed Error Message: " . $ErrorLongMsg;
            echo "Short Error Message: " . $ErrorShortMsg;
            echo "Error Code: " . $ErrorCode;
            echo "Error Severity Code: " . $ErrorSeverityCode;

            //Display a user friendly Error on the page using any of the following error information returned by PayPal
            $ErrorCode = urldecode($resArray["L_ERRORCODE1"]);
            $ErrorShortMsg = urldecode($resArray["L_SHORTMESSAGE1"]);
            $ErrorLongMsg = urldecode($resArray["L_LONGMESSAGE1"]);
            $ErrorSeverityCode = urldecode($resArray["L_SEVERITYCODE1"]);

            echo "GetExpressCheckoutDetails API call failed on transaction 1. ";
            echo "Detailed Error Message: " . $ErrorLongMsg;
            echo "Short Error Message: " . $ErrorShortMsg;
            echo "Error Code: " . $ErrorCode;
            echo "Error Severity Code: " . $ErrorSeverityCode;

            return $this->redirect($cancelURL);
        }


        unset($_SESSION['Params']);

        if($paymentStatus_0 == 'Completed'){


            //CUADNO SE PAGUE
            $give->setPayed('1');

            $give->setTransactionId($transactionId_0);


            $em = $this->getDoctrine()->getManager();
            $em->merge($give);
            $em->flush();

            return $this->redirect($this->generateUrl('step3', array(
                'codeGive' => $give->getCodeGive()
            )));

        }

    }


    public function step3Action($codeGive)
    {

        $give = $this->getDoctrine()->getManager()->getRepository("FlorProjectBackendBundle:Give")->findOneByCodeGive($codeGive);

        if($give->getPayed() !=  '1' || $give->getPayed() != '2'){
            return $this->redirect($this->generateUrl('flor_project_frontend_homepage'));
        }else{
            $codeGift = $give->getGift()->getId();
            $em = $this->getDoctrine()->getManager();
            $gift = $em->getRepository('FlorProjectBackendBundle:Gift')->find($codeGift);

            return $this->render('FlorProjectFrontendBundle:Catalog:step3.html.twig', array(
                'codeGift' => $codeGift,
                'gift' => $gift,
                'codeGive' => $give->getCodeGive()));
        }

    }


    public function createViewAction(Request $request)
    {
        $codeGive = $request->get('codeGive');
        $give = $this->getDoctrine()->getManager()->getRepository("FlorProjectBackendBundle:Give")->findOneByCodeGive($codeGive);


        $latitude = floatval($request->get('latitude'));
        $longitude = floatval($request->get('longitude'));
        $latitude2 = floatval($request->get('latitude2'));
        $longitude2 = floatval($request->get('longitude2'));
        $zoom = floatval($request->get('zoom'));
        $latitude3 = floatval($request->get('latitude3'));
        $longitude3 = floatval($request->get('longitude3'));
        $zoom2 = floatval($request->get('zoom2'));
        $heading = floatval($request->get('heading'));
        $pitch = floatval($request->get('pitch'));

        $give->setLatitude($latitude);
        $give->setLongitude($longitude);
        $give->setLatitude2($latitude2);
        $give->setLongitude2($longitude2);
        $give->setZoom($zoom);
        $give->setLatitude3($latitude3);
        $give->setLongitude3($longitude3);
        $give->setZoom2($zoom2);
        $give->setHeading($heading);
        $give->setPitch($pitch);

        $em = $this->getDoctrine()->getManager();
        $em->merge($give);
        $em->flush();

        /*$securityContext = $this->get('security.context');

        if ($securityContext->isGranted('IS_AUTHENTICATED_REMEMBERED')) {
            $user = $securityContext->getToken()->getUser();
            $give->setUser($user);
        }*/

        return $this->redirect($this->generateUrl('show_gift', array(
            'codeGive' => $give->getCodeGive()
        )));
    }

    public function showGiftAction($codeGive)
    {
        $give = $this->getDoctrine()->getManager()->getRepository("FlorProjectBackendBundle:Give")->findOneByCodeGive($codeGive);

        if($give->getSended() != 'Sent'){
            $contentType = 'text/html';
            $mailer = $this->get('mailer');
            $message = $mailer->createMessage()
                ->setSubject('Ha recibido un candado de amor.')
                ->setFrom($give->getSenderEmail())
                ->setTo($give->getReceptorEmail())
                ->setContentType($contentType)
                ->setBody(
                $this->renderView(
                    'FlorProjectFrontendBundle:Catalog:email.html.twig',
                    array('give' => $give)
                )
            );
            $mailer->send($message);
            $give->setSended('Sent');
            $em = $this->getDoctrine()->getManager();
            $em->merge($give);
            $em->flush();
        }


        $sended = true;

        if($give->getPayed() == '1'){
            $payed = true;
        }else{
            $payed = false;
        }

        return $this->render('FlorProjectFrontendBundle:Catalog:show_give.html.twig', array(
            'give' => $give,
            'sended' => $sended,
            'payed'  => $payed
        ));
    }





    public function showCausesAction()
    {
        return $this->render('FlorProjectFrontendBundle:Common:causes.html.twig');
    }

    public function mapsAction()
    {
        return $this->render('FlorProjectFrontendBundle:Maps:index.html.twig');
    }
	
	public function showAllGivesAction()
    {
        $em = $this->getDoctrine()->getManager();

        // IN THIS SELECT YOU MUST CHOOSE WICH GIVES YOU WANT TO SHOW IN MAP. A
        //Actually it is showing gives that has the new latitude2, longitude2 parameters defined becouse we need them to show the streetview image

        $query = "SELECT g FROM FlorProjectBackendBundle:Give g WHERE g.latitude2 != ''";
        $queryObject = $em->createQuery($query);
        $gives = $queryObject->getResult();


        return $this->render('FlorProjectFrontendBundle:Catalog:show_all_gives.html.twig', array(
            'gives' => $gives
        ));
    }











    /*----------------------------------------------------------------------------------------------------------------
    ----------------------------------------------  PAYPAL FUNCTIONS ---------------------------------------------------
    ----------------------------------------------------------------------------------------------------------------*/


    public static function    CallShortcutExpressCheckout( $paymentAmount_0, $desc_0, $cartId, $currencyCodeType, $paymentType, $returnURL, $cancelURL, $merchant_mail_0, $API_Endpoint, $version, $API_UserName, $API_Password, $API_Signature, $USE_PROXY, $PROXY_HOST, $PROXY_PORT,$sBNCode)
    {


        $nvpstr = '&SOLUTIONTYPE=Sole&LANDINGPAGE=Billing';

        $nvpstr= $nvpstr . "&PAYMENTREQUEST_0_AMT=". $paymentAmount_0;
        $nvpstr = $nvpstr . "&PAYMENTREQUEST_0_PAYMENTACTION=" . $paymentType;
        $nvpstr = $nvpstr . "&PAYMENTREQUEST_0_DESC=" . $desc_0;

        $nvpstr = $nvpstr . "&L_PAYMENTREQUEST_0_NAME0=" . $desc_0;
        $nvpstr = $nvpstr . "&L_PAYMENTREQUEST_0_AMT0=" . $paymentAmount_0;

        $nvpstr = $nvpstr . "&PAYMENTREQUEST_0_SELLERPAYPALACCOUNTID=" .$merchant_mail_0;
        $nvpstr = $nvpstr . "&PAYMENTREQUEST_0_PAYMENTREQUESTID=CART".$cartId."-PAYMENT0";

        $nvpstr = $nvpstr . "&RETURNURL=" . $returnURL;
        $nvpstr = $nvpstr . "&CANCELURL=" . $cancelURL;
        $nvpstr = $nvpstr . "&PAYMENTREQUEST_0_CURRENCYCODE=" . $currencyCodeType;

        $_SESSION["currencyCodeType"] = $currencyCodeType;
        $_SESSION["PaymentType"] = $paymentType;

        //'---------------------------------------------------------------------------------------------------------------
        //' Make the API call to PayPal
        //' If the API call succeded, then redirect the buyer to PayPal to begin to authorize payment.
        //' If an error occured, show the resulting errors
        //'---------------------------------------------------------------------------------------------------------------
        $resArray=self::hash_call("SetExpressCheckout", $nvpstr);
        $ack = strtoupper($resArray["ACK"]);
        if($ack=="SUCCESS" || $ack=="SUCCESSWITHWARNING")
        {
            $token = urldecode($resArray["TOKEN"]);
            $_SESSION['TOKEN']=$token;
        }

        return $resArray;
    }


    public static function hash_call($methodName,$nvpStr)
    {


        $API_UserName = $_SESSION['params']['API_UserName'];
        $API_Password = $_SESSION['params']['API_Password'];
        $API_Signature = $_SESSION['params']['API_Signature'];
        $USE_PROXY = $_SESSION['params']['USE_PROXY'];
        $PROXY_HOST = $_SESSION['params']['PROXY_HOST'];
        $PROXY_PORT = $_SESSION['params']['PROXY_PORT'];
        $sBNCode = $_SESSION['params']['sBNCode'];
        $version = $_SESSION['params']['version'];
        $API_Endpoint = $_SESSION['params']['API_Endpoint'];
        //setting the curl parameters.
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL,$API_Endpoint);
        curl_setopt($ch, CURLOPT_VERBOSE, 1);

        //turning off the server and peer verification(TrustManager Concept).
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);

        curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
        curl_setopt($ch, CURLOPT_POST, 1);

        //if USE_PROXY constant set to TRUE in Constants.php, then only proxy will be enabled.
        //Set proxy name to PROXY_HOST and port number to PROXY_PORT in constants.php
        if($USE_PROXY)
            curl_setopt ($ch, CURLOPT_PROXY, $PROXY_HOST. ":" . $PROXY_PORT);

        //NVPRequest for submitting to server
        $nvpreq="METHOD=" . urlencode($methodName) . "&VERSION=" . urlencode($version) . "&PWD=" . urlencode($API_Password) . "&USER=" . urlencode($API_UserName) . "&SIGNATURE=" . urlencode($API_Signature) . $nvpStr . "&BUTTONSOURCE=" . urlencode($sBNCode);

        //setting the nvpreq as POST FIELD to curl
        curl_setopt($ch, CURLOPT_POSTFIELDS, $nvpreq);

        //getting response from server
        $response = curl_exec($ch);

        //convrting NVPResponse to an Associative Array
        $nvpResArray=self::deformatNVP($response);
        $nvpReqArray=self::deformatNVP($nvpreq);
        $_SESSION['nvpReqArray']=$nvpReqArray;

        if (curl_errno($ch))
        {
            // moving to display page to display curl errors
            $_SESSION['curl_error_no']=curl_errno($ch) ;
            $_SESSION['curl_error_msg']=curl_error($ch);

            //Execute the Error handling module to display errors.
        }
        else
        {
            //closing the curl
            curl_close($ch);
        }

        return $nvpResArray;
    }

    public static function deformatNVP($nvpstr)
    {
        $intial=0;
        $nvpArray = array();

        while(strlen($nvpstr))
        {
            //postion of Key
            $keypos= strpos($nvpstr,'=');
            //position of value
            $valuepos = strpos($nvpstr,'&') ? strpos($nvpstr,'&'): strlen($nvpstr);

            /*getting the Key and Value values and storing in a Associative Array*/
            $keyval=substr($nvpstr,$intial,$keypos);
            $valval=substr($nvpstr,$keypos+1,$valuepos-$keypos-1);
            //decoding the respose
            $nvpArray[urldecode($keyval)] =urldecode( $valval);
            $nvpstr=substr($nvpstr,$valuepos+1,strlen($nvpstr));
        }
        return $nvpArray;
    }


    function GetShippingDetails( $token )
    {
        $nvpstr="&TOKEN=" . $token;


        $resArray=self::hash_call("GetExpressCheckoutDetails",$nvpstr);
        $ack = strtoupper($resArray["ACK"]);
        if($ack == "SUCCESS" || $ack=="SUCCESSWITHWARNING")
        {
            $_SESSION['payer_id'] =	$resArray['PAYERID'];
        }
        return $resArray;
    }

    function ConfirmPayment( $FinalPaymentAmt_0,   $desc_0, $cartId, $merchant_mail_0)
    {


        //Format the other parameters that were stored in the session from the previous calls
        $token 				= urlencode($_SESSION['TOKEN']);
        $paymentType 		= urlencode($_SESSION['PaymentType']);
        $currencyCodeType 	= urlencode($_SESSION['currencyCodeType']);
        $payerID 			= urlencode($_SESSION['payer_id']);

        $serverName 		= urlencode($_SERVER['SERVER_NAME']);
        $nvpstr  = '&TOKEN=' . $token . '&PAYERID=' . $payerID . '&PAYMENTREQUEST_0_PAYMENTACTION=' . $paymentType;
        $nvpstr = $nvpstr . '&SOLUTIONTYPE=Sole&LANDINGPAGE=Billing';
        $nvpstr .=  '&PAYMENTREQUEST_0_AMT=' . $FinalPaymentAmt_0.'&PAYMENTREQUEST_0_DESC='.$desc_0.'&PAYMENTREQUEST_0_CURRENCYCODE=' . $currencyCodeType . '&PAYMENTREQUEST_0_SELLERPAYPALACCOUNTID=' . $merchant_mail_0.'&PAYMENTREQUEST_0_PAYMENTREQUESTID=CART'.$cartId.'-PAYMENT0'.'&IPADDRESS=' . $serverName;

        $resArray=self::hash_call("DoExpressCheckoutPayment",$nvpstr);

        $ack = strtoupper($resArray["ACK"]);

        return $resArray;
    }


    /*----------------------------------------------------------------------------------------------------------------
       ------------------------------------------- END PAYPAL FUNCTIONS -------------------------------------------------
       ----------------------------------------------------------------------------------------------------------------*/












}
