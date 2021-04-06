<?php
/*********************************************************************
 *
 * $Id: yocto_gyro.php 43580 2021-01-26 17:46:01Z mvuilleu $
 *
 * Implements YGyro, the high-level API for Gyro functions
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

//--- (generated code: YQt return codes)
//--- (end of generated code: YQt return codes)
//--- (generated code: YQt definitions)
//--- (end of generated code: YQt definitions)

//--- (generated code: YQt declaration)
/**
 * YQt Class: Base interface to access quaternion components, available for instance in the Yocto-3D-V2
 *
 * The YQt class provides direct access to the 3D attitude estimation
 * provided by Yoctopuce inertial sensors. The four instances of YQt
 * provide direct access to the individual quaternion components representing the
 * orientation. It is usually not needed to use the YQt class
 * directly, as the YGyro class provides a more convenient higher-level
 * interface.
 */
class YQt extends YSensor
{
    //--- (end of generated code: YQt declaration)

    //--- (generated code: YQt attributes)
    //--- (end of generated code: YQt attributes)

    function __construct($str_func)
    {
        //--- (generated code: YQt constructor)
        parent::__construct($str_func);
        $this->_className = 'Qt';

        //--- (end of generated code: YQt constructor)
    }

    //--- (generated code: YQt implementation)

    /**
     * Retrieves a quaternion component for a given identifier.
     * The identifier can be specified using several formats:
     * <ul>
     * <li>FunctionLogicalName</li>
     * <li>ModuleSerialNumber.FunctionIdentifier</li>
     * <li>ModuleSerialNumber.FunctionLogicalName</li>
     * <li>ModuleLogicalName.FunctionIdentifier</li>
     * <li>ModuleLogicalName.FunctionLogicalName</li>
     * </ul>
     *
     * This function does not require that the quaternion component is online at the time
     * it is invoked. The returned object is nevertheless valid.
     * Use the method isOnline() to test if the quaternion component is
     * indeed online at a given time. In case of ambiguity when looking for
     * a quaternion component by logical name, no error is notified: the first instance
     * found is returned. The search is performed first by hardware name,
     * then by logical name.
     *
     * If a call to this object's is_online() method returns FALSE although
     * you are certain that the matching device is plugged, make sure that you did
     * call registerHub() at application initialization time.
     *
     * @param string $func : a string that uniquely characterizes the quaternion component, for instance
     *         Y3DMK002.qt1.
     *
     * @return YQt : a YQt object allowing you to drive the quaternion component.
     */
    public static function FindQt($func)
    {
        // $obj                    is a YQt;
        $obj = YFunction::_FindFromCache('Qt', $func);
        if ($obj == null) {
            $obj = new YQt($func);
            YFunction::_AddToCache('Qt', $func, $obj);
        }
        return $obj;
    }

    /**
     * Continues the enumeration of quaternion components started using yFirstQt().
     * Caution: You can't make any assumption about the returned quaternion components order.
     * If you want to find a specific a quaternion component, use Qt.findQt()
     * and a hardwareID or a logical name.
     *
     * @return YQt : a pointer to a YQt object, corresponding to
     *         a quaternion component currently online, or a null pointer
     *         if there are no more quaternion components to enumerate.
     */
    public function nextQt()
    {   $resolve = YAPI::resolveFunction($this->_className, $this->_func);
        if($resolve->errorType != YAPI_SUCCESS) return null;
        $next_hwid = YAPI::getNextHardwareId($this->_className, $resolve->result);
        if($next_hwid == null) return null;
        return self::FindQt($next_hwid);
    }

    /**
     * Starts the enumeration of quaternion components currently accessible.
     * Use the method YQt::nextQt() to iterate on
     * next quaternion components.
     *
     * @return YQt : a pointer to a YQt object, corresponding to
     *         the first quaternion component currently online, or a null pointer
     *         if there are none.
     */
    public static function FirstQt()
    {   $next_hwid = YAPI::getFirstHardwareId('Qt');
        if($next_hwid == null) return null;
        return self::FindQt($next_hwid);
    }

