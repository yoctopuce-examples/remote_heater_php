<?php
/*********************************************************************
 *
 *  $Id: yocto_servo.php 43580 2021-01-26 17:46:01Z mvuilleu $
 *
 *  Implements YServo, the high-level API for Servo functions
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

//--- (YServo return codes)
//--- (end of YServo return codes)
//--- (YServo definitions)
if(!defined('Y_ENABLED_FALSE'))              define('Y_ENABLED_FALSE',             0);
if(!defined('Y_ENABLED_TRUE'))               define('Y_ENABLED_TRUE',              1);
if(!defined('Y_ENABLED_INVALID'))            define('Y_ENABLED_INVALID',           -1);
if(!defined('Y_ENABLEDATPOWERON_FALSE'))     define('Y_ENABLEDATPOWERON_FALSE',    0);
if(!defined('Y_ENABLEDATPOWERON_TRUE'))      define('Y_ENABLEDATPOWERON_TRUE',     1);
if(!defined('Y_ENABLEDATPOWERON_INVALID'))   define('Y_ENABLEDATPOWERON_INVALID',  -1);
if(!defined('Y_POSITION_INVALID'))           define('Y_POSITION_INVALID',          YAPI_INVALID_INT);
if(!defined('Y_RANGE_INVALID'))              define('Y_RANGE_INVALID',             YAPI_INVALID_UINT);
if(!defined('Y_NEUTRAL_INVALID'))            define('Y_NEUTRAL_INVALID',           YAPI_INVALID_UINT);
if(!defined('Y_MOVE_INVALID'))               define('Y_MOVE_INVALID',              null);
if(!defined('Y_POSITIONATPOWERON_INVALID'))  define('Y_POSITIONATPOWERON_INVALID', YAPI_INVALID_INT);
//--- (end of YServo definitions)
    #--- (YServo yapiwrapper)
   #--- (end of YServo yapiwrapper)

//--- (YServo declaration)
/**
 * YServo Class: RC servo motor control interface, available for instance in the Yocto-Servo
 *
 * The YServo class is designed to drive remote-control servo motors
 * outputs. This class allows you not only to move
 * a servo to a given position, but also to specify the time interval
 * in which the move should be performed. This makes it possible to
 * synchronize two servos involved in a same move.
 */
class YServo extends YFunction
{
    const POSITION_INVALID               = YAPI_INVALID_INT;
    const ENABLED_FALSE                  = 0;
    const ENABLED_TRUE                   = 1;
    const ENABLED_INVALID                = -1;
    const RANGE_INVALID                  = YAPI_INVALID_UINT;
    const NEUTRAL_INVALID                = YAPI_INVALID_UINT;
    const MOVE_INVALID                   = null;
    const POSITIONATPOWERON_INVALID      = YAPI_INVALID_INT;
    const ENABLEDATPOWERON_FALSE         = 0;
    const ENABLEDATPOWERON_TRUE          = 1;
    const ENABLEDATPOWERON_INVALID       = -1;
    //--- (end of YServo declaration)

    //--- (YServo attributes)
    protected $_position                 = Y_POSITION_INVALID;           // Int
    protected $_enabled                  = Y_ENABLED_INVALID;            // Bool
    protected $_range                    = Y_RANGE_INVALID;              // Percent
    protected $_neutral                  = Y_NEUTRAL_INVALID;            // MicroSeconds
    protected $_move                     = Y_MOVE_INVALID;               // Move
    protected $_positionAtPowerOn        = Y_POSITIONATPOWERON_INVALID;  // Int
    protected $_enabledAtPowerOn         = Y_ENABLEDATPOWERON_INVALID;   // Bool
    //--- (end of YServo attributes)

    function __construct($str_func)
    {
        //--- (YServo constructor)
        parent::__construct($str_func);
        $this->_className = 'Servo';

        //--- (end of YServo constructor)
    }

    //--- (YServo implementation)

    function _parseAttr($name, $val)
    {
        switch($name) {
        case 'position':
            $this->_position = intval($val);
            return 1;
        case 'enabled':
            $this->_enabled = intval($val);
            return 1;
        case 'range':
            $this->_range = intval($val);
            return 1;
        case 'neutral':
            $this->_neutral = intval($val);
            return 1;
        case 'move':
            $this->_move = $val;
            return 1;
        case 'positionAtPowerOn':
            $this->_positionAtPowerOn = intval($val);
            return 1;
        case 'enabledAtPowerOn':
            $this->_enabledAtPowerOn = intval($val);
            return 1;
        }
        return parent::_parseAttr($name, $val);
    }

