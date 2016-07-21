<?php
/*********************************************************************
 *
 * $Id: yocto_audioout.php 23243 2016-02-23 14:13:12Z seb $
 *
 * Implements YAudioOut, the high-level API for AudioOut functions
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

//--- (YAudioOut return codes)
//--- (end of YAudioOut return codes)
//--- (YAudioOut definitions)
if(!defined('Y_MUTE_FALSE'))                 define('Y_MUTE_FALSE',                0);
if(!defined('Y_MUTE_TRUE'))                  define('Y_MUTE_TRUE',                 1);
if(!defined('Y_MUTE_INVALID'))               define('Y_MUTE_INVALID',              -1);
if(!defined('Y_VOLUME_INVALID'))             define('Y_VOLUME_INVALID',            YAPI_INVALID_UINT);
if(!defined('Y_VOLUMERANGE_INVALID'))        define('Y_VOLUMERANGE_INVALID',       YAPI_INVALID_STRING);
if(!defined('Y_SIGNAL_INVALID'))             define('Y_SIGNAL_INVALID',            YAPI_INVALID_INT);
if(!defined('Y_NOSIGNALFOR_INVALID'))        define('Y_NOSIGNALFOR_INVALID',       YAPI_INVALID_INT);
//--- (end of YAudioOut definitions)

//--- (YAudioOut declaration)
/**
 * YAudioOut Class: AudioOut function interface
 *
 * The Yoctopuce application programming interface allows you to configure the volume of the outout.
 */
class YAudioOut extends YFunction
{
    const VOLUME_INVALID                 = YAPI_INVALID_UINT;
    const MUTE_FALSE                     = 0;
    const MUTE_TRUE                      = 1;
    const MUTE_INVALID                   = -1;
    const VOLUMERANGE_INVALID            = YAPI_INVALID_STRING;
    const SIGNAL_INVALID                 = YAPI_INVALID_INT;
    const NOSIGNALFOR_INVALID            = YAPI_INVALID_INT;
    //--- (end of YAudioOut declaration)

    //--- (YAudioOut attributes)
    protected $_volume                   = Y_VOLUME_INVALID;             // Percent
    protected $_mute                     = Y_MUTE_INVALID;               // Bool
    protected $_volumeRange              = Y_VOLUMERANGE_INVALID;        // ValueRange
    protected $_signal                   = Y_SIGNAL_INVALID;             // Int
    protected $_noSignalFor              = Y_NOSIGNALFOR_INVALID;        // Int
    //--- (end of YAudioOut attributes)

    function __construct($str_func)
    {
        //--- (YAudioOut constructor)
        parent::__construct($str_func);
        $this->_className = 'AudioOut';

        //--- (end of YAudioOut constructor)
    }

    //--- (YAudioOut implementation)

    function _parseAttr($name, $val)
    {
        switch($name) {
        case 'volume':
            $this->_volume = intval($val);
            return 1;
        case 'mute':
            $this->_mute = intval($val);
            return 1;
        case 'volumeRange':
            $this->_volumeRange = $val;
            return 1;
        case 'signal':
            $this->_signal = intval($val);
            return 1;
        case 'noSignalFor':
            $this->_noSignalFor = intval($val);
            return 1;
        }
        return parent::_parseAttr($name, $val);
    }

    /**
     * Returns audio output volume, in per cents.
     *
     * @return an integer corresponding to audio output volume, in per cents
     *
     * On failure, throws an exception or returns Y_VOLUME_INVALID.
     */
    public function get_volume()
    {
        if ($this->_cacheExpiration <= YAPI::GetTickCount()) {
            if ($this->load(YAPI::$defaultCacheValidity) != YAPI_SUCCESS) {
                return Y_VOLUME_INVALID;
            }
        }
        return $this->_volume;
    }

    /**
     * Changes audio output volume, in per cents.
     *
     * @param newval : an integer corresponding to audio output volume, in per cents
     *
     * @return YAPI_SUCCESS if the call succeeds.
     *
     * On failure, throws an exception or returns a negative error code.
     */
    public function set_volume($newval)
    {
        $rest_val = strval($newval);
        return $this->_setAttr("volume",$rest_val);
    }

    /**
     * Returns the state of the mute function.
     *
     * @return either Y_MUTE_FALSE or Y_MUTE_TRUE, according to the state of the mute function
     *
     * On failure, throws an exception or returns Y_MUTE_INVALID.
     */
    public function get_mute()
    {
        if ($this->_cacheExpiration <= YAPI::GetTickCount()) {
            if ($this->load(YAPI::$defaultCacheValidity) != YAPI_SUCCESS) {
                return Y_MUTE_INVALID;
            }
        }
        return $this->_mute;
    }

    /**
     * Changes the state of the mute function. Remember to call the matching module
     * saveToFlash() method to save the setting permanently.
     *
     * @param newval : either Y_MUTE_FALSE or Y_MUTE_TRUE, according to the state of the mute function
     *
     * @return YAPI_SUCCESS if the call succeeds.
     *
     * On failure, throws an exception or returns a negative error code.
     */
    public function set_mute($newval)
    {
        $rest_val = strval($newval);
        return $this->_setAttr("mute",$rest_val);
    }

    /**
     * Returns the supported volume range. The low value of the
     * range corresponds to the minimal audible value. To
     * completely mute the sound, use set_mute()
     * instead of the set_volume().
     *
     * @return a string corresponding to the supported volume range
     *
     * On failure, throws an exception or returns Y_VOLUMERANGE_INVALID.
     */
    public function get_volumeRange()
    {
        if ($this->_cacheExpiration <= YAPI::GetTickCount()) {
            if ($this->load(YAPI::$defaultCacheValidity) != YAPI_SUCCESS) {
                return Y_VOLUMERANGE_INVALID;
            }
        }
        return $this->_volumeRange;
    }

