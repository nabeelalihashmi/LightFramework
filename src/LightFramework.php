<?php

namespace IconicCodes\LightFramework;

use Exception;

/**
 * LightFramework
 *
 * @version 1.0.0
 * @author  Nabeel Ali | IconicCodes
 * @license MIT with Custom Conditions
 */
class LightFramework {
    private const VERSION = '1.0.0';
    private $config_dir = "";
    private $autoload_dir = "";
    private $defer_calls = [];
    private $modules = [];

    private static $__instance = null;

    public static $config = [];

    /**
     * Get version of the LightFramework
     *
     * @return void
     */
    public static function getVersion() {
        return self::VERSION;
    }

    /**
     * Constructor
     *
     * @param string $config_dir
     * @param string $autoload_dir
     * @param string $global_symbol
     */
    public function __construct($config_dir = 'config', $autoload_dir = 'autoload', $global_symbol = '__') {
        $this->config_dir = $config_dir;
        $this->autoload_dir = $autoload_dir;
        $this->build_config($this->config_dir);
        define($global_symbol,  $this);
        self::$__instance = $this;
    }


    /**
     * Returns the instance of framework if constructor is called otherwise thrrows exception
     *
     * @return self
     * @throws Exception
     */
    public static function instance() {
        if (self::$__instance === null) {
            throw new Exception("No instance defined");
        }
        return self::$__instance;
    }

    /**
     * Defer the call to run at the end of exection or when runDefered() is called
     *
     * @param integer $id
     * @param callable $callback
     * @return void
     */
    public function defer(int $id, callable $callback) {
        $this->defer_calls[$id] = $callback;
    }

    /**
     * Initiate the function
     *
     * @return void
     */
    public function init() {
        $this->build_autoload($this->autoload_dir);
        $this->build_autoload_modules();
    }

    /**
     * Run the deffered calls. If second argument is set to false, the calls are not removed from list. If calls 
     * are prematurely called, then at end of execution they will be called again.
     *
     * @param array $ids
     * @param boolean $remove_upon_calling
     * @return void
     */
    public function runDeferred($ids = [], $remove_upon_calling = true) {
        if (empty($ids)) {
            ksort($this->defer_calls);
            foreach ($this->defer_calls as $key => $callback) {
                $callback();
                if ($remove_upon_calling) unset($this->defer_calls[$key]);
            }
        } else {
            foreach ($ids as $id) {
                $this->defer_calls[$id]();
                if ($remove_upon_calling) unset($this->defer_calls[$id]);
            }
        }
    }


    /**
     * Add Modules to Framework and run callback functions
     *
     * @param string $identifier
     * @param callable $object
     * @param string $callback
     * @return void
     */
    public function plugModule($identifier, $object, $callback = null) {
        $this->modules[$identifier] = $object;
        if ($callback !== null) {
            $callback($object);
        }
    }

    /**
     * Remove plugged in modules from Framework and run callback function
     *
     * @param string $identifier
     * @param callback $callback
     * @return void
     */
    public function unplugModule($identifier, $callback = null) {
        $module = $this->modules[$identifier];
        unset($this->modules[$identifier]);
        if (is_callable($callback)) {
            $callback($module);
        }
    }

    /**
     * Access the plugged in module
     *
     * @param string $module_name
     * @return callable
     */
    public function getModule($module_name) {
        return $this->modules[$module_name];
    }

    /**
     * Get vlue from array using dot notation
     *
     * @param array $array
     * @param string $key
     * @param string $default
     * @return void
     */
    public static function get($array, $key, $default = null) {
        if (is_null($key)) return $array;

        if (isset($array[$key])) return $array[$key];

        foreach (explode('.', $key) as $segment) {
            if (
                !is_array($array) ||
                !array_key_exists($segment, $array)
            ) {
                return $default;
            }

            $array = $array[$segment];
        }

        return $array;
    }

    /**
     * Set value of array using dot notation
     *
     * @param string $array
     * @param string $key
     * @param string $value
     * @return void
     */
    public static function set(&$array, $key, $value) {
        if (is_null($key)) return $array = $value;

        $keys = explode('.', $key);

        while (count($keys) > 1) {
            $key = array_shift($keys);

            if (!isset($array[$key]) || !is_array($array[$key])) {
                $array[$key] = array();
            }

            $array = &$array[$key];
        }

        $array[array_shift($keys)] = $value;

        return $array;
    }

    /**
     * Get configurations from config using dot notation
     *
     * @param string $key
     * @return void
     */
    public static function getConfig($key) {
        return self::get(self::$config, $key);
    }

    /**
     * Set value of config using dot notation
     *
     * @param string $key
     * @param string $value
     * @return void
     */
    public static function setConfig($key, $value) {
        return self::set(self::$config, $key, $value);
    }

    /**
     * Get configurations from config using array key
     *
     * @param string $key
     * @return void
     */
    public static function getConfigRaw($key) {
        return self::$config[$key];
    }

    /**
     * Set configurations in config using array key
     *
     * @param string $key
     * @param string $val
     * @return void
     */
    public static function setConfigRaw($key, $val) {
        self::$config[$key] = $val;
    }

    /**
     * Return config array for debugging purposes.
     *
     * @return void
     */
    public static function getConfigAll() {
        return self::$config;
    }

    /**
     * Desctructor
     */
    public function __destruct() {
        $this->runDeferred();
    }

    private function build_config($config_dir) {
        $files = scandir($config_dir);
        foreach ($files as $file) {
            if (strpos($file, '.config.php') !== false) {
                $key = str_replace('.config.php', '', $file);
                self::$config[$key] = require $config_dir . '/' . $file;
            }
        }
    }

    private function build_autoload($autoload_dir) {
        $files = scandir($autoload_dir);
        foreach ($files as $file) {
            if (strpos($file, '.autoload.php') !== false) {
                require $autoload_dir . '/' . $file;
            }
        }
    }

    private function build_autoload_modules() {
        foreach (self::$config['autoload_modules'] as $key => $val) {
            $className = $val[0];
            if (is_callable($className)) {
                $callback  = $val[1];
                $this->plugModule($key, $className, $callback);
                return;
            }
            $constructorArgs = isset($val[1]) ? $val[1] : [];
            $callback        = isset($val[2]) ? $val[2] : null;
            $static          = isset($val[3]) ? $val[3] : null;


            if (!$static) {
                if ($constructorArgs == null) {
                    $obj = new $className;
                } else {
                    $obj =  new $className(...$constructorArgs);
                }
                $this->plugModule($key, $obj, $callback);
            } else {
                $this->plugModule($key, $className, $callback);
            }

            unset(self::$config['autoload_modules']);
        }
    }
}
