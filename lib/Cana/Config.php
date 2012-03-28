<?php

/**
 * Config object
 * 
 * @author		Devin Smith <devin@cana.la>
 * @date		2009.10.01
 *
 * The config object is a self loading xml parser. Pass it a file base name and
 * it will construct an object for you to access.
 *
 */


class Cana_Config extends Cana_Xml {

    private $_root = 'data';

    /**
     * Construct the xml, parse it, and load it as an object
     *
     * @see    Xml
     */
    public function __construct($xml = null, $params = null) {
        parent::__construct($xml,$params);        
        $data = $this->data();
        if (isset($params['append'])) {
            $this->data(array_merge($data[$this->_root],$params['append']));
        }        

        $this->data($this->toModel($this->data()));
    }
    
    /**
     * Magic get function to read from our private data
     *
     * @param     string        The variable to read
     * @return    mixed
     */
    public function __get($var) {
        if (!isset($this->data()->{$this->_root}->$var)) {
            throw new Exception('Unable to read config value: '.$var);
        } else {
            return $this->data()->{$this->_root}->$var;
        }
    }
    
    /**
     * Magic get function to write to our private data
     *
     * @param     string        The variable to write
     * @return    Config
     */
    public function __set($var,$value) {
        if (!isset($this->data()->{$this->_root}->$var)) {
            throw new Exception('Unable to write config value: '.$var);
        } else {
            $this->data()->{$this->_root}->$var = $value;
            return $this;
        }
    }
} 