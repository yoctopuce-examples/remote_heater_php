<?php
/*********************************************************************
 *
 *  $Id: yocto_relay.php 43580 2021-01-26 17:46:01Z mvuilleu $
 *
 *  Implements YRelay, the high-level API for Relay functions
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

//--- (YRelay return codes)
//--- (end of YRelay return codes)
//--- (YRelay definitions)
if(!defined('Y_STATE_A'))                    define('Y_STATE_A',                   0);
if(!defined('Y_STATE_B'))                    define('Y_STATE_B',                   1);
if(!defined('Y_STATE_INVALID'))              define('Y_STATE_INVALID',             -1);
if(!defined('Y_STATEATPOWERON_UNCHANGED'))   define('Y_STATEATPOWERON_UNCHANGED',  0);
if(!defined('Y_STATEATPOWERON_A'))           define('Y_STATEATPOWERON_A',          1);
if(!defined('Y_STATEATPOWERON_B'))           define('Y_STATEATPOWERON_B',          2);
if(!defined('Y_STATEATPOWERON_INVALID'))     define('Y_STATEATPOWERON_INVALID',    -1);
if(!defined('Y_OUTPUT_OFF'))                 define('Y_OUTPUT_OFF',                0);
if(!defined('Y_OUTPUT_ON'))                  define('Y_OUTPUT_ON',                 1);
if(!defined('Y_OUTPUT_INVALID'))             define('Y_OUTPUT_INVALID',            -1);
if(!defined('Y_MAXTIMEONSTATEA_INVALID'))    define('Y_MAXTIMEONSTATEA_INVALID',   YAPI_INVALID_LONG);
if(!defined('Y_MAXTIMEONSTATEB_INVALID'))    define('Y_MAXTIMEONSTATEB_INVALID',   YAPI_INVALID_LONG);
if(!defined('Y_PULSETIMER_INVALID'))         define('Y_PULSETIMER_INVALID',        YAPI_INVALID_LONG);
if(!defined('Y_DELAYEDPULSETIMER_INVALID'))  define('Y_DELAYEDPULSETIMER_INVALID', null);
if(!defined('Y_COUNTDOWN_INVALID'))          define('Y_COUNTDOWN_INVALID',         YAPI_INVALID_LONG);
//--- (end of YRelay definitions)
    #--- (YRelay yapiwrapper)
   #--- (end of YRelay yapiwrapper)

//--- (YRelay declaration)
/**
 * YRelay Class: relay control interface, available for instance in the Yocto-LatchedRelay, the
 * Yocto-MaxiPowerRelay, the Yocto-PowerRelay-V3 or the Yocto-Relay
 *
 * The YRelay class allows you to drive a Yoctopuce relay or optocoupled output.
 * It can be used to simply switch the output on or off, but also to automatically generate short
 * pulses of determined duration.
 * On devices with two output for each relay (double throw), the two outputs are named A and B,
 * with output A corresponding to the idle position (normally closed) and the output B corresponding to the
 * active state (normally open).
 */
class YRelay extends YFunction
{
    const STATE_A                        = 0;
    const STATE_B                        = 1;
    const STATE_INVALID                  = -1;
    const STATEATPOWERON_UNCHANGED       = 0;
    const STATEATPOWERON_A               = 1;
    const STATEATPOWERON_B               = 2;
    const STATEATPOWERON_INVALID         = -1;
    const MAXTIMEONSTATEA_INVALID        = YAPI_INVALID_LONG;
    const MAXTIMEONSTATEB_INVALID        = YAPI_INVALID_LONG;
    const OUTPUT_OFF                     = 0;
    const OUTPUT_ON                      = 1;
    const OUTPUT_INVALID                 = -1;
    const PULSETIMER_INVALID             = YAPI_INVALID_LONG;
    const DELAYEDPULSETIMER_INVALID      = null;
    const COUNTDOWN_INVALID              = YAPI_INVALID_LONG;
    //--- (end of YRelay declaration)

    //--- (YRelay attributes)
    protected $_state                    = Y_STATE_INVALID;              // Toggle
    protected $_stateAtPowerOn           = Y_STATEATPOWERON_INVALID;     // ToggleAtPowerOn
    protected $_maxTimeOnStateA          = Y_MAXTIMEONSTATEA_INVALID;    // Time
    protected $_maxTimeOnStateB          = Y_MAXTIMEONSTATEB_INVALID;    // Time
    protected $_output                   = Y_OUTPUT_INVALID;             // OnOff
    protected $_pulseTimer               = Y_PULSETIMER_INVALID;         // Time
    protected $_delayedPulseTimer        = Y_DELAYEDPULSETIMER_INVALID;  // DelayedPulse
    protected $_countdown                = Y_COUNTDOWN_INVALID;          // Time
    protected $_firm                     = 0;                            // int
    //--- (end of YRelay attributes)