    /**
     * Returns the current servo position.
     *
     * @return integer : an integer corresponding to the current servo position
     *
     * On failure, throws an exception or returns YServo::POSITION_INVALID.
     */
    public function get_position()
    {
        // $res                    is a int;
        if ($this->_cacheExpiration <= YAPI::GetTickCount()) {
            if ($this->load(YAPI::$_yapiContext->GetCacheValidity()) != YAPI_SUCCESS) {
                return Y_POSITION_INVALID;
            }
        }
        $res = $this->_position;
        return $res;
    }

    /**
     * Changes immediately the servo driving position.
     *
     * @param integer $newval : an integer corresponding to immediately the servo driving position
     *
     * @return integer : YAPI::SUCCESS if the call succeeds.
     *
     * On failure, throws an exception or returns a negative error code.
     */
    public function set_position($newval)
    {
        $rest_val = strval($newval);
        return $this->_setAttr("position",$rest_val);
    }

    /**
     * Returns the state of the RC servo motors.
     *
     * @return integer : either YServo::ENABLED_FALSE or YServo::ENABLED_TRUE, according to the state of the
     * RC servo motors
     *
     * On failure, throws an exception or returns YServo::ENABLED_INVALID.
     */
    public function get_enabled()
    {
        // $res                    is a enumBOOL;
        if ($this->_cacheExpiration <= YAPI::GetTickCount()) {
            if ($this->load(YAPI::$_yapiContext->GetCacheValidity()) != YAPI_SUCCESS) {
                return Y_ENABLED_INVALID;
            }
        }
        $res = $this->_enabled;
        return $res;
    }

    /**
     * Stops or starts the RC servo motor.
     *
     * @param integer $newval : either YServo::ENABLED_FALSE or YServo::ENABLED_TRUE
     *
     * @return integer : YAPI::SUCCESS if the call succeeds.
     *
     * On failure, throws an exception or returns a negative error code.
     */
    public function set_enabled($newval)
    {
        $rest_val = strval($newval);
        return $this->_setAttr("enabled",$rest_val);
    }

    /**
     * Returns the current range of use of the servo.
     *
     * @return integer : an integer corresponding to the current range of use of the servo
     *
     * On failure, throws an exception or returns YServo::RANGE_INVALID.
     */
    public function get_range()
    {
        // $res                    is a int;
        if ($this->_cacheExpiration <= YAPI::GetTickCount()) {
            if ($this->load(YAPI::$_yapiContext->GetCacheValidity()) != YAPI_SUCCESS) {
                return Y_RANGE_INVALID;
            }
        }
        $res = $this->_range;
        return $res;
    }

    /**
     * Changes the range of use of the servo, specified in per cents.
     * A range of 100% corresponds to a standard control signal, that varies
     * from 1 [ms] to 2 [ms], When using a servo that supports a double range,
     * from 0.5 [ms] to 2.5 [ms], you can select a range of 200%.
     * Be aware that using a range higher than what is supported by the servo
     * is likely to damage the servo. Remember to call the matching module
     * saveToFlash() method, otherwise this call will have no effect.
     *
     * @param integer $newval : an integer corresponding to the range of use of the servo, specified in per cents
     *
     * @return integer : YAPI::SUCCESS if the call succeeds.
     *
     * On failure, throws an exception or returns a negative error code.
     */
    public function set_range($newval)
    {
        $rest_val = strval($newval);
        return $this->_setAttr("range",$rest_val);
    }

    /**
     * Returns the duration in microseconds of a neutral pulse for the servo.
     *
     * @return integer : an integer corresponding to the duration in microseconds of a neutral pulse for the servo
     *
     * On failure, throws an exception or returns YServo::NEUTRAL_INVALID.
     */
    public function get_neutral()
    {
        // $res                    is a int;
        if ($this->_cacheExpiration <= YAPI::GetTickCount()) {
            if ($this->load(YAPI::$_yapiContext->GetCacheValidity()) != YAPI_SUCCESS) {
                return Y_NEUTRAL_INVALID;
            }
        }
        $res = $this->_neutral;
        return $res;
    }

    /**
     * Changes the duration of the pulse corresponding to the neutral position of the servo.
     * The duration is specified in microseconds, and the standard value is 1500 [us].
     * This setting makes it possible to shift the range of use of the servo.
     * Be aware that using a range higher than what is supported by the servo is
     * likely to damage the servo. Remember to call the matching module
     * saveToFlash() method, otherwise this call will have no effect.
     *
     * @param integer $newval : an integer corresponding to the duration of the pulse corresponding to the
     * neutral position of the servo
     *
     * @return integer : YAPI::SUCCESS if the call succeeds.
     *
     * On failure, throws an exception or returns a negative error code.
     */
    public function set_neutral($newval)
    {
        $rest_val = strval($newval);
        return $this->_setAttr("neutral",$rest_val);
    }

