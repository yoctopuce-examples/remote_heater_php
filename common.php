<?php

/**
 *  CONSTANTS TO BE PATCHED WITH USER DATA
 */
define('DB_HOST', 'XXXXXXXXXXXXXX');
define('DB_NAME', 'XXXXXXXXXXXXXX');
define('DB_USER', 'XXXXXXXXXXXXXX');
define('DB_PASS', 'XXXXXXXXXXXXXX');


include('yoctoLib/yocto_api.php');
include('yoctoLib/yocto_network.php');
include('yoctoLib/yocto_cellular.php');
include('yoctoLib/yocto_relay.php');
include('yoctoLib/yocto_temperature.php');
include('yoctoLib/yocto_humidity.php');
include('yoctoLib/yocto_display.php');


/**
 *  setup db connection
 * @return mysqli
 */
function setupDbConnection()
{
    $mysqli = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
    if ($mysqli->connect_errno) {
        echo "Echec lors de la connexion � MySQL : (" . $mysqli->connect_errno . ") " . $mysqli->connect_error;
    }
    $query = "CREATE TABLE IF NOT EXISTS `heater` (`hub_serial` VARCHAR(20) NOT NULL,"
        . ' `requested_state` VARCHAR(64) NOT NULL, `known_state` VARCHAR(64) NOT NULL,'
        . ' `last_connextion` BIGINT, `known_temp` DOUBLE, `known_humidity` DOUBLE, `passwd` VARCHAR(512) NOT NULL,'
        . '  PRIMARY KEY (`hub_serial`), UNIQUE KEY `hub_serial` (`hub_serial`)) ENGINE=MyISAM DEFAULT CHARSET=utf8;';
    if (!$mysqli->query($query)) {
        echo "unable to create table : (" . $mysqli->errno . ") " . $mysqli->error;
        die;
    }
    $query = 'CREATE TABLE IF NOT EXISTS `logs` (  id BIGINT NOT NULL AUTO_INCREMENT,'
        . ' `time` BIGINT,'
        . ' `hub_serial` VARCHAR(20) NOT NULL,'
        . ' `uptime` BIGINT,'
        . ' `firmware` VARCHAR(64) NOT NULL,'
        . ' `ipAddress` VARCHAR(64) NOT NULL,'
        . ' `linkQuality` int,'
        . ' `operator` VARCHAR(64) NOT NULL,'
        . ' `dataReceived` BIGINT,'
        . ' `dataSent` BIGINT,'
        . ' `state` VARCHAR(64) NOT NULL,`temp` DOUBLE, `humidity` DOUBLE,'
        . ' `relay_serial` VARCHAR(20) NOT NULL,'
        . ' `relay_uptime` BIGINT,'
        . ' `relay_firmware` VARCHAR(64) NOT NULL,'
        . ' `meteo_serial` VARCHAR(20) NOT NULL,'
        . ' `meteo_uptime` BIGINT,'
        . ' `meteo_firmware` VARCHAR(64) NOT NULL,'
        . ' `disp_serial` VARCHAR(20) NOT NULL,'
        . ' `disp_uptime` BIGINT,'
        . ' `dips_firmware` VARCHAR(64) NOT NULL,'
        . ' PRIMARY KEY (id)) ENGINE=MyISAM DEFAULT CHARSET=utf8;';
    if (!$mysqli->query($query)) {
        echo $query;
        echo "unable to create table : (" . $mysqli->errno . ") " . $mysqli->error;
        die;
    }
    return $mysqli;
}


/**
 * @param $mysqli mysqli
 * @param $hub_module YModule
 * @param $relay_module YModule
 * @param $meteo_module YModule
 * @param $disp_module YModule
 * @param $ip string
 * @param $link integer
 * @param $operator string
 * @param $state string
 * @param $temp double
 * @param $humidity double
 */