    //--- (end of generated code: YQt implementation)

};

//--- (generated code: YQt functions)

/**
 * Retrieves a quaternion component for a given identifier.
 * The identifier can be specified using several formats:
 * <ul>
 * <li>FunctionLogicalName</li>
 * <li>ModuleSerialNumber.FunctionIdentifier</li>
 * <li>ModuleSerialNumber.FunctionLogicalName</li>
 * <li>ModuleLogicalName.FunctionIdentifier</li>
 * <li>ModuleLogicalName.FunctionLogicalName</li>
 * </ul>
 *
 * This function does not require that the quaternion component is online at the time
 * it is invoked. The returned object is nevertheless valid.
 * Use the method isOnline() to test if the quaternion component is
 * indeed online at a given time. In case of ambiguity when looking for
 * a quaternion component by logical name, no error is notified: the first instance
 * found is returned. The search is performed first by hardware name,
 * then by logical name.
 *
 * If a call to this object's is_online() method returns FALSE although
 * you are certain that the matching device is plugged, make sure that you did
 * call registerHub() at application initialization time.
 *
 * @param string $func : a string that uniquely characterizes the quaternion component, for instance
 *         Y3DMK002.qt1.
 *
 * @return YQt : a YQt object allowing you to drive the quaternion component.
 */
function yFindQt($func)
{
    return YQt::FindQt($func);
}

/**
 * Starts the enumeration of quaternion components currently accessible.
 * Use the method YQt::nextQt() to iterate on
 * next quaternion components.
 *
 * @return YQt : a pointer to a YQt object, corresponding to
 *         the first quaternion component currently online, or a null pointer
 *         if there are none.
 */
function yFirstQt()
{
    return YQt::FirstQt();
}

//--- (end of generated code: YQt functions)

//--- (generated code: YGyro return codes)
//--- (end of generated code: YGyro return codes)
//--- (generated code: YGyro definitions)
if(!defined('Y_BANDWIDTH_INVALID'))          define('Y_BANDWIDTH_INVALID',         YAPI_INVALID_UINT);
if(!defined('Y_XVALUE_INVALID'))             define('Y_XVALUE_INVALID',            YAPI_INVALID_DOUBLE);
if(!defined('Y_YVALUE_INVALID'))             define('Y_YVALUE_INVALID',            YAPI_INVALID_DOUBLE);
if(!defined('Y_ZVALUE_INVALID'))             define('Y_ZVALUE_INVALID',            YAPI_INVALID_DOUBLE);
//--- (end of generated code: YGyro definitions)

function yInternalGyroCallback($YQt_obj, $str_value)
{
    $gyro = $YQt_obj->get_userData();
    if(!$gyro) return;
    $idx = intVal(substr($YQt_obj->get_functionId(),2));
    $gyro->_invokeGyroCallbacks($idx, floatVal($str_value));
}

//--- (generated code: YGyro declaration)
/**
 * YGyro Class: gyroscope control interface, available for instance in the Yocto-3D-V2
 *
 * The YGyro class allows you to read and configure Yoctopuce gyroscopes.
 * It inherits from YSensor class the core functions to read measurements,
 * to register callback functions, and to access the autonomous datalogger.
 * This class adds the possibility to access x, y and z components of the rotation
 * vector separately, as well as the possibility to deal with quaternion-based
 * orientation estimates.
 */
class YGyro extends YSensor
{
    const BANDWIDTH_INVALID              = YAPI_INVALID_UINT;
    const XVALUE_INVALID                 = YAPI_INVALID_DOUBLE;
    const YVALUE_INVALID                 = YAPI_INVALID_DOUBLE;
    const ZVALUE_INVALID                 = YAPI_INVALID_DOUBLE;
    //--- (end of generated code: YGyro declaration)