    function __construct($str_func)
    {
        //--- (YRelay constructor)
        parent::__construct($str_func);
        $this->_className = 'Relay';

        //--- (end of YRelay constructor)
    }

    //--- (YRelay implementation)

    function _parseAttr($name, $val)
    {
        switch($name) {
        case 'state':
            $this->_state = intval($val);
            return 1;
        case 'stateAtPowerOn':
            $this->_stateAtPowerOn = intval($val);
            return 1;
        case 'maxTimeOnStateA':
            $this->_maxTimeOnStateA = intval($val);
            return 1;
        case 'maxTimeOnStateB':
            $this->_maxTimeOnStateB = intval($val);
            return 1;
        case 'output':
            $this->_output = intval($val);
            return 1;
        case 'pulseTimer':
            $this->_pulseTimer = intval($val);
            return 1;
        case 'delayedPulseTimer':
            $this->_delayedPulseTimer = $val;
            return 1;
        case 'countdown':
            $this->_countdown = intval($val);
            return 1;
        }
        return parent::_parseAttr($name, $val);
    }

    /**
     * Returns the state of the relays (A for the idle position, B for the active position).
     *
     * @return integer : either YRelay::STATE_A or YRelay::STATE_B, according to the state of the relays (A
     * for the idle position, B for the active position)
     *
     * On failure, throws an exception or returns YRelay::STATE_INVALID.
     */
    public function get_state()
    {
        // $res                    is a enumTOGGLE;
        if ($this->_cacheExpiration <= YAPI::GetTickCount()) {
            if ($this->load(YAPI::$_yapiContext->GetCacheValidity()) != YAPI_SUCCESS) {
                return Y_STATE_INVALID;
            }
        }
        $res = $this->_state;
        return $res;
    }

    /**
     * Changes the state of the relays (A for the idle position, B for the active position).
     *
     * @param integer $newval : either YRelay::STATE_A or YRelay::STATE_B, according to the state of the
     * relays (A for the idle position, B for the active position)
     *
     * @return integer : YAPI::SUCCESS if the call succeeds.
     *
     * On failure, throws an exception or returns a negative error code.
     */
    public function set_state($newval)
    {
        $rest_val = strval($newval);
        return $this->_setAttr("state",$rest_val);
    }

    /**
     * Returns the state of the relays at device startup (A for the idle position,
     * B for the active position, UNCHANGED to leave the relay state as is).
     *
     * @return integer : a value among YRelay::STATEATPOWERON_UNCHANGED, YRelay::STATEATPOWERON_A and
     * YRelay::STATEATPOWERON_B corresponding to the state of the relays at device startup (A for the idle position,
     *         B for the active position, UNCHANGED to leave the relay state as is)
     *
     * On failure, throws an exception or returns YRelay::STATEATPOWERON_INVALID.
     */
    public function get_stateAtPowerOn()
    {
        // $res                    is a enumTOGGLEATPOWERON;
        if ($this->_cacheExpiration <= YAPI::GetTickCount()) {
            if ($this->load(YAPI::$_yapiContext->GetCacheValidity()) != YAPI_SUCCESS) {
                return Y_STATEATPOWERON_INVALID;
            }
        }
        $res = $this->_stateAtPowerOn;
        return $res;
    }

    /**
     * Changes the state of the relays at device startup (A for the idle position,
     * B for the active position, UNCHANGED to leave the relay state as is).
     * Remember to call the matching module saveToFlash()
     * method, otherwise this call will have no effect.
     *
     * @param integer $newval : a value among YRelay::STATEATPOWERON_UNCHANGED, YRelay::STATEATPOWERON_A and
     * YRelay::STATEATPOWERON_B corresponding to the state of the relays at device startup (A for the idle position,
     *         B for the active position, UNCHANGED to leave the relay state as is)
     *
     * @return integer : YAPI::SUCCESS if the call succeeds.
     *
     * On failure, throws an exception or returns a negative error code.
     */
    public function set_stateAtPowerOn($newval)
    {
        $rest_val = strval($newval);
        return $this->_setAttr("stateAtPowerOn",$rest_val);
    }

