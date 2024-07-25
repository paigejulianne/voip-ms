<?
$user = "john.doe@mydomain.com";
$pass = "johnspassword";

$method = "getSubAccounts";
$client = "500000";

$ch = curl_init();
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_URL, "https://voip.ms/api/v1/rest.php?api_username={$user}&api_password={$pass}&method={$method}&account={$client}");
$result = curl_exec($ch);
curl_close($ch);

$response = json_decode($result, true);

/* Get Errors - Invalid_Account */
if ($response[status] != 'success') {
    echo $response[status];
    exit;
}

/* Get Accounts Array */
$accounts = $response[accounts];

/* Display Accounts */
foreach ($accounts as $account) {
    ?>
    <table style="border: 1px solid #336699; width:500px">
        <tr>
            <td>Account:</td>
            <td>
                <? echo $account[account]; ?>
            </td>
        <tr>

        <tr>
            <td>Username:</td>
            <td>
                <? echo $account[username]; ?>
            </td>
        <tr>

        <tr>
            <td>Description:</td>
            <td>
                <? echo $account[description]; ?>
            </td>
        <tr>

        <tr>
            <td>Protocol:</td>
            <td>
                <? echo $account[protocol]; ?>
            </td>
        <tr>

        <tr>
            <td>Authorizacion Type:</td>
            <td>
                <? echo $account[auth_type]; ?>
            </td>
        <tr>

        <tr>
            <td>Password:</td>
            <td>
                <? echo $account[password]; ?>
            </td>
        <tr>

        <tr>
            <td>IP:</td>
            <td>
                <? echo $account[ip]; ?>
            </td>
        <tr>

        <tr>
            <td>Device Type:</td>
            <td>
                <? echo $account[device_type]; ?>
            </td>
        <tr>

        <tr>
            <td>CallerID Number:</td>
            <td>
                <? echo $account[callerid_number]; ?>
            </td>
        <tr>

        <tr>
            <td>Allow International Calls:</td>
            <td>
                <? echo $account[lock_international]; ?>
            </td>
        <tr>

        <tr>
            <td>International Route:</td>
            <td>
                <? echo $account[international_route]; ?>
            </td>
        <tr>

        <tr>
            <td>Music on Hold:</td>
            <td>
                <? echo $account[music_on_hold]; ?>
            </td>
        <tr>

        <tr>
            <td>Allowed Codecs:</td>
            <td>
                <? echo $account[allowed_codecs]; ?>
            </td>
        <tr>

        <tr>
            <td>DTMF Mode:</td>
            <td>
                <? echo $account[dtmf_mode]; ?>
            </td>
        <tr>

        <tr>
            <td>NAT:</td>
            <td>
                <? echo $account[nat]; ?>
            </td>
        <tr>

        <tr>
            <td>Internal Extension:</td>
            <td>
                <? echo $account[internal_extension]; ?>
            </td>
        <tr>

        <tr>
            <td>Internal Voicemail:</td>
            <td>
                <? echo $account[internal_voicemail]; ?>
            </td>
        <tr>

        <tr>
            <td>Internal Dialtime:</td>
            <td>
                <? echo $account[internal_dialtime]; ?>
            </td>
        <tr>

        <tr>
            <td>Reseller Client:</td>
            <td>
                <? echo $account[reseller_client]; ?>
            </td>
        <tr>

        <tr>
            <td>Reseller Package:</td>
            <td>
                <? echo $account[reseller_package]; ?>
            </td>
        <tr>

        <tr>
            <td>Reseller Next Billing Date:</td>
            <td>
                <? echo $account[reseller_nextbilling]; ?>
            </td>
        <tr>

    </table>
    <br><br><br>
    <?
}


?>