    //--- (generated code: YGyro attributes)
    protected $_bandwidth                = Y_BANDWIDTH_INVALID;          // UInt31
    protected $_xValue                   = Y_XVALUE_INVALID;             // MeasureVal
    protected $_yValue                   = Y_YVALUE_INVALID;             // MeasureVal
    protected $_zValue                   = Y_ZVALUE_INVALID;             // MeasureVal
    protected $_qt_stamp                 = 0;                            // int
    protected $_qt_w                     = null;                         // YQt
    protected $_qt_x                     = null;                         // YQt
    protected $_qt_y                     = null;                         // YQt
    protected $_qt_z                     = null;                         // YQt
    protected $_w                        = 0;                            // float
    protected $_x                        = 0;                            // float
    protected $_y                        = 0;                            // float
    protected $_z                        = 0;                            // float
    protected $_angles_stamp             = 0;                            // int
    protected $_head                     = 0;                            // float
    protected $_pitch                    = 0;                            // float
    protected $_roll                     = 0;                            // float
    protected $_quatCallback             = null;                         // YQuatCallback
    protected $_anglesCallback           = null;                         // YAnglesCallback
    //--- (end of generated code: YGyro attributes)

    function __construct($str_func)
    {
        //--- (generated code: YGyro constructor)
        parent::__construct($str_func);
        $this->_className = 'Gyro';

        //--- (end of generated code: YGyro constructor)
    }

    //--- (generated code: YGyro implementation)

    function _parseAttr($name, $val)
    {
        switch($name) {
        case 'bandwidth':
            $this->_bandwidth = intval($val);
            return 1;
        case 'xValue':
            $this->_xValue = round($val * 1000.0 / 65536.0) / 1000.0;
            return 1;
        case 'yValue':
            $this->_yValue = round($val * 1000.0 / 65536.0) / 1000.0;
            return 1;
        case 'zValue':
            $this->_zValue = round($val * 1000.0 / 65536.0) / 1000.0;
            return 1;
        }
        return parent::_parseAttr($name, $val);
    }

    /**
     * Returns the measure update frequency, measured in Hz.
     *
     * @return integer : an integer corresponding to the measure update frequency, measured in Hz
     *
     * On failure, throws an exception or returns YGyro::BANDWIDTH_INVALID.
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

    /**
     * Returns the angular velocity around the X axis of the device, as a floating point number.
     *
     * @return double : a floating point number corresponding to the angular velocity around the X axis of
     * the device, as a floating point number
     *
     * On failure, throws an exception or returns YGyro::XVALUE_INVALID.
     */
    public function get_xValue()
    {
        // $res                    is a double;
        if ($this->_cacheExpiration <= YAPI::GetTickCount()) {
            if ($this->load(YAPI::$_yapiContext->GetCacheValidity()) != YAPI_SUCCESS) {
                return Y_XVALUE_INVALID;
            }
        }
        $res = $this->_xValue;
        return $res;
    }

    /**
     * Returns the angular velocity around the Y axis of the device, as a floating point number.
     *
     * @return double : a floating point number corresponding to the angular velocity around the Y axis of
     * the device, as a floating point number
     *
     * On failure, throws an exception or returns YGyro::YVALUE_INVALID.
     */
    public function get_yValue()
    {
        // $res                    is a double;
        if ($this->_cacheExpiration <= YAPI::GetTickCount()) {
            if ($this->load(YAPI::$_yapiContext->GetCacheValidity()) != YAPI_SUCCESS) {
                return Y_YVALUE_INVALID;
            }
        }
        $res = $this->_yValue;
        return $res;
    }

    /**
     * Returns the angular velocity around the Z axis of the device, as a floating point number.
     *
     * @return double : a floating point number corresponding to the angular velocity around the Z axis of
     * the device, as a floating point number
     *
     * On failure, throws an exception or returns YGyro::ZVALUE_INVALID.
     */
    public function get_zValue()
    {
        // $res                    is a double;
        if ($this->_cacheExpiration <= YAPI::GetTickCount()) {
            if ($this->load(YAPI::$_yapiContext->GetCacheValidity()) != YAPI_SUCCESS) {
                return Y_ZVALUE_INVALID;
            }
        }
        $res = $this->_zValue;
        return $res;
    }