    /**
     * Returns the maximum time (ms) allowed for the relay to stay in state
     * A before automatically switching back in to B state. Zero means no time limit.
     *
     * @return integer : an integer corresponding to the maximum time (ms) allowed for the relay to stay in state
     *         A before automatically switching back in to B state
     *
     * On failure, throws an exception or returns YRelay::MAXTIMEONSTATEA_INVALID.
     */
    public function get_maxTimeOnStateA()
    {
        // $res                    is a long;
        if ($this->_cacheExpiration <= YAPI::GetTickCount()) {
            if ($this->load(YAPI::$_yapiContext->GetCacheValidity()) != YAPI_SUCCESS) {
                return Y_MAXTIMEONSTATEA_INVALID;
            }
        }
        $res = $this->_maxTimeOnStateA;
        return $res;
    }

    /**
     * Changes the maximum time (ms) allowed for the relay to stay in state A
     * before automatically switching back in to B state. Use zero for no time limit.
     * Remember to call the saveToFlash()
     * method of the module if the modification must be kept.
     *
     * @param integer $newval : an integer corresponding to the maximum time (ms) allowed for the relay to
     * stay in state A
     *         before automatically switching back in to B state
     *
     * @return integer : YAPI::SUCCESS if the call succeeds.
     *
     * On failure, throws an exception or returns a negative error code.
     */
    public function set_maxTimeOnStateA($newval)
    {
        $rest_val = strval($newval);
        return $this->_setAttr("maxTimeOnStateA",$rest_val);
    }

    /**
     * Retourne the maximum time (ms) allowed for the relay to stay in state B
     * before automatically switching back in to A state. Zero means no time limit.
     *
     * @return integer : an integer
     *
     * On failure, throws an exception or returns YRelay::MAXTIMEONSTATEB_INVALID.
     */
    public function get_maxTimeOnStateB()
    {
        // $res                    is a long;
        if ($this->_cacheExpiration <= YAPI::GetTickCount()) {
            if ($this->load(YAPI::$_yapiContext->GetCacheValidity()) != YAPI_SUCCESS) {
                return Y_MAXTIMEONSTATEB_INVALID;
            }
        }
        $res = $this->_maxTimeOnStateB;
        return $res;
    }

    /**
     * Changes the maximum time (ms) allowed for the relay to stay in state B before
     * automatically switching back in to A state. Use zero for no time limit.
     * Remember to call the saveToFlash()
     * method of the module if the modification must be kept.
     *
     * @param integer $newval : an integer corresponding to the maximum time (ms) allowed for the relay to
     * stay in state B before
     *         automatically switching back in to A state
     *
     * @return integer : YAPI::SUCCESS if the call succeeds.
     *
     * On failure, throws an exception or returns a negative error code.
     */
    public function set_maxTimeOnStateB($newval)
    {
        $rest_val = strval($newval);
        return $this->_setAttr("maxTimeOnStateB",$rest_val);
    }

    /**
     * Returns the output state of the relays, when used as a simple switch (single throw).
     *
     * @return integer : either YRelay::OUTPUT_OFF or YRelay::OUTPUT_ON, according to the output state of
     * the relays, when used as a simple switch (single throw)
     *
     * On failure, throws an exception or returns YRelay::OUTPUT_INVALID.
     */
    public function get_output()
    {
        // $res                    is a enumONOFF;
        if ($this->_cacheExpiration <= YAPI::GetTickCount()) {
            if ($this->load(YAPI::$_yapiContext->GetCacheValidity()) != YAPI_SUCCESS) {
                return Y_OUTPUT_INVALID;
            }
        }
        $res = $this->_output;
        return $res;
    }

    /**
     * Changes the output state of the relays, when used as a simple switch (single throw).
     *
     * @param integer $newval : either YRelay::OUTPUT_OFF or YRelay::OUTPUT_ON, according to the output
     * state of the relays, when used as a simple switch (single throw)
     *
     * @return integer : YAPI::SUCCESS if the call succeeds.
     *
     * On failure, throws an exception or returns a negative error code.
     */
    public function set_output($newval)
    {
        $rest_val = strval($newval);
        return $this->_setAttr("output",$rest_val);
    }

    /**
     * Returns the number of milliseconds remaining before the relays is returned to idle position
     * (state A), during a measured pulse generation. When there is no ongoing pulse, returns zero.
     *
     * @return integer : an integer corresponding to the number of milliseconds remaining before the
     * relays is returned to idle position
     *         (state A), during a measured pulse generation
     *
     * On failure, throws an exception or returns YRelay::PULSETIMER_INVALID.
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

    public function set_pulseTimer($newval)
    {
        $rest_val = strval($newval);
        return $this->_setAttr("pulseTimer",$rest_val);
    }

    /**
     * Sets the relay to output B (active) for a specified duration, then brings it
     * automatically back to output A (idle state).
     *
     * @param integer $ms_duration : pulse duration, in milliseconds
     *
     * @return integer : YAPI::SUCCESS if the call succeeds.
     *
     * On failure, throws an exception or returns a negative error code.
     */
    public function pulse($ms_duration)
    {
        $rest_val = strval($ms_duration);
        return $this->_setAttr("pulseTimer",$rest_val);
    }

