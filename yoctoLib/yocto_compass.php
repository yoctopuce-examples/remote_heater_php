<?php
/*********************************************************************
 *
 *  $Id: yocto_compass.php 43580 2021-01-26 17:46:01Z mvuilleu $
 *
 *  Implements YCompass, the high-level API for Compass functions
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

//--- (YCompass return codes)
//--- (end of YCompass return codes)
//--- (YCompass definitions)
if(!defined('Y_AXIS_X'))                     define('Y_AXIS_X',                    0);
if(!defined('Y_AXIS_Y'))                     define('Y_AXIS_Y',                    1);
if(!defined('Y_AXIS_Z'))                     define('Y_AXIS_Z',                    2);
if(!defined('Y_AXIS_INVALID'))               define('Y_AXIS_INVALID',              -1);
if(!defined('Y_BANDWIDTH_INVALID'))          define('Y_BANDWIDTH_INVALID',         YAPI_INVALID_UINT);
if(!defined('Y_MAGNETICHEADING_INVALID'))    define('Y_MAGNETICHEADING_INVALID',   YAPI_INVALID_DOUBLE);
//--- (end of YCompass definitions)
    #--- (YCompass yapiwrapper)
   #--- (end of YCompass yapiwrapper)

//--- (YCompass declaration)
/**
 * YCompass Class: compass function control interface, available for instance in the Yocto-3D-V2
 *
 * The YCompass class allows you to read and configure Yoctopuce compass functions.
 * It inherits from YSensor class the core functions to read measurements,
 * to register callback functions, and to access the autonomous datalogger.
 */
class YCompass extends YSensor
{
    const BANDWIDTH_INVALID              = YAPI_INVALID_UINT;
    const AXIS_X                         = 0;
    const AXIS_Y                         = 1;
    const AXIS_Z                         = 2;
    const AXIS_INVALID                   = -1;
    const MAGNETICHEADING_INVALID        = YAPI_INVALID_DOUBLE;
    //--- (end of YCompass declaration)

    //--- (YCompass attributes)
    protected $_bandwidth                = Y_BANDWIDTH_INVALID;          // UInt31
    protected $_axis                     = Y_AXIS_INVALID;               // Axis
    protected $_magneticHeading          = Y_MAGNETICHEADING_INVALID;    // MeasureVal
    //--- (end of YCompass attributes)

    function __construct($str_func)
    {
        //--- (YCompass constructor)
        parent::__construct($str_func);
        $this->_className = 'Compass';

        //--- (end of YCompass constructor)
    }

    //--- (YCompass implementation)

    function _parseAttr($name, $val)
    {
        switch($name) {
        case 'bandwidth':
            $this->_bandwidth = intval($val);
            return 1;
        case 'axis':
            $this->_axis = intval($val);
            return 1;
        case 'magneticHeading':
            $this->_magneticHeading = round($val * 1000.0 / 65536.0) / 1000.0;
            return 1;
        }
        return parent::_parseAttr($name, $val);
    }

    /**
     * Returns the measure update frequency, measured in Hz.
     *
     * @return integer : an integer corresponding to the measure update frequency, measured in Hz
     *
     * On failure, throws an exception or returns YCompass::BANDWIDTH_INVALID.
     */
    public function get_bandwidth()
    {
        // $res                    is a int;
        if ($this->_cacheExpiration <= YAPI::GetTickCount()) {
            if ($this->load(YAPI::$_yapiContext->GetCacheValidity()) != YAPI_SUCCESS) {
                return Y_BANDWIDTH_INVALID;
            }
        }
        $res = $this->_bandwidth;
        return $res;
    }

    /**
     * Changes the measure update frequency, measured in Hz. When the
     * frequency is lower, the device performs averaging.
     * Remember to call the saveToFlash()
     * method of the module if the modification must be kept.
     *
     * @param integer $newval : an integer corresponding to the measure update frequency, measured in Hz
     *
     * @return integer : YAPI::SUCCESS if the call succeeds.
     *
     * On failure, throws an exception or returns a negative error code.
     */
    public function set_bandwidth($newval)
    {
        $rest_val = strval($newval);
        return $this->_setAttr("bandwidth",$rest_val);
    }

    public function get_axis()
    {
        // $res                    is a enumAXIS;
        if ($this->_cacheExpiration <= YAPI::GetTickCount()) {
            if ($this->load(YAPI::$_yapiContext->GetCacheValidity()) != YAPI_SUCCESS) {
                return Y_AXIS_INVALID;
            }
        }
        $res = $this->_axis;
        return $res;
    }

    /**
     * Returns the magnetic heading, regardless of the configured bearing.
     *
     * @return double : a floating point number corresponding to the magnetic heading, regardless of the
     * configured bearing
     *
     * On failure, throws an exception or returns YCompass::MAGNETICHEADING_INVALID.
     */
    public function get_magneticHeading()
    {
        // $res                    is a double;
        if ($this->_cacheExpiration <= YAPI::GetTickCount()) {
            if ($this->load(YAPI::$_yapiContext->GetCacheValidity()) != YAPI_SUCCESS) {
                return Y_MAGNETICHEADING_INVALID;
            }
        }
        $res = $this->_magneticHeading;
        return $res;
    }

