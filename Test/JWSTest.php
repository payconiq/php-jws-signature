<?php

require_once '../src/PayconiqJWSUtil.php';

$util = new PayconiqJWSUtil();

$environment = 'ext';
$jws = 'eyJ0eXAiOiJKT1NFK0pTT04iLCJraWQiOiJlcy5zaWduYXR1cmUuZXh0LnBheWNvbmlxLmNvbSIsImFsZyI6IkVTMjU2IiwiaHR0cHM6Ly9wYXljb25pcS5jb20vaWF0IjoiMjAxOS0wNC0wMVQxMTowNTo1OS4wMDhaIiwiaHR0cHM6Ly9wYXljb25pcS5jb20vanRpIjoiMzRhM2JmMjRhZjgzYTVlOSIsImh0dHBzOi8vcGF5Y29uaXEuY29tL3BhdGgiOiJodHRwczovL3dlYmhvb2suc2l0ZS8yOWRmMGYwZi0xNjA3LTQ1MWMtYTI1Ni0zNzQyOGE0MDNlOGEiLCJodHRwczovL3BheWNvbmlxLmNvbS9pc3MiOiJQYXljb25pcSIsImh0dHBzOi8vcGF5Y29uaXEuY29tL3N1YiI6IjVhZDcyYjJmNWZhOWFkMzE4YTc1ODFmZCIsImNyaXQiOlsiaHR0cHM6Ly9wYXljb25pcS5jb20vaWF0IiwiaHR0cHM6Ly9wYXljb25pcS5jb20vanRpIiwiaHR0cHM6Ly9wYXljb25pcS5jb20vcGF0aCIsImh0dHBzOi8vcGF5Y29uaXEuY29tL2lzcyIsImh0dHBzOi8vcGF5Y29uaXEuY29tL3N1YiJdfQ..GpmaYSsRhyrKdkUfuKU6qtTr_n1_jOGa3_nzRLA2A0y3zGn03BHOGssGXuSqXSF-ilgzfEfTj7TfrE-CyaRkXQ';
$payload = '{"paymentId":"e21cce1186cabe30d6478c48","transferAmount":960,"tippingAmount":0,"amount":960,"totalAmount":960,"description":"Test Payment","reference":"Order1234","createdAt":"2019-04-01T11:05:43.512Z","expireAt":"2019-04-01T11:07:43.512Z","status":"SUCCEEDED","debtor":{"name":"The can","iban":"***********00012"},"currency":"EUR"}';

echo 'Is Signature valid? ' . $util->verifyJWS($environment, $jws, $payload);