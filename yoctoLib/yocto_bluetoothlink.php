<?php
/*********************************************************************
 *
 *  $Id: yocto_bluetoothlink.php 43580 2021-01-26 17:46:01Z mvuilleu $
 *
 *  Implements YBluetoothLink, the high-level API for BluetoothLink functions
 *
 *  - - - - - - - - - License information: - - - - - - - - -
 *
 *  Copyright (C) 2011 and beyond by Yoctopuce Sarl, Switzerland.
 *
 *  Yoctopuce Sarl (hereafter Licensor) grants to you a perpetual
 *  non-exclusive license to use, modify, copy and integrate this
 *  file into your software for the sole purpose of interfacing
 *  with Yoctopuce products.
 *
 *  You may reproduce and distribute copies of this file in
 *  source or object form, as long as the sole purpose of this
 *  code is to interface with Yoctopuce products. You must retain
 *  this notice in the distributed source file.
 *
 *  You should refer to Yoctopuce General Terms and Conditions
 *  for additional information regarding your rights and
 *  obligations.
 *
 *  THE SOFTWARE AND DOCUMENTATION ARE PROVIDED 'AS IS' WITHOUT
 *  WARRANTY OF ANY KIND, EITHER EXPRESS OR IMPLIED, INCLUDING
 *  WITHOUT LIMITATION, ANY WARRANTY OF MERCHANTABILITY, FITNESS
 *  FOR A PARTICULAR PURPOSE, TITLE AND NON-INFRINGEMENT. IN NO
 *  EVENT SHALL LICENSOR BE LIABLE FOR ANY INCIDENTAL, SPECIAL,
 *  INDIRECT OR CONSEQUENTIAL DAMAGES, LOST PROFITS OR LOST DATA,
 *  COST OF PROCUREMENT OF SUBSTITUTE GOODS, TECHNOLOGY OR
 *  SERVICES, ANY CLAIMS BY THIRD PARTIES (INCLUDING BUT NOT
 *  LIMITED TO ANY DEFENSE THEREOF), ANY CLAIMS FOR INDEMNITY OR
 *  CONTRIBUTION, OR OTHER SIMILAR COSTS, WHETHER ASSERTED ON THE
 *  BASIS OF CONTRACT, TORT (INCLUDING NEGLIGENCE), BREACH OF
 *  WARRANTY, OR OTHERWISE.
 *
 *********************************************************************/

//--- (YBluetoothLink return codes)
//--- (end of YBluetoothLink return codes)
//--- (YBluetoothLink definitions)
if(!defined('Y_MUTE_FALSE'))                 define('Y_MUTE_FALSE',                0);
if(!defined('Y_MUTE_TRUE'))                  define('Y_MUTE_TRUE',                 1);
if(!defined('Y_MUTE_INVALID'))               define('Y_MUTE_INVALID',              -1);
if(!defined('Y_LINKSTATE_DOWN'))             define('Y_LINKSTATE_DOWN',            0);
if(!defined('Y_LINKSTATE_FREE'))             define('Y_LINKSTATE_FREE',            1);
if(!defined('Y_LINKSTATE_SEARCH'))           define('Y_LINKSTATE_SEARCH',          2);
if(!defined('Y_LINKSTATE_EXISTS'))           define('Y_LINKSTATE_EXISTS',          3);
if(!defined('Y_LINKSTATE_LINKED'))           define('Y_LINKSTATE_LINKED',          4);
if(!defined('Y_LINKSTATE_PLAY'))             define('Y_LINKSTATE_PLAY',            5);
if(!defined('Y_LINKSTATE_INVALID'))          define('Y_LINKSTATE_INVALID',         -1);
if(!defined('Y_OWNADDRESS_INVALID'))         define('Y_OWNADDRESS_INVALID',        YAPI_INVALID_STRING);
if(!defined('Y_PAIRINGPIN_INVALID'))         define('Y_PAIRINGPIN_INVALID',        YAPI_INVALID_STRING);
if(!defined('Y_REMOTEADDRESS_INVALID'))      define('Y_REMOTEADDRESS_INVALID',     YAPI_INVALID_STRING);
if(!defined('Y_REMOTENAME_INVALID'))         define('Y_REMOTENAME_INVALID',        YAPI_INVALID_STRING);
if(!defined('Y_PREAMPLIFIER_INVALID'))       define('Y_PREAMPLIFIER_INVALID',      YAPI_INVALID_UINT);
if(!defined('Y_VOLUME_INVALID'))             define('Y_VOLUME_INVALID',            YAPI_INVALID_UINT);
if(!defined('Y_LINKQUALITY_INVALID'))        define('Y_LINKQUALITY_INVALID',       YAPI_INVALID_UINT);
if(!defined('Y_COMMAND_INVALID'))            define('Y_COMMAND_INVALID',           YAPI_INVALID_STRING);
//--- (end of YBluetoothLink definitions)
    #--- (YBluetoothLink yapiwrapper)
   #--- (end of YBluetoothLink yapiwrapper)

