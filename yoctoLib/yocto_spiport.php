<?php
/*********************************************************************
 *
 * $Id: yocto_spiport.php 24252 2016-04-26 13:39:30Z seb $
 *
 * Implements YSpiPort, the high-level API for SpiPort functions
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

//--- (YSpiPort return codes)
//--- (end of YSpiPort return codes)
//--- (YSpiPort definitions)
if(!defined('Y_VOLTAGELEVEL_OFF'))           define('Y_VOLTAGELEVEL_OFF',          0);
if(!defined('Y_VOLTAGELEVEL_TTL3V'))         define('Y_VOLTAGELEVEL_TTL3V',        1);
if(!defined('Y_VOLTAGELEVEL_TTL3VR'))        define('Y_VOLTAGELEVEL_TTL3VR',       2);
if(!defined('Y_VOLTAGELEVEL_TTL5V'))         define('Y_VOLTAGELEVEL_TTL5V',        3);
if(!defined('Y_VOLTAGELEVEL_TTL5VR'))        define('Y_VOLTAGELEVEL_TTL5VR',       4);
if(!defined('Y_VOLTAGELEVEL_RS232'))         define('Y_VOLTAGELEVEL_RS232',        5);
if(!defined('Y_VOLTAGELEVEL_RS485'))         define('Y_VOLTAGELEVEL_RS485',        6);
if(!defined('Y_VOLTAGELEVEL_INVALID'))       define('Y_VOLTAGELEVEL_INVALID',      -1);
if(!defined('Y_SSPOLARITY_ACTIVE_LOW'))      define('Y_SSPOLARITY_ACTIVE_LOW',     0);
if(!defined('Y_SSPOLARITY_ACTIVE_HIGH'))     define('Y_SSPOLARITY_ACTIVE_HIGH',    1);
if(!defined('Y_SSPOLARITY_INVALID'))         define('Y_SSPOLARITY_INVALID',        -1);
if(!defined('Y_SHITFTSAMPLING_OFF'))         define('Y_SHITFTSAMPLING_OFF',        0);
if(!defined('Y_SHITFTSAMPLING_ON'))          define('Y_SHITFTSAMPLING_ON',         1);
if(!defined('Y_SHITFTSAMPLING_INVALID'))     define('Y_SHITFTSAMPLING_INVALID',    -1);
if(!defined('Y_RXCOUNT_INVALID'))            define('Y_RXCOUNT_INVALID',           YAPI_INVALID_UINT);
if(!defined('Y_TXCOUNT_INVALID'))            define('Y_TXCOUNT_INVALID',           YAPI_INVALID_UINT);
if(!defined('Y_ERRCOUNT_INVALID'))           define('Y_ERRCOUNT_INVALID',          YAPI_INVALID_UINT);
if(!defined('Y_RXMSGCOUNT_INVALID'))         define('Y_RXMSGCOUNT_INVALID',        YAPI_INVALID_UINT);
if(!defined('Y_TXMSGCOUNT_INVALID'))         define('Y_TXMSGCOUNT_INVALID',        YAPI_INVALID_UINT);
if(!defined('Y_LASTMSG_INVALID'))            define('Y_LASTMSG_INVALID',           YAPI_INVALID_STRING);
if(!defined('Y_CURRENTJOB_INVALID'))         define('Y_CURRENTJOB_INVALID',        YAPI_INVALID_STRING);
if(!defined('Y_STARTUPJOB_INVALID'))         define('Y_STARTUPJOB_INVALID',        YAPI_INVALID_STRING);
if(!defined('Y_COMMAND_INVALID'))            define('Y_COMMAND_INVALID',           YAPI_INVALID_STRING);
if(!defined('Y_PROTOCOL_INVALID'))           define('Y_PROTOCOL_INVALID',          YAPI_INVALID_STRING);
if(!defined('Y_SPIMODE_INVALID'))            define('Y_SPIMODE_INVALID',           YAPI_INVALID_STRING);
//--- (end of YSpiPort definitions)

//--- (YSpiPort declaration)
/**
 * YSpiPort Class: SPI Port function interface
 *
 * The SpiPort function interface allows you to fully drive a Yoctopuce
 * SPI port, to send and receive data, and to configure communication
 * parameters (baud rate, bit count, parity, flow control and protocol).
 * Note that Yoctopuce SPI ports are not exposed as virtual COM ports.
 * They are meant to be used in the same way as all Yoctopuce devices.
 */
class YSpiPort extends YFunction
{
    const RXCOUNT_INVALID                = YAPI_INVALID_UINT;
    const TXCOUNT_INVALID                = YAPI_INVALID_UINT;
    const ERRCOUNT_INVALID               = YAPI_INVALID_UINT;
    const RXMSGCOUNT_INVALID             = YAPI_INVALID_UINT;
    const TXMSGCOUNT_INVALID             = YAPI_INVALID_UINT;
    const LASTMSG_INVALID                = YAPI_INVALID_STRING;
    const CURRENTJOB_INVALID             = YAPI_INVALID_STRING;
    const STARTUPJOB_INVALID             = YAPI_INVALID_STRING;
    const COMMAND_INVALID                = YAPI_INVALID_STRING;
    const VOLTAGELEVEL_OFF               = 0;
    const VOLTAGELEVEL_TTL3V             = 1;
    const VOLTAGELEVEL_TTL3VR            = 2;
    const VOLTAGELEVEL_TTL5V             = 3;
    const VOLTAGELEVEL_TTL5VR            = 4;
    const VOLTAGELEVEL_RS232             = 5;
    const VOLTAGELEVEL_RS485             = 6;
    const VOLTAGELEVEL_INVALID           = -1;
    const PROTOCOL_INVALID               = YAPI_INVALID_STRING;
    const SPIMODE_INVALID                = YAPI_INVALID_STRING;
    const SSPOLARITY_ACTIVE_LOW          = 0;
    const SSPOLARITY_ACTIVE_HIGH         = 1;
    const SSPOLARITY_INVALID             = -1;
    const SHITFTSAMPLING_OFF             = 0;
    const SHITFTSAMPLING_ON              = 1;
    const SHITFTSAMPLING_INVALID         = -1;
    //--- (end of YSpiPort declaration)

