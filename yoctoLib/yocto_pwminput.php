<?php
/*********************************************************************
 *
 *  $Id: yocto_pwminput.php 43580 2021-01-26 17:46:01Z mvuilleu $
 *
 *  Implements YPwmInput, the high-level API for PwmInput functions
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

//--- (YPwmInput return codes)
//--- (end of YPwmInput return codes)
//--- (YPwmInput definitions)
if(!defined('Y_PWMREPORTMODE_PWM_DUTYCYCLE')) define('Y_PWMREPORTMODE_PWM_DUTYCYCLE', 0);
if(!defined('Y_PWMREPORTMODE_PWM_FREQUENCY')) define('Y_PWMREPORTMODE_PWM_FREQUENCY', 1);
if(!defined('Y_PWMREPORTMODE_PWM_PULSEDURATION')) define('Y_PWMREPORTMODE_PWM_PULSEDURATION', 2);
if(!defined('Y_PWMREPORTMODE_PWM_EDGECOUNT')) define('Y_PWMREPORTMODE_PWM_EDGECOUNT', 3);
if(!defined('Y_PWMREPORTMODE_PWM_PULSECOUNT')) define('Y_PWMREPORTMODE_PWM_PULSECOUNT', 4);
if(!defined('Y_PWMREPORTMODE_PWM_CPS'))      define('Y_PWMREPORTMODE_PWM_CPS',     5);
if(!defined('Y_PWMREPORTMODE_PWM_CPM'))      define('Y_PWMREPORTMODE_PWM_CPM',     6);
if(!defined('Y_PWMREPORTMODE_PWM_STATE'))    define('Y_PWMREPORTMODE_PWM_STATE',   7);
if(!defined('Y_PWMREPORTMODE_PWM_FREQ_CPS')) define('Y_PWMREPORTMODE_PWM_FREQ_CPS', 8);
if(!defined('Y_PWMREPORTMODE_PWM_FREQ_CPM')) define('Y_PWMREPORTMODE_PWM_FREQ_CPM', 9);
if(!defined('Y_PWMREPORTMODE_PWM_PERIODCOUNT')) define('Y_PWMREPORTMODE_PWM_PERIODCOUNT', 10);
if(!defined('Y_PWMREPORTMODE_INVALID'))      define('Y_PWMREPORTMODE_INVALID',     -1);
if(!defined('Y_DUTYCYCLE_INVALID'))          define('Y_DUTYCYCLE_INVALID',         YAPI_INVALID_DOUBLE);
if(!defined('Y_PULSEDURATION_INVALID'))      define('Y_PULSEDURATION_INVALID',     YAPI_INVALID_DOUBLE);
if(!defined('Y_FREQUENCY_INVALID'))          define('Y_FREQUENCY_INVALID',         YAPI_INVALID_DOUBLE);
if(!defined('Y_PERIOD_INVALID'))             define('Y_PERIOD_INVALID',            YAPI_INVALID_DOUBLE);
if(!defined('Y_PULSECOUNTER_INVALID'))       define('Y_PULSECOUNTER_INVALID',      YAPI_INVALID_LONG);
if(!defined('Y_PULSETIMER_INVALID'))         define('Y_PULSETIMER_INVALID',        YAPI_INVALID_LONG);
if(!defined('Y_DEBOUNCEPERIOD_INVALID'))     define('Y_DEBOUNCEPERIOD_INVALID',    YAPI_INVALID_UINT);
if(!defined('Y_BANDWIDTH_INVALID'))          define('Y_BANDWIDTH_INVALID',         YAPI_INVALID_UINT);
if(!defined('Y_EDGESPERPERIOD_INVALID'))     define('Y_EDGESPERPERIOD_INVALID',    YAPI_INVALID_UINT);
//--- (end of YPwmInput definitions)
    #--- (YPwmInput yapiwrapper)
   #--- (end of YPwmInput yapiwrapper)

//--- (YPwmInput declaration)
/**
 * YPwmInput Class: PWM input control interface, available for instance in the Yocto-PWM-Rx
 *
 * The YPwmInput class allows you to read and configure Yoctopuce PWM inputs.
 * It inherits from YSensor class the core functions to read measurements,
 * to register callback functions, and to access the autonomous datalogger.
 * This class adds the ability to configure the signal parameter used to transmit
 * information: the duty cycle, the frequency or the pulse width.
 */