//--- (YBluetoothLink declaration)
/**
 * YBluetoothLink Class: Bluetooth sound controller control interface
 *
 * BluetoothLink function provides control over Bluetooth link
 * and status for devices that are Bluetooth-enabled.
 */
class YBluetoothLink extends YFunction
{
    const OWNADDRESS_INVALID             = YAPI_INVALID_STRING;
    const PAIRINGPIN_INVALID             = YAPI_INVALID_STRING;
    const REMOTEADDRESS_INVALID          = YAPI_INVALID_STRING;
    const REMOTENAME_INVALID             = YAPI_INVALID_STRING;
    const MUTE_FALSE                     = 0;
    const MUTE_TRUE                      = 1;
    const MUTE_INVALID                   = -1;
    const PREAMPLIFIER_INVALID           = YAPI_INVALID_UINT;
    const VOLUME_INVALID                 = YAPI_INVALID_UINT;
    const LINKSTATE_DOWN                 = 0;
    const LINKSTATE_FREE                 = 1;
    const LINKSTATE_SEARCH               = 2;
    const LINKSTATE_EXISTS               = 3;
    const LINKSTATE_LINKED               = 4;
    const LINKSTATE_PLAY                 = 5;
    const LINKSTATE_INVALID              = -1;
    const LINKQUALITY_INVALID            = YAPI_INVALID_UINT;
    const COMMAND_INVALID                = YAPI_INVALID_STRING;
    //--- (end of YBluetoothLink declaration)

    //--- (YBluetoothLink attributes)
    protected $_ownAddress               = Y_OWNADDRESS_INVALID;         // MACAddress
    protected $_pairingPin               = Y_PAIRINGPIN_INVALID;         // Text
    protected $_remoteAddress            = Y_REMOTEADDRESS_INVALID;      // MACAddress
    protected $_remoteName               = Y_REMOTENAME_INVALID;         // Text
    protected $_mute                     = Y_MUTE_INVALID;               // Bool
    protected $_preAmplifier             = Y_PREAMPLIFIER_INVALID;       // Percent
    protected $_volume                   = Y_VOLUME_INVALID;             // Percent
    protected $_linkState                = Y_LINKSTATE_INVALID;          // BtState
    protected $_linkQuality              = Y_LINKQUALITY_INVALID;        // Percent
    protected $_command                  = Y_COMMAND_INVALID;            // Text
    //--- (end of YBluetoothLink attributes)

    function __construct($str_func)
    {
        //--- (YBluetoothLink constructor)
        parent::__construct($str_func);
        $this->_className = 'BluetoothLink';

        //--- (end of YBluetoothLink constructor)
    }

    //--- (YBluetoothLink implementation)

    function _parseAttr($name, $val)
    {
        switch($name) {
        case 'ownAddress':
            $this->_ownAddress = $val;
            return 1;
        case 'pairingPin':
            $this->_pairingPin = $val;
            return 1;
        case 'remoteAddress':
            $this->_remoteAddress = $val;
            return 1;
        case 'remoteName':
            $this->_remoteName = $val;
            return 1;
        case 'mute':
            $this->_mute = intval($val);
            return 1;
        case 'preAmplifier':
            $this->_preAmplifier = intval($val);
            return 1;
        case 'volume':
            $this->_volume = intval($val);
            return 1;
        case 'linkState':
            $this->_linkState = intval($val);
            return 1;
        case 'linkQuality':
            $this->_linkQuality = intval($val);
            return 1;
        case 'command':
            $this->_command = $val;
            return 1;
        }
        return parent::_parseAttr($name, $val);
    }