    //--- (YSpiPort attributes)
    protected $_rxCount                  = Y_RXCOUNT_INVALID;            // UInt31
    protected $_txCount                  = Y_TXCOUNT_INVALID;            // UInt31
    protected $_errCount                 = Y_ERRCOUNT_INVALID;           // UInt31
    protected $_rxMsgCount               = Y_RXMSGCOUNT_INVALID;         // UInt31
    protected $_txMsgCount               = Y_TXMSGCOUNT_INVALID;         // UInt31
    protected $_lastMsg                  = Y_LASTMSG_INVALID;            // Text
    protected $_currentJob               = Y_CURRENTJOB_INVALID;         // Text
    protected $_startupJob               = Y_STARTUPJOB_INVALID;         // Text
    protected $_command                  = Y_COMMAND_INVALID;            // Text
    protected $_voltageLevel             = Y_VOLTAGELEVEL_INVALID;       // SerialVoltageLevel
    protected $_protocol                 = Y_PROTOCOL_INVALID;           // Protocol
    protected $_spiMode                  = Y_SPIMODE_INVALID;            // SpiMode
    protected $_ssPolarity               = Y_SSPOLARITY_INVALID;         // Polarity
    protected $_shitftSampling           = Y_SHITFTSAMPLING_INVALID;     // OnOff
    protected $_rxptr                    = 0;                            // int
    //--- (end of YSpiPort attributes)

    function __construct($str_func)
    {
        //--- (YSpiPort constructor)
        parent::__construct($str_func);
        $this->_className = 'SpiPort';

        //--- (end of YSpiPort constructor)
    }

    //--- (YSpiPort implementation)

    function _parseAttr($name, $val)
    {
        switch($name) {
        case 'rxCount':
            $this->_rxCount = intval($val);
            return 1;
        case 'txCount':
            $this->_txCount = intval($val);
            return 1;
        case 'errCount':
            $this->_errCount = intval($val);
            return 1;
        case 'rxMsgCount':
            $this->_rxMsgCount = intval($val);
            return 1;
        case 'txMsgCount':
            $this->_txMsgCount = intval($val);
            return 1;
        case 'lastMsg':
            $this->_lastMsg = $val;
            return 1;
        case 'currentJob':
            $this->_currentJob = $val;
            return 1;
        case 'startupJob':
            $this->_startupJob = $val;
            return 1;
        case 'command':
            $this->_command = $val;
            return 1;
        case 'voltageLevel':
            $this->_voltageLevel = intval($val);
            return 1;
        case 'protocol':
            $this->_protocol = $val;
            return 1;
        case 'spiMode':
            $this->_spiMode = $val;
            return 1;
        case 'ssPolarity':
            $this->_ssPolarity = intval($val);
            return 1;
        case 'shitftSampling':
            $this->_shitftSampling = intval($val);
            return 1;
        }
        return parent::_parseAttr($name, $val);
    }

    /**
     * Returns the total number of bytes received since last reset.
     *
     * @return an integer corresponding to the total number of bytes received since last reset
     *
     * On failure, throws an exception or returns Y_RXCOUNT_INVALID.
     */
    public function get_rxCount()
    {
        if ($this->_cacheExpiration <= YAPI::GetTickCount()) {
            if ($this->load(YAPI::$defaultCacheValidity) != YAPI_SUCCESS) {
                return Y_RXCOUNT_INVALID;
            }
        }
        return $this->_rxCount;
    }

    /**
     * Returns the total number of bytes transmitted since last reset.
     *
     * @return an integer corresponding to the total number of bytes transmitted since last reset
     *
     * On failure, throws an exception or returns Y_TXCOUNT_INVALID.
     */
    public function get_txCount()
    {
        if ($this->_cacheExpiration <= YAPI::GetTickCount()) {
            if ($this->load(YAPI::$defaultCacheValidity) != YAPI_SUCCESS) {
                return Y_TXCOUNT_INVALID;
            }
        }
        return $this->_txCount;
    }

    /**
     * Returns the total number of communication errors detected since last reset.
     *
     * @return an integer corresponding to the total number of communication errors detected since last reset
     *
     * On failure, throws an exception or returns Y_ERRCOUNT_INVALID.
     */
    public function get_errCount()
    {
        if ($this->_cacheExpiration <= YAPI::GetTickCount()) {
            if ($this->load(YAPI::$defaultCacheValidity) != YAPI_SUCCESS) {
                return Y_ERRCOUNT_INVALID;
            }
        }
        return $this->_errCount;
    }

    /**
     * Returns the total number of messages received since last reset.
     *
     * @return an integer corresponding to the total number of messages received since last reset
     *
     * On failure, throws an exception or returns Y_RXMSGCOUNT_INVALID.
     */
    public function get_rxMsgCount()
    {
        if ($this->_cacheExpiration <= YAPI::GetTickCount()) {
            if ($this->load(YAPI::$defaultCacheValidity) != YAPI_SUCCESS) {
                return Y_RXMSGCOUNT_INVALID;
            }
        }
        return $this->_rxMsgCount;
    }

    /**
     * Returns the total number of messages send since last reset.
     *
     * @return an integer corresponding to the total number of messages send since last reset
     *
     * On failure, throws an exception or returns Y_TXMSGCOUNT_INVALID.
     */
    public function get_txMsgCount()
    {
        if ($this->_cacheExpiration <= YAPI::GetTickCount()) {
            if ($this->load(YAPI::$defaultCacheValidity) != YAPI_SUCCESS) {
                return Y_TXMSGCOUNT_INVALID;
            }
        }
        return $this->_txMsgCount;
    }

