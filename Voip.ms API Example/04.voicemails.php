<?
$user = "john.doe@mydomain.com";
$pass = "johnspassword";

$method = "getVoicemails";
$mailbox = "900000";

$ch = curl_init();
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_URL, "https://voip.ms/api/v1/rest.php?api_username={$user}&api_password={$pass}&method={$method}&mailbox={$mailbox}");
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

/* Get Errors - Invalid_Voicemail */
if ($response[status] != 'success') {
    echo $response[status];
    exit;
}

/* Get Voicemails Array */
$voicemails = $response[voicemails];

/* Display Voicemails */
foreach ($voicemails as $voicemail) {
    ?>
    <table style="border: 1px solid #336699; width:500px">
        <tr>
            <td>Mailbox:</td>
            <td>
                <? echo $voicemail[mailbox] ?>
            </td>
        <tr>

        <tr>
            <td>Name:</td>
            <td>
                <? echo $voicemail[name] ?>
            </td>
        <tr>


        <tr>
            <td>Password:</td>
            <td>
                <? echo $voicemail[password] ?>
            </td>
        <tr>

        <tr>
            <td>Skip Password:</td>
            <td>
                <? echo $voicemail[skip_password] ? "Yes" : "No" ?>
            </td>
        <tr>

        <tr>
            <td>e-mail:</td>
            <td>
                <? echo $voicemail[email] ?>
            </td>
        <tr>

        <tr>
            <td>Attach Message to e-mail:</td>
            <td>
                <? echo $voicemail[attach_message] ?>
            </td>
        <tr>

        <tr>
            <td>Delete Message after emailed:</td>
            <td>
                <? echo $voicemail[delete_message] ?>
            </td>
        <tr>

        <tr>
            <td>Say Time Envelope:</td>
            <td>
                <? echo $voicemail[say_time] ?>
            </td>
        <tr>


        <tr>
            <td>Time Zone:</td>
            <td>
                <? echo $voicemail[timezone] ?>
            </td>
        <tr>

        <tr>
            <td>Say Caller ID:</td>
            <td>
                <? echo $voicemail[say_callerid] ?>
            </td>
        <tr>

        <tr>
            <td>Play Instructions before beep:</td>
            <td>
                <? echo $voicemail[play_instructions] ?>
            </td>
        <tr>

        <tr>
            <td>Language:</td>
            <td>
                <? echo $voicemail[language] ?>
            </td>
        <tr>

    </table>
    <br><br><br>
    <?
}


?>