    /**
     * Returns the MAC-48 address of the bluetooth interface, which is unique on the bluetooth network.
     *
     * @return string : a string corresponding to the MAC-48 address of the bluetooth interface, which is
     * unique on the bluetooth network
     *
     * On failure, throws an exception or returns YBluetoothLink::OWNADDRESS_INVALID.
     */
    public function get_ownAddress()
    {
        // $res                    is a string;
        if ($this->_cacheExpiration <= YAPI::GetTickCount()) {
            if ($this->load(YAPI::$_yapiContext->GetCacheValidity()) != YAPI_SUCCESS) {
                return Y_OWNADDRESS_INVALID;
            }
        }
        $res = $this->_ownAddress;
        return $res;
    }

    /**
     * Returns an opaque string if a PIN code has been configured in the device to access
     * the SIM card, or an empty string if none has been configured or if the code provided
     * was rejected by the SIM card.
     *
     * @return string : a string corresponding to an opaque string if a PIN code has been configured in
     * the device to access
     *         the SIM card, or an empty string if none has been configured or if the code provided
     *         was rejected by the SIM card
     *
     * On failure, throws an exception or returns YBluetoothLink::PAIRINGPIN_INVALID.
     */
    public function get_pairingPin()
    {
        // $res                    is a string;
        if ($this->_cacheExpiration <= YAPI::GetTickCount()) {
            if ($this->load(YAPI::$_yapiContext->GetCacheValidity()) != YAPI_SUCCESS) {
                return Y_PAIRINGPIN_INVALID;
            }
        }
        $res = $this->_pairingPin;
        return $res;
    }

    /**
     * Changes the PIN code used by the module for bluetooth pairing.
     * Remember to call the saveToFlash() method of the module to save the
     * new value in the device flash.
     *
     * @param string $newval : a string corresponding to the PIN code used by the module for bluetooth pairing
     *
     * @return integer : YAPI::SUCCESS if the call succeeds.
     *
     * On failure, throws an exception or returns a negative error code.
     */
    public function set_pairingPin($newval)
    {
        $rest_val = $newval;
        return $this->_setAttr("pairingPin",$rest_val);
    }

    /**
     * Returns the MAC-48 address of the remote device to connect to.
     *
     * @return string : a string corresponding to the MAC-48 address of the remote device to connect to
     *
     * On failure, throws an exception or returns YBluetoothLink::REMOTEADDRESS_INVALID.
     */
    public function get_remoteAddress()
    {
        // $res                    is a string;
        if ($this->_cacheExpiration <= YAPI::GetTickCount()) {
            if ($this->load(YAPI::$_yapiContext->GetCacheValidity()) != YAPI_SUCCESS) {
                return Y_REMOTEADDRESS_INVALID;
            }
        }
        $res = $this->_remoteAddress;
        return $res;
    }

    /**
     * Changes the MAC-48 address defining which remote device to connect to.
     * Remember to call the saveToFlash()
     * method of the module if the modification must be kept.
     *
     * @param string $newval : a string corresponding to the MAC-48 address defining which remote device to connect to
     *
     * @return integer : YAPI::SUCCESS if the call succeeds.
     *
     * On failure, throws an exception or returns a negative error code.
     */
    public function set_remoteAddress($newval)
    {
        $rest_val = $newval;
        return $this->_setAttr("remoteAddress",$rest_val);
    }

    /**
     * Returns the bluetooth name the remote device, if found on the bluetooth network.
     *
     * @return string : a string corresponding to the bluetooth name the remote device, if found on the
     * bluetooth network
     *
     * On failure, throws an exception or returns YBluetoothLink::REMOTENAME_INVALID.
     */
    public function get_remoteName()
    {
        // $res                    is a string;
        if ($this->_cacheExpiration <= YAPI::GetTickCount()) {
            if ($this->load(YAPI::$_yapiContext->GetCacheValidity()) != YAPI_SUCCESS) {
                return Y_REMOTENAME_INVALID;
            }
        }
        $res = $this->_remoteName;
        return $res;
    }

