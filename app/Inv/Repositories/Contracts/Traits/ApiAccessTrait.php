<?php

namespace App\Inv\Repositories\Contracts\Traits;

use File;
use Auth;
use Helpers;
use Response;
use Exception;
use Illuminate\Http\Request;
trait ApiAccessTrait
{

   
    public static function callindividualApi($targetEntityId, $token, $get_users_wci_single, $parentId)
    {
        try {

            $gatwayhost = config('common.gatwayhost');
            $groupId = Helpers::getgroupId();
            $gatwayurl = config('common.gatwayurl');
            $gatwayhost = config('common.gatwayhost');
            $contentType = config('common.contentType');
            $APISecret = config('common.APISecret');
            $apiKey  = config('common.apiKey');
            $get_users_wci_single = $get_users_wci_single;
            ?>
            <script src="http://admin.dextercapital.local/backend/theme/assets/plugins/jquery/jquery-2.2.4.min.js" type="text/javascript"></script>
            <script src="https://cdnjs.cloudflare.com/ajax/libs/crypto-js/3.1.9-1/crypto-js.js"></script>
            <script>
            jQuery(document).ready(function ($){
                var gatwayurl = '<?=$gatwayurl?>';
                var profileId = '<?=$targetEntityId?>';
                var encoded = encodeURIComponent(profileId);
                var gatwayhost = '<?=$gatwayhost?>';
                var gatwayhost = '<?=$gatwayhost?>';
                var gatwayhost = '<?=$gatwayhost?>';
                var token2     = '<?=$token?>';
                var APISecret = '<?=$APISecret?>';
                var apiKey   = '<?=$apiKey?>';
                var get_individual_wci_single = '<?=$get_users_wci_single?>';
                var parentId = '<?=$parentId?>';
                

                function generateAuthHeader(dataToSign) {
                    var hash = CryptoJS.HmacSHA256(dataToSign, APISecret);
                    return hash.toString(CryptoJS.enc.Base64);
                }
                
                var date = new Date().toGMTString();
                var dataToSign = "(request-target): get " + gatwayurl + "reference/profile/" + encoded + "\n" +
                    "host: " + gatwayhost + "\n" +
                    "date: " + date;
                var hmac = generateAuthHeader(dataToSign);
                var authorisation = "Signature keyId=\"" + apiKey + "\",algorithm=\"hmac-sha256\",headers=\"(request-target) host date\",signature=\"" + hmac + "\"";



                var myKeyVals = {_token: token2, authorisation: authorisation, currentDate: date, Signature: hmac, profileID: profileId,parentId: parentId};
                $.ajax({
                    type: 'POST',
                    async: false,
                    url: get_individual_wci_single,
                    data: myKeyVals,
                    dataType: "text",
                    success: function (resultData) {
                        //alert(resultData)
                        //$('#profileDetail').html(resultData);
                    }
                });
                });
                
            </script>

            
<?php
        } catch (Exception $ex) {
            return redirect()->back()->withErrors(Helpers::getExceptionMessage($ex))->withInput();
        }
    }


     public function createCredentails($post)
    {
        try {
            $postArr =[];
            // Create array of files to post
            //$postArr =  $post;
            $url = config('inv_common.API_URL_GENERATE_CREDENTIALS');
            //echo "<pre>";
           //print_r($post);
            //echo "==>".$url; exit;
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
            $result = curl_exec($ch);
           // echo "<pre>";
           //print_r($result);
            //print_r(curl_error($ch));
            $httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
           // echo "==>".$httpcode; exit;
            return ($result); 
            curl_close($ch);
            } catch (Exception $ex) {
                dd($ex);
            }
    }
}
?>