    public function get_move()
    {
        // $res                    is a YMove;
        if ($this->_cacheExpiration <= YAPI::GetTickCount()) {
            if ($this->load(YAPI::$_yapiContext->GetCacheValidity()) != YAPI_SUCCESS) {
                return Y_MOVE_INVALID;
            }
        }
        $res = $this->_move;
        return $res;
    }

    public function set_move($newval)
    {
        $rest_val = strval($newval["target"]).':'.strval($newval["ms"]);
        return $this->_setAttr("move",$rest_val);
    }

    /**
     * Performs a smooth move at constant speed toward a given position.
     *
     * @param target      : new position at the end of the move
     * @param integer $ms_duration : total duration of the move, in milliseconds
     *
     * @return integer : YAPI::SUCCESS if the call succeeds.
     *
     * On failure, throws an exception or returns a negative error code.
     */
    public function move($target,$ms_duration)
    {
        $rest_val = strval($target).':'.strval($ms_duration);
        return $this->_setAttr("move",$rest_val);
    }

    /**
     * Returns the servo position at device power up.
     *
     * @return integer : an integer corresponding to the servo position at device power up
     *
     * On failure, throws an exception or returns YServo::POSITIONATPOWERON_INVALID.
     */
    public function get_positionAtPowerOn()
    {
        // $res                    is a int;
        if ($this->_cacheExpiration <= YAPI::GetTickCount()) {
            if ($this->load(YAPI::$_yapiContext->GetCacheValidity()) != YAPI_SUCCESS) {
                return Y_POSITIONATPOWERON_INVALID;
            }
        }
        $res = $this->_positionAtPowerOn;
        return $res;
    }

    /**
     * Configure the servo position at device power up. Remember to call the matching
     * module saveToFlash() method, otherwise this call will have no effect.
     *
     * @param integer $newval : an integer
     *
     * @return integer : YAPI::SUCCESS if the call succeeds.
     *
     * On failure, throws an exception or returns a negative error code.
     */
    public function set_positionAtPowerOn($newval)
    {
        $rest_val = strval($newval);
        return $this->_setAttr("positionAtPowerOn",$rest_val);
    }

    /**
     * Returns the servo signal generator state at power up.
     *
     * @return integer : either YServo::ENABLEDATPOWERON_FALSE or YServo::ENABLEDATPOWERON_TRUE, according
     * to the servo signal generator state at power up
     *
     * On failure, throws an exception or returns YServo::ENABLEDATPOWERON_INVALID.
     */
    public function get_enabledAtPowerOn()
    {
        // $res                    is a enumBOOL;
        if ($this->_cacheExpiration <= YAPI::GetTickCount()) {
            if ($this->load(YAPI::$_yapiContext->GetCacheValidity()) != YAPI_SUCCESS) {
                return Y_ENABLEDATPOWERON_INVALID;
            }
        }
        $res = $this->_enabledAtPowerOn;
        return $res;
    }

    /**
     * Configure the servo signal generator state at power up. Remember to call the matching module saveToFlash()
     * method, otherwise this call will have no effect.
     *
     * @param integer $newval : either YServo::ENABLEDATPOWERON_FALSE or YServo::ENABLEDATPOWERON_TRUE
     *
     * @return integer : YAPI::SUCCESS if the call succeeds.
     *
     * On failure, throws an exception or returns a negative error code.
     */
    public function set_enabledAtPowerOn($newval)
    {
        $rest_val = strval($newval);
        return $this->_setAttr("enabledAtPowerOn",$rest_val);
    }

    /**
     * Retrieves a RC servo motor for a given identifier.
     * The identifier can be specified using several formats:
     * <ul>
     * <li>FunctionLogicalName</li>
     * <li>ModuleSerialNumber.FunctionIdentifier</li>
     * <li>ModuleSerialNumber.FunctionLogicalName</li>
     * <li>ModuleLogicalName.FunctionIdentifier</li>
     * <li>ModuleLogicalName.FunctionLogicalName</li>
     * </ul>
     *
     * This function does not require that the RC servo motor is online at the time
     * it is invoked. The returned object is nevertheless valid.
     * Use the method isOnline() to test if the RC servo motor is
     * indeed online at a given time. In case of ambiguity when looking for
     * a RC servo motor by logical name, no error is notified: the first instance
     * found is returned. The search is performed first by hardware name,
     * then by logical name.
     *
     * If a call to this object's is_online() method returns FALSE although
     * you are certain that the matching device is plugged, make sure that you did
     * call registerHub() at application initialization time.
     *
     * @param string $func : a string that uniquely characterizes the RC servo motor, for instance
     *         SERVORC1.servo1.
     *
     * @return YServo : a YServo object allowing you to drive the RC servo motor.
     */
    public static function FindServo($func)
    {
        // $obj                    is a YServo;
        $obj = YFunction::_FindFromCache('Servo', $func);
        if ($obj == null) {
            $obj = new YServo($func);
            YFunction::_AddToCache('Servo', $func, $obj);
        }
        return $obj;
    }

