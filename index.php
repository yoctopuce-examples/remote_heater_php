<?php
include('common.php');
$mysqli = setupDbConnection();
YAPI::DisableExceptions();
if (YAPI::TestHub("callback", 10) == YAPI::SUCCESS) {
    YAPI::RegisterHub("callback");
    UpdateFromHubCallback($mysqli);
    die();
}
$hubs = getAllHubs($mysqli);
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <link href="htmlstuff/style.css" rel="stylesheet">
    <title>Heater control</title>
</head>
<body>
<h1>List of heater</h1>
<ul>
    <?php
    foreach ($hubs as $hub) {
        printf('<li><a href="main.php?hub_serial=%s">%s</a></li>', $hub, $hub);
    }
    ?>
</ul>

<h2>Configuration of the YoctoHub.</h2>
<ol>
    <li>Connect to the web interface of the VirtualHub or YoctoHub that will run this script.</li>
    <li>Click on the <em>configure</em> button of the VirtualHub or YoctoHub.</li>
    <li>Click on the <em>edit</em> button of "Callback URL" settings.</li>
    <li>Set the <em>type of Callback</em> to <b>Yocto-API Callback</b>.</li>
    <li>Set the <em>callback URL</em> to
        http://<b><?php print($_SERVER['SERVER_NAME'] . ':' . $_SERVER['SERVER_PORT'] . $_SERVER['SCRIPT_NAME']); ?></b>.
    </li>
    <li>Click on the <em>test</em> button.</li>
</ol>
</body>
</html>