class YPwmInput extends YSensor
{
    const DUTYCYCLE_INVALID              = YAPI_INVALID_DOUBLE;
    const PULSEDURATION_INVALID          = YAPI_INVALID_DOUBLE;
    const FREQUENCY_INVALID              = YAPI_INVALID_DOUBLE;
    const PERIOD_INVALID                 = YAPI_INVALID_DOUBLE;
    const PULSECOUNTER_INVALID           = YAPI_INVALID_LONG;
    const PULSETIMER_INVALID             = YAPI_INVALID_LONG;
    const PWMREPORTMODE_PWM_DUTYCYCLE    = 0;
    const PWMREPORTMODE_PWM_FREQUENCY    = 1;
    const PWMREPORTMODE_PWM_PULSEDURATION = 2;
    const PWMREPORTMODE_PWM_EDGECOUNT    = 3;
    const PWMREPORTMODE_PWM_PULSECOUNT   = 4;
    const PWMREPORTMODE_PWM_CPS          = 5;
    const PWMREPORTMODE_PWM_CPM          = 6;
    const PWMREPORTMODE_PWM_STATE        = 7;
    const PWMREPORTMODE_PWM_FREQ_CPS     = 8;
    const PWMREPORTMODE_PWM_FREQ_CPM     = 9;
    const PWMREPORTMODE_PWM_PERIODCOUNT  = 10;
    const PWMREPORTMODE_INVALID          = -1;
    const DEBOUNCEPERIOD_INVALID         = YAPI_INVALID_UINT;
    const BANDWIDTH_INVALID              = YAPI_INVALID_UINT;
    const EDGESPERPERIOD_INVALID         = YAPI_INVALID_UINT;
    //--- (end of YPwmInput declaration)

    //--- (YPwmInput attributes)
    protected $_dutyCycle                = Y_DUTYCYCLE_INVALID;          // MeasureVal
    protected $_pulseDuration            = Y_PULSEDURATION_INVALID;      // MeasureVal
    protected $_frequency                = Y_FREQUENCY_INVALID;          // MeasureVal
    protected $_period                   = Y_PERIOD_INVALID;             // MeasureVal
    protected $_pulseCounter             = Y_PULSECOUNTER_INVALID;       // UInt
    protected $_pulseTimer               = Y_PULSETIMER_INVALID;         // Time
    protected $_pwmReportMode            = Y_PWMREPORTMODE_INVALID;      // PwmReportModeType
    protected $_debouncePeriod           = Y_DEBOUNCEPERIOD_INVALID;     // UInt31
    protected $_bandwidth                = Y_BANDWIDTH_INVALID;          // UInt31
    protected $_edgesPerPeriod           = Y_EDGESPERPERIOD_INVALID;     // UInt31
    //--- (end of YPwmInput attributes)

    function __construct($str_func)
    {
        //--- (YPwmInput constructor)
        parent::__construct($str_func);
        $this->_className = 'PwmInput';

        //--- (end of YPwmInput constructor)
    }

    //--- (YPwmInput implementation)

    function _parseAttr($name, $val)
    {
        switch($name) {
        case 'dutyCycle':
            $this->_dutyCycle = round($val * 1000.0 / 65536.0) / 1000.0;
            return 1;
        case 'pulseDuration':
            $this->_pulseDuration = round($val * 1000.0 / 65536.0) / 1000.0;
            return 1;
        case 'frequency':
            $this->_frequency = round($val * 1000.0 / 65536.0) / 1000.0;
            return 1;
        case 'period':
            $this->_period = round($val * 1000.0 / 65536.0) / 1000.0;
            return 1;
        case 'pulseCounter':
            $this->_pulseCounter = intval($val);
            return 1;
        case 'pulseTimer':
            $this->_pulseTimer = intval($val);
            return 1;
        case 'pwmReportMode':
            $this->_pwmReportMode = intval($val);
            return 1;
        case 'debouncePeriod':
            $this->_debouncePeriod = intval($val);
            return 1;
        case 'bandwidth':
            $this->_bandwidth = intval($val);
            return 1;
        case 'edgesPerPeriod':
            $this->_edgesPerPeriod = intval($val);
            return 1;
        }
        return parent::_parseAttr($name, $val);
    }