    /**
     * Returns the detected output current level.
     *
     * @return an integer corresponding to the detected output current level
     *
     * On failure, throws an exception or returns Y_SIGNAL_INVALID.
     */
    public function get_signal()
    {
        if ($this->_cacheExpiration <= YAPI::GetTickCount()) {
            if ($this->load(YAPI::$defaultCacheValidity) != YAPI_SUCCESS) {
                return Y_SIGNAL_INVALID;
            }
        }
        return $this->_signal;
    }

    /**
     * Returns the number of seconds elapsed without detecting a signal.
     *
     * @return an integer corresponding to the number of seconds elapsed without detecting a signal
     *
     * On failure, throws an exception or returns Y_NOSIGNALFOR_INVALID.
     */
    public function get_noSignalFor()
    {
        if ($this->_cacheExpiration <= YAPI::GetTickCount()) {
            if ($this->load(YAPI::$defaultCacheValidity) != YAPI_SUCCESS) {
                return Y_NOSIGNALFOR_INVALID;
            }
        }
        return $this->_noSignalFor;
    }

    /**
     * Retrieves an audio output for a given identifier.
     * The identifier can be specified using several formats:
     * <ul>
     * <li>FunctionLogicalName</li>
     * <li>ModuleSerialNumber.FunctionIdentifier</li>
     * <li>ModuleSerialNumber.FunctionLogicalName</li>
     * <li>ModuleLogicalName.FunctionIdentifier</li>
     * <li>ModuleLogicalName.FunctionLogicalName</li>
     * </ul>
     *
     * This function does not require that the audio output is online at the time
     * it is invoked. The returned object is nevertheless valid.
     * Use the method YAudioOut.isOnline() to test if the audio output is
     * indeed online at a given time. In case of ambiguity when looking for
     * an audio output by logical name, no error is notified: the first instance
     * found is returned. The search is performed first by hardware name,
     * then by logical name.
     *
     * @param func : a string that uniquely characterizes the audio output
     *
     * @return a YAudioOut object allowing you to drive the audio output.
     */
    public static function FindAudioOut($func)
    {
        // $obj                    is a YAudioOut;
        $obj = YFunction::_FindFromCache('AudioOut', $func);
        if ($obj == null) {
            $obj = new YAudioOut($func);
            YFunction::_AddToCache('AudioOut', $func, $obj);
        }
        return $obj;
    }

    public function volume()
    { return $this->get_volume(); }

    public function setVolume($newval)
    { return $this->set_volume($newval); }

    public function mute()
    { return $this->get_mute(); }

    public function setMute($newval)
    { return $this->set_mute($newval); }

    public function volumeRange()
    { return $this->get_volumeRange(); }

    public function signal()
    { return $this->get_signal(); }

    public function noSignalFor()
    { return $this->get_noSignalFor(); }

    /**
     * Continues the enumeration of audio outputs started using yFirstAudioOut().
     *
     * @return a pointer to a YAudioOut object, corresponding to
     *         an audio output currently online, or a null pointer
     *         if there are no more audio outputs to enumerate.
     */
    public function nextAudioOut()
    {   $resolve = YAPI::resolveFunction($this->_className, $this->_func);
        if($resolve->errorType != YAPI_SUCCESS) return null;
        $next_hwid = YAPI::getNextHardwareId($this->_className, $resolve->result);
        if($next_hwid == null) return null;
        return yFindAudioOut($next_hwid);
    }

    /**
     * Starts the enumeration of audio outputs currently accessible.
     * Use the method YAudioOut.nextAudioOut() to iterate on
     * next audio outputs.
     *
     * @return a pointer to a YAudioOut object, corresponding to
     *         the first audio output currently online, or a null pointer
     *         if there are none.
     */
    public static function FirstAudioOut()
    {   $next_hwid = YAPI::getFirstHardwareId('AudioOut');
        if($next_hwid == null) return null;
        return self::FindAudioOut($next_hwid);
    }

    //--- (end of YAudioOut implementation)

};

//--- (AudioOut functions)

/**
 * Retrieves an audio output for a given identifier.
 * The identifier can be specified using several formats:
 * <ul>
 * <li>FunctionLogicalName</li>
 * <li>ModuleSerialNumber.FunctionIdentifier</li>
 * <li>ModuleSerialNumber.FunctionLogicalName</li>
 * <li>ModuleLogicalName.FunctionIdentifier</li>
 * <li>ModuleLogicalName.FunctionLogicalName</li>
 * </ul>
 *
 * This function does not require that the audio output is online at the time
 * it is invoked. The returned object is nevertheless valid.
 * Use the method YAudioOut.isOnline() to test if the audio output is
 * indeed online at a given time. In case of ambiguity when looking for
 * an audio output by logical name, no error is notified: the first instance
 * found is returned. The search is performed first by hardware name,
 * then by logical name.
 *
 * @param func : a string that uniquely characterizes the audio output
 *
 * @return a YAudioOut object allowing you to drive the audio output.
 */
function yFindAudioOut($func)
{
    return YAudioOut::FindAudioOut($func);
}

/**
 * Starts the enumeration of audio outputs currently accessible.
 * Use the method YAudioOut.nextAudioOut() to iterate on
 * next audio outputs.
 *
 * @return a pointer to a YAudioOut object, corresponding to
 *         the first audio output currently online, or a null pointer
 *         if there are none.
 */
function yFirstAudioOut()
{
    return YAudioOut::FirstAudioOut();
}

//--- (end of AudioOut functions)
?>