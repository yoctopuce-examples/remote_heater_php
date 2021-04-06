<?php
/*********************************************************************
 *
 *  $Id: yocto_pwmpowersource.php 43580 2021-01-26 17:46:01Z mvuilleu $
 *
 *  Implements YPwmPowerSource, the high-level API for PwmPowerSource functions
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

//--- (YPwmPowerSource return codes)
//--- (end of YPwmPowerSource return codes)
//--- (YPwmPowerSource definitions)
if(!defined('Y_POWERMODE_USB_5V'))           define('Y_POWERMODE_USB_5V',          0);
if(!defined('Y_POWERMODE_USB_3V'))           define('Y_POWERMODE_USB_3V',          1);
if(!defined('Y_POWERMODE_EXT_V'))            define('Y_POWERMODE_EXT_V',           2);
if(!defined('Y_POWERMODE_OPNDRN'))           define('Y_POWERMODE_OPNDRN',          3);
if(!defined('Y_POWERMODE_INVALID'))          define('Y_POWERMODE_INVALID',         -1);
//--- (end of YPwmPowerSource definitions)
    #--- (YPwmPowerSource yapiwrapper)
   #--- (end of YPwmPowerSource yapiwrapper)

//--- (YPwmPowerSource declaration)
/**
 * YPwmPowerSource Class: PWM generator power source control interface, available for instance in the Yocto-PWM-Tx
 *
 * The YPwmPowerSource class allows you to configure
 * the voltage source used by all PWM outputs on the same device.
 */
class YPwmPowerSource extends YFunction
{
    const POWERMODE_USB_5V               = 0;
    const POWERMODE_USB_3V               = 1;
    const POWERMODE_EXT_V                = 2;
    const POWERMODE_OPNDRN               = 3;
    const POWERMODE_INVALID              = -1;
    //--- (end of YPwmPowerSource declaration)

    //--- (YPwmPowerSource attributes)
    protected $_powerMode                = Y_POWERMODE_INVALID;          // PwmPwrState
    //--- (end of YPwmPowerSource attributes)

    function __construct($str_func)
    {
        //--- (YPwmPowerSource constructor)
        parent::__construct($str_func);
        $this->_className = 'PwmPowerSource';

        //--- (end of YPwmPowerSource constructor)
    }

    //--- (YPwmPowerSource implementation)

    function _parseAttr($name, $val)
    {
        switch($name) {
        case 'powerMode':
            $this->_powerMode = intval($val);
            return 1;
        }
        return parent::_parseAttr($name, $val);
    }

    /**
     * Returns the selected power source for the PWM on the same device.
     *
     * @return integer : a value among YPwmPowerSource::POWERMODE_USB_5V, YPwmPowerSource::POWERMODE_USB_3V,
     * YPwmPowerSource::POWERMODE_EXT_V and YPwmPowerSource::POWERMODE_OPNDRN corresponding to the selected
     * power source for the PWM on the same device
     *
     * On failure, throws an exception or returns YPwmPowerSource::POWERMODE_INVALID.
     */
    public function get_powerMode()
    {
        // $res                    is a enumPWMPWRMODE;
        if ($this->_cacheExpiration <= YAPI::GetTickCount()) {
            if ($this->load(YAPI::$_yapiContext->GetCacheValidity()) != YAPI_SUCCESS) {
                return Y_POWERMODE_INVALID;
            }
        }
        $res = $this->_powerMode;
        return $res;
    }

    /**
     * Changes  the PWM power source. PWM can use isolated 5V from USB, isolated 3V from USB or
     * voltage from an external power source. The PWM can also work in open drain  mode. In that
     * mode, the PWM actively pulls the line down.
     * Warning: this setting is common to all PWM on the same device. If you change that parameter,
     * all PWM located on the same device are  affected.
     * If you want the change to be kept after a device reboot, make sure  to call the matching
     * module saveToFlash().
     *
     * @param integer $newval : a value among YPwmPowerSource::POWERMODE_USB_5V,
     * YPwmPowerSource::POWERMODE_USB_3V, YPwmPowerSource::POWERMODE_EXT_V and
     * YPwmPowerSource::POWERMODE_OPNDRN corresponding to  the PWM power source
     *
     * @return integer : YAPI::SUCCESS if the call succeeds.
     *
     * On failure, throws an exception or returns a negative error code.
     */
    public function set_powerMode($newval)
    {
        $rest_val = strval($newval);
        return $this->_setAttr("powerMode",$rest_val);
    }