    /**
     * Returns the latest message fully received (for Line and Frame protocols).
     *
     * @return a string corresponding to the latest message fully received (for Line and Frame protocols)
     *
     * On failure, throws an exception or returns Y_LASTMSG_INVALID.
     */
    public function get_lastMsg()
    {
        if ($this->_cacheExpiration <= YAPI::GetTickCount()) {
            if ($this->load(YAPI::$defaultCacheValidity) != YAPI_SUCCESS) {
                return Y_LASTMSG_INVALID;
            }
        }
        return $this->_lastMsg;
    }

    /**
     * Returns the name of the job file currently in use.
     *
     * @return a string corresponding to the name of the job file currently in use
     *
     * On failure, throws an exception or returns Y_CURRENTJOB_INVALID.
     */
    public function get_currentJob()
    {
        if ($this->_cacheExpiration <= YAPI::GetTickCount()) {
            if ($this->load(YAPI::$defaultCacheValidity) != YAPI_SUCCESS) {
                return Y_CURRENTJOB_INVALID;
            }
        }
        return $this->_currentJob;
    }

    /**
     * Changes the job to use when the device is powered on.
     * Remember to call the saveToFlash() method of the module if the
     * modification must be kept.
     *
     * @param newval : a string corresponding to the job to use when the device is powered on
     *
     * @return YAPI_SUCCESS if the call succeeds.
     *
     * On failure, throws an exception or returns a negative error code.
     */
    public function set_currentJob($newval)
    {
        $rest_val = $newval;
        return $this->_setAttr("currentJob",$rest_val);
    }

    /**
     * Returns the job file to use when the device is powered on.
     *
     * @return a string corresponding to the job file to use when the device is powered on
     *
     * On failure, throws an exception or returns Y_STARTUPJOB_INVALID.
     */
    public function get_startupJob()
    {
        if ($this->_cacheExpiration <= YAPI::GetTickCount()) {
            if ($this->load(YAPI::$defaultCacheValidity) != YAPI_SUCCESS) {
                return Y_STARTUPJOB_INVALID;
            }
        }
        return $this->_startupJob;
    }

    /**
     * Changes the job to use when the device is powered on.
     * Remember to call the saveToFlash() method of the module if the
     * modification must be kept.
     *
     * @param newval : a string corresponding to the job to use when the device is powered on
     *
     * @return YAPI_SUCCESS if the call succeeds.
     *
     * On failure, throws an exception or returns a negative error code.
     */
    public function set_startupJob($newval)
    {
        $rest_val = $newval;
        return $this->_setAttr("startupJob",$rest_val);
    }

    public function get_command()
    {
        if ($this->_cacheExpiration <= YAPI::GetTickCount()) {
            if ($this->load(YAPI::$defaultCacheValidity) != YAPI_SUCCESS) {
                return Y_COMMAND_INVALID;
            }
        }
        return $this->_command;
    }

    public function set_command($newval)
    {
        $rest_val = $newval;
        return $this->_setAttr("command",$rest_val);
    }

    /**
     * Returns the voltage level used on the serial line.
     *
     * @return a value among Y_VOLTAGELEVEL_OFF, Y_VOLTAGELEVEL_TTL3V, Y_VOLTAGELEVEL_TTL3VR,
     * Y_VOLTAGELEVEL_TTL5V, Y_VOLTAGELEVEL_TTL5VR, Y_VOLTAGELEVEL_RS232 and Y_VOLTAGELEVEL_RS485
     * corresponding to the voltage level used on the serial line
     *
     * On failure, throws an exception or returns Y_VOLTAGELEVEL_INVALID.
     */
    public function get_voltageLevel()
    {
        if ($this->_cacheExpiration <= YAPI::GetTickCount()) {
            if ($this->load(YAPI::$defaultCacheValidity) != YAPI_SUCCESS) {
                return Y_VOLTAGELEVEL_INVALID;
            }
        }
        return $this->_voltageLevel;
    }

    /**
     * Changes the voltage type used on the serial line. Valid
     * values  will depend on the Yoctopuce device model featuring
     * the serial port feature.  Check your device documentation
     * to find out which values are valid for that specific model.
     * Trying to set an invalid value will have no effect.
     *
     * @param newval : a value among Y_VOLTAGELEVEL_OFF, Y_VOLTAGELEVEL_TTL3V, Y_VOLTAGELEVEL_TTL3VR,
     * Y_VOLTAGELEVEL_TTL5V, Y_VOLTAGELEVEL_TTL5VR, Y_VOLTAGELEVEL_RS232 and Y_VOLTAGELEVEL_RS485
     * corresponding to the voltage type used on the serial line
     *
     * @return YAPI_SUCCESS if the call succeeds.
     *
     * On failure, throws an exception or returns a negative error code.
     */
    public function set_voltageLevel($newval)
    {
        $rest_val = strval($newval);
        return $this->_setAttr("voltageLevel",$rest_val);
    }

    /**
     * Returns the type of protocol used over the serial line, as a string.
     * Possible values are "Line" for ASCII messages separated by CR and/or LF,
     * "Frame:[timeout]ms" for binary messages separated by a delay time,
     * "Char" for a continuous ASCII stream or
     * "Byte" for a continuous binary stream.
     *
     * @return a string corresponding to the type of protocol used over the serial line, as a string
     *
     * On failure, throws an exception or returns Y_PROTOCOL_INVALID.
     */
    public function get_protocol()
    {
        if ($this->_cacheExpiration <= YAPI::GetTickCount()) {
            if ($this->load(YAPI::$defaultCacheValidity) != YAPI_SUCCESS) {
                return Y_PROTOCOL_INVALID;
            }
        }
        return $this->_protocol;
    }

