<?php
/*********************************************************************
 *
 *  $Id: yocto_dualpower.php 43580 2021-01-26 17:46:01Z mvuilleu $
 *
 *  Implements YDualPower, the high-level API for DualPower functions
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

//--- (YDualPower return codes)
//--- (end of YDualPower return codes)
//--- (YDualPower definitions)
if(!defined('Y_POWERSTATE_OFF'))             define('Y_POWERSTATE_OFF',            0);
if(!defined('Y_POWERSTATE_FROM_USB'))        define('Y_POWERSTATE_FROM_USB',       1);
if(!defined('Y_POWERSTATE_FROM_EXT'))        define('Y_POWERSTATE_FROM_EXT',       2);
if(!defined('Y_POWERSTATE_INVALID'))         define('Y_POWERSTATE_INVALID',        -1);
if(!defined('Y_POWERCONTROL_AUTO'))          define('Y_POWERCONTROL_AUTO',         0);
if(!defined('Y_POWERCONTROL_FROM_USB'))      define('Y_POWERCONTROL_FROM_USB',     1);
if(!defined('Y_POWERCONTROL_FROM_EXT'))      define('Y_POWERCONTROL_FROM_EXT',     2);
if(!defined('Y_POWERCONTROL_OFF'))           define('Y_POWERCONTROL_OFF',          3);
if(!defined('Y_POWERCONTROL_INVALID'))       define('Y_POWERCONTROL_INVALID',      -1);
if(!defined('Y_EXTVOLTAGE_INVALID'))         define('Y_EXTVOLTAGE_INVALID',        YAPI_INVALID_UINT);
//--- (end of YDualPower definitions)
    #--- (YDualPower yapiwrapper)
   #--- (end of YDualPower yapiwrapper)

//--- (YDualPower declaration)
/**
 * YDualPower Class: dual power switch control interface, available for instance in the Yocto-Servo
 *
 * The YDualPower class allows you to control
 * the power source to use for module functions that require high current.
 * The module can also automatically disconnect the external power
 * when a voltage drop is observed on the external power source
 * (external battery running out of power).
 */
class YDualPower extends YFunction
{
    const POWERSTATE_OFF                 = 0;
    const POWERSTATE_FROM_USB            = 1;
    const POWERSTATE_FROM_EXT            = 2;
    const POWERSTATE_INVALID             = -1;
    const POWERCONTROL_AUTO              = 0;
    const POWERCONTROL_FROM_USB          = 1;
    const POWERCONTROL_FROM_EXT          = 2;
    const POWERCONTROL_OFF               = 3;
    const POWERCONTROL_INVALID           = -1;
    const EXTVOLTAGE_INVALID             = YAPI_INVALID_UINT;
    //--- (end of YDualPower declaration)

    //--- (YDualPower attributes)
    protected $_powerState               = Y_POWERSTATE_INVALID;         // DualPwrState
    protected $_powerControl             = Y_POWERCONTROL_INVALID;       // DualPwrControl
    protected $_extVoltage               = Y_EXTVOLTAGE_INVALID;         // UInt31
    //--- (end of YDualPower attributes)

    function __construct($str_func)
    {
        //--- (YDualPower constructor)
        parent::__construct($str_func);
        $this->_className = 'DualPower';

        //--- (end of YDualPower constructor)
    }

    //--- (YDualPower implementation)

    function _parseAttr($name, $val)
    {
        switch($name) {
        case 'powerState':
            $this->_powerState = intval($val);
            return 1;
        case 'powerControl':
            $this->_powerControl = intval($val);
            return 1;
        case 'extVoltage':
            $this->_extVoltage = intval($val);
            return 1;
        }
        return parent::_parseAttr($name, $val);
    }