    /**
     * Returns the state of the mute function.
     *
     * @return integer : either YBluetoothLink::MUTE_FALSE or YBluetoothLink::MUTE_TRUE, according to the
     * state of the mute function
     *
     * On failure, throws an exception or returns YBluetoothLink::MUTE_INVALID.
     */
    public function get_mute()
    {
        // $res                    is a enumBOOL;
        if ($this->_cacheExpiration <= YAPI::GetTickCount()) {
            if ($this->load(YAPI::$_yapiContext->GetCacheValidity()) != YAPI_SUCCESS) {
                return Y_MUTE_INVALID;
            }
        }
        $res = $this->_mute;
        return $res;
    }

    /**
     * Changes the state of the mute function. Remember to call the matching module
     * saveToFlash() method to save the setting permanently.
     *
     * @param integer $newval : either YBluetoothLink::MUTE_FALSE or YBluetoothLink::MUTE_TRUE, according to
     * the state of the mute function
     *
     * @return integer : YAPI::SUCCESS if the call succeeds.
     *
     * On failure, throws an exception or returns a negative error code.
     */
    public function set_mute($newval)
    {
        $rest_val = strval($newval);
        return $this->_setAttr("mute",$rest_val);
    }

    /**
     * Returns the audio pre-amplifier volume, in per cents.
     *
     * @return integer : an integer corresponding to the audio pre-amplifier volume, in per cents
     *
     * On failure, throws an exception or returns YBluetoothLink::PREAMPLIFIER_INVALID.
     */
    public function get_preAmplifier()
    {
        // $res                    is a int;
        if ($this->_cacheExpiration <= YAPI::GetTickCount()) {
            if ($this->load(YAPI::$_yapiContext->GetCacheValidity()) != YAPI_SUCCESS) {
                return Y_PREAMPLIFIER_INVALID;
            }
        }
        $res = $this->_preAmplifier;
        return $res;
    }

    /**
     * Changes the audio pre-amplifier volume, in per cents.
     * Remember to call the saveToFlash()
     * method of the module if the modification must be kept.
     *
     * @param integer $newval : an integer corresponding to the audio pre-amplifier volume, in per cents
     *
     * @return integer : YAPI::SUCCESS if the call succeeds.
     *
     * On failure, throws an exception or returns a negative error code.
     */
    public function set_preAmplifier($newval)
    {
        $rest_val = strval($newval);
        return $this->_setAttr("preAmplifier",$rest_val);
    }

    /**
     * Returns the connected headset volume, in per cents.
     *
     * @return integer : an integer corresponding to the connected headset volume, in per cents
     *
     * On failure, throws an exception or returns YBluetoothLink::VOLUME_INVALID.
     */
    public function get_volume()
    {
        // $res                    is a int;
        if ($this->_cacheExpiration <= YAPI::GetTickCount()) {
            if ($this->load(YAPI::$_yapiContext->GetCacheValidity()) != YAPI_SUCCESS) {
                return Y_VOLUME_INVALID;
            }
        }
        $res = $this->_volume;
        return $res;
    }

    /**
     * Changes the connected headset volume, in per cents.
     *
     * @param integer $newval : an integer corresponding to the connected headset volume, in per cents
     *
     * @return integer : YAPI::SUCCESS if the call succeeds.
     *
     * On failure, throws an exception or returns a negative error code.
     */
    public function set_volume($newval)
    {
        $rest_val = strval($newval);
        return $this->_setAttr("volume",$rest_val);
    }

    /**
     * Returns the bluetooth link state.
     *
     * @return integer : a value among YBluetoothLink::LINKSTATE_DOWN, YBluetoothLink::LINKSTATE_FREE,
     * YBluetoothLink::LINKSTATE_SEARCH, YBluetoothLink::LINKSTATE_EXISTS, YBluetoothLink::LINKSTATE_LINKED
     * and YBluetoothLink::LINKSTATE_PLAY corresponding to the bluetooth link state
     *
     * On failure, throws an exception or returns YBluetoothLink::LINKSTATE_INVALID.
     */
    public function get_linkState()
    {
        // $res                    is a enumBTSTATE;
        if ($this->_cacheExpiration <= YAPI::GetTickCount()) {
            if ($this->load(YAPI::$_yapiContext->GetCacheValidity()) != YAPI_SUCCESS) {
                return Y_LINKSTATE_INVALID;
            }
        }
        $res = $this->_linkState;
        return $res;
    }

    /**
     * Returns the bluetooth receiver signal strength, in pourcents, or 0 if no connection is established.
     *
     * @return integer : an integer corresponding to the bluetooth receiver signal strength, in pourcents,
     * or 0 if no connection is established
     *
     * On failure, throws an exception or returns YBluetoothLink::LINKQUALITY_INVALID.
     */
    public function get_linkQuality()
    {
        // $res                    is a int;
        if ($this->_cacheExpiration <= YAPI::GetTickCount()) {
            if ($this->load(YAPI::$_yapiContext->GetCacheValidity()) != YAPI_SUCCESS) {
                return Y_LINKQUALITY_INVALID;
            }
        }
        $res = $this->_linkQuality;
        return $res;
    }

    public function get_command()
    {
        // $res                    is a string;
        if ($this->_cacheExpiration <= YAPI::GetTickCount()) {
            if ($this->load(YAPI::$_yapiContext->GetCacheValidity()) != YAPI_SUCCESS) {
                return Y_COMMAND_INVALID;
            }
        }
        $res = $this->_command;
        return $res;
    }

    public function set_command($newval)
    {
        $rest_val = $newval;
        return $this->_setAttr("command",$rest_val);
    }

    /**
     * Retrieves a Bluetooth sound controller for a given identifier.
     * The identifier can be specified using several formats:
     * <ul>
     * <li>FunctionLogicalName</li>
     * <li>ModuleSerialNumber.FunctionIdentifier</li>
     * <li>ModuleSerialNumber.FunctionLogicalName</li>
     * <li>ModuleLogicalName.FunctionIdentifier</li>
     * <li>ModuleLogicalName.FunctionLogicalName</li>
     * </ul>
     *
     * This function does not require that the Bluetooth sound controller is online at the time
     * it is invoked. The returned object is nevertheless valid.
     * Use the method isOnline() to test if the Bluetooth sound controller is
     * indeed online at a given time. In case of ambiguity when looking for
     * a Bluetooth sound controller by logical name, no error is notified: the first instance
     * found is returned. The search is performed first by hardware name,
     * then by logical name.
     *
     * If a call to this object's is_online() method returns FALSE although
     * you are certain that the matching device is plugged, make sure that you did
     * call registerHub() at application initialization time.
     *
     * @param string $func : a string that uniquely characterizes the Bluetooth sound controller, for instance
     *         MyDevice.bluetoothLink1.
     *
     * @return YBluetoothLink : a YBluetoothLink object allowing you to drive the Bluetooth sound controller.
     */
    public static function FindBluetoothLink($func)
    {
        // $obj                    is a YBluetoothLink;
        $obj = YFunction::_FindFromCache('BluetoothLink', $func);
        if ($obj == null) {
            $obj = new YBluetoothLink($func);
            YFunction::_AddToCache('BluetoothLink', $func, $obj);
        }
        return $obj;
    }

    /**
     * Attempt to connect to the previously selected remote device.
     *
     * @return integer : YAPI::SUCCESS when the call succeeds.
     *
     * On failure, throws an exception or returns a negative error code.
     */
    public function connect()
    {
        return $this->set_command('C');
    }

    /**
     * Disconnect from the previously selected remote device.
     *
     * @return integer : YAPI::SUCCESS when the call succeeds.
     *
     * On failure, throws an exception or returns a negative error code.
     */
    public function disconnect()
    {
        return $this->set_command('D');
    }

    public function ownAddress()
    { return $this->get_ownAddress(); }

    public function pairingPin()
    { return $this->get_pairingPin(); }

    public function setPairingPin($newval)
    { return $this->set_pairingPin($newval); }

    public function remoteAddress()
    { return $this->get_remoteAddress(); }

    public function setRemoteAddress($newval)
    { return $this->set_remoteAddress($newval); }

    public function remoteName()
    { return $this->get_remoteName(); }

    public function mute()
    { return $this->get_mute(); }