    /**
     * Retrieves a gyroscope for a given identifier.
     * The identifier can be specified using several formats:
     * <ul>
     * <li>FunctionLogicalName</li>
     * <li>ModuleSerialNumber.FunctionIdentifier</li>
     * <li>ModuleSerialNumber.FunctionLogicalName</li>
     * <li>ModuleLogicalName.FunctionIdentifier</li>
     * <li>ModuleLogicalName.FunctionLogicalName</li>
     * </ul>
     *
     * This function does not require that the gyroscope is online at the time
     * it is invoked. The returned object is nevertheless valid.
     * Use the method isOnline() to test if the gyroscope is
     * indeed online at a given time. In case of ambiguity when looking for
     * a gyroscope by logical name, no error is notified: the first instance
     * found is returned. The search is performed first by hardware name,
     * then by logical name.
     *
     * If a call to this object's is_online() method returns FALSE although
     * you are certain that the matching device is plugged, make sure that you did
     * call registerHub() at application initialization time.
     *
     * @param string $func : a string that uniquely characterizes the gyroscope, for instance
     *         Y3DMK002.gyro.
     *
     * @return YGyro : a YGyro object allowing you to drive the gyroscope.
     */
    public static function FindGyro($func)
    {
        // $obj                    is a YGyro;
        $obj = YFunction::_FindFromCache('Gyro', $func);
        if ($obj == null) {
            $obj = new YGyro($func);
            YFunction::_AddToCache('Gyro', $func, $obj);
        }
        return $obj;
    }

    public function _loadQuaternion()
    {
        // $now_stamp              is a int;
        // $age_ms                 is a int;
        $now_stamp = ((YAPI::GetTickCount()) & (0x7FFFFFFF));
        $age_ms = ((($now_stamp - $this->_qt_stamp)) & (0x7FFFFFFF));
        if (($age_ms >= 10) || ($this->_qt_stamp == 0)) {
            if ($this->load(10) != YAPI_SUCCESS) {
                return YAPI_DEVICE_NOT_FOUND;
            }
            if ($this->_qt_stamp == 0) {
                $this->_qt_w = YQt::FindQt(sprintf('%s.qt1',$this->_serial));
                $this->_qt_x = YQt::FindQt(sprintf('%s.qt2',$this->_serial));
                $this->_qt_y = YQt::FindQt(sprintf('%s.qt3',$this->_serial));
                $this->_qt_z = YQt::FindQt(sprintf('%s.qt4',$this->_serial));
            }
            if ($this->_qt_w->load(9) != YAPI_SUCCESS) {
                return YAPI_DEVICE_NOT_FOUND;
            }
            if ($this->_qt_x->load(9) != YAPI_SUCCESS) {
                return YAPI_DEVICE_NOT_FOUND;
            }
            if ($this->_qt_y->load(9) != YAPI_SUCCESS) {
                return YAPI_DEVICE_NOT_FOUND;
            }
            if ($this->_qt_z->load(9) != YAPI_SUCCESS) {
                return YAPI_DEVICE_NOT_FOUND;
            }
            $this->_w = $this->_qt_w->get_currentValue();
            $this->_x = $this->_qt_x->get_currentValue();
            $this->_y = $this->_qt_y->get_currentValue();
            $this->_z = $this->_qt_z->get_currentValue();
            $this->_qt_stamp = $now_stamp;
        }
        return YAPI_SUCCESS;
    }