    /**
     * Changes the measuring unit for the measured quantity. That unit
     * is just a string which is automatically initialized each time
     * the measurement mode is changed. But is can be set to an
     * arbitrary value.
     * Remember to call the saveToFlash() method of the module if the modification must be kept.
     *
     * @param string $newval : a string corresponding to the measuring unit for the measured quantity
     *
     * @return integer : YAPI::SUCCESS if the call succeeds.
     *
     * On failure, throws an exception or returns a negative error code.
     */
    public function set_unit($newval)
    {
        $rest_val = $newval;
        return $this->_setAttr("unit",$rest_val);
    }

    /**
     * Returns the PWM duty cycle, in per cents.
     *
     * @return double : a floating point number corresponding to the PWM duty cycle, in per cents
     *
     * On failure, throws an exception or returns YPwmInput::DUTYCYCLE_INVALID.
     */
    public function get_dutyCycle()
    {
        // $res                    is a double;
        if ($this->_cacheExpiration <= YAPI::GetTickCount()) {
            if ($this->load(YAPI::$_yapiContext->GetCacheValidity()) != YAPI_SUCCESS) {
                return Y_DUTYCYCLE_INVALID;
            }
        }
        $res = $this->_dutyCycle;
        return $res;
    }

    /**
     * Returns the PWM pulse length in milliseconds, as a floating point number.
     *
     * @return double : a floating point number corresponding to the PWM pulse length in milliseconds, as
     * a floating point number
     *
     * On failure, throws an exception or returns YPwmInput::PULSEDURATION_INVALID.
     */
    public function get_pulseDuration()
    {
        // $res                    is a double;
        if ($this->_cacheExpiration <= YAPI::GetTickCount()) {
            if ($this->load(YAPI::$_yapiContext->GetCacheValidity()) != YAPI_SUCCESS) {
                return Y_PULSEDURATION_INVALID;
            }
        }
        $res = $this->_pulseDuration;
        return $res;
    }

    /**
     * Returns the PWM frequency in Hz.
     *
     * @return double : a floating point number corresponding to the PWM frequency in Hz
     *
     * On failure, throws an exception or returns YPwmInput::FREQUENCY_INVALID.
     */
    public function get_frequency()
    {
        // $res                    is a double;
        if ($this->_cacheExpiration <= YAPI::GetTickCount()) {
            if ($this->load(YAPI::$_yapiContext->GetCacheValidity()) != YAPI_SUCCESS) {
                return Y_FREQUENCY_INVALID;
            }
        }
        $res = $this->_frequency;
        return $res;
    }

    /**
     * Returns the PWM period in milliseconds.
     *
     * @return double : a floating point number corresponding to the PWM period in milliseconds
     *
     * On failure, throws an exception or returns YPwmInput::PERIOD_INVALID.
     */
    public function get_period()
    {
        // $res                    is a double;
        if ($this->_cacheExpiration <= YAPI::GetTickCount()) {
            if ($this->load(YAPI::$_yapiContext->GetCacheValidity()) != YAPI_SUCCESS) {
                return Y_PERIOD_INVALID;
            }
        }
        $res = $this->_period;
        return $res;
    }

    /**
     * Returns the pulse counter value. Actually that
     * counter is incremented twice per period. That counter is
     * limited  to 1 billion.
     *
     * @return integer : an integer corresponding to the pulse counter value
     *
     * On failure, throws an exception or returns YPwmInput::PULSECOUNTER_INVALID.
     */
    public function get_pulseCounter()
    {
        // $res                    is a long;
        if ($this->_cacheExpiration <= YAPI::GetTickCount()) {
            if ($this->load(YAPI::$_yapiContext->GetCacheValidity()) != YAPI_SUCCESS) {
                return Y_PULSECOUNTER_INVALID;
            }
        }
        $res = $this->_pulseCounter;
        return $res;
    }

    public function set_pulseCounter($newval)
    {
        $rest_val = strval($newval);
        return $this->_setAttr("pulseCounter",$rest_val);
    }