function addLog($mysqli, $hub_module, $relay_module, $meteo_module, $disp_module, $ip, $link, $operator, $dataSent, $dataReceived, $state, $temp, $humidity)
{

    $query = 'INSERT INTO logs (`hub_serial`, `time`, `uptime`, `firmware`,`ipAddress`, `linkQuality`, `operator`, `dataReceived`, `dataSent`, `state`, `temp`, `humidity`, `relay_serial`, `relay_uptime`, `relay_firmware`, `meteo_serial`, `meteo_uptime`, `meteo_firmware`, `disp_serial`,`disp_uptime`,`dips_firmware`) VALUES ('
        . "'" . $hub_module->get_serialNumber() . "',"
        . "'" . time() . "',"
        . $hub_module->get_upTime() . ","
        . "'" . $hub_module->get_firmwareRelease() . "',"
        . "'" . $ip . "',"
        . $link . ","
        . "'" . $operator . "',"
        . $dataReceived . ","
        . $dataSent . ","
        . "'" . $state . "',"
        . "'" . $temp . "',"
        . "'" . $humidity . "',"
        . "'" . $relay_module->get_serialNumber() . "',"
        . $relay_module->get_upTime() . ","
        . "'" . $relay_module->get_firmwareRelease() . "',"
        . "'" . $meteo_module->get_serialNumber() . "',"
        . $meteo_module->get_upTime() . ","
        . "'" . $meteo_module->get_firmwareRelease() . "',"
        . "'" . $disp_module->get_serialNumber() . "',"
        . $disp_module->get_upTime() . ","
        . "'" . $disp_module->get_firmwareRelease() . "')";
    if (!$mysqli->query($query)) {
        echo $query;
        echo "unable to insert log : (" . $mysqli->errno . ") " . $mysqli->error;
    }
}


/**
 * @param $mysqli
 * @param $serial
 * @param $nb
 * @return null
 */
function getLogs($mysqli, $serial, $nb)
{
    $query = "SELECT * FROM `logs` WHERE `hub_serial` = '$serial' ORDER BY `time` DESC LIMIT $nb;";
    $result = $mysqli->query($query);
    $res = array();
    while ($obj = $result->fetch_object()) {
        $res[] = $obj;
    }
    $result->close();
    return $res;
}

/**
 * @param $mysqli mysqli
 * @param $client Google_Client
 * @param $serial string
 */
function updateHubInfo($mysqli, $serial, $known_state, $last_connextion, $temp, $humidity, $requested)
{

    $query = "INSERT INTO heater (hub_serial, known_state, requested_state, last_connextion, known_temp, known_humidity) VALUES ('$serial','$known_state','$requested', '$last_connextion',$temp,$humidity) ON DUPLICATE KEY UPDATE" .
        " hub_serial = VALUES(hub_serial), known_state = VALUES(known_state), requested_state = VALUES(requested_state), last_connextion = VALUES(last_connextion), known_temp = VALUES(known_temp), known_humidity = VALUES(known_humidity);";
    if (!$mysqli->query($query)) {
        echo "unable to insert token : (" . $mysqli->errno . ") " . $mysqli->error;
    }
}


/**
 * @param $mysqli mysqli
 * @param $client Google_Client
 * @param $serial string
 * @param $requested string
 */
function updateReqState($mysqli, $serial, $requested)
{

    $query = "INSERT INTO heater (hub_serial, requested_state) VALUES ('$serial','$requested') ON DUPLICATE KEY UPDATE" .
        " hub_serial = VALUES(hub_serial), requested_state = VALUES(requested_state);";
    if (!$mysqli->query($query)) {
        echo "unable to insert token : (" . $mysqli->errno . ") " . $mysqli->error;
    }
}

/**
 * @param $mysqli mysqli
 * @param $serial
 * @return object
 */
function getHubState($mysqli, $serial)
{
    $query = "SELECT * FROM heater WHERE hub_serial='$serial';";
    $result = $mysqli->query($query);
    if ($result && $result->num_rows > 0) {
        $obj = $result->fetch_object();
        $result->close();
        return $obj;
    } else {
        return null;
    }
}

function getAllHubs($mysqli)
{
    $query = "SELECT * FROM heater ;";
    $result = $mysqli->query($query);
    $res = array();
    while ($obj = $result->fetch_object()) {
        $res[] = $obj->hub_serial;
    }
    $result->close();
    return $res;
}


function getImgFromState($state, $req_state)
{
    if ($state != 'A' && $state != 'B') {
        return 'htmlstuff/disc.png';
    }
    if ($req_state == $state) {
        if ($state == 'A') {
            return 'htmlstuff/off.png';
        } else {
            return 'htmlstuff/on.png';
        }
    } else {
        if ($req_state == 'A') {
            return 'htmlstuff/off_pending.png';
        } else {
            return 'htmlstuff/on_pending.png';
        }
    }
}