    public function _loadAngles()
    {
        // $sqw                    is a float;
        // $sqx                    is a float;
        // $sqy                    is a float;
        // $sqz                    is a float;
        // $norm                   is a float;
        // $delta                  is a float;

        if ($this->_loadQuaternion() != YAPI_SUCCESS) {
            return YAPI_DEVICE_NOT_FOUND;
        }
        if ($this->_angles_stamp != $this->_qt_stamp) {
            $sqw = $this->_w * $this->_w;
            $sqx = $this->_x * $this->_x;
            $sqy = $this->_y * $this->_y;
            $sqz = $this->_z * $this->_z;
            $norm = $sqx + $sqy + $sqz + $sqw;
            $delta = $this->_y * $this->_w - $this->_x * $this->_z;
            if ($delta > 0.499 * $norm) {
                // singularity at north pole
                $this->_pitch = 90.0;
                $this->_head  = round(2.0 * 1800.0/3.141592653589793238463 * atan2($this->_x,-$this->_w)) / 10.0;
            } else {
                if ($delta < -0.499 * $norm) {
                    // singularity at south pole
                    $this->_pitch = -90.0;
                    $this->_head  = round(-2.0 * 1800.0/3.141592653589793238463 * atan2($this->_x,-$this->_w)) / 10.0;
                } else {
                    $this->_roll  = round(1800.0/3.141592653589793238463 * atan2(2.0 * ($this->_w * $this->_x + $this->_y * $this->_z),$sqw - $sqx - $sqy + $sqz)) / 10.0;
                    $this->_pitch = round(1800.0/3.141592653589793238463 * asin(2.0 * $delta / $norm)) / 10.0;
                    $this->_head  = round(1800.0/3.141592653589793238463 * atan2(2.0 * ($this->_x * $this->_y + $this->_z * $this->_w),$sqw + $sqx - $sqy - $sqz)) / 10.0;
                }
            }
            $this->_angles_stamp = $this->_qt_stamp;
        }
        return YAPI_SUCCESS;
    }

    /**
     * Returns the estimated roll angle, based on the integration of
     * gyroscopic measures combined with acceleration and
     * magnetic field measurements.
     * The axis corresponding to the roll angle can be mapped to any
     * of the device X, Y or Z physical directions using methods of
     * the class YRefFrame.
     *
     * @return double : a floating-point number corresponding to roll angle
     *         in degrees, between -180 and +180.
     */
    public function get_roll()
    {
        $this->_loadAngles();
        return $this->_roll;
    }

    /**
     * Returns the estimated pitch angle, based on the integration of
     * gyroscopic measures combined with acceleration and
     * magnetic field measurements.
     * The axis corresponding to the pitch angle can be mapped to any
     * of the device X, Y or Z physical directions using methods of
     * the class YRefFrame.
     *
     * @return double : a floating-point number corresponding to pitch angle
     *         in degrees, between -90 and +90.
     */
    public function get_pitch()
    {
        $this->_loadAngles();
        return $this->_pitch;
    }

    /**
     * Returns the estimated heading angle, based on the integration of
     * gyroscopic measures combined with acceleration and
     * magnetic field measurements.
     * The axis corresponding to the heading can be mapped to any
     * of the device X, Y or Z physical directions using methods of
     * the class YRefFrame.
     *
     * @return double : a floating-point number corresponding to heading
     *         in degrees, between 0 and 360.
     */
    public function get_heading()
    {
        $this->_loadAngles();
        return $this->_head;
    }

    /**
     * Returns the w component (real part) of the quaternion
     * describing the device estimated orientation, based on the
     * integration of gyroscopic measures combined with acceleration and
     * magnetic field measurements.
     *
     * @return double : a floating-point number corresponding to the w
     *         component of the quaternion.
     */
    public function get_quaternionW()
    {
        $this->_loadQuaternion();
        return $this->_w;
    }

    /**
     * Returns the x component of the quaternion
     * describing the device estimated orientation, based on the
     * integration of gyroscopic measures combined with acceleration and
     * magnetic field measurements. The x component is
     * mostly correlated with rotations on the roll axis.
     *
     * @return double : a floating-point number corresponding to the x
     *         component of the quaternion.
     */
    public function get_quaternionX()
    {
        $this->_loadQuaternion();
        return $this->_x;
    }

    /**
     * Returns the y component of the quaternion
     * describing the device estimated orientation, based on the
     * integration of gyroscopic measures combined with acceleration and
     * magnetic field measurements. The y component is
     * mostly correlated with rotations on the pitch axis.
     *
     * @return double : a floating-point number corresponding to the y
     *         component of the quaternion.
     */
    public function get_quaternionY()
    {
        $this->_loadQuaternion();
        return $this->_y;
    }

