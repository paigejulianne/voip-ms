<?
$user = "john.doe@mydomain.com";
$pass = "johnspassword";

$method = "getLanguages";

$ch = curl_init();
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_URL, "https://voip.ms/api/v1/rest.php?api_username={$user}&api_password={$pass}&method={$method}");
$result = curl_exec($ch);
curl_close($ch);

$data = json_decode($result, true);

?>
<pre>
    <? print_r($data); ?>
</pre>