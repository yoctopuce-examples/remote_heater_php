<?php
/*********************************************************************
 *
 *  $Id: yocto_multiaxiscontroller.php 43580 2021-01-26 17:46:01Z mvuilleu $
 *
 *  Implements YMultiAxisController, the high-level API for MultiAxisController functions
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

//--- (YMultiAxisController return codes)
//--- (end of YMultiAxisController return codes)
//--- (YMultiAxisController definitions)
if(!defined('Y_GLOBALSTATE_ABSENT'))         define('Y_GLOBALSTATE_ABSENT',        0);
if(!defined('Y_GLOBALSTATE_ALERT'))          define('Y_GLOBALSTATE_ALERT',         1);
if(!defined('Y_GLOBALSTATE_HI_Z'))           define('Y_GLOBALSTATE_HI_Z',          2);
if(!defined('Y_GLOBALSTATE_STOP'))           define('Y_GLOBALSTATE_STOP',          3);
if(!defined('Y_GLOBALSTATE_RUN'))            define('Y_GLOBALSTATE_RUN',           4);
if(!defined('Y_GLOBALSTATE_BATCH'))          define('Y_GLOBALSTATE_BATCH',         5);
if(!defined('Y_GLOBALSTATE_INVALID'))        define('Y_GLOBALSTATE_INVALID',       -1);
if(!defined('Y_NAXIS_INVALID'))              define('Y_NAXIS_INVALID',             YAPI_INVALID_UINT);
if(!defined('Y_COMMAND_INVALID'))            define('Y_COMMAND_INVALID',           YAPI_INVALID_STRING);
//--- (end of YMultiAxisController definitions)
    #--- (YMultiAxisController yapiwrapper)
   #--- (end of YMultiAxisController yapiwrapper)

//--- (YMultiAxisController declaration)
/**
 * YMultiAxisController Class: MultiAxisController function interface
 *
 * The YMultiAxisController class allows you to drive multiple stepper motors
 * synchronously.
 */
class YMultiAxisController extends YFunction
{
    const NAXIS_INVALID                  = YAPI_INVALID_UINT;
    const GLOBALSTATE_ABSENT             = 0;
    const GLOBALSTATE_ALERT              = 1;
    const GLOBALSTATE_HI_Z               = 2;
    const GLOBALSTATE_STOP               = 3;
    const GLOBALSTATE_RUN                = 4;
    const GLOBALSTATE_BATCH              = 5;
    const GLOBALSTATE_INVALID            = -1;
    const COMMAND_INVALID                = YAPI_INVALID_STRING;
    //--- (end of YMultiAxisController declaration)

    //--- (YMultiAxisController attributes)
    protected $_nAxis                    = Y_NAXIS_INVALID;              // UInt31
    protected $_globalState              = Y_GLOBALSTATE_INVALID;        // StepperState
    protected $_command                  = Y_COMMAND_INVALID;            // Text
    //--- (end of YMultiAxisController attributes)

    function __construct($str_func)
    {
        //--- (YMultiAxisController constructor)
        parent::__construct($str_func);
        $this->_className = 'MultiAxisController';

        //--- (end of YMultiAxisController constructor)
    }

    //--- (YMultiAxisController implementation)

    function _parseAttr($name, $val)
    {
        switch($name) {
        case 'nAxis':
            $this->_nAxis = intval($val);
            return 1;
        case 'globalState':
            $this->_globalState = intval($val);
            return 1;
        case 'command':
            $this->_command = $val;
            return 1;
        }
        return parent::_parseAttr($name, $val);
    }

    /**
     * Returns the number of synchronized controllers.
     *
     * @return integer : an integer corresponding to the number of synchronized controllers
     *
     * On failure, throws an exception or returns YMultiAxisController::NAXIS_INVALID.
     */
    public function get_nAxis()
    {
        // $res                    is a int;
        if ($this->_cacheExpiration <= YAPI::GetTickCount()) {
            if ($this->load(YAPI::$_yapiContext->GetCacheValidity()) != YAPI_SUCCESS) {
                return Y_NAXIS_INVALID;
            }
        }
        $res = $this->_nAxis;
        return $res;
    }

