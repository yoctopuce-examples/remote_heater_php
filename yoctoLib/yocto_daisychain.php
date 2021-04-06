<?php
/*********************************************************************
 *
 *  $Id: yocto_daisychain.php 43580 2021-01-26 17:46:01Z mvuilleu $
 *
 *  Implements YDaisyChain, the high-level API for DaisyChain functions
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

//--- (YDaisyChain return codes)
//--- (end of YDaisyChain return codes)
//--- (YDaisyChain definitions)
if(!defined('Y_DAISYSTATE_READY'))           define('Y_DAISYSTATE_READY',          0);
if(!defined('Y_DAISYSTATE_IS_CHILD'))        define('Y_DAISYSTATE_IS_CHILD',       1);
if(!defined('Y_DAISYSTATE_FIRMWARE_MISMATCH')) define('Y_DAISYSTATE_FIRMWARE_MISMATCH', 2);
if(!defined('Y_DAISYSTATE_CHILD_MISSING'))   define('Y_DAISYSTATE_CHILD_MISSING',  3);
if(!defined('Y_DAISYSTATE_CHILD_LOST'))      define('Y_DAISYSTATE_CHILD_LOST',     4);
if(!defined('Y_DAISYSTATE_INVALID'))         define('Y_DAISYSTATE_INVALID',        -1);
if(!defined('Y_CHILDCOUNT_INVALID'))         define('Y_CHILDCOUNT_INVALID',        YAPI_INVALID_UINT);
if(!defined('Y_REQUIREDCHILDCOUNT_INVALID')) define('Y_REQUIREDCHILDCOUNT_INVALID', YAPI_INVALID_UINT);
//--- (end of YDaisyChain definitions)
    #--- (YDaisyChain yapiwrapper)
   #--- (end of YDaisyChain yapiwrapper)

//--- (YDaisyChain declaration)
/**
 * YDaisyChain Class: Module chain configuration interface
 *
 * The YDaisyChain class can be used to verify that devices that
 * are daisy-chained directly from device to device, without a hub,
 * are detected properly.
 */
class YDaisyChain extends YFunction
{
    const DAISYSTATE_READY               = 0;
    const DAISYSTATE_IS_CHILD            = 1;
    const DAISYSTATE_FIRMWARE_MISMATCH   = 2;
    const DAISYSTATE_CHILD_MISSING       = 3;
    const DAISYSTATE_CHILD_LOST          = 4;
    const DAISYSTATE_INVALID             = -1;
    const CHILDCOUNT_INVALID             = YAPI_INVALID_UINT;
    const REQUIREDCHILDCOUNT_INVALID     = YAPI_INVALID_UINT;
    //--- (end of YDaisyChain declaration)

    //--- (YDaisyChain attributes)
    protected $_daisyState               = Y_DAISYSTATE_INVALID;         // DaisyState
    protected $_childCount               = Y_CHILDCOUNT_INVALID;         // UInt31
    protected $_requiredChildCount       = Y_REQUIREDCHILDCOUNT_INVALID; // UInt31
    //--- (end of YDaisyChain attributes)

    function __construct($str_func)
    {
        //--- (YDaisyChain constructor)
        parent::__construct($str_func);
        $this->_className = 'DaisyChain';

        //--- (end of YDaisyChain constructor)
    }

    //--- (YDaisyChain implementation)

    function _parseAttr($name, $val)
    {
        switch($name) {
        case 'daisyState':
            $this->_daisyState = intval($val);
            return 1;
        case 'childCount':
            $this->_childCount = intval($val);
            return 1;
        case 'requiredChildCount':
            $this->_requiredChildCount = intval($val);
            return 1;
        }
        return parent::_parseAttr($name, $val);
    }