    /**
     * Changes the type of protocol used over the serial line.
     * Possible values are "Line" for ASCII messages separated by CR and/or LF,
     * "Frame:[timeout]ms" for binary messages separated by a delay time,
     * "Char" for a continuous ASCII stream or
     * "Byte" for a continuous binary stream.
     * The suffix "/[wait]ms" can be added to reduce the transmit rate so that there
     * is always at lest the specified number of milliseconds between each bytes sent.
     *
     * @param newval : a string corresponding to the type of protocol used over the serial line
     *
     * @return YAPI_SUCCESS if the call succeeds.
     *
     * On failure, throws an exception or returns a negative error code.
     */
    public function set_protocol($newval)
    {
        $rest_val = $newval;
        return $this->_setAttr("protocol",$rest_val);
    }

    /**
     * Returns the SPI port communication parameters, as a string such as
     * "125000,0,msb". The string includes the baud rate, the SPI mode (between
     * 0 and 3) and the bit order.
     *
     * @return a string corresponding to the SPI port communication parameters, as a string such as
     *         "125000,0,msb"
     *
     * On failure, throws an exception or returns Y_SPIMODE_INVALID.
     */
    public function get_spiMode()
    {
        if ($this->_cacheExpiration <= YAPI::GetTickCount()) {
            if ($this->load(YAPI::$defaultCacheValidity) != YAPI_SUCCESS) {
                return Y_SPIMODE_INVALID;
            }
        }
        return $this->_spiMode;
    }

    /**
     * Changes the SPI port communication parameters, with a string such as
     * "125000,0,msb". The string includes the baud rate, the SPI mode (between
     * 0 and 3) and the bit order.
     *
     * @param newval : a string corresponding to the SPI port communication parameters, with a string such as
     *         "125000,0,msb"
     *
     * @return YAPI_SUCCESS if the call succeeds.
     *
     * On failure, throws an exception or returns a negative error code.
     */
    public function set_spiMode($newval)
    {
        $rest_val = $newval;
        return $this->_setAttr("spiMode",$rest_val);
    }

    /**
     * Returns the SS line polarity.
     *
     * @return either Y_SSPOLARITY_ACTIVE_LOW or Y_SSPOLARITY_ACTIVE_HIGH, according to the SS line polarity
     *
     * On failure, throws an exception or returns Y_SSPOLARITY_INVALID.
     */
    public function get_ssPolarity()
    {
        if ($this->_cacheExpiration <= YAPI::GetTickCount()) {
            if ($this->load(YAPI::$defaultCacheValidity) != YAPI_SUCCESS) {
                return Y_SSPOLARITY_INVALID;
            }
        }
        return $this->_ssPolarity;
    }

    /**
     * Changes the SS line polarity.
     *
     * @param newval : either Y_SSPOLARITY_ACTIVE_LOW or Y_SSPOLARITY_ACTIVE_HIGH, according to the SS line polarity
     *
     * @return YAPI_SUCCESS if the call succeeds.
     *
     * On failure, throws an exception or returns a negative error code.
     */
    public function set_ssPolarity($newval)
    {
        $rest_val = strval($newval);
        return $this->_setAttr("ssPolarity",$rest_val);
    }

    /**
     * Returns true when the SDI line phase is shifted with regards to the SDO line.
     *
     * @return either Y_SHITFTSAMPLING_OFF or Y_SHITFTSAMPLING_ON, according to true when the SDI line
     * phase is shifted with regards to the SDO line
     *
     * On failure, throws an exception or returns Y_SHITFTSAMPLING_INVALID.
     */
    public function get_shitftSampling()
    {
        if ($this->_cacheExpiration <= YAPI::GetTickCount()) {
            if ($this->load(YAPI::$defaultCacheValidity) != YAPI_SUCCESS) {
                return Y_SHITFTSAMPLING_INVALID;
            }
        }
        return $this->_shitftSampling;
    }

    /**
     * Changes the SDI line sampling shift. When disabled, SDI line is
     * sampled in the middle of data output time. When enabled, SDI line is
     * samples at the end of data output time.
     *
     * @param newval : either Y_SHITFTSAMPLING_OFF or Y_SHITFTSAMPLING_ON, according to the SDI line sampling shift
     *
     * @return YAPI_SUCCESS if the call succeeds.
     *
     * On failure, throws an exception or returns a negative error code.
     */
    public function set_shitftSampling($newval)
    {
        $rest_val = strval($newval);
        return $this->_setAttr("shitftSampling",$rest_val);
    }

    /**
     * Retrieves a SPI port for a given identifier.
     * The identifier can be specified using several formats:
     * <ul>
     * <li>FunctionLogicalName</li>
     * <li>ModuleSerialNumber.FunctionIdentifier</li>
     * <li>ModuleSerialNumber.FunctionLogicalName</li>
     * <li>ModuleLogicalName.FunctionIdentifier</li>
     * <li>ModuleLogicalName.FunctionLogicalName</li>
     * </ul>
     *
     * This function does not require that the SPI port is online at the time
     * it is invoked. The returned object is nevertheless valid.
     * Use the method YSpiPort.isOnline() to test if the SPI port is
     * indeed online at a given time. In case of ambiguity when looking for
     * a SPI port by logical name, no error is notified: the first instance
     * found is returned. The search is performed first by hardware name,
     * then by logical name.
     *
     * @param func : a string that uniquely characterizes the SPI port
     *
     * @return a YSpiPort object allowing you to drive the SPI port.
     */
    public static function FindSpiPort($func)
    {
        // $obj                    is a YSpiPort;
        $obj = YFunction::_FindFromCache('SpiPort', $func);
        if ($obj == null) {
            $obj = new YSpiPort($func);
            YFunction::_AddToCache('SpiPort', $func, $obj);
        }
        return $obj;
    }