    /**
     * Changes the number of synchronized controllers.
     *
     * @param integer $newval : an integer corresponding to the number of synchronized controllers
     *
     * @return integer : YAPI::SUCCESS if the call succeeds.
     *
     * On failure, throws an exception or returns a negative error code.
     */
    public function set_nAxis($newval)
    {
        $rest_val = strval($newval);
        return $this->_setAttr("nAxis",$rest_val);
    }

    /**
     * Returns the stepper motor set overall state.
     *
     * @return integer : a value among YMultiAxisController::GLOBALSTATE_ABSENT,
     * YMultiAxisController::GLOBALSTATE_ALERT, YMultiAxisController::GLOBALSTATE_HI_Z,
     * YMultiAxisController::GLOBALSTATE_STOP, YMultiAxisController::GLOBALSTATE_RUN and
     * YMultiAxisController::GLOBALSTATE_BATCH corresponding to the stepper motor set overall state
     *
     * On failure, throws an exception or returns YMultiAxisController::GLOBALSTATE_INVALID.
     */
    public function get_globalState()
    {
        // $res                    is a enumSTEPPERSTATE;
        if ($this->_cacheExpiration <= YAPI::GetTickCount()) {
            if ($this->load(YAPI::$_yapiContext->GetCacheValidity()) != YAPI_SUCCESS) {
                return Y_GLOBALSTATE_INVALID;
            }
        }
        $res = $this->_globalState;
        return $res;
    }

    public function get_command()
    {
        // $res                    is a string;
        if ($this->_cacheExpiration <= YAPI::GetTickCount()) {
            if ($this->load(YAPI::$_yapiContext->GetCacheValidity()) != YAPI_SUCCESS) {
                return Y_COMMAND_INVALID;
            }
        }
        $res = $this->_command;
        return $res;
    }

    public function set_command($newval)
    {
        $rest_val = $newval;
        return $this->_setAttr("command",$rest_val);
    }

    /**
     * Retrieves a multi-axis controller for a given identifier.
     * The identifier can be specified using several formats:
     * <ul>
     * <li>FunctionLogicalName</li>
     * <li>ModuleSerialNumber.FunctionIdentifier</li>
     * <li>ModuleSerialNumber.FunctionLogicalName</li>
     * <li>ModuleLogicalName.FunctionIdentifier</li>
     * <li>ModuleLogicalName.FunctionLogicalName</li>
     * </ul>
     *
     * This function does not require that the multi-axis controller is online at the time
     * it is invoked. The returned object is nevertheless valid.
     * Use the method isOnline() to test if the multi-axis controller is
     * indeed online at a given time. In case of ambiguity when looking for
     * a multi-axis controller by logical name, no error is notified: the first instance
     * found is returned. The search is performed first by hardware name,
     * then by logical name.
     *
     * If a call to this object's is_online() method returns FALSE although
     * you are certain that the matching device is plugged, make sure that you did
     * call registerHub() at application initialization time.
     *
     * @param string $func : a string that uniquely characterizes the multi-axis controller, for instance
     *         MyDevice.multiAxisController.
     *
     * @return YMultiAxisController : a YMultiAxisController object allowing you to drive the multi-axis controller.
     */
    public static function FindMultiAxisController($func)
    {
        // $obj                    is a YMultiAxisController;
        $obj = YFunction::_FindFromCache('MultiAxisController', $func);
        if ($obj == null) {
            $obj = new YMultiAxisController($func);
            YFunction::_AddToCache('MultiAxisController', $func, $obj);
        }
        return $obj;
    }

    public function sendCommand($command)
    {
        // $url                    is a str;
        // $retBin                 is a bin;
        // $res                    is a int;
        $url = sprintf('cmd.txt?X=%s', $command);
        //may throw an exception
        $retBin = $this->_download($url);
        $res = ord($retBin[0]);
        if ($res < 58) {
            if (!($res == 48)) return $this->_throw( YAPI_DEVICE_BUSY, 'Motor command pipeline is full, try again later',YAPI_DEVICE_BUSY);
        } else {
            if (!($res == 48)) return $this->_throw( YAPI_IO_ERROR, 'Motor command failed permanently',YAPI_IO_ERROR);
        }
        return YAPI_SUCCESS;
    }

