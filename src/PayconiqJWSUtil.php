<?php

require_once '../vendor/autoload.php';

use Jose\Component\Core\AlgorithmManager;
use Jose\Component\Core\JWK;
use Jose\Component\Signature\Algorithm\ES256;
use Jose\Component\Signature\JWSVerifier;
use Jose\Component\Signature\Serializer\JWSSerializerManager;
use Jose\Component\Signature\Serializer\CompactSerializer;

/**
 * Description of PayconiqJWSUtil
 *
 * @author ivan
 */
class PayconiqJWSUtil {

    //put your code here
    const algoES256 = 'ES256';
    const extUrl = 'https://ext.payconiq.com/certificates';
    const prodUrl = 'https://payconiq.com/certificates';
    const extKid = 'es.signature.ext.payconiq.com';
    const prodKid = 'es.signature.payconiq.com';
    const extEnv = 'ext';
    const prodEnv = 'prod';

    public static function verifyJWS(string $environment, string $jws, string $payload): bool
    {
        try {
            $url = PayconiqJWSUtil::getUrl($environment);
            $kid = PayconiqJWSUtil::getKid($environment);

            $jwkJson = PayconiqJWSUtil::getJWKFromUrl($url, $kid);
            $key = JWK::createFromJson($jwkJson);

            // The algorithm manager with the ES256 algorithm.
            $algorithm_manager = new AlgorithmManager([new ES256()]);

            // We instantiate our JWS Verifier.
            $jwsVerifier = new JWSVerifier($algorithm_manager);

            // The serializer manager. We only use the JWS Compact Serialization Mode.
            $serializerManager = new JWSSerializerManager([new CompactSerializer()]);

            // We try to load the JWS.
            $jwsUserialized = $serializerManager->unserialize($jws);

            // We verify the signature. This method does NOT check the header.
            // The arguments are:
            // - The JWS object,
            // - The key,
            // - The index of the signature to check.
            // - The payload of the callback
            $isVerified = $jwsVerifier->verifyWithKey($jwsUserialized, $key, 0, $payload);

            return $isVerified;
        } catch (\Throwable $e) {
            return false;
        }
    }

    private static function getJWKFromUrl(string $url, string $kty): string
    {
        try {
            $curl = curl_init();

            curl_setopt_array($curl, array(
                CURLOPT_URL => $url,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => "",
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 30,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => "GET",
                CURLOPT_POSTFIELDS => "",
            ));

            $response = curl_exec($curl);
            $err = curl_error($curl);

            curl_close($curl);

            if ($err) {
                echo "cURL Error #:" . $err;
            } else {
                return PayconiqJWSUtil::getJWK($response, $kty);
            }
        } catch (\Throwable $ex) {
            throw new \Exception("Exception occurred while fetching JWKs");
        }
    }

    private static function getJWK(string $jwkParam, string $ktyString)
    {
        try {
            $array = json_decode($jwkParam, true);
            $keysArray = $array["keys"];

            foreach ($keysArray as $key => $value) {
                if (strcmp($value["kid"], $ktyString) == 0) {
                    return (json_encode($value));
                }
            }
            return null;
        } catch (\Throwable $ex) {
            throw new \Exception("Unable to extract JWK from data \n");
        }
    }

    private static function getAlgorithmManager(string $algorithm): AlgorithmManager
    {
        if (strcmp($algorithm, PayconiqJWSUtil::algoES256) == 0) {
            return new AlgorithmManager([new ES256()]);
        }
    }

    private static function getUrl(string $environment): string
    {
        if (strcmp($environment, PayconiqJWSUtil::extEnv) == 0) {
            return PayconiqJWSUtil::extUrl;
        } else if (strcmp($environment, PayconiqJWSUtil::prodEnv) == 0) {
            return PayconiqJWSUtil::prodUrl;
        } else {
            throw new \Exception('Environment prameter must be \'ext\' or \'prod\'');
        }
    }

    private static function getKid(string $environment): string
    {
        if (strcmp($environment, PayconiqJWSUtil::extEnv) == 0) {
            return PayconiqJWSUtil::extKid;
        } else if (strcmp($environment, PayconiqJWSUtil::prodEnv) == 0) {
            return PayconiqJWSUtil::prodKid;
        } else {
            throw new \Exception('Environment prameter must be \'ext\' or \'prod\'');
        }
    }
}