    /**
     * Returns the state of the daisy-link between modules.
     *
     * @return integer : a value among YDaisyChain::DAISYSTATE_READY, YDaisyChain::DAISYSTATE_IS_CHILD,
     * YDaisyChain::DAISYSTATE_FIRMWARE_MISMATCH, YDaisyChain::DAISYSTATE_CHILD_MISSING and
     * YDaisyChain::DAISYSTATE_CHILD_LOST corresponding to the state of the daisy-link between modules
     *
     * On failure, throws an exception or returns YDaisyChain::DAISYSTATE_INVALID.
     */
    public function get_daisyState()
    {
        // $res                    is a enumDAISYSTATE;
        if ($this->_cacheExpiration <= YAPI::GetTickCount()) {
            if ($this->load(YAPI::$_yapiContext->GetCacheValidity()) != YAPI_SUCCESS) {
                return Y_DAISYSTATE_INVALID;
            }
        }
        $res = $this->_daisyState;
        return $res;
    }

    /**
     * Returns the number of child nodes currently detected.
     *
     * @return integer : an integer corresponding to the number of child nodes currently detected
     *
     * On failure, throws an exception or returns YDaisyChain::CHILDCOUNT_INVALID.
     */
    public function get_childCount()
    {
        // $res                    is a int;
        if ($this->_cacheExpiration <= YAPI::GetTickCount()) {
            if ($this->load(YAPI::$_yapiContext->GetCacheValidity()) != YAPI_SUCCESS) {
                return Y_CHILDCOUNT_INVALID;
            }
        }
        $res = $this->_childCount;
        return $res;
    }

    /**
     * Returns the number of child nodes expected in normal conditions.
     *
     * @return integer : an integer corresponding to the number of child nodes expected in normal conditions
     *
     * On failure, throws an exception or returns YDaisyChain::REQUIREDCHILDCOUNT_INVALID.
     */
    public function get_requiredChildCount()
    {
        // $res                    is a int;
        if ($this->_cacheExpiration <= YAPI::GetTickCount()) {
            if ($this->load(YAPI::$_yapiContext->GetCacheValidity()) != YAPI_SUCCESS) {
                return Y_REQUIREDCHILDCOUNT_INVALID;
            }
        }
        $res = $this->_requiredChildCount;
        return $res;
    }

    /**
     * Changes the number of child nodes expected in normal conditions.
     * If the value is zero, no check is performed. If it is non-zero, the number
     * child nodes is checked on startup and the status will change to error if
     * the count does not match. Remember to call the saveToFlash()
     * method of the module if the modification must be kept.
     *
     * @param integer $newval : an integer corresponding to the number of child nodes expected in normal conditions
     *
     * @return integer : YAPI::SUCCESS if the call succeeds.
     *
     * On failure, throws an exception or returns a negative error code.
     */
    public function set_requiredChildCount($newval)
    {
        $rest_val = strval($newval);
        return $this->_setAttr("requiredChildCount",$rest_val);
    }

    /**
     * Retrieves a module chain for a given identifier.
     * The identifier can be specified using several formats:
     * <ul>
     * <li>FunctionLogicalName</li>
     * <li>ModuleSerialNumber.FunctionIdentifier</li>
     * <li>ModuleSerialNumber.FunctionLogicalName</li>
     * <li>ModuleLogicalName.FunctionIdentifier</li>
     * <li>ModuleLogicalName.FunctionLogicalName</li>
     * </ul>
     *
     * This function does not require that the module chain is online at the time
     * it is invoked. The returned object is nevertheless valid.
     * Use the method isOnline() to test if the module chain is
     * indeed online at a given time. In case of ambiguity when looking for
     * a module chain by logical name, no error is notified: the first instance
     * found is returned. The search is performed first by hardware name,
     * then by logical name.
     *
     * If a call to this object's is_online() method returns FALSE although
     * you are certain that the matching device is plugged, make sure that you did
     * call registerHub() at application initialization time.
     *
     * @param string $func : a string that uniquely characterizes the module chain, for instance
     *         MyDevice.daisyChain.
     *
     * @return YDaisyChain : a YDaisyChain object allowing you to drive the module chain.
     */
    public static function FindDaisyChain($func)
    {
        // $obj                    is a YDaisyChain;
        $obj = YFunction::_FindFromCache('DaisyChain', $func);
        if ($obj == null) {
            $obj = new YDaisyChain($func);
            YFunction::_AddToCache('DaisyChain', $func, $obj);
        }
        return $obj;
    }