    /**
     * Returns the x component of the quaternion
     * describing the device estimated orientation, based on the
     * integration of gyroscopic measures combined with acceleration and
     * magnetic field measurements. The x component is
     * mostly correlated with changes of heading.
     *
     * @return double : a floating-point number corresponding to the z
     *         component of the quaternion.
     */
    public function get_quaternionZ()
    {
        $this->_loadQuaternion();
        return $this->_z;
    }

    /**
     * Registers a callback function that will be invoked each time that the estimated
     * device orientation has changed. The call frequency is typically around 95Hz during a move.
     * The callback is invoked only during the execution of ySleep or yHandleEvents.
     * This provides control over the time when the callback is triggered.
     * For good responsiveness, remember to call one of these two functions periodically.
     * To unregister a callback, pass a null pointer as argument.
     *
     * @param function $callback : the callback function to invoke, or a null pointer.
     *         The callback function should take five arguments:
     *         the YGyro object of the turning device, and the floating
     *         point values of the four components w, x, y and z
     *         (as floating-point numbers).
     * @noreturn
     */
    public function registerQuaternionCallback($callback)
    {
        $this->_quatCallback = $callback;
        if (!is_null($callback)) {
            if ($this->_loadQuaternion() != YAPI_SUCCESS) {
                return YAPI_DEVICE_NOT_FOUND;
            }
            $this->_qt_w->set_userData($this);
            $this->_qt_x->set_userData($this);
            $this->_qt_y->set_userData($this);
            $this->_qt_z->set_userData($this);
            $this->_qt_w->registerValueCallback("yInternalGyroCallback");
            $this->_qt_x->registerValueCallback("yInternalGyroCallback");
            $this->_qt_y->registerValueCallback("yInternalGyroCallback");
            $this->_qt_z->registerValueCallback("yInternalGyroCallback");
        } else {
            if (!(!is_null($this->_anglesCallback))) {
                $this->_qt_w->registerValueCallback(null);
                $this->_qt_x->registerValueCallback(null);
                $this->_qt_y->registerValueCallback(null);
                $this->_qt_z->registerValueCallback(null);
            }
        }
        return 0;
    }

    /**
     * Registers a callback function that will be invoked each time that the estimated
     * device orientation has changed. The call frequency is typically around 95Hz during a move.
     * The callback is invoked only during the execution of ySleep or yHandleEvents.
     * This provides control over the time when the callback is triggered.
     * For good responsiveness, remember to call one of these two functions periodically.
     * To unregister a callback, pass a null pointer as argument.
     *
     * @param function $callback : the callback function to invoke, or a null pointer.
     *         The callback function should take four arguments:
     *         the YGyro object of the turning device, and the floating
     *         point values of the three angles roll, pitch and heading
     *         in degrees (as floating-point numbers).
     * @noreturn
     */
    public function registerAnglesCallback($callback)
    {
        $this->_anglesCallback = $callback;
        if (!is_null($callback)) {
            if ($this->_loadQuaternion() != YAPI_SUCCESS) {
                return YAPI_DEVICE_NOT_FOUND;
            }
            $this->_qt_w->set_userData($this);
            $this->_qt_x->set_userData($this);
            $this->_qt_y->set_userData($this);
            $this->_qt_z->set_userData($this);
            $this->_qt_w->registerValueCallback("yInternalGyroCallback");
            $this->_qt_x->registerValueCallback("yInternalGyroCallback");
            $this->_qt_y->registerValueCallback("yInternalGyroCallback");
            $this->_qt_z->registerValueCallback("yInternalGyroCallback");
        } else {
            if (!(!is_null($this->_quatCallback))) {
                $this->_qt_w->registerValueCallback(null);
                $this->_qt_x->registerValueCallback(null);
                $this->_qt_y->registerValueCallback(null);
                $this->_qt_z->registerValueCallback(null);
            }
        }
        return 0;
    }