    /**
     * Reinitialize all controllers and clear all alert flags.
     *
     * @return integer : YAPI::SUCCESS if the call succeeds.
     *         On failure, throws an exception or returns a negative error code.
     */
    public function reset()
    {
        return $this->set_command('Z');
    }

    /**
     * Starts all motors backward at the specified speeds, to search for the motor home position.
     *
     * @param double[] $speed : desired speed for all axis, in steps per second.
     *
     * @return integer : YAPI::SUCCESS if the call succeeds.
     *         On failure, throws an exception or returns a negative error code.
     */
    public function findHomePosition($speed)
    {
        // $cmd                    is a str;
        // $i                      is a int;
        // $ndim                   is a int;
        $ndim = sizeof($speed);
        $cmd = sprintf('H%d', round(1000*$speed[0]));
        $i = 1;
        while ($i < $ndim) {
            $cmd = sprintf('%s,%d', $cmd, round(1000*$speed[$i]));
            $i = $i + 1;
        }
        return $this->sendCommand($cmd);
    }

    /**
     * Starts all motors synchronously to reach a given absolute position.
     * The time needed to reach the requested position will depend on the lowest
     * acceleration and max speed parameters configured for all motors.
     * The final position will be reached on all axis at the same time.
     *
     * @param double[] $absPos : absolute position, measured in steps from each origin.
     *
     * @return integer : YAPI::SUCCESS if the call succeeds.
     *         On failure, throws an exception or returns a negative error code.
     */
    public function moveTo($absPos)
    {
        // $cmd                    is a str;
        // $i                      is a int;
        // $ndim                   is a int;
        $ndim = sizeof($absPos);
        $cmd = sprintf('M%d', round(16*$absPos[0]));
        $i = 1;
        while ($i < $ndim) {
            $cmd = sprintf('%s,%d', $cmd, round(16*$absPos[$i]));
            $i = $i + 1;
        }
        return $this->sendCommand($cmd);
    }

    /**
     * Starts all motors synchronously to reach a given relative position.
     * The time needed to reach the requested position will depend on the lowest
     * acceleration and max speed parameters configured for all motors.
     * The final position will be reached on all axis at the same time.
     *
     * @param double[] $relPos : relative position, measured in steps from the current position.
     *
     * @return integer : YAPI::SUCCESS if the call succeeds.
     *         On failure, throws an exception or returns a negative error code.
     */
    public function moveRel($relPos)
    {
        // $cmd                    is a str;
        // $i                      is a int;
        // $ndim                   is a int;
        $ndim = sizeof($relPos);
        $cmd = sprintf('m%d', round(16*$relPos[0]));
        $i = 1;
        while ($i < $ndim) {
            $cmd = sprintf('%s,%d', $cmd, round(16*$relPos[$i]));
            $i = $i + 1;
        }
        return $this->sendCommand($cmd);
    }

    /**
     * Keep the motor in the same state for the specified amount of time, before processing next command.
     *
     * @param integer $waitMs : wait time, specified in milliseconds.
     *
     * @return integer : YAPI::SUCCESS if the call succeeds.
     *         On failure, throws an exception or returns a negative error code.
     */
    public function pause($waitMs)
    {
        return $this->sendCommand(sprintf('_%d',$waitMs));
    }

    /**
     * Stops the motor with an emergency alert, without taking any additional precaution.
     *
     * @return integer : YAPI::SUCCESS if the call succeeds.
     *         On failure, throws an exception or returns a negative error code.
     */
    public function emergencyStop()
    {
        return $this->set_command('!');
    }

    /**
     * Stops the motor smoothly as soon as possible, without waiting for ongoing move completion.
     *
     * @return integer : YAPI::SUCCESS if the call succeeds.
     *         On failure, throws an exception or returns a negative error code.
     */
    public function abortAndBrake()
    {
        return $this->set_command('B');
    }