    public function sendCommand($text)
    {
        return $this->set_command($text);
    }

    /**
     * Clears the serial port buffer and resets counters to zero.
     *
     * @return YAPI_SUCCESS if the call succeeds.
     *
     * On failure, throws an exception or returns a negative error code.
     */
    public function reset()
    {
        $this->_rxptr = 0;
        // may throw an exception
        return $this->sendCommand('Z');
    }

    /**
     * Sends a single byte to the serial port.
     *
     * @param code : the byte to send
     *
     * @return YAPI_SUCCESS if the call succeeds.
     *
     * On failure, throws an exception or returns a negative error code.
     */
    public function writeByte($code)
    {
        return $this->sendCommand(sprintf('$%02x', $code));
    }

    /**
     * Sends an ASCII string to the serial port, as is.
     *
     * @param text : the text string to send
     *
     * @return YAPI_SUCCESS if the call succeeds.
     *
     * On failure, throws an exception or returns a negative error code.
     */
    public function writeStr($text)
    {
        // $buff                   is a bin;
        // $bufflen                is a int;
        // $idx                    is a int;
        // $ch                     is a int;
        $buff = $text;
        $bufflen = strlen($buff);
        if ($bufflen < 100) {
            $ch = 0x20;
            $idx = 0;
            while (($idx < $bufflen) && ($ch != 0)) {
                $ch = ord($buff[$idx]);
                if (($ch >= 0x20) && ($ch < 0x7f)) {
                    $idx = $idx + 1;
                } else {
                    $ch = 0;
                }
            }
            if ($idx >= $bufflen) {
                return $this->sendCommand(sprintf('+%s',$text));
            }
        }
        // send string using file upload
        return $this->_upload('txdata', $buff);
    }

    /**
     * Sends a binary buffer to the serial port, as is.
     *
     * @param buff : the binary buffer to send
     *
     * @return YAPI_SUCCESS if the call succeeds.
     *
     * On failure, throws an exception or returns a negative error code.
     */
    public function writeBin($buff)
    {
        return $this->_upload('txdata', $buff);
    }

    /**
     * Sends a byte sequence (provided as a list of bytes) to the serial port.
     *
     * @param byteList : a list of byte codes
     *
     * @return YAPI_SUCCESS if the call succeeds.
     *
     * On failure, throws an exception or returns a negative error code.
     */
    public function writeArray($byteList)
    {
        // $buff                   is a bin;
        // $bufflen                is a int;
        // $idx                    is a int;
        // $hexb                   is a int;
        // $res                    is a int;
        $bufflen = sizeof($byteList);
        $buff =  pack('C',array_fill(0, $bufflen, 0));
        $idx = 0;
        while ($idx < $bufflen) {
            $hexb = $byteList[$idx];
            $buff[$idx] = pack('C', $hexb);
            $idx = $idx + 1;
        }
        // may throw an exception
        $res = $this->_upload('txdata', $buff);
        return $res;
    }

    /**
     * Sends a byte sequence (provided as a hexadecimal string) to the serial port.
     *
     * @param hexString : a string of hexadecimal byte codes
     *
     * @return YAPI_SUCCESS if the call succeeds.
     *
     * On failure, throws an exception or returns a negative error code.
     */
    public function writeHex($hexString)
    {
        // $buff                   is a bin;
        // $bufflen                is a int;
        // $idx                    is a int;
        // $hexb                   is a int;
        // $res                    is a int;
        $bufflen = strlen($hexString);
        if ($bufflen < 100) {
            return $this->sendCommand(sprintf('$%s',$hexString));
        }
        $bufflen = (($bufflen) >> (1));
        $buff =  pack('C',array_fill(0, $bufflen, 0));
        $idx = 0;
        while ($idx < $bufflen) {
            $hexb = hexdec(substr($hexString,  2 * $idx, 2));
            $buff[$idx] = pack('C', $hexb);
            $idx = $idx + 1;
        }
        // may throw an exception
        $res = $this->_upload('txdata', $buff);
        return $res;
    }

    /**
     * Sends an ASCII string to the serial port, followed by a line break (CR LF).
     *
     * @param text : the text string to send
     *
     * @return YAPI_SUCCESS if the call succeeds.
     *
     * On failure, throws an exception or returns a negative error code.
     */
    public function writeLine($text)
    {
        // $buff                   is a bin;
        // $bufflen                is a int;
        // $idx                    is a int;
        // $ch                     is a int;
        $buff = sprintf('%s'."\r".''."\n".'', $text);
        $bufflen = strlen($buff)-2;
        if ($bufflen < 100) {
            $ch = 0x20;
            $idx = 0;
            while (($idx < $bufflen) && ($ch != 0)) {
                $ch = ord($buff[$idx]);
                if (($ch >= 0x20) && ($ch < 0x7f)) {
                    $idx = $idx + 1;
                } else {
                    $ch = 0;
                }
            }
            if ($idx >= $bufflen) {
                return $this->sendCommand(sprintf('!%s',$text));
            }
        }
        // send string using file upload
        return $this->_upload('txdata', $buff);
    }

    /**
     * Reads one byte from the receive buffer, starting at current stream position.
     * If data at current stream position is not available anymore in the receive buffer,
     * or if there is no data available yet, the function returns YAPI_NO_MORE_DATA.
     *
     * @return the next byte
     *
     * On failure, throws an exception or returns a negative error code.
     */
    public function readByte()
    {
        // $buff                   is a bin;
        // $bufflen                is a int;
        // $mult                   is a int;
        // $endpos                 is a int;
        // $res                    is a int;
        // may throw an exception
        $buff = $this->_download(sprintf('rxdata.bin?pos=%d&len=1', $this->_rxptr));
        $bufflen = strlen($buff) - 1;
        $endpos = 0;
        $mult = 1;
        while (($bufflen > 0) && (ord($buff[$bufflen]) != 64)) {
            $endpos = $endpos + $mult * (ord($buff[$bufflen]) - 48);
            $mult = $mult * 10;
            $bufflen = $bufflen - 1;
        }
        $this->_rxptr = $endpos;
        if ($bufflen == 0) {
            return YAPI_NO_MORE_DATA;
        }
        $res = ord($buff[0]);
        return $res;
    }

