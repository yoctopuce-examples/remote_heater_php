<?php
/*********************************************************************
 *
 *  $Id: yocto_oscontrol.php 43580 2021-01-26 17:46:01Z mvuilleu $
 *
 *  Implements YOsControl, the high-level API for OsControl functions
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

//--- (YOsControl return codes)
//--- (end of YOsControl return codes)
//--- (YOsControl definitions)
if(!defined('Y_SHUTDOWNCOUNTDOWN_INVALID'))  define('Y_SHUTDOWNCOUNTDOWN_INVALID', YAPI_INVALID_UINT);
//--- (end of YOsControl definitions)
    #--- (YOsControl yapiwrapper)
   #--- (end of YOsControl yapiwrapper)

//--- (YOsControl declaration)
/**
 * YOsControl Class: Operating system control interface via the VirtualHub application
 *
 * The YOScontrol class provides some control over the operating system running a VirtualHub.
 * YOsControl is available on VirtualHub software only. This feature must be activated at the VirtualHub
 * start up with -o option.
 */
class YOsControl extends YFunction
{
    const SHUTDOWNCOUNTDOWN_INVALID      = YAPI_INVALID_UINT;
    //--- (end of YOsControl declaration)

    //--- (YOsControl attributes)
    protected $_shutdownCountdown        = Y_SHUTDOWNCOUNTDOWN_INVALID;  // UInt31
    //--- (end of YOsControl attributes)

    function __construct($str_func)
    {
        //--- (YOsControl constructor)
        parent::__construct($str_func);
        $this->_className = 'OsControl';

        //--- (end of YOsControl constructor)
    }

    //--- (YOsControl implementation)

    function _parseAttr($name, $val)
    {
        switch($name) {
        case 'shutdownCountdown':
            $this->_shutdownCountdown = intval($val);
            return 1;
        }
        return parent::_parseAttr($name, $val);
    }

    /**
     * Returns the remaining number of seconds before the OS shutdown, or zero when no
     * shutdown has been scheduled.
     *
     * @return integer : an integer corresponding to the remaining number of seconds before the OS
     * shutdown, or zero when no
     *         shutdown has been scheduled
     *
     * On failure, throws an exception or returns YOsControl::SHUTDOWNCOUNTDOWN_INVALID.
     */
    public function get_shutdownCountdown()
    {
        // $res                    is a int;
        if ($this->_cacheExpiration <= YAPI::GetTickCount()) {
            if ($this->load(YAPI::$_yapiContext->GetCacheValidity()) != YAPI_SUCCESS) {
                return Y_SHUTDOWNCOUNTDOWN_INVALID;
            }
        }
        $res = $this->_shutdownCountdown;
        return $res;
    }

    public function set_shutdownCountdown($newval)
    {
        $rest_val = strval($newval);
        return $this->_setAttr("shutdownCountdown",$rest_val);
    }

    /**
     * Retrieves OS control for a given identifier.
     * The identifier can be specified using several formats:
     * <ul>
     * <li>FunctionLogicalName</li>
     * <li>ModuleSerialNumber.FunctionIdentifier</li>
     * <li>ModuleSerialNumber.FunctionLogicalName</li>
     * <li>ModuleLogicalName.FunctionIdentifier</li>
     * <li>ModuleLogicalName.FunctionLogicalName</li>
     * </ul>
     *
     * This function does not require that the OS control is online at the time
     * it is invoked. The returned object is nevertheless valid.
     * Use the method isOnline() to test if the OS control is
     * indeed online at a given time. In case of ambiguity when looking for
     * OS control by logical name, no error is notified: the first instance
     * found is returned. The search is performed first by hardware name,
     * then by logical name.
     *
     * If a call to this object's is_online() method returns FALSE although
     * you are certain that the matching device is plugged, make sure that you did
     * call registerHub() at application initialization time.
     *
     * @param string $func : a string that uniquely characterizes the OS control, for instance
     *         MyDevice.osControl.
     *
     * @return YOsControl : a YOsControl object allowing you to drive the OS control.
     */
    public static function FindOsControl($func)
    {
        // $obj                    is a YOsControl;
        $obj = YFunction::_FindFromCache('OsControl', $func);
        if ($obj == null) {
            $obj = new YOsControl($func);
            YFunction::_AddToCache('OsControl', $func, $obj);
        }
        return $obj;
    }