    public function get_delayedPulseTimer()
    {
        // $res                    is a YDelayedPulse;
        if ($this->_cacheExpiration <= YAPI::GetTickCount()) {
            if ($this->load(YAPI::$_yapiContext->GetCacheValidity()) != YAPI_SUCCESS) {
                return Y_DELAYEDPULSETIMER_INVALID;
            }
        }
        $res = $this->_delayedPulseTimer;
        return $res;
    }

    public function set_delayedPulseTimer($newval)
    {
        $rest_val = strval($newval["target"]).':'.strval($newval["ms"]);
        return $this->_setAttr("delayedPulseTimer",$rest_val);
    }

    /**
     * Schedules a pulse.
     *
     * @param integer $ms_delay : waiting time before the pulse, in milliseconds
     * @param integer $ms_duration : pulse duration, in milliseconds
     *
     * @return integer : YAPI::SUCCESS if the call succeeds.
     *
     * On failure, throws an exception or returns a negative error code.
     */
    public function delayedPulse($ms_delay,$ms_duration)
    {
        $rest_val = strval($ms_delay).':'.strval($ms_duration);
        return $this->_setAttr("delayedPulseTimer",$rest_val);
    }

    /**
     * Returns the number of milliseconds remaining before a pulse (delayedPulse() call)
     * When there is no scheduled pulse, returns zero.
     *
     * @return integer : an integer corresponding to the number of milliseconds remaining before a pulse
     * (delayedPulse() call)
     *         When there is no scheduled pulse, returns zero
     *
     * On failure, throws an exception or returns YRelay::COUNTDOWN_INVALID.
     */
    public function get_countdown()
    {
        // $res                    is a long;
        if ($this->_cacheExpiration <= YAPI::GetTickCount()) {
            if ($this->load(YAPI::$_yapiContext->GetCacheValidity()) != YAPI_SUCCESS) {
                return Y_COUNTDOWN_INVALID;
            }
        }
        $res = $this->_countdown;
        return $res;
    }

    /**
     * Retrieves a relay for a given identifier.
     * The identifier can be specified using several formats:
     * <ul>
     * <li>FunctionLogicalName</li>
     * <li>ModuleSerialNumber.FunctionIdentifier</li>
     * <li>ModuleSerialNumber.FunctionLogicalName</li>
     * <li>ModuleLogicalName.FunctionIdentifier</li>
     * <li>ModuleLogicalName.FunctionLogicalName</li>
     * </ul>
     *
     * This function does not require that the relay is online at the time
     * it is invoked. The returned object is nevertheless valid.
     * Use the method isOnline() to test if the relay is
     * indeed online at a given time. In case of ambiguity when looking for
     * a relay by logical name, no error is notified: the first instance
     * found is returned. The search is performed first by hardware name,
     * then by logical name.
     *
     * If a call to this object's is_online() method returns FALSE although
     * you are certain that the matching device is plugged, make sure that you did
     * call registerHub() at application initialization time.
     *
     * @param string $func : a string that uniquely characterizes the relay, for instance
     *         YLTCHRL1.relay1.
     *
     * @return YRelay : a YRelay object allowing you to drive the relay.
     */
    public static function FindRelay($func)
    {
        // $obj                    is a YRelay;
        $obj = YFunction::_FindFromCache('Relay', $func);
        if ($obj == null) {
            $obj = new YRelay($func);
            YFunction::_AddToCache('Relay', $func, $obj);
        }
        return $obj;
    }

    /**
     * Switch the relay to the opposite state.
     *
     * @return integer : YAPI::SUCCESS if the call succeeds.
     *
     * On failure, throws an exception or returns a negative error code.
     */
    public function toggle()
    {
        // $sta                    is a int;
        // $fw                     is a str;
        // $mo                     is a YModule;
        if ($this->_firm == 0) {
            $mo = $this->get_module();
            $fw = $mo->get_firmwareRelease();
            if ($fw == Y_FIRMWARERELEASE_INVALID) {
                return Y_STATE_INVALID;
            }
            $this->_firm = intVal($fw);
        }
        if ($this->_firm < 34921) {
            $sta = $this->get_state();
            if ($sta == Y_STATE_INVALID) {
                return Y_STATE_INVALID;
            }
            if ($sta == Y_STATE_B) {
                $this->set_state(Y_STATE_A);
            } else {
                $this->set_state(Y_STATE_B);
            }
            return YAPI_SUCCESS;
        } else {
            return $this->_setAttr('state','X');
        }
    }