    /**
     * Reads data from the receive buffer as a string, starting at current stream position.
     * If data at current stream position is not available anymore in the receive buffer, the
     * function performs a short read.
     *
     * @param nChars : the maximum number of characters to read
     *
     * @return a string with receive buffer contents
     *
     * On failure, throws an exception or returns a negative error code.
     */
    public function readStr($nChars)
    {
        // $buff                   is a bin;
        // $bufflen                is a int;
        // $mult                   is a int;
        // $endpos                 is a int;
        // $res                    is a str;
        if ($nChars > 65535) {
            $nChars = 65535;
        }
        // may throw an exception
        $buff = $this->_download(sprintf('rxdata.bin?pos=%d&len=%d', $this->_rxptr, $nChars));
        $bufflen = strlen($buff) - 1;
        $endpos = 0;
        $mult = 1;
        while (($bufflen > 0) && (ord($buff[$bufflen]) != 64)) {
            $endpos = $endpos + $mult * (ord($buff[$bufflen]) - 48);
            $mult = $mult * 10;
            $bufflen = $bufflen - 1;
        }
        $this->_rxptr = $endpos;
        $res = substr($buff,  0, $bufflen);
        return $res;
    }

    /**
     * Reads data from the receive buffer as a binary buffer, starting at current stream position.
     * If data at current stream position is not available anymore in the receive buffer, the
     * function performs a short read.
     *
     * @param nChars : the maximum number of bytes to read
     *
     * @return a binary object with receive buffer contents
     *
     * On failure, throws an exception or returns a negative error code.
     */
    public function readBin($nChars)
    {
        // $buff                   is a bin;
        // $bufflen                is a int;
        // $mult                   is a int;
        // $endpos                 is a int;
        // $idx                    is a int;
        // $res                    is a bin;
        if ($nChars > 65535) {
            $nChars = 65535;
        }
        // may throw an exception
        $buff = $this->_download(sprintf('rxdata.bin?pos=%d&len=%d', $this->_rxptr, $nChars));
        $bufflen = strlen($buff) - 1;
        $endpos = 0;
        $mult = 1;
        while (($bufflen > 0) && (ord($buff[$bufflen]) != 64)) {
            $endpos = $endpos + $mult * (ord($buff[$bufflen]) - 48);
            $mult = $mult * 10;
            $bufflen = $bufflen - 1;
        }
        $this->_rxptr = $endpos;
        $res =  pack('C',array_fill(0, $bufflen, 0));
        $idx = 0;
        while ($idx < $bufflen) {
            $res[$idx] = pack('C', ord($buff[$idx]));
            $idx = $idx + 1;
        }
        return $res;
    }

    /**
     * Reads data from the receive buffer as a list of bytes, starting at current stream position.
     * If data at current stream position is not available anymore in the receive buffer, the
     * function performs a short read.
     *
     * @param nChars : the maximum number of bytes to read
     *
     * @return a sequence of bytes with receive buffer contents
     *
     * On failure, throws an exception or returns a negative error code.
     */
    public function readArray($nChars)
    {
        // $buff                   is a bin;
        // $bufflen                is a int;
        // $mult                   is a int;
        // $endpos                 is a int;
        // $idx                    is a int;
        // $b                      is a int;
        $res = Array();         // intArr;
        if ($nChars > 65535) {
            $nChars = 65535;
        }
        // may throw an exception
        $buff = $this->_download(sprintf('rxdata.bin?pos=%d&len=%d', $this->_rxptr, $nChars));
        $bufflen = strlen($buff) - 1;
        $endpos = 0;
        $mult = 1;
        while (($bufflen > 0) && (ord($buff[$bufflen]) != 64)) {
            $endpos = $endpos + $mult * (ord($buff[$bufflen]) - 48);
            $mult = $mult * 10;
            $bufflen = $bufflen - 1;
        }
        $this->_rxptr = $endpos;
        while(sizeof($res) > 0) { array_pop($res); };
        $idx = 0;
        while ($idx < $bufflen) {
            $b = ord($buff[$idx]);
            $res[] = $b;
            $idx = $idx + 1;
        }
        return $res;
    }

    /**
     * Reads data from the receive buffer as a hexadecimal string, starting at current stream position.
     * If data at current stream position is not available anymore in the receive buffer, the
     * function performs a short read.
     *
     * @param nBytes : the maximum number of bytes to read
     *
     * @return a string with receive buffer contents, encoded in hexadecimal
     *
     * On failure, throws an exception or returns a negative error code.
     */
    public function readHex($nBytes)
    {
        // $buff                   is a bin;
        // $bufflen                is a int;
        // $mult                   is a int;
        // $endpos                 is a int;
        // $ofs                    is a int;
        // $res                    is a str;
        if ($nBytes > 65535) {
            $nBytes = 65535;
        }
        // may throw an exception
        $buff = $this->_download(sprintf('rxdata.bin?pos=%d&len=%d', $this->_rxptr, $nBytes));
        $bufflen = strlen($buff) - 1;
        $endpos = 0;
        $mult = 1;
        while (($bufflen > 0) && (ord($buff[$bufflen]) != 64)) {
            $endpos = $endpos + $mult * (ord($buff[$bufflen]) - 48);
            $mult = $mult * 10;
            $bufflen = $bufflen - 1;
        }
        $this->_rxptr = $endpos;
        $res = '';
        $ofs = 0;
        while ($ofs + 3 < $bufflen) {
            $res = sprintf('%s%02x%02x%02x%02x', $res, ord($buff[$ofs]), ord($buff[$ofs + 1]), ord($buff[$ofs + 2]), ord($buff[$ofs + 3]));
            $ofs = $ofs + 4;
        }
        while ($ofs < $bufflen) {
            $res = sprintf('%s%02x', $res, ord($buff[$ofs]));
            $ofs = $ofs + 1;
        }
        return $res;
    }