    public function daisyState()
    { return $this->get_daisyState(); }

    public function childCount()
    { return $this->get_childCount(); }

    public function requiredChildCount()
    { return $this->get_requiredChildCount(); }

    public function setRequiredChildCount($newval)
    { return $this->set_requiredChildCount($newval); }

    /**
     * Continues the enumeration of module chains started using yFirstDaisyChain().
     * Caution: You can't make any assumption about the returned module chains order.
     * If you want to find a specific a module chain, use DaisyChain.findDaisyChain()
     * and a hardwareID or a logical name.
     *
     * @return YDaisyChain : a pointer to a YDaisyChain object, corresponding to
     *         a module chain currently online, or a null pointer
     *         if there are no more module chains to enumerate.
     */
    public function nextDaisyChain()
    {   $resolve = YAPI::resolveFunction($this->_className, $this->_func);
        if($resolve->errorType != YAPI_SUCCESS) return null;
        $next_hwid = YAPI::getNextHardwareId($this->_className, $resolve->result);
        if($next_hwid == null) return null;
        return self::FindDaisyChain($next_hwid);
    }

    /**
     * Starts the enumeration of module chains currently accessible.
     * Use the method YDaisyChain::nextDaisyChain() to iterate on
     * next module chains.
     *
     * @return YDaisyChain : a pointer to a YDaisyChain object, corresponding to
     *         the first module chain currently online, or a null pointer
     *         if there are none.
     */
    public static function FirstDaisyChain()
    {   $next_hwid = YAPI::getFirstHardwareId('DaisyChain');
        if($next_hwid == null) return null;
        return self::FindDaisyChain($next_hwid);
    }

    //--- (end of YDaisyChain implementation)

};

//--- (YDaisyChain functions)

/**
 * Retrieves a module chain for a given identifier.
 * The identifier can be specified using several formats:
 * <ul>
 * <li>FunctionLogicalName</li>
 * <li>ModuleSerialNumber.FunctionIdentifier</li>
 * <li>ModuleSerialNumber.FunctionLogicalName</li>
 * <li>ModuleLogicalName.FunctionIdentifier</li>
 * <li>ModuleLogicalName.FunctionLogicalName</li>
 * </ul>
 *
 * This function does not require that the module chain is online at the time
 * it is invoked. The returned object is nevertheless valid.
 * Use the method isOnline() to test if the module chain is
 * indeed online at a given time. In case of ambiguity when looking for
 * a module chain by logical name, no error is notified: the first instance
 * found is returned. The search is performed first by hardware name,
 * then by logical name.
 *
 * If a call to this object's is_online() method returns FALSE although
 * you are certain that the matching device is plugged, make sure that you did
 * call registerHub() at application initialization time.
 *
 * @param string $func : a string that uniquely characterizes the module chain, for instance
 *         MyDevice.daisyChain.
 *
 * @return YDaisyChain : a YDaisyChain object allowing you to drive the module chain.
 */
function yFindDaisyChain($func)
{
    return YDaisyChain::FindDaisyChain($func);
}

/**
 * Starts the enumeration of module chains currently accessible.
 * Use the method YDaisyChain::nextDaisyChain() to iterate on
 * next module chains.
 *
 * @return YDaisyChain : a pointer to a YDaisyChain object, corresponding to
 *         the first module chain currently online, or a null pointer
 *         if there are none.
 */
function yFirstDaisyChain()
{
    return YDaisyChain::FirstDaisyChain();
}

//--- (end of YDaisyChain functions)
?>