    /**
     * Turn the controller into Hi-Z mode immediately, without waiting for ongoing move completion.
     *
     * @return integer : YAPI::SUCCESS if the call succeeds.
     *         On failure, throws an exception or returns a negative error code.
     */
    public function abortAndHiZ()
    {
        return $this->set_command('z');
    }

    public function nAxis()
    { return $this->get_nAxis(); }

    public function setNAxis($newval)
    { return $this->set_nAxis($newval); }

    public function globalState()
    { return $this->get_globalState(); }

    public function command()
    { return $this->get_command(); }

    public function setCommand($newval)
    { return $this->set_command($newval); }

    /**
     * Continues the enumeration of multi-axis controllers started using yFirstMultiAxisController().
     * Caution: You can't make any assumption about the returned multi-axis controllers order.
     * If you want to find a specific a multi-axis controller, use MultiAxisController.findMultiAxisController()
     * and a hardwareID or a logical name.
     *
     * @return YMultiAxisController : a pointer to a YMultiAxisController object, corresponding to
     *         a multi-axis controller currently online, or a null pointer
     *         if there are no more multi-axis controllers to enumerate.
     */
    public function nextMultiAxisController()
    {   $resolve = YAPI::resolveFunction($this->_className, $this->_func);
        if($resolve->errorType != YAPI_SUCCESS) return null;
        $next_hwid = YAPI::getNextHardwareId($this->_className, $resolve->result);
        if($next_hwid == null) return null;
        return self::FindMultiAxisController($next_hwid);
    }

    /**
     * Starts the enumeration of multi-axis controllers currently accessible.
     * Use the method YMultiAxisController::nextMultiAxisController() to iterate on
     * next multi-axis controllers.
     *
     * @return YMultiAxisController : a pointer to a YMultiAxisController object, corresponding to
     *         the first multi-axis controller currently online, or a null pointer
     *         if there are none.
     */
    public static function FirstMultiAxisController()
    {   $next_hwid = YAPI::getFirstHardwareId('MultiAxisController');
        if($next_hwid == null) return null;
        return self::FindMultiAxisController($next_hwid);
    }

    //--- (end of YMultiAxisController implementation)

};

//--- (YMultiAxisController functions)

/**
 * Retrieves a multi-axis controller for a given identifier.
 * The identifier can be specified using several formats:
 * <ul>
 * <li>FunctionLogicalName</li>
 * <li>ModuleSerialNumber.FunctionIdentifier</li>
 * <li>ModuleSerialNumber.FunctionLogicalName</li>
 * <li>ModuleLogicalName.FunctionIdentifier</li>
 * <li>ModuleLogicalName.FunctionLogicalName</li>
 * </ul>
 *
 * This function does not require that the multi-axis controller is online at the time
 * it is invoked. The returned object is nevertheless valid.
 * Use the method isOnline() to test if the multi-axis controller is
 * indeed online at a given time. In case of ambiguity when looking for
 * a multi-axis controller by logical name, no error is notified: the first instance
 * found is returned. The search is performed first by hardware name,
 * then by logical name.
 *
 * If a call to this object's is_online() method returns FALSE although
 * you are certain that the matching device is plugged, make sure that you did
 * call registerHub() at application initialization time.
 *
 * @param string $func : a string that uniquely characterizes the multi-axis controller, for instance
 *         MyDevice.multiAxisController.
 *
 * @return YMultiAxisController : a YMultiAxisController object allowing you to drive the multi-axis controller.
 */
function yFindMultiAxisController($func)
{
    return YMultiAxisController::FindMultiAxisController($func);
}

/**
 * Starts the enumeration of multi-axis controllers currently accessible.
 * Use the method YMultiAxisController::nextMultiAxisController() to iterate on
 * next multi-axis controllers.
 *
 * @return YMultiAxisController : a pointer to a YMultiAxisController object, corresponding to
 *         the first multi-axis controller currently online, or a null pointer
 *         if there are none.
 */
function yFirstMultiAxisController()
{
    return YMultiAxisController::FirstMultiAxisController();
}

//--- (end of YMultiAxisController functions)
?>