    /**
     * Retrieves a compass function for a given identifier.
     * The identifier can be specified using several formats:
     * <ul>
     * <li>FunctionLogicalName</li>
     * <li>ModuleSerialNumber.FunctionIdentifier</li>
     * <li>ModuleSerialNumber.FunctionLogicalName</li>
     * <li>ModuleLogicalName.FunctionIdentifier</li>
     * <li>ModuleLogicalName.FunctionLogicalName</li>
     * </ul>
     *
     * This function does not require that the compass function is online at the time
     * it is invoked. The returned object is nevertheless valid.
     * Use the method isOnline() to test if the compass function is
     * indeed online at a given time. In case of ambiguity when looking for
     * a compass function by logical name, no error is notified: the first instance
     * found is returned. The search is performed first by hardware name,
     * then by logical name.
     *
     * If a call to this object's is_online() method returns FALSE although
     * you are certain that the matching device is plugged, make sure that you did
     * call registerHub() at application initialization time.
     *
     * @param string $func : a string that uniquely characterizes the compass function, for instance
     *         Y3DMK002.compass.
     *
     * @return YCompass : a YCompass object allowing you to drive the compass function.
     */
    public static function FindCompass($func)
    {
        // $obj                    is a YCompass;
        $obj = YFunction::_FindFromCache('Compass', $func);
        if ($obj == null) {
            $obj = new YCompass($func);
            YFunction::_AddToCache('Compass', $func, $obj);
        }
        return $obj;
    }

    public function bandwidth()
    { return $this->get_bandwidth(); }

    public function setBandwidth($newval)
    { return $this->set_bandwidth($newval); }

    public function axis()
    { return $this->get_axis(); }

    public function magneticHeading()
    { return $this->get_magneticHeading(); }

    /**
     * Continues the enumeration of compass functions started using yFirstCompass().
     * Caution: You can't make any assumption about the returned compass functions order.
     * If you want to find a specific a compass function, use Compass.findCompass()
     * and a hardwareID or a logical name.
     *
     * @return YCompass : a pointer to a YCompass object, corresponding to
     *         a compass function currently online, or a null pointer
     *         if there are no more compass functions to enumerate.
     */
    public function nextCompass()
    {   $resolve = YAPI::resolveFunction($this->_className, $this->_func);
        if($resolve->errorType != YAPI_SUCCESS) return null;
        $next_hwid = YAPI::getNextHardwareId($this->_className, $resolve->result);
        if($next_hwid == null) return null;
        return self::FindCompass($next_hwid);
    }

    /**
     * Starts the enumeration of compass functions currently accessible.
     * Use the method YCompass::nextCompass() to iterate on
     * next compass functions.
     *
     * @return YCompass : a pointer to a YCompass object, corresponding to
     *         the first compass function currently online, or a null pointer
     *         if there are none.
     */
    public static function FirstCompass()
    {   $next_hwid = YAPI::getFirstHardwareId('Compass');
        if($next_hwid == null) return null;
        return self::FindCompass($next_hwid);
    }

    //--- (end of YCompass implementation)

};

//--- (YCompass functions)

/**
 * Retrieves a compass function for a given identifier.
 * The identifier can be specified using several formats:
 * <ul>
 * <li>FunctionLogicalName</li>
 * <li>ModuleSerialNumber.FunctionIdentifier</li>
 * <li>ModuleSerialNumber.FunctionLogicalName</li>
 * <li>ModuleLogicalName.FunctionIdentifier</li>
 * <li>ModuleLogicalName.FunctionLogicalName</li>
 * </ul>
 *
 * This function does not require that the compass function is online at the time
 * it is invoked. The returned object is nevertheless valid.
 * Use the method isOnline() to test if the compass function is
 * indeed online at a given time. In case of ambiguity when looking for
 * a compass function by logical name, no error is notified: the first instance
 * found is returned. The search is performed first by hardware name,
 * then by logical name.
 *
 * If a call to this object's is_online() method returns FALSE although
 * you are certain that the matching device is plugged, make sure that you did
 * call registerHub() at application initialization time.
 *
 * @param string $func : a string that uniquely characterizes the compass function, for instance
 *         Y3DMK002.compass.
 *
 * @return YCompass : a YCompass object allowing you to drive the compass function.
 */
function yFindCompass($func)
{
    return YCompass::FindCompass($func);
}

/**
 * Starts the enumeration of compass functions currently accessible.
 * Use the method YCompass::nextCompass() to iterate on
 * next compass functions.
 *
 * @return YCompass : a pointer to a YCompass object, corresponding to
 *         the first compass function currently online, or a null pointer
 *         if there are none.
 */
function yFirstCompass()
{
    return YCompass::FirstCompass();
}

//--- (end of YCompass functions)
?>