    public function state()
    { return $this->get_state(); }

    public function setState($newval)
    { return $this->set_state($newval); }

    public function stateAtPowerOn()
    { return $this->get_stateAtPowerOn(); }

    public function setStateAtPowerOn($newval)
    { return $this->set_stateAtPowerOn($newval); }

    public function maxTimeOnStateA()
    { return $this->get_maxTimeOnStateA(); }

    public function setMaxTimeOnStateA($newval)
    { return $this->set_maxTimeOnStateA($newval); }

    public function maxTimeOnStateB()
    { return $this->get_maxTimeOnStateB(); }

    public function setMaxTimeOnStateB($newval)
    { return $this->set_maxTimeOnStateB($newval); }

    public function output()
    { return $this->get_output(); }

    public function setOutput($newval)
    { return $this->set_output($newval); }

    public function pulseTimer()
    { return $this->get_pulseTimer(); }

    public function setPulseTimer($newval)
    { return $this->set_pulseTimer($newval); }

    public function delayedPulseTimer()
    { return $this->get_delayedPulseTimer(); }

    public function setDelayedPulseTimer($newval)
    { return $this->set_delayedPulseTimer($newval); }

    public function countdown()
    { return $this->get_countdown(); }

    /**
     * Continues the enumeration of relays started using yFirstRelay().
     * Caution: You can't make any assumption about the returned relays order.
     * If you want to find a specific a relay, use Relay.findRelay()
     * and a hardwareID or a logical name.
     *
     * @return YRelay : a pointer to a YRelay object, corresponding to
     *         a relay currently online, or a null pointer
     *         if there are no more relays to enumerate.
     */
    public function nextRelay()
    {   $resolve = YAPI::resolveFunction($this->_className, $this->_func);
        if($resolve->errorType != YAPI_SUCCESS) return null;
        $next_hwid = YAPI::getNextHardwareId($this->_className, $resolve->result);
        if($next_hwid == null) return null;
        return self::FindRelay($next_hwid);
    }

    /**
     * Starts the enumeration of relays currently accessible.
     * Use the method YRelay::nextRelay() to iterate on
     * next relays.
     *
     * @return YRelay : a pointer to a YRelay object, corresponding to
     *         the first relay currently online, or a null pointer
     *         if there are none.
     */
    public static function FirstRelay()
    {   $next_hwid = YAPI::getFirstHardwareId('Relay');
        if($next_hwid == null) return null;
        return self::FindRelay($next_hwid);
    }

    //--- (end of YRelay implementation)

};

//--- (YRelay functions)

/**
 * Retrieves a relay for a given identifier.
 * The identifier can be specified using several formats:
 * <ul>
 * <li>FunctionLogicalName</li>
 * <li>ModuleSerialNumber.FunctionIdentifier</li>
 * <li>ModuleSerialNumber.FunctionLogicalName</li>
 * <li>ModuleLogicalName.FunctionIdentifier</li>
 * <li>ModuleLogicalName.FunctionLogicalName</li>
 * </ul>
 *
 * This function does not require that the relay is online at the time
 * it is invoked. The returned object is nevertheless valid.
 * Use the method isOnline() to test if the relay is
 * indeed online at a given time. In case of ambiguity when looking for
 * a relay by logical name, no error is notified: the first instance
 * found is returned. The search is performed first by hardware name,
 * then by logical name.
 *
 * If a call to this object's is_online() method returns FALSE although
 * you are certain that the matching device is plugged, make sure that you did
 * call registerHub() at application initialization time.
 *
 * @param string $func : a string that uniquely characterizes the relay, for instance
 *         YLTCHRL1.relay1.
 *
 * @return YRelay : a YRelay object allowing you to drive the relay.
 */
function yFindRelay($func)
{
    return YRelay::FindRelay($func);
}

/**
 * Starts the enumeration of relays currently accessible.
 * Use the method YRelay::nextRelay() to iterate on
 * next relays.
 *
 * @return YRelay : a pointer to a YRelay object, corresponding to
 *         the first relay currently online, or a null pointer
 *         if there are none.
 */
function yFirstRelay()
{
    return YRelay::FirstRelay();
}

//--- (end of YRelay functions)
?>