function getTxtFromState($state, $req_state = "")
{
    switch ($state) {
        case 'A':
            return 'Off';
        case 'B':
            return 'On';
        case 'U':
            return 'Unknown state';
        default:
            return 'Error';
    }
}


/**
 * @param $serial
 */
function UpdateFromHubCallback($mysqli)
{
// first nework is the YoctoHub
    /** @var YNetwork $network */
    $network = YNetwork::FirstNetwork();
    /** @var YModule $module */
    $module = $network->get_module();
    /** @var YTemperature $temperature */
    $temperature = YTemperature::FirstTemperature();
    /** @var YHumidity $temperature */
    $humidity = YHumidity::FirstHumidity();
    /** @var YDisplay $display */
    $display = YDisplay::FirstDisplay();
    /** @var YRelay $relay */
    $relay = YRelay::FirstRelay();
//todo: check for missing modules and reboot
    /** @var string $serial */
    $serial = $module->get_serialNumber();
    $relay_state = $relay->get_state();
    switch ($relay_state) {
        case YRelay::STATE_A:
            $known_state = 'A';
            break;
        case YRelay::STATE_B:
            $known_state = 'B';
            break;
        default:
            $known_state = 'U';
            break;
    }
    $last_connextion = time(); // fixme use UTC time
    if ($temperature != null) {
        $known_temp = $temperature->get_currentValue();
        $temperature->muteValueCallbacks();
    } else {
        $known_temp = YTemperature::CURRENTVALUE_INVALID;
    }
    if ($humidity != null) {
        $known_humidity = $humidity->get_currentValue();
        $humidity->muteValueCallbacks();
    } else {
        $known_humidity = YHumidity::CURRENTVALUE_INVALID;
    }
    /** @var YCellular $celular */
    $celular = YCellular::FindCellular($serial . '.cellular');
    if ($celular->isOnline()) {
        $link = $celular->get_linkQuality();
        $operator = $celular->get_cellOperator();
        $dataReceived = $celular->get_dataReceived();
        $dataSent = $celular->get_dataSent();
    } else {
        $link = 0;
        $operator = $module->get_productName();
        $dataReceived = 0;
        $dataSent = 0;
    }
    addLog($mysqli, $module, $relay->get_module(), $temperature->get_module(), $display->get_module(), $network->get_ipAddress(), $link, $operator, $dataReceived, $dataSent, $known_state, $known_temp, $known_humidity);

    // set sensor configuration in case of
    $state = getHubState($mysqli, $serial);
    // check if we need to change the Relay state
    if ($known_state != $state->known_state) {
        // local button pressed -> update the requested state tool
        $state->requested_state = $known_state;
    } else if ($known_state != $state->requested_state) {
        //we need to change the state
        switch ($state->requested_state) {
            case 'A':
                $relay->set_state(YRelay::STATE_A);
                Print("Set relay to state A\n");
                $known_state = "A";
                break;
            case 'B':
                $relay->set_state(YRelay::STATE_B);
                Print("Set relay to state B\n");
                $known_state = "B";
                break;
            default:
        }
    }
    /** @var YDisplayLayer $layer0 */
    $layer0 = $display->get_displayLayer(0);
    $layer0->clear();
    $msg = getTxtFromState($known_state);
    $h = $layer0->get_displayHeight();
    $w = $layer0->get_displayWidth();
    $layer0->selectFont('Large.yfm');
    $v_pos = $h / 4;
    $h_pos = $w / 2;
    $layer0->drawText($h_pos, $v_pos * 2, YDisplayLayer::ALIGN_BOTTOM_CENTER, $msg);
    $layer0->selectFont('Small.yfm');
    $layer0->drawText($w / 2, $v_pos * 2, YDisplayLayer::ALIGN_TOP_CENTER, date('r'));
    $layer0->drawText($w / 2, $v_pos * 3, YDisplayLayer::ALIGN_TOP_CENTER, "{$operator} {$link}%");
    updateHubInfo($mysqli, $serial, $known_state, $last_connextion, $known_temp, $known_humidity, $state->requested_state);
}
