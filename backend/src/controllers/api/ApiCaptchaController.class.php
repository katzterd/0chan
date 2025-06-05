<?php

class ApiCaptchaController extends ApiBaseController
{
    const REQUEST_LIMIT  = 10;
    const REQUEST_BLOCK_TTL  = 300;  // 5 min
    const REQUEST_COUNTER_TTL  = 15;  // 15 sec
    /**
     * @return CaptchaProvider
     */
    public function getCaptchaProvider()
    {
        //return YandexCaptchaProvider::me();
        //return SecurimageCaptchaProvider::me();
        return MyataCaptchaProvider::me();
    }

    /**
     * @param null|string $captcha
     * @param null|string $answer
     * @return array
     */
    public function defaultAction($captcha = null, $answer = null)
    {
        $provider = $this->getCaptchaProvider();

        $captchaId = $captcha;
        /** @var Captcha|null $captcha */
        $captcha = CaptchaStorage::me()->get($captchaId);

        if ($answer || $captchaId) {
            $valid = false;
            if ($captcha instanceof Captcha) {
                if ($captcha->isChecked()) {
                    $valid = $captcha->isValid();
                } else {
                    $valid = $provider->validateCaptcha($captcha, $answer);
                    $captcha
                        ->setChecked(true)
                        ->setValid($valid);
                    CaptchaStorage::me()->put($captcha);
                }
            }

            return [
                'captcha' => $captchaId,
                'ok' => $valid
            ];
        } else {
            $ipHash = $this->getSession()->getIpHash();
            
            if (!empty($ipHash)) {
            
                $blockKey = '__captcha_request_block_storage__' . $ipHash;
                
                if (Cache::me()->get($blockKey)) {
                    throw new ApiForbiddenException('too many captcha requests');
                }
                
                $reqKey = '__captcha_request_storage__' . $ipHash;
                $count = Cache::me()->get($reqKey) ?: 0;
                Cache::me()->set($reqKey, $count + 1, self::REQUEST_COUNTER_TTL);
            
                if ($count == self::REQUEST_LIMIT) {
                    Cache::me()->set($blockKey, true, self::REQUEST_BLOCK_TTL);
                    Cache::me()->delete($reqKey);
                }
            
            }
            
            if (!$captcha) {
                $captcha = $provider->getCaptcha();
                CaptchaStorage::me()->put($captcha);
            }

            return [
                'captcha' => $captcha->getId(),
                'image' => $captcha->getImage()
            ];
        }
    }
}