    /**
     * Schedules an OS shutdown after a given number of seconds.
     *
     * @param integer $secBeforeShutDown : number of seconds before shutdown
     *
     * @return integer : YAPI::SUCCESS when the call succeeds.
     *
     * On failure, throws an exception or returns a negative error code.
     */
    public function shutdown($secBeforeShutDown)
    {
        return $this->set_shutdownCountdown($secBeforeShutDown);
    }

    public function shutdownCountdown()
    { return $this->get_shutdownCountdown(); }

    public function setShutdownCountdown($newval)
    { return $this->set_shutdownCountdown($newval); }

    /**
     * Continues the enumeration of OS control started using yFirstOsControl().
     * Caution: You can't make any assumption about the returned OS control order.
     * If you want to find a specific OS control, use OsControl.findOsControl()
     * and a hardwareID or a logical name.
     *
     * @return YOsControl : a pointer to a YOsControl object, corresponding to
     *         OS control currently online, or a null pointer
     *         if there are no more OS control to enumerate.
     */
    public function nextOsControl()
    {   $resolve = YAPI::resolveFunction($this->_className, $this->_func);
        if($resolve->errorType != YAPI_SUCCESS) return null;
        $next_hwid = YAPI::getNextHardwareId($this->_className, $resolve->result);
        if($next_hwid == null) return null;
        return self::FindOsControl($next_hwid);
    }

    /**
     * Starts the enumeration of OS control currently accessible.
     * Use the method YOsControl::nextOsControl() to iterate on
     * next OS control.
     *
     * @return YOsControl : a pointer to a YOsControl object, corresponding to
     *         the first OS control currently online, or a null pointer
     *         if there are none.
     */
    public static function FirstOsControl()
    {   $next_hwid = YAPI::getFirstHardwareId('OsControl');
        if($next_hwid == null) return null;
        return self::FindOsControl($next_hwid);
    }

    //--- (end of YOsControl implementation)

};

//--- (YOsControl functions)

/**
 * Retrieves OS control for a given identifier.
 * The identifier can be specified using several formats:
 * <ul>
 * <li>FunctionLogicalName</li>
 * <li>ModuleSerialNumber.FunctionIdentifier</li>
 * <li>ModuleSerialNumber.FunctionLogicalName</li>
 * <li>ModuleLogicalName.FunctionIdentifier</li>
 * <li>ModuleLogicalName.FunctionLogicalName</li>
 * </ul>
 *
 * This function does not require that the OS control is online at the time
 * it is invoked. The returned object is nevertheless valid.
 * Use the method isOnline() to test if the OS control is
 * indeed online at a given time. In case of ambiguity when looking for
 * OS control by logical name, no error is notified: the first instance
 * found is returned. The search is performed first by hardware name,
 * then by logical name.
 *
 * If a call to this object's is_online() method returns FALSE although
 * you are certain that the matching device is plugged, make sure that you did
 * call registerHub() at application initialization time.
 *
 * @param string $func : a string that uniquely characterizes the OS control, for instance
 *         MyDevice.osControl.
 *
 * @return YOsControl : a YOsControl object allowing you to drive the OS control.
 */
function yFindOsControl($func)
{
    return YOsControl::FindOsControl($func);
}

/**
 * Starts the enumeration of OS control currently accessible.
 * Use the method YOsControl::nextOsControl() to iterate on
 * next OS control.
 *
 * @return YOsControl : a pointer to a YOsControl object, corresponding to
 *         the first OS control currently online, or a null pointer
 *         if there are none.
 */
function yFirstOsControl()
{
    return YOsControl::FirstOsControl();
}

//--- (end of YOsControl functions)
?>