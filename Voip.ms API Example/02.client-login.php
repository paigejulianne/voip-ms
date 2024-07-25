<?
$user = "john.doe@mydomain.com";
$pass = "johnspassword";

$method = "getClients";
$email = "Jane.doe@mydomain.com";
$password = "janespassword";

$ch = curl_init();
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_URL, "https://voip.ms/api/v1/rest.php?api_username={$user}&api_password={$pass}&method={$method}&email={$email}");
$result = curl_exec($ch);
curl_close($ch);

$response = json_decode($result, true);

/* Get Errors - Invalid_Client */
if ($response[status] != 'success') {
    echo $response[status];
    exit;
}

/* See if Password is Correct */
$client = $response[clients][0];
if ($password != $client[password]) {
    echo "invalid_password";
    exit;
}

/* Client Exists and Password OK */
echo "{$client[client]} - {$client[firstname]} {$client[lastname]}"

?>