    /**
     * Reads a single line (or message) from the receive buffer, starting at current stream position.
     * This function is intended to be used when the serial port is configured for a message protocol,
     * such as 'Line' mode or frame protocols.
     *
     * If data at current stream position is not available anymore in the receive buffer,
     * the function returns the oldest available line and moves the stream position just after.
     * If no new full line is received, the function returns an empty line.
     *
     * @return a string with a single line of text
     *
     * On failure, throws an exception or returns a negative error code.
     */
    public function readLine()
    {
        // $url                    is a str;
        // $msgbin                 is a bin;
        $msgarr = Array();      // strArr;
        // $msglen                 is a int;
        // $res                    is a str;
        // may throw an exception
        $url = sprintf('rxmsg.json?pos=%d&len=1&maxw=1', $this->_rxptr);
        $msgbin = $this->_download($url);
        $msgarr = $this->_json_get_array($msgbin);
        $msglen = sizeof($msgarr);
        if ($msglen == 0) {
            return '';
        }
        // last element of array is the new position
        $msglen = $msglen - 1;
        $this->_rxptr = intVal($msgarr[$msglen]);
        if ($msglen == 0) {
            return '';
        }
        $res = $this->_json_get_string($msgarr[0]);
        return $res;
    }

    /**
     * Searches for incoming messages in the serial port receive buffer matching a given pattern,
     * starting at current position. This function will only compare and return printable characters
     * in the message strings. Binary protocols are handled as hexadecimal strings.
     *
     * The search returns all messages matching the expression provided as argument in the buffer.
     * If no matching message is found, the search waits for one up to the specified maximum timeout
     * (in milliseconds).
     *
     * @param pattern : a limited regular expression describing the expected message format,
     *         or an empty string if all messages should be returned (no filtering).
     *         When using binary protocols, the format applies to the hexadecimal
     *         representation of the message.
     * @param maxWait : the maximum number of milliseconds to wait for a message if none is found
     *         in the receive buffer.
     *
     * @return an array of strings containing the messages found, if any.
     *         Binary messages are converted to hexadecimal representation.
     *
     * On failure, throws an exception or returns an empty array.
     */
    public function readMessages($pattern,$maxWait)
    {
        // $url                    is a str;
        // $msgbin                 is a bin;
        $msgarr = Array();      // strArr;
        // $msglen                 is a int;
        $res = Array();         // strArr;
        // $idx                    is a int;
        // may throw an exception
        $url = sprintf('rxmsg.json?pos=%d&maxw=%d&pat=%s', $this->_rxptr, $maxWait, $pattern);
        $msgbin = $this->_download($url);
        $msgarr = $this->_json_get_array($msgbin);
        $msglen = sizeof($msgarr);
        if ($msglen == 0) {
            return $res;
        }
        // last element of array is the new position
        $msglen = $msglen - 1;
        $this->_rxptr = intVal($msgarr[$msglen]);
        $idx = 0;
        while ($idx < $msglen) {
            $res[] = $this->_json_get_string($msgarr[$idx]);
            $idx = $idx + 1;
        }
        return $res;
    }

    /**
     * Changes the current internal stream position to the specified value. This function
     * does not affect the device, it only changes the value stored in the API object
     * for the next read operations.
     *
     * @param absPos : the absolute position index for next read operations.
     *
     * @return nothing.
     */
    public function read_seek($absPos)
    {
        $this->_rxptr = $absPos;
        return YAPI_SUCCESS;
    }

    /**
     * Returns the current absolute stream position pointer of the API object.
     *
     * @return the absolute position index for next read operations.
     */
    public function read_tell()
    {
        return $this->_rxptr;
    }

    /**
     * Returns the number of bytes available to read in the input buffer starting from the
     * current absolute stream position pointer of the API object.
     *
     * @return the number of bytes available to read
     */
    public function read_avail()
    {
        // $buff                   is a bin;
        // $bufflen                is a int;
        // $res                    is a int;
        // may throw an exception
        $buff = $this->_download(sprintf('rxcnt.bin?pos=%d', $this->_rxptr));
        $bufflen = strlen($buff) - 1;
        while (($bufflen > 0) && (ord($buff[$bufflen]) != 64)) {
            $bufflen = $bufflen - 1;
        }
        $res = intVal(substr($buff,  0, $bufflen));
        return $res;
    }

    /**
     * Sends a text line query to the serial port, and reads the reply, if any.
     * This function is intended to be used when the serial port is configured for 'Line' protocol.
     *
     * @param query : the line query to send (without CR/LF)
     * @param maxWait : the maximum number of milliseconds to wait for a reply.
     *
     * @return the next text line received after sending the text query, as a string.
     *         Additional lines can be obtained by calling readLine or readMessages.
     *
     * On failure, throws an exception or returns an empty array.
     */
    public function queryLine($query,$maxWait)
    {
        // $url                    is a str;
        // $msgbin                 is a bin;
        $msgarr = Array();      // strArr;
        // $msglen                 is a int;
        // $res                    is a str;
        // may throw an exception
        $url = sprintf('rxmsg.json?len=1&maxw=%d&cmd=!%s', $maxWait, $query);
        $msgbin = $this->_download($url);
        $msgarr = $this->_json_get_array($msgbin);
        $msglen = sizeof($msgarr);
        if ($msglen == 0) {
            return '';
        }
        // last element of array is the new position
        $msglen = $msglen - 1;
        $this->_rxptr = intVal($msgarr[$msglen]);
        if ($msglen == 0) {
            return '';
        }
        $res = $this->_json_get_string($msgarr[0]);
        return $res;
    }