    public function _invokeGyroCallbacks($qtIndex,$qtValue)
    {
        switch($qtIndex - 1) {
        case 0:
            $this->_w = $qtValue;
            break;
        case 1:
            $this->_x = $qtValue;
            break;
        case 2:
            $this->_y = $qtValue;
            break;
        case 3:
            $this->_z = $qtValue;
            break;
        }
        if ($qtIndex < 4) {
            return 0;
        }
        $this->_qt_stamp = ((YAPI::GetTickCount()) & (0x7FFFFFFF));
        if (!is_null($this->_quatCallback)) {
            call_user_func($this->_quatCallback, $this, $this->_w, $this->_x, $this->_y, $this->_z);
        }
        if (!is_null($this->_anglesCallback)) {
            $this->_loadAngles();
            call_user_func($this->_anglesCallback, $this, $this->_roll, $this->_pitch, $this->_head);
        }
        return 0;
    }

    public function bandwidth()
    { return $this->get_bandwidth(); }

    public function setBandwidth($newval)
    { return $this->set_bandwidth($newval); }

    public function xValue()
    { return $this->get_xValue(); }

    public function yValue()
    { return $this->get_yValue(); }

    public function zValue()
    { return $this->get_zValue(); }

    /**
     * Continues the enumeration of gyroscopes started using yFirstGyro().
     * Caution: You can't make any assumption about the returned gyroscopes order.
     * If you want to find a specific a gyroscope, use Gyro.findGyro()
     * and a hardwareID or a logical name.
     *
     * @return YGyro : a pointer to a YGyro object, corresponding to
     *         a gyroscope currently online, or a null pointer
     *         if there are no more gyroscopes to enumerate.
     */
    public function nextGyro()
    {   $resolve = YAPI::resolveFunction($this->_className, $this->_func);
        if($resolve->errorType != YAPI_SUCCESS) return null;
        $next_hwid = YAPI::getNextHardwareId($this->_className, $resolve->result);
        if($next_hwid == null) return null;
        return self::FindGyro($next_hwid);
    }

    /**
     * Starts the enumeration of gyroscopes currently accessible.
     * Use the method YGyro::nextGyro() to iterate on
     * next gyroscopes.
     *
     * @return YGyro : a pointer to a YGyro object, corresponding to
     *         the first gyro currently online, or a null pointer
     *         if there are none.
     */
    public static function FirstGyro()
    {   $next_hwid = YAPI::getFirstHardwareId('Gyro');
        if($next_hwid == null) return null;
        return self::FindGyro($next_hwid);
    }

    //--- (end of generated code: YGyro implementation)

};

//--- (generated code: YGyro functions)

/**
 * Retrieves a gyroscope for a given identifier.
 * The identifier can be specified using several formats:
 * <ul>
 * <li>FunctionLogicalName</li>
 * <li>ModuleSerialNumber.FunctionIdentifier</li>
 * <li>ModuleSerialNumber.FunctionLogicalName</li>
 * <li>ModuleLogicalName.FunctionIdentifier</li>
 * <li>ModuleLogicalName.FunctionLogicalName</li>
 * </ul>
 *
 * This function does not require that the gyroscope is online at the time
 * it is invoked. The returned object is nevertheless valid.
 * Use the method isOnline() to test if the gyroscope is
 * indeed online at a given time. In case of ambiguity when looking for
 * a gyroscope by logical name, no error is notified: the first instance
 * found is returned. The search is performed first by hardware name,
 * then by logical name.
 *
 * If a call to this object's is_online() method returns FALSE although
 * you are certain that the matching device is plugged, make sure that you did
 * call registerHub() at application initialization time.
 *
 * @param string $func : a string that uniquely characterizes the gyroscope, for instance
 *         Y3DMK002.gyro.
 *
 * @return YGyro : a YGyro object allowing you to drive the gyroscope.
 */
function yFindGyro($func)
{
    return YGyro::FindGyro($func);
}

/**
 * Starts the enumeration of gyroscopes currently accessible.
 * Use the method YGyro::nextGyro() to iterate on
 * next gyroscopes.
 *
 * @return YGyro : a pointer to a YGyro object, corresponding to
 *         the first gyro currently online, or a null pointer
 *         if there are none.
 */
function yFirstGyro()
{
    return YGyro::FirstGyro();
}

//--- (end of generated code: YGyro functions)
?>