    /**
     * Returns the timer of the pulses counter (ms).
     *
     * @return integer : an integer corresponding to the timer of the pulses counter (ms)
     *
     * On failure, throws an exception or returns YPwmInput::PULSETIMER_INVALID.
     */
    public function get_pulseTimer()
    {
        // $res                    is a long;
        if ($this->_cacheExpiration <= YAPI::GetTickCount()) {
            if ($this->load(YAPI::$_yapiContext->GetCacheValidity()) != YAPI_SUCCESS) {
                return Y_PULSETIMER_INVALID;
            }
        }
        $res = $this->_pulseTimer;
        return $res;
    }

    /**
     * Returns the parameter (frequency/duty cycle, pulse width, edges count) returned by the
     * get_currentValue function and callbacks. Attention
     *
     * @return integer : a value among YPwmInput::PWMREPORTMODE_PWM_DUTYCYCLE,
     * YPwmInput::PWMREPORTMODE_PWM_FREQUENCY, YPwmInput::PWMREPORTMODE_PWM_PULSEDURATION,
     * YPwmInput::PWMREPORTMODE_PWM_EDGECOUNT, YPwmInput::PWMREPORTMODE_PWM_PULSECOUNT,
     * YPwmInput::PWMREPORTMODE_PWM_CPS, YPwmInput::PWMREPORTMODE_PWM_CPM,
     * YPwmInput::PWMREPORTMODE_PWM_STATE, YPwmInput::PWMREPORTMODE_PWM_FREQ_CPS,
     * YPwmInput::PWMREPORTMODE_PWM_FREQ_CPM and YPwmInput::PWMREPORTMODE_PWM_PERIODCOUNT corresponding to
     * the parameter (frequency/duty cycle, pulse width, edges count) returned by the get_currentValue
     * function and callbacks
     *
     * On failure, throws an exception or returns YPwmInput::PWMREPORTMODE_INVALID.
     */
    public function get_pwmReportMode()
    {
        // $res                    is a enumPWMREPORTMODETYPE;
        if ($this->_cacheExpiration <= YAPI::GetTickCount()) {
            if ($this->load(YAPI::$_yapiContext->GetCacheValidity()) != YAPI_SUCCESS) {
                return Y_PWMREPORTMODE_INVALID;
            }
        }
        $res = $this->_pwmReportMode;
        return $res;
    }

    /**
     * Changes the  parameter  type (frequency/duty cycle, pulse width, or edge count) returned by the
     * get_currentValue function and callbacks.
     * The edge count value is limited to the 6 lowest digits. For values greater than one million, use
     * get_pulseCounter().
     * Remember to call the saveToFlash() method of the module if the modification must be kept.
     *
     * @param integer $newval : a value among YPwmInput::PWMREPORTMODE_PWM_DUTYCYCLE,
     * YPwmInput::PWMREPORTMODE_PWM_FREQUENCY, YPwmInput::PWMREPORTMODE_PWM_PULSEDURATION,
     * YPwmInput::PWMREPORTMODE_PWM_EDGECOUNT, YPwmInput::PWMREPORTMODE_PWM_PULSECOUNT,
     * YPwmInput::PWMREPORTMODE_PWM_CPS, YPwmInput::PWMREPORTMODE_PWM_CPM,
     * YPwmInput::PWMREPORTMODE_PWM_STATE, YPwmInput::PWMREPORTMODE_PWM_FREQ_CPS,
     * YPwmInput::PWMREPORTMODE_PWM_FREQ_CPM and YPwmInput::PWMREPORTMODE_PWM_PERIODCOUNT corresponding to
     * the  parameter  type (frequency/duty cycle, pulse width, or edge count) returned by the
     * get_currentValue function and callbacks
     *
     * @return integer : YAPI::SUCCESS if the call succeeds.
     *
     * On failure, throws an exception or returns a negative error code.
     */
    public function set_pwmReportMode($newval)
    {
        $rest_val = strval($newval);
        return $this->_setAttr("pwmReportMode",$rest_val);
    }

    /**
     * Returns the shortest expected pulse duration, in ms. Any shorter pulse will be automatically ignored (debounce).
     *
     * @return integer : an integer corresponding to the shortest expected pulse duration, in ms
     *
     * On failure, throws an exception or returns YPwmInput::DEBOUNCEPERIOD_INVALID.
     */
    public function get_debouncePeriod()
    {
        // $res                    is a int;
        if ($this->_cacheExpiration <= YAPI::GetTickCount()) {
            if ($this->load(YAPI::$_yapiContext->GetCacheValidity()) != YAPI_SUCCESS) {
                return Y_DEBOUNCEPERIOD_INVALID;
            }
        }
        $res = $this->_debouncePeriod;
        return $res;
    }