    /**
     * Retrieves a PWM generator power source for a given identifier.
     * The identifier can be specified using several formats:
     * <ul>
     * <li>FunctionLogicalName</li>
     * <li>ModuleSerialNumber.FunctionIdentifier</li>
     * <li>ModuleSerialNumber.FunctionLogicalName</li>
     * <li>ModuleLogicalName.FunctionIdentifier</li>
     * <li>ModuleLogicalName.FunctionLogicalName</li>
     * </ul>
     *
     * This function does not require that the PWM generator power source is online at the time
     * it is invoked. The returned object is nevertheless valid.
     * Use the method isOnline() to test if the PWM generator power source is
     * indeed online at a given time. In case of ambiguity when looking for
     * a PWM generator power source by logical name, no error is notified: the first instance
     * found is returned. The search is performed first by hardware name,
     * then by logical name.
     *
     * If a call to this object's is_online() method returns FALSE although
     * you are certain that the matching device is plugged, make sure that you did
     * call registerHub() at application initialization time.
     *
     * @param string $func : a string that uniquely characterizes the PWM generator power source, for instance
     *         YPWMTX01.pwmPowerSource.
     *
     * @return YPwmPowerSource : a YPwmPowerSource object allowing you to drive the PWM generator power source.
     */
    public static function FindPwmPowerSource($func)
    {
        // $obj                    is a YPwmPowerSource;
        $obj = YFunction::_FindFromCache('PwmPowerSource', $func);
        if ($obj == null) {
            $obj = new YPwmPowerSource($func);
            YFunction::_AddToCache('PwmPowerSource', $func, $obj);
        }
        return $obj;
    }

    public function powerMode()
    { return $this->get_powerMode(); }

    public function setPowerMode($newval)
    { return $this->set_powerMode($newval); }

    /**
     * Continues the enumeration of PWM generator power sources started using yFirstPwmPowerSource().
     * Caution: You can't make any assumption about the returned PWM generator power sources order.
     * If you want to find a specific a PWM generator power source, use PwmPowerSource.findPwmPowerSource()
     * and a hardwareID or a logical name.
     *
     * @return YPwmPowerSource : a pointer to a YPwmPowerSource object, corresponding to
     *         a PWM generator power source currently online, or a null pointer
     *         if there are no more PWM generator power sources to enumerate.
     */
    public function nextPwmPowerSource()
    {   $resolve = YAPI::resolveFunction($this->_className, $this->_func);
        if($resolve->errorType != YAPI_SUCCESS) return null;
        $next_hwid = YAPI::getNextHardwareId($this->_className, $resolve->result);
        if($next_hwid == null) return null;
        return self::FindPwmPowerSource($next_hwid);
    }

    /**
     * Starts the enumeration of PWM generator power sources currently accessible.
     * Use the method YPwmPowerSource::nextPwmPowerSource() to iterate on
     * next PWM generator power sources.
     *
     * @return YPwmPowerSource : a pointer to a YPwmPowerSource object, corresponding to
     *         the first PWM generator power source currently online, or a null pointer
     *         if there are none.
     */
    public static function FirstPwmPowerSource()
    {   $next_hwid = YAPI::getFirstHardwareId('PwmPowerSource');
        if($next_hwid == null) return null;
        return self::FindPwmPowerSource($next_hwid);
    }

    //--- (end of YPwmPowerSource implementation)

};

//--- (YPwmPowerSource functions)

/**
 * Retrieves a PWM generator power source for a given identifier.
 * The identifier can be specified using several formats:
 * <ul>
 * <li>FunctionLogicalName</li>
 * <li>ModuleSerialNumber.FunctionIdentifier</li>
 * <li>ModuleSerialNumber.FunctionLogicalName</li>
 * <li>ModuleLogicalName.FunctionIdentifier</li>
 * <li>ModuleLogicalName.FunctionLogicalName</li>
 * </ul>
 *
 * This function does not require that the PWM generator power source is online at the time
 * it is invoked. The returned object is nevertheless valid.
 * Use the method isOnline() to test if the PWM generator power source is
 * indeed online at a given time. In case of ambiguity when looking for
 * a PWM generator power source by logical name, no error is notified: the first instance
 * found is returned. The search is performed first by hardware name,
 * then by logical name.
 *
 * If a call to this object's is_online() method returns FALSE although
 * you are certain that the matching device is plugged, make sure that you did
 * call registerHub() at application initialization time.
 *
 * @param string $func : a string that uniquely characterizes the PWM generator power source, for instance
 *         YPWMTX01.pwmPowerSource.
 *
 * @return YPwmPowerSource : a YPwmPowerSource object allowing you to drive the PWM generator power source.
 */
function yFindPwmPowerSource($func)
{
    return YPwmPowerSource::FindPwmPowerSource($func);
}

/**
 * Starts the enumeration of PWM generator power sources currently accessible.
 * Use the method YPwmPowerSource::nextPwmPowerSource() to iterate on
 * next PWM generator power sources.
 *
 * @return YPwmPowerSource : a pointer to a YPwmPowerSource object, corresponding to
 *         the first PWM generator power source currently online, or a null pointer
 *         if there are none.
 */
function yFirstPwmPowerSource()
{
    return YPwmPowerSource::FirstPwmPowerSource();
}

//--- (end of YPwmPowerSource functions)
?>