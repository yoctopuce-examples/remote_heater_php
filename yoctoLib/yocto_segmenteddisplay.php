<?php
/*********************************************************************
 *
 *  $Id: yocto_segmenteddisplay.php 43580 2021-01-26 17:46:01Z mvuilleu $
 *
 *  Implements YSegmentedDisplay, the high-level API for SegmentedDisplay functions
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

//--- (YSegmentedDisplay return codes)
//--- (end of YSegmentedDisplay return codes)
//--- (YSegmentedDisplay definitions)
if(!defined('Y_DISPLAYMODE_DISCONNECTED'))   define('Y_DISPLAYMODE_DISCONNECTED',  0);
if(!defined('Y_DISPLAYMODE_MANUAL'))         define('Y_DISPLAYMODE_MANUAL',        1);
if(!defined('Y_DISPLAYMODE_AUTO1'))          define('Y_DISPLAYMODE_AUTO1',         2);
if(!defined('Y_DISPLAYMODE_AUTO60'))         define('Y_DISPLAYMODE_AUTO60',        3);
if(!defined('Y_DISPLAYMODE_INVALID'))        define('Y_DISPLAYMODE_INVALID',       -1);
if(!defined('Y_DISPLAYEDTEXT_INVALID'))      define('Y_DISPLAYEDTEXT_INVALID',     YAPI_INVALID_STRING);
//--- (end of YSegmentedDisplay definitions)
    #--- (YSegmentedDisplay yapiwrapper)
   #--- (end of YSegmentedDisplay yapiwrapper)

//--- (YSegmentedDisplay declaration)
/**
 * YSegmentedDisplay Class: segmented display control interface
 *
 * The SegmentedDisplay class allows you to drive segmented displays.
 */
class YSegmentedDisplay extends YFunction
{
    const DISPLAYEDTEXT_INVALID          = YAPI_INVALID_STRING;
    const DISPLAYMODE_DISCONNECTED       = 0;
    const DISPLAYMODE_MANUAL             = 1;
    const DISPLAYMODE_AUTO1              = 2;
    const DISPLAYMODE_AUTO60             = 3;
    const DISPLAYMODE_INVALID            = -1;
    //--- (end of YSegmentedDisplay declaration)

    //--- (YSegmentedDisplay attributes)
    protected $_displayedText            = Y_DISPLAYEDTEXT_INVALID;      // Text
    protected $_displayMode              = Y_DISPLAYMODE_INVALID;        // DisplayMode
    //--- (end of YSegmentedDisplay attributes)

    function __construct($str_func)
    {
        //--- (YSegmentedDisplay constructor)
        parent::__construct($str_func);
        $this->_className = 'SegmentedDisplay';

        //--- (end of YSegmentedDisplay constructor)
    }

    //--- (YSegmentedDisplay implementation)

    function _parseAttr($name, $val)
    {
        switch($name) {
        case 'displayedText':
            $this->_displayedText = $val;
            return 1;
        case 'displayMode':
            $this->_displayMode = intval($val);
            return 1;
        }
        return parent::_parseAttr($name, $val);
    }

    /**
     * Returns the text currently displayed on the screen.
     *
     * @return string : a string corresponding to the text currently displayed on the screen
     *
     * On failure, throws an exception or returns YSegmentedDisplay::DISPLAYEDTEXT_INVALID.
     */
    public function get_displayedText()
    {
        // $res                    is a string;
        if ($this->_cacheExpiration <= YAPI::GetTickCount()) {
            if ($this->load(YAPI::$_yapiContext->GetCacheValidity()) != YAPI_SUCCESS) {
                return Y_DISPLAYEDTEXT_INVALID;
            }
        }
        $res = $this->_displayedText;
        return $res;
    }

    /**
     * Changes the text currently displayed on the screen.
     *
     * @param string $newval : a string corresponding to the text currently displayed on the screen
     *
     * @return integer : YAPI::SUCCESS if the call succeeds.
     *
     * On failure, throws an exception or returns a negative error code.
     */
    public function set_displayedText($newval)
    {
        $rest_val = $newval;
        return $this->_setAttr("displayedText",$rest_val);
    }

    public function get_displayMode()
    {
        // $res                    is a enumDISPLAYMODE;
        if ($this->_cacheExpiration <= YAPI::GetTickCount()) {
            if ($this->load(YAPI::$_yapiContext->GetCacheValidity()) != YAPI_SUCCESS) {
                return Y_DISPLAYMODE_INVALID;
            }
        }
        $res = $this->_displayMode;
        return $res;
    }

    public function set_displayMode($newval)
    {
        $rest_val = strval($newval);
        return $this->_setAttr("displayMode",$rest_val);
    }