    /**
     * Saves the job definition string (JSON data) into a job file.
     * The job file can be later enabled using selectJob().
     *
     * @param jobfile : name of the job file to save on the device filesystem
     * @param jsonDef : a string containing a JSON definition of the job
     *
     * @return YAPI_SUCCESS if the call succeeds.
     *
     * On failure, throws an exception or returns a negative error code.
     */
    public function uploadJob($jobfile,$jsonDef)
    {
        $this->_upload($jobfile, $jsonDef);
        return YAPI_SUCCESS;
    }

    /**
     * Load and start processing the specified job file. The file must have
     * been previously created using the user interface or uploaded on the
     * device filesystem using the uploadJob() function.
     *
     * @param jobfile : name of the job file (on the device filesystem)
     *
     * @return YAPI_SUCCESS if the call succeeds.
     *
     * On failure, throws an exception or returns a negative error code.
     */
    public function selectJob($jobfile)
    {
        return $this->set_currentJob($jobfile);
    }

    /**
     * Manually sets the state of the SS line. This function has no effect when
     * the SS line is handled automatically.
     *
     * @param val : 1 to turn SS active, 0 to release SS.
     *
     * @return YAPI_SUCCESS if the call succeeds.
     *
     * On failure, throws an exception or returns a negative error code.
     */
    public function set_SS($val)
    {
        return $this->sendCommand(sprintf('S%d',$val));
    }

    public function rxCount()
    { return $this->get_rxCount(); }

    public function txCount()
    { return $this->get_txCount(); }

    public function errCount()
    { return $this->get_errCount(); }

    public function rxMsgCount()
    { return $this->get_rxMsgCount(); }

    public function txMsgCount()
    { return $this->get_txMsgCount(); }

    public function lastMsg()
    { return $this->get_lastMsg(); }

    public function currentJob()
    { return $this->get_currentJob(); }

    public function setCurrentJob($newval)
    { return $this->set_currentJob($newval); }

    public function startupJob()
    { return $this->get_startupJob(); }

    public function setStartupJob($newval)
    { return $this->set_startupJob($newval); }

    public function command()
    { return $this->get_command(); }

    public function setCommand($newval)
    { return $this->set_command($newval); }

    public function voltageLevel()
    { return $this->get_voltageLevel(); }

    public function setVoltageLevel($newval)
    { return $this->set_voltageLevel($newval); }

    public function protocol()
    { return $this->get_protocol(); }

    public function setProtocol($newval)
    { return $this->set_protocol($newval); }

    public function spiMode()
    { return $this->get_spiMode(); }

    public function setSpiMode($newval)
    { return $this->set_spiMode($newval); }

    public function ssPolarity()
    { return $this->get_ssPolarity(); }

    public function setSsPolarity($newval)
    { return $this->set_ssPolarity($newval); }

    public function shitftSampling()
    { return $this->get_shitftSampling(); }

    public function setShitftSampling($newval)
    { return $this->set_shitftSampling($newval); }

    /**
     * Continues the enumeration of SPI ports started using yFirstSpiPort().
     *
     * @return a pointer to a YSpiPort object, corresponding to
     *         a SPI port currently online, or a null pointer
     *         if there are no more SPI ports to enumerate.
     */
    public function nextSpiPort()
    {   $resolve = YAPI::resolveFunction($this->_className, $this->_func);
        if($resolve->errorType != YAPI_SUCCESS) return null;
        $next_hwid = YAPI::getNextHardwareId($this->_className, $resolve->result);
        if($next_hwid == null) return null;
        return yFindSpiPort($next_hwid);
    }

    /**
     * Starts the enumeration of SPI ports currently accessible.
     * Use the method YSpiPort.nextSpiPort() to iterate on
     * next SPI ports.
     *
     * @return a pointer to a YSpiPort object, corresponding to
     *         the first SPI port currently online, or a null pointer
     *         if there are none.
     */
    public static function FirstSpiPort()
    {   $next_hwid = YAPI::getFirstHardwareId('SpiPort');
        if($next_hwid == null) return null;
        return self::FindSpiPort($next_hwid);
    }

    //--- (end of YSpiPort implementation)

};

//--- (SpiPort functions)

/**
 * Retrieves a SPI port for a given identifier.
 * The identifier can be specified using several formats:
 * <ul>
 * <li>FunctionLogicalName</li>
 * <li>ModuleSerialNumber.FunctionIdentifier</li>
 * <li>ModuleSerialNumber.FunctionLogicalName</li>
 * <li>ModuleLogicalName.FunctionIdentifier</li>
 * <li>ModuleLogicalName.FunctionLogicalName</li>
 * </ul>
 *
 * This function does not require that the SPI port is online at the time
 * it is invoked. The returned object is nevertheless valid.
 * Use the method YSpiPort.isOnline() to test if the SPI port is
 * indeed online at a given time. In case of ambiguity when looking for
 * a SPI port by logical name, no error is notified: the first instance
 * found is returned. The search is performed first by hardware name,
 * then by logical name.
 *
 * @param func : a string that uniquely characterizes the SPI port
 *
 * @return a YSpiPort object allowing you to drive the SPI port.
 */
function yFindSpiPort($func)
{
    return YSpiPort::FindSpiPort($func);
}

/**
 * Starts the enumeration of SPI ports currently accessible.
 * Use the method YSpiPort.nextSpiPort() to iterate on
 * next SPI ports.
 *
 * @return a pointer to a YSpiPort object, corresponding to
 *         the first SPI port currently online, or a null pointer
 *         if there are none.
 */
function yFirstSpiPort()
{
    return YSpiPort::FirstSpiPort();
}

//--- (end of SpiPort functions)
?>