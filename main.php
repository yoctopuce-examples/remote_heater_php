<?php
include('common.php');

$mysqli = setupDbConnection();
$hub_serial = $mysqli->real_escape_string($_GET["hub_serial"]);

if (isset($_GET['req_state'])) {
    switch ($_GET['req_state']) {
        case 'on':
            updateReqState($mysqli, $hub_serial, "B");
            break;
        case 'off':
            updateReqState($mysqli, $hub_serial, "A");
            break;
        default:
    }
}

$state = getHubState($mysqli, $hub_serial);
if ($state == null) {
    $heater_txt = getTxtFromState("Unknown heater", "Unknown heater");
    $heater_img = getImgFromState("Unknown heater", "Unknown heater");
} else {
    $heater_txt = getTxtFromState($state->known_state, $state->requested_state);
    $heater_img = getImgFromState($state->known_state, $state->requested_state);
}
?>

<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <link href="htmlstuff/style.css" rel="stylesheet">
    <title>Heater control</title>
</head>
<body>
<h1>Heater state :<?php print($heater_txt); ?></h1>
<img src="<?php print($heater_img); ?>" alt="<?php print($heater_txt); ?>"/>
<div>
    <a href="<?php printf("?hub_serial=%s&req_state=on", $hub_serial); ?>" class="button">Switch On</a><a
        href="<?php printf("?hub_serial=%s&req_state=off", $hub_serial); ?>" class="button">Switch Off</a>
</div>
<table>
    <thead>
    <tr>
        <th>Date</th>
        <th>State</th>
        <th>Temp</th>
        <th>Humitidty</th>
        <th>Uptime</th>
        <th>Link</th>
        <th>Operator</th>
    </tr>
    </thead>
    <?php
    $logs = getLogs($mysqli, $hub_serial, 25);
    foreach ($logs as $l) {
        printf("<tr><td>%s</td>", date('r', $l->time));
        printf("<td>%s</td>", getTxtFromState($l->state));
        printf("<td>%s</td>", $l->temp);
        printf("<td>%s</td>", $l->humidity);
        printf("<td>%d</td>", $l->uptime);
        printf("<td>%d%%</td>", $l->linkQuality);
        printf("<td>%s</td></tr>\n", $l->operator);
    }
    ?>
</table>
</body>
</html>