    /**
     * Retrieves a segmented display for a given identifier.
     * The identifier can be specified using several formats:
     * <ul>
     * <li>FunctionLogicalName</li>
     * <li>ModuleSerialNumber.FunctionIdentifier</li>
     * <li>ModuleSerialNumber.FunctionLogicalName</li>
     * <li>ModuleLogicalName.FunctionIdentifier</li>
     * <li>ModuleLogicalName.FunctionLogicalName</li>
     * </ul>
     *
     * This function does not require that the segmented display is online at the time
     * it is invoked. The returned object is nevertheless valid.
     * Use the method isOnline() to test if the segmented display is
     * indeed online at a given time. In case of ambiguity when looking for
     * a segmented display by logical name, no error is notified: the first instance
     * found is returned. The search is performed first by hardware name,
     * then by logical name.
     *
     * If a call to this object's is_online() method returns FALSE although
     * you are certain that the matching device is plugged, make sure that you did
     * call registerHub() at application initialization time.
     *
     * @param string $func : a string that uniquely characterizes the segmented display, for instance
     *         MyDevice.segmentedDisplay.
     *
     * @return YSegmentedDisplay : a YSegmentedDisplay object allowing you to drive the segmented display.
     */
    public static function FindSegmentedDisplay($func)
    {
        // $obj                    is a YSegmentedDisplay;
        $obj = YFunction::_FindFromCache('SegmentedDisplay', $func);
        if ($obj == null) {
            $obj = new YSegmentedDisplay($func);
            YFunction::_AddToCache('SegmentedDisplay', $func, $obj);
        }
        return $obj;
    }

    public function displayedText()
    { return $this->get_displayedText(); }

    public function setDisplayedText($newval)
    { return $this->set_displayedText($newval); }

    public function displayMode()
    { return $this->get_displayMode(); }

    public function setDisplayMode($newval)
    { return $this->set_displayMode($newval); }

    /**
     * Continues the enumeration of segmented displays started using yFirstSegmentedDisplay().
     * Caution: You can't make any assumption about the returned segmented displays order.
     * If you want to find a specific a segmented display, use SegmentedDisplay.findSegmentedDisplay()
     * and a hardwareID or a logical name.
     *
     * @return YSegmentedDisplay : a pointer to a YSegmentedDisplay object, corresponding to
     *         a segmented display currently online, or a null pointer
     *         if there are no more segmented displays to enumerate.
     */
    public function nextSegmentedDisplay()
    {   $resolve = YAPI::resolveFunction($this->_className, $this->_func);
        if($resolve->errorType != YAPI_SUCCESS) return null;
        $next_hwid = YAPI::getNextHardwareId($this->_className, $resolve->result);
        if($next_hwid == null) return null;
        return self::FindSegmentedDisplay($next_hwid);
    }

    /**
     * Starts the enumeration of segmented displays currently accessible.
     * Use the method YSegmentedDisplay::nextSegmentedDisplay() to iterate on
     * next segmented displays.
     *
     * @return YSegmentedDisplay : a pointer to a YSegmentedDisplay object, corresponding to
     *         the first segmented display currently online, or a null pointer
     *         if there are none.
     */
    public static function FirstSegmentedDisplay()
    {   $next_hwid = YAPI::getFirstHardwareId('SegmentedDisplay');
        if($next_hwid == null) return null;
        return self::FindSegmentedDisplay($next_hwid);
    }

    //--- (end of YSegmentedDisplay implementation)

};

//--- (YSegmentedDisplay functions)

/**
 * Retrieves a segmented display for a given identifier.
 * The identifier can be specified using several formats:
 * <ul>
 * <li>FunctionLogicalName</li>
 * <li>ModuleSerialNumber.FunctionIdentifier</li>
 * <li>ModuleSerialNumber.FunctionLogicalName</li>
 * <li>ModuleLogicalName.FunctionIdentifier</li>
 * <li>ModuleLogicalName.FunctionLogicalName</li>
 * </ul>
 *
 * This function does not require that the segmented display is online at the time
 * it is invoked. The returned object is nevertheless valid.
 * Use the method isOnline() to test if the segmented display is
 * indeed online at a given time. In case of ambiguity when looking for
 * a segmented display by logical name, no error is notified: the first instance
 * found is returned. The search is performed first by hardware name,
 * then by logical name.
 *
 * If a call to this object's is_online() method returns FALSE although
 * you are certain that the matching device is plugged, make sure that you did
 * call registerHub() at application initialization time.
 *
 * @param string $func : a string that uniquely characterizes the segmented display, for instance
 *         MyDevice.segmentedDisplay.
 *
 * @return YSegmentedDisplay : a YSegmentedDisplay object allowing you to drive the segmented display.
 */
function yFindSegmentedDisplay($func)
{
    return YSegmentedDisplay::FindSegmentedDisplay($func);
}

/**
 * Starts the enumeration of segmented displays currently accessible.
 * Use the method YSegmentedDisplay::nextSegmentedDisplay() to iterate on
 * next segmented displays.
 *
 * @return YSegmentedDisplay : a pointer to a YSegmentedDisplay object, corresponding to
 *         the first segmented display currently online, or a null pointer
 *         if there are none.
 */
function yFirstSegmentedDisplay()
{
    return YSegmentedDisplay::FirstSegmentedDisplay();
}

//--- (end of YSegmentedDisplay functions)
?>