<?php

namespace app\utils;

use Yii;
use yii\httpclient\Client;
use yii\web\Request;

class CAPTCHA {
    /**
     * Verifies a users request with google's captcha
     * 
     * The request must have a POST parameter name 'g-recaptcha-response'. This is tagged along with the form request.
     * 
     * This is generated with some javascript and HTML.
     * 
     * Javascript:
     * 
     * <script>
     *    function reCaptchaOnSubmit(token) {
     *        $('#ticket-draft-form').submit()
     *    }
     * </script>
     * 
     * HTML:
     * 
     * <?= Html::submitButton('Submit Ticket to CABOCES', [
     *       'class' => 'btn btn-success g-recaptcha', 
     *       'data-sitekey' => '6LcEMsEqAAAAACHqBOkDNZDP7CFW2JjMLvPdN7IQ',
     *       'data-callback' => 'reCaptchaOnSubmit',
     *       'data-action' => 'submit',
     *  ]) ?>
     * 
     * @return array Containing a "message" about what happened, and a "result" if it successfully passed.
     */
    public static function verify(Request $request, float $threshold = 0.5): array {
        // Send recaptcha request. If failed, then redirect to ticket draft again.

        if (!$request->isPost) {
            return [
                'success' => false,
                'message' => "Your reCAPTCHA request must be an HTTP POST method."
            ];
        }

        if (!$request->post('g-recaptcha-response')) {
            return [
                'success' => false,
                'message' => "Your reCAPTCHA request requires the verification key; however, it was not provided."
            ];
        }

        // expects a v3 secret key
        $recaptchaSecretKey = Yii::$app->params['recaptcha']['apikey'];

        // POST Request and Response format: developers.google.com/recaptcha/docs/verify
        $client = new Client();
        $captchaResponse = $client->createRequest()
            ->setMethod('POST')
            ->setUrl('https://www.google.com/recaptcha/api/siteverify')
            ->setData([
                'secret' => $recaptchaSecretKey,
                // must be valid
                'response' => $request->post('g-recaptcha-response'),
                'remoteip' => Yii::$app->request->getUserIP(),
            ])
            ->setFormat(Client::FORMAT_URLENCODED)
            ->send();
        
        if ($captchaResponse->isOk) {
            // reCaptcha failed
            if (!$captchaResponse->data['success']) {
                return [
                    'success' => false,
                    'message' => "Your reCAPTCHA request had an error during verification."
                ];
            }
            if ($captchaResponse->data['score'] < $threshold) {
                return [
                    'success' => false,
                    'message' => "Your reCAPTCHA request was determined to be suspicous and thus your request was blocked. Try again."
                ];
            }
        } else {
            return [
                'success' => false,
                'message' => "Your reCAPTCHA request could not be resolved because the request either did not have a response or was poorly formatted."
            ];
        }

        return [
            'success' => true,
            'message' => "Your reCAPTCHA request has resolved successfully."
        ];
    }
}

?>