    /**
     * Changes the shortest expected pulse duration, in ms. Any shorter pulse will be automatically ignored (debounce).
     * Remember to call the saveToFlash() method of the module if the modification must be kept.
     *
     * @param integer $newval : an integer corresponding to the shortest expected pulse duration, in ms
     *
     * @return integer : YAPI::SUCCESS if the call succeeds.
     *
     * On failure, throws an exception or returns a negative error code.
     */
    public function set_debouncePeriod($newval)
    {
        $rest_val = strval($newval);
        return $this->_setAttr("debouncePeriod",$rest_val);
    }

    /**
     * Returns the input signal sampling rate, in kHz.
     *
     * @return integer : an integer corresponding to the input signal sampling rate, in kHz
     *
     * On failure, throws an exception or returns YPwmInput::BANDWIDTH_INVALID.
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
     * Changes the input signal sampling rate, measured in kHz.
     * A lower sampling frequency can be used to hide hide-frequency bounce effects,
     * for instance on electromechanical contacts, but limits the measure resolution.
     * Remember to call the saveToFlash()
     * method of the module if the modification must be kept.
     *
     * @param integer $newval : an integer corresponding to the input signal sampling rate, measured in kHz
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
     * Returns the number of edges detected per preiod. For a clean PWM signal, this should be exactly two,
     * but in cas the signal is created by a mechanical contact with bounces, it can get higher.
     *
     * @return integer : an integer corresponding to the number of edges detected per preiod
     *
     * On failure, throws an exception or returns YPwmInput::EDGESPERPERIOD_INVALID.
     */
    public function get_edgesPerPeriod()
    {
        // $res                    is a int;
        if ($this->_cacheExpiration <= YAPI::GetTickCount()) {
            if ($this->load(YAPI::$_yapiContext->GetCacheValidity()) != YAPI_SUCCESS) {
                return Y_EDGESPERPERIOD_INVALID;
            }
        }
        $res = $this->_edgesPerPeriod;
        return $res;
    }

    /**
     * Retrieves a PWM input for a given identifier.
     * The identifier can be specified using several formats:
     * <ul>
     * <li>FunctionLogicalName</li>
     * <li>ModuleSerialNumber.FunctionIdentifier</li>
     * <li>ModuleSerialNumber.FunctionLogicalName</li>
     * <li>ModuleLogicalName.FunctionIdentifier</li>
     * <li>ModuleLogicalName.FunctionLogicalName</li>
     * </ul>
     *
     * This function does not require that the PWM input is online at the time
     * it is invoked. The returned object is nevertheless valid.
     * Use the method isOnline() to test if the PWM input is
     * indeed online at a given time. In case of ambiguity when looking for
     * a PWM input by logical name, no error is notified: the first instance
     * found is returned. The search is performed first by hardware name,
     * then by logical name.
     *
     * If a call to this object's is_online() method returns FALSE although
     * you are certain that the matching device is plugged, make sure that you did
     * call registerHub() at application initialization time.
     *
     * @param string $func : a string that uniquely characterizes the PWM input, for instance
     *         YPWMRX01.pwmInput1.
     *
     * @return YPwmInput : a YPwmInput object allowing you to drive the PWM input.
     */
    public static function FindPwmInput($func)
    {
        // $obj                    is a YPwmInput;
        $obj = YFunction::_FindFromCache('PwmInput', $func);
        if ($obj == null) {
            $obj = new YPwmInput($func);
            YFunction::_AddToCache('PwmInput', $func, $obj);
        }
        return $obj;
    }

    /**
     * Returns the pulse counter value as well as its timer.
     *
     * @return integer : YAPI::SUCCESS if the call succeeds.
     *
     * On failure, throws an exception or returns a negative error code.
     */
    public function resetCounter()
    {
        return $this->set_pulseCounter(0);
    }

    public function setUnit($newval)
    { return $this->set_unit($newval); }

