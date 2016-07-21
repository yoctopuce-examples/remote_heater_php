<?php
/*********************************************************************
 *
 * $Id: yocto_humidity.php 23243 2016-02-23 14:13:12Z seb $
 *
 * Implements YHumidity, the high-level API for Humidity functions
 *
 * - - - - - - - - - License information: - - - - - - - - - 
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

//--- (YHumidity return codes)
//--- (end of YHumidity return codes)
//--- (YHumidity definitions)
if(!defined('Y_RELHUM_INVALID'))             define('Y_RELHUM_INVALID',            YAPI_INVALID_DOUBLE);
if(!defined('Y_ABSHUM_INVALID'))             define('Y_ABSHUM_INVALID',            YAPI_INVALID_DOUBLE);
//--- (end of YHumidity definitions)

//--- (YHumidity declaration)
/**
 * YHumidity Class: Humidity function interface
 *
 * The Yoctopuce class YHumidity allows you to read and configure Yoctopuce humidity
 * sensors. It inherits from YSensor class the core functions to read measurements,
 * register callback functions, access to the autonomous datalogger.
 */
class YHumidity extends YSensor
{
    const RELHUM_INVALID                 = YAPI_INVALID_DOUBLE;
    const ABSHUM_INVALID                 = YAPI_INVALID_DOUBLE;
    //--- (end of YHumidity declaration)

    //--- (YHumidity attributes)
    protected $_relHum                   = Y_RELHUM_INVALID;             // MeasureVal
    protected $_absHum                   = Y_ABSHUM_INVALID;             // MeasureVal
    //--- (end of YHumidity attributes)

    function __construct($str_func)
    {
        //--- (YHumidity constructor)
        parent::__construct($str_func);
        $this->_className = 'Humidity';

        //--- (end of YHumidity constructor)
    }

    //--- (YHumidity implementation)

    function _parseAttr($name, $val)
    {
        switch($name) {
        case 'relHum':
            $this->_relHum = round($val * 1000.0 / 65536.0) / 1000.0;
            return 1;
        case 'absHum':
            $this->_absHum = round($val * 1000.0 / 65536.0) / 1000.0;
            return 1;
        }
        return parent::_parseAttr($name, $val);
    }

    /**
     * Changes the primary unit for measuring humidity. That unit is a string.
     * If that strings starts with the letter 'g', the primary measured value is the absolute
     * humidity, in g/m3. Otherwise, the primary measured value will be the relative humidity
     * (RH), in per cents.
     *
     * Remember to call the saveToFlash() method of the module if the modification
     * must be kept.
     *
     * @param newval : a string corresponding to the primary unit for measuring humidity
     *
     * @return YAPI_SUCCESS if the call succeeds.
     *
     * On failure, throws an exception or returns a negative error code.
     */
    public function set_unit($newval)
    {
        $rest_val = $newval;
        return $this->_setAttr("unit",$rest_val);
    }

    /**
     * Returns the current relative humidity, in per cents.
     *
     * @return a floating point number corresponding to the current relative humidity, in per cents
     *
     * On failure, throws an exception or returns Y_RELHUM_INVALID.
     */
    public function get_relHum()
    {
        if ($this->_cacheExpiration <= YAPI::GetTickCount()) {
            if ($this->load(YAPI::$defaultCacheValidity) != YAPI_SUCCESS) {
                return Y_RELHUM_INVALID;
            }
        }
        return $this->_relHum;
    }

    /**
     * Returns the current absolute humidity, in grams per cubic meter of air.
     *
     * @return a floating point number corresponding to the current absolute humidity, in grams per cubic meter of air
     *
     * On failure, throws an exception or returns Y_ABSHUM_INVALID.
     */
    public function get_absHum()
    {
        if ($this->_cacheExpiration <= YAPI::GetTickCount()) {
            if ($this->load(YAPI::$defaultCacheValidity) != YAPI_SUCCESS) {
                return Y_ABSHUM_INVALID;
            }
        }
        return $this->_absHum;
    }