    public function position()
    { return $this->get_position(); }

    public function setPosition($newval)
    { return $this->set_position($newval); }

    public function enabled()
    { return $this->get_enabled(); }

    public function setEnabled($newval)
    { return $this->set_enabled($newval); }

    public function range()
    { return $this->get_range(); }

    public function setRange($newval)
    { return $this->set_range($newval); }

    public function neutral()
    { return $this->get_neutral(); }

    public function setNeutral($newval)
    { return $this->set_neutral($newval); }

    public function setMove($newval)
    { return $this->set_move($newval); }

    public function positionAtPowerOn()
    { return $this->get_positionAtPowerOn(); }

    public function setPositionAtPowerOn($newval)
    { return $this->set_positionAtPowerOn($newval); }

    public function enabledAtPowerOn()
    { return $this->get_enabledAtPowerOn(); }

    public function setEnabledAtPowerOn($newval)
    { return $this->set_enabledAtPowerOn($newval); }

    /**
     * Continues the enumeration of RC servo motors started using yFirstServo().
     * Caution: You can't make any assumption about the returned RC servo motors order.
     * If you want to find a specific a RC servo motor, use Servo.findServo()
     * and a hardwareID or a logical name.
     *
     * @return YServo : a pointer to a YServo object, corresponding to
     *         a RC servo motor currently online, or a null pointer
     *         if there are no more RC servo motors to enumerate.
     */
    public function nextServo()
    {   $resolve = YAPI::resolveFunction($this->_className, $this->_func);
        if($resolve->errorType != YAPI_SUCCESS) return null;
        $next_hwid = YAPI::getNextHardwareId($this->_className, $resolve->result);
        if($next_hwid == null) return null;
        return self::FindServo($next_hwid);
    }

    /**
     * Starts the enumeration of RC servo motors currently accessible.
     * Use the method YServo::nextServo() to iterate on
     * next RC servo motors.
     *
     * @return YServo : a pointer to a YServo object, corresponding to
     *         the first RC servo motor currently online, or a null pointer
     *         if there are none.
     */
    public static function FirstServo()
    {   $next_hwid = YAPI::getFirstHardwareId('Servo');
        if($next_hwid == null) return null;
        return self::FindServo($next_hwid);
    }

    //--- (end of YServo implementation)

};

//--- (YServo functions)

/**
 * Retrieves a RC servo motor for a given identifier.
 * The identifier can be specified using several formats:
 * <ul>
 * <li>FunctionLogicalName</li>
 * <li>ModuleSerialNumber.FunctionIdentifier</li>
 * <li>ModuleSerialNumber.FunctionLogicalName</li>
 * <li>ModuleLogicalName.FunctionIdentifier</li>
 * <li>ModuleLogicalName.FunctionLogicalName</li>
 * </ul>
 *
 * This function does not require that the RC servo motor is online at the time
 * it is invoked. The returned object is nevertheless valid.
 * Use the method isOnline() to test if the RC servo motor is
 * indeed online at a given time. In case of ambiguity when looking for
 * a RC servo motor by logical name, no error is notified: the first instance
 * found is returned. The search is performed first by hardware name,
 * then by logical name.
 *
 * If a call to this object's is_online() method returns FALSE although
 * you are certain that the matching device is plugged, make sure that you did
 * call registerHub() at application initialization time.
 *
 * @param string $func : a string that uniquely characterizes the RC servo motor, for instance
 *         SERVORC1.servo1.
 *
 * @return YServo : a YServo object allowing you to drive the RC servo motor.
 */
function yFindServo($func)
{
    return YServo::FindServo($func);
}

/**
 * Starts the enumeration of RC servo motors currently accessible.
 * Use the method YServo::nextServo() to iterate on
 * next RC servo motors.
 *
 * @return YServo : a pointer to a YServo object, corresponding to
 *         the first RC servo motor currently online, or a null pointer
 *         if there are none.
 */
function yFirstServo()
{
    return YServo::FirstServo();
}

//--- (end of YServo functions)
?>