    public function dutyCycle()
    { return $this->get_dutyCycle(); }

    public function pulseDuration()
    { return $this->get_pulseDuration(); }

    public function frequency()
    { return $this->get_frequency(); }

    public function period()
    { return $this->get_period(); }

    public function pulseCounter()
    { return $this->get_pulseCounter(); }

    public function setPulseCounter($newval)
    { return $this->set_pulseCounter($newval); }

    public function pulseTimer()
    { return $this->get_pulseTimer(); }

    public function pwmReportMode()
    { return $this->get_pwmReportMode(); }

    public function setPwmReportMode($newval)
    { return $this->set_pwmReportMode($newval); }

    public function debouncePeriod()
    { return $this->get_debouncePeriod(); }

    public function setDebouncePeriod($newval)
    { return $this->set_debouncePeriod($newval); }

    public function bandwidth()
    { return $this->get_bandwidth(); }

    public function setBandwidth($newval)
    { return $this->set_bandwidth($newval); }

    public function edgesPerPeriod()
    { return $this->get_edgesPerPeriod(); }

    /**
     * Continues the enumeration of PWM inputs started using yFirstPwmInput().
     * Caution: You can't make any assumption about the returned PWM inputs order.
     * If you want to find a specific a PWM input, use PwmInput.findPwmInput()
     * and a hardwareID or a logical name.
     *
     * @return YPwmInput : a pointer to a YPwmInput object, corresponding to
     *         a PWM input currently online, or a null pointer
     *         if there are no more PWM inputs to enumerate.
     */
    public function nextPwmInput()
    {   $resolve = YAPI::resolveFunction($this->_className, $this->_func);
        if($resolve->errorType != YAPI_SUCCESS) return null;
        $next_hwid = YAPI::getNextHardwareId($this->_className, $resolve->result);
        if($next_hwid == null) return null;
        return self::FindPwmInput($next_hwid);
    }

    /**
     * Starts the enumeration of PWM inputs currently accessible.
     * Use the method YPwmInput::nextPwmInput() to iterate on
     * next PWM inputs.
     *
     * @return YPwmInput : a pointer to a YPwmInput object, corresponding to
     *         the first PWM input currently online, or a null pointer
     *         if there are none.
     */
    public static function FirstPwmInput()
    {   $next_hwid = YAPI::getFirstHardwareId('PwmInput');
        if($next_hwid == null) return null;
        return self::FindPwmInput($next_hwid);
    }

    //--- (end of YPwmInput implementation)

};

//--- (YPwmInput functions)

/**
 * Retrieves a PWM input for a given identifier.
 * The identifier can be specified using several formats:
 * <ul>
 * <li>FunctionLogicalName</li>
 * <li>ModuleSerialNumber.FunctionIdentifier</li>
 * <li>ModuleSerialNumber.FunctionLogicalName</li>
 * <li>ModuleLogicalName.FunctionIdentifier</li>
 * <li>ModuleLogicalName.FunctionLogicalName</li>
 * </ul>
 *
 * This function does not require that the PWM input is online at the time
 * it is invoked. The returned object is nevertheless valid.
 * Use the method isOnline() to test if the PWM input is
 * indeed online at a given time. In case of ambiguity when looking for
 * a PWM input by logical name, no error is notified: the first instance
 * found is returned. The search is performed first by hardware name,
 * then by logical name.
 *
 * If a call to this object's is_online() method returns FALSE although
 * you are certain that the matching device is plugged, make sure that you did
 * call registerHub() at application initialization time.
 *
 * @param string $func : a string that uniquely characterizes the PWM input, for instance
 *         YPWMRX01.pwmInput1.
 *
 * @return YPwmInput : a YPwmInput object allowing you to drive the PWM input.
 */
function yFindPwmInput($func)
{
    return YPwmInput::FindPwmInput($func);
}

/**
 * Starts the enumeration of PWM inputs currently accessible.
 * Use the method YPwmInput::nextPwmInput() to iterate on
 * next PWM inputs.
 *
 * @return YPwmInput : a pointer to a YPwmInput object, corresponding to
 *         the first PWM input currently online, or a null pointer
 *         if there are none.
 */
function yFirstPwmInput()
{
    return YPwmInput::FirstPwmInput();
}

//--- (end of YPwmInput functions)
?>