    /**
     * Retrieves a humidity sensor for a given identifier.
     * The identifier can be specified using several formats:
     * <ul>
     * <li>FunctionLogicalName</li>
     * <li>ModuleSerialNumber.FunctionIdentifier</li>
     * <li>ModuleSerialNumber.FunctionLogicalName</li>
     * <li>ModuleLogicalName.FunctionIdentifier</li>
     * <li>ModuleLogicalName.FunctionLogicalName</li>
     * </ul>
     *
     * This function does not require that the humidity sensor is online at the time
     * it is invoked. The returned object is nevertheless valid.
     * Use the method YHumidity.isOnline() to test if the humidity sensor is
     * indeed online at a given time. In case of ambiguity when looking for
     * a humidity sensor by logical name, no error is notified: the first instance
     * found is returned. The search is performed first by hardware name,
     * then by logical name.
     *
     * @param func : a string that uniquely characterizes the humidity sensor
     *
     * @return a YHumidity object allowing you to drive the humidity sensor.
     */
    public static function FindHumidity($func)
    {
        // $obj                    is a YHumidity;
        $obj = YFunction::_FindFromCache('Humidity', $func);
        if ($obj == null) {
            $obj = new YHumidity($func);
            YFunction::_AddToCache('Humidity', $func, $obj);
        }
        return $obj;
    }

    public function setUnit($newval)
    { return $this->set_unit($newval); }

    public function relHum()
    { return $this->get_relHum(); }

    public function absHum()
    { return $this->get_absHum(); }

    /**
     * Continues the enumeration of humidity sensors started using yFirstHumidity().
     *
     * @return a pointer to a YHumidity object, corresponding to
     *         a humidity sensor currently online, or a null pointer
     *         if there are no more humidity sensors to enumerate.
     */
    public function nextHumidity()
    {   $resolve = YAPI::resolveFunction($this->_className, $this->_func);
        if($resolve->errorType != YAPI_SUCCESS) return null;
        $next_hwid = YAPI::getNextHardwareId($this->_className, $resolve->result);
        if($next_hwid == null) return null;
        return yFindHumidity($next_hwid);
    }

    /**
     * Starts the enumeration of humidity sensors currently accessible.
     * Use the method YHumidity.nextHumidity() to iterate on
     * next humidity sensors.
     *
     * @return a pointer to a YHumidity object, corresponding to
     *         the first humidity sensor currently online, or a null pointer
     *         if there are none.
     */
    public static function FirstHumidity()
    {   $next_hwid = YAPI::getFirstHardwareId('Humidity');
        if($next_hwid == null) return null;
        return self::FindHumidity($next_hwid);
    }

    //--- (end of YHumidity implementation)

};

//--- (Humidity functions)

/**
 * Retrieves a humidity sensor for a given identifier.
 * The identifier can be specified using several formats:
 * <ul>
 * <li>FunctionLogicalName</li>
 * <li>ModuleSerialNumber.FunctionIdentifier</li>
 * <li>ModuleSerialNumber.FunctionLogicalName</li>
 * <li>ModuleLogicalName.FunctionIdentifier</li>
 * <li>ModuleLogicalName.FunctionLogicalName</li>
 * </ul>
 *
 * This function does not require that the humidity sensor is online at the time
 * it is invoked. The returned object is nevertheless valid.
 * Use the method YHumidity.isOnline() to test if the humidity sensor is
 * indeed online at a given time. In case of ambiguity when looking for
 * a humidity sensor by logical name, no error is notified: the first instance
 * found is returned. The search is performed first by hardware name,
 * then by logical name.
 *
 * @param func : a string that uniquely characterizes the humidity sensor
 *
 * @return a YHumidity object allowing you to drive the humidity sensor.
 */
function yFindHumidity($func)
{
    return YHumidity::FindHumidity($func);
}

/**
 * Starts the enumeration of humidity sensors currently accessible.
 * Use the method YHumidity.nextHumidity() to iterate on
 * next humidity sensors.
 *
 * @return a pointer to a YHumidity object, corresponding to
 *         the first humidity sensor currently online, or a null pointer
 *         if there are none.
 */
function yFirstHumidity()
{
    return YHumidity::FirstHumidity();
}

//--- (end of Humidity functions)
?>