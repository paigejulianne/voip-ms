<?
$user = "john.doe@mydomain.com";
$pass = "johnspassword";

$method = "getPackages";


$ch = curl_init();
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_URL, "https://voip.ms/api/v1/rest.php?api_username={$user}&api_password={$pass}&method={$method}");
$result = curl_exec($ch);
curl_close($ch);

$response = json_decode($result, true);

/* Get Errors - Invalid_Package */
if ($response[status] != 'success') {
    echo $response[status];
    exit;
}

/* Get Packages Array */
$packages = $response[packages];

/* Display Packages */
foreach ($packages as $package) {
    ?>
    <table style="border: 1px solid #336699; width:500px">
        <tr>
            <td>Package:</td>
            <td>
                <? echo $package[package] ?>
            </td>
        <tr>

        <tr>
            <td>Name:</td>
            <td>
                <? echo $package[name] ?>
            </td>
        <tr>

        <tr>
            <td>Mark Up Fixed:</td>
            <td>
                <? echo $package[markup_fixed] ?>
            </td>
        <tr>

        <tr>
            <td>Mark Up Percentage:</td>
            <td>
                <? echo $package[markup_percentage] ?>
            </td>
        <tr>

        <tr>
            <td>Pulse:</td>
            <td>
                <? echo $package[pulse] ?>
            </td>
        <tr>

        <tr>
            <td>International Route:</td>
            <td>
                <? echo $package[international_route] ?>
            </td>
        <tr>

        <tr>
            <td>Montly Fee:</td>
            <td>
                <? echo $package[monthly_fee] ?>
            </td>
        <tr>

        <tr>
            <td>Setup Fee:</td>
            <td>
                <? echo $package[setup_fee] ?>
            </td>
        <tr>

        <tr>
            <td>Free Minutes:</td>
            <td>
                <? echo $package[free_minutes] ?>
            </td>
        <tr>

    </table>
    <br><br><br>
    <?
}


?>