    public function setMute($newval)
    { return $this->set_mute($newval); }

    public function preAmplifier()
    { return $this->get_preAmplifier(); }

    public function setPreAmplifier($newval)
    { return $this->set_preAmplifier($newval); }

    public function volume()
    { return $this->get_volume(); }

    public function setVolume($newval)
    { return $this->set_volume($newval); }

    public function linkState()
    { return $this->get_linkState(); }

    public function linkQuality()
    { return $this->get_linkQuality(); }

    public function command()
    { return $this->get_command(); }

    public function setCommand($newval)
    { return $this->set_command($newval); }

    /**
     * Continues the enumeration of Bluetooth sound controllers started using yFirstBluetoothLink().
     * Caution: You can't make any assumption about the returned Bluetooth sound controllers order.
     * If you want to find a specific a Bluetooth sound controller, use BluetoothLink.findBluetoothLink()
     * and a hardwareID or a logical name.
     *
     * @return YBluetoothLink : a pointer to a YBluetoothLink object, corresponding to
     *         a Bluetooth sound controller currently online, or a null pointer
     *         if there are no more Bluetooth sound controllers to enumerate.
     */
    public function nextBluetoothLink()
    {   $resolve = YAPI::resolveFunction($this->_className, $this->_func);
        if($resolve->errorType != YAPI_SUCCESS) return null;
        $next_hwid = YAPI::getNextHardwareId($this->_className, $resolve->result);
        if($next_hwid == null) return null;
        return self::FindBluetoothLink($next_hwid);
    }

    /**
     * Starts the enumeration of Bluetooth sound controllers currently accessible.
     * Use the method YBluetoothLink::nextBluetoothLink() to iterate on
     * next Bluetooth sound controllers.
     *
     * @return YBluetoothLink : a pointer to a YBluetoothLink object, corresponding to
     *         the first Bluetooth sound controller currently online, or a null pointer
     *         if there are none.
     */
    public static function FirstBluetoothLink()
    {   $next_hwid = YAPI::getFirstHardwareId('BluetoothLink');
        if($next_hwid == null) return null;
        return self::FindBluetoothLink($next_hwid);
    }

    //--- (end of YBluetoothLink implementation)

};

//--- (YBluetoothLink functions)

/**
 * Retrieves a Bluetooth sound controller for a given identifier.
 * The identifier can be specified using several formats:
 * <ul>
 * <li>FunctionLogicalName</li>
 * <li>ModuleSerialNumber.FunctionIdentifier</li>
 * <li>ModuleSerialNumber.FunctionLogicalName</li>
 * <li>ModuleLogicalName.FunctionIdentifier</li>
 * <li>ModuleLogicalName.FunctionLogicalName</li>
 * </ul>
 *
 * This function does not require that the Bluetooth sound controller is online at the time
 * it is invoked. The returned object is nevertheless valid.
 * Use the method isOnline() to test if the Bluetooth sound controller is
 * indeed online at a given time. In case of ambiguity when looking for
 * a Bluetooth sound controller by logical name, no error is notified: the first instance
 * found is returned. The search is performed first by hardware name,
 * then by logical name.
 *
 * If a call to this object's is_online() method returns FALSE although
 * you are certain that the matching device is plugged, make sure that you did
 * call registerHub() at application initialization time.
 *
 * @param string $func : a string that uniquely characterizes the Bluetooth sound controller, for instance
 *         MyDevice.bluetoothLink1.
 *
 * @return YBluetoothLink : a YBluetoothLink object allowing you to drive the Bluetooth sound controller.
 */
function yFindBluetoothLink($func)
{
    return YBluetoothLink::FindBluetoothLink($func);
}

/**
 * Starts the enumeration of Bluetooth sound controllers currently accessible.
 * Use the method YBluetoothLink::nextBluetoothLink() to iterate on
 * next Bluetooth sound controllers.
 *
 * @return YBluetoothLink : a pointer to a YBluetoothLink object, corresponding to
 *         the first Bluetooth sound controller currently online, or a null pointer
 *         if there are none.
 */
function yFirstBluetoothLink()
{
    return YBluetoothLink::FirstBluetoothLink();
}

//--- (end of YBluetoothLink functions)
?>