    /**
     * Returns the current power source for module functions that require lots of current.
     *
     * @return integer : a value among YDualPower::POWERSTATE_OFF, YDualPower::POWERSTATE_FROM_USB and
     * YDualPower::POWERSTATE_FROM_EXT corresponding to the current power source for module functions that
     * require lots of current
     *
     * On failure, throws an exception or returns YDualPower::POWERSTATE_INVALID.
     */
    public function get_powerState()
    {
        // $res                    is a enumPWRSTATE;
        if ($this->_cacheExpiration <= YAPI::GetTickCount()) {
            if ($this->load(YAPI::$_yapiContext->GetCacheValidity()) != YAPI_SUCCESS) {
                return Y_POWERSTATE_INVALID;
            }
        }
        $res = $this->_powerState;
        return $res;
    }

    /**
     * Returns the selected power source for module functions that require lots of current.
     *
     * @return integer : a value among YDualPower::POWERCONTROL_AUTO, YDualPower::POWERCONTROL_FROM_USB,
     * YDualPower::POWERCONTROL_FROM_EXT and YDualPower::POWERCONTROL_OFF corresponding to the selected
     * power source for module functions that require lots of current
     *
     * On failure, throws an exception or returns YDualPower::POWERCONTROL_INVALID.
     */
    public function get_powerControl()
    {
        // $res                    is a enumPWRCTRL;
        if ($this->_cacheExpiration <= YAPI::GetTickCount()) {
            if ($this->load(YAPI::$_yapiContext->GetCacheValidity()) != YAPI_SUCCESS) {
                return Y_POWERCONTROL_INVALID;
            }
        }
        $res = $this->_powerControl;
        return $res;
    }

    /**
     * Changes the selected power source for module functions that require lots of current.
     * Remember to call the saveToFlash() method of the module if the modification must be kept.
     *
     * @param integer $newval : a value among YDualPower::POWERCONTROL_AUTO,
     * YDualPower::POWERCONTROL_FROM_USB, YDualPower::POWERCONTROL_FROM_EXT and YDualPower::POWERCONTROL_OFF
     * corresponding to the selected power source for module functions that require lots of current
     *
     * @return integer : YAPI::SUCCESS if the call succeeds.
     *
     * On failure, throws an exception or returns a negative error code.
     */
    public function set_powerControl($newval)
    {
        $rest_val = strval($newval);
        return $this->_setAttr("powerControl",$rest_val);
    }

    /**
     * Returns the measured voltage on the external power source, in millivolts.
     *
     * @return integer : an integer corresponding to the measured voltage on the external power source, in millivolts
     *
     * On failure, throws an exception or returns YDualPower::EXTVOLTAGE_INVALID.
     */
    public function get_extVoltage()
    {
        // $res                    is a int;
        if ($this->_cacheExpiration <= YAPI::GetTickCount()) {
            if ($this->load(YAPI::$_yapiContext->GetCacheValidity()) != YAPI_SUCCESS) {
                return Y_EXTVOLTAGE_INVALID;
            }
        }
        $res = $this->_extVoltage;
        return $res;
    }

    /**
     * Retrieves a dual power switch for a given identifier.
     * The identifier can be specified using several formats:
     * <ul>
     * <li>FunctionLogicalName</li>
     * <li>ModuleSerialNumber.FunctionIdentifier</li>
     * <li>ModuleSerialNumber.FunctionLogicalName</li>
     * <li>ModuleLogicalName.FunctionIdentifier</li>
     * <li>ModuleLogicalName.FunctionLogicalName</li>
     * </ul>
     *
     * This function does not require that the dual power switch is online at the time
     * it is invoked. The returned object is nevertheless valid.
     * Use the method isOnline() to test if the dual power switch is
     * indeed online at a given time. In case of ambiguity when looking for
     * a dual power switch by logical name, no error is notified: the first instance
     * found is returned. The search is performed first by hardware name,
     * then by logical name.
     *
     * If a call to this object's is_online() method returns FALSE although
     * you are certain that the matching device is plugged, make sure that you did
     * call registerHub() at application initialization time.
     *
     * @param string $func : a string that uniquely characterizes the dual power switch, for instance
     *         SERVORC1.dualPower.
     *
     * @return YDualPower : a YDualPower object allowing you to drive the dual power switch.
     */
    public static function FindDualPower($func)
    {
        // $obj                    is a YDualPower;
        $obj = YFunction::_FindFromCache('DualPower', $func);
        if ($obj == null) {
            $obj = new YDualPower($func);
            YFunction::_AddToCache('DualPower', $func, $obj);
        }
        return $obj;
    }

    public function powerState()
    { return $this->get_powerState(); }

    public function powerControl()
    { return $this->get_powerControl(); }

    public function setPowerControl($newval)
    { return $this->set_powerControl($newval); }

    public function extVoltage()
    { return $this->get_extVoltage(); }

    /**
     * Continues the enumeration of dual power switches started using yFirstDualPower().
     * Caution: You can't make any assumption about the returned dual power switches order.
     * If you want to find a specific a dual power switch, use DualPower.findDualPower()
     * and a hardwareID or a logical name.
     *
     * @return YDualPower : a pointer to a YDualPower object, corresponding to
     *         a dual power switch currently online, or a null pointer
     *         if there are no more dual power switches to enumerate.
     */
    public function nextDualPower()
    {   $resolve = YAPI::resolveFunction($this->_className, $this->_func);
        if($resolve->errorType != YAPI_SUCCESS) return null;
        $next_hwid = YAPI::getNextHardwareId($this->_className, $resolve->result);
        if($next_hwid == null) return null;
        return self::FindDualPower($next_hwid);
    }

    /**
     * Starts the enumeration of dual power switches currently accessible.
     * Use the method YDualPower::nextDualPower() to iterate on
     * next dual power switches.
     *
     * @return YDualPower : a pointer to a YDualPower object, corresponding to
     *         the first dual power switch currently online, or a null pointer
     *         if there are none.
     */
    public static function FirstDualPower()
    {   $next_hwid = YAPI::getFirstHardwareId('DualPower');
        if($next_hwid == null) return null;
        return self::FindDualPower($next_hwid);
    }

    //--- (end of YDualPower implementation)

};

//--- (YDualPower functions)

/**
 * Retrieves a dual power switch for a given identifier.
 * The identifier can be specified using several formats:
 * <ul>
 * <li>FunctionLogicalName</li>
 * <li>ModuleSerialNumber.FunctionIdentifier</li>
 * <li>ModuleSerialNumber.FunctionLogicalName</li>
 * <li>ModuleLogicalName.FunctionIdentifier</li>
 * <li>ModuleLogicalName.FunctionLogicalName</li>
 * </ul>
 *
 * This function does not require that the dual power switch is online at the time
 * it is invoked. The returned object is nevertheless valid.
 * Use the method isOnline() to test if the dual power switch is
 * indeed online at a given time. In case of ambiguity when looking for
 * a dual power switch by logical name, no error is notified: the first instance
 * found is returned. The search is performed first by hardware name,
 * then by logical name.
 *
 * If a call to this object's is_online() method returns FALSE although
 * you are certain that the matching device is plugged, make sure that you did
 * call registerHub() at application initialization time.
 *
 * @param string $func : a string that uniquely characterizes the dual power switch, for instance
 *         SERVORC1.dualPower.
 *
 * @return YDualPower : a YDualPower object allowing you to drive the dual power switch.
 */
function yFindDualPower($func)
{
    return YDualPower::FindDualPower($func);
}

/**
 * Starts the enumeration of dual power switches currently accessible.
 * Use the method YDualPower::nextDualPower() to iterate on
 * next dual power switches.
 *
 * @return YDualPower : a pointer to a YDualPower object, corresponding to
 *         the first dual power switch currently online, or a null pointer
 *         if there are none.
 */
function yFirstDualPower()
{
    return YDualPower::FirstDualPower();
}

//--- (end of YDualPower functions)
?>