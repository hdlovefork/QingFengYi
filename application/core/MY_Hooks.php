<?php
/**
 * Created by PhpStorm.
 * User: hdlovefork
 * Date: 2018-01-28
 * Time: 21:09
 */

class MY_Hooks extends CI_Hooks
{
    /**
     * Call Hook
     *
     * Calls a particular hook. Called by CodeIgniter.php.
     *
     * @uses    CI_Hooks::_run_hook()
     *
     * @param    string $which Hook name
     * @param null $params
     * @return    bool    TRUE on success or FALSE on failure
     */
    public function call_hook($which = '', $params = NULL)
    {
        if (!$this->enabled OR !isset($this->hooks[$which])) {
            return FALSE;
        }

        if (is_array($this->hooks[$which]) && !isset($this->hooks[$which]['function'])) {
            foreach ($this->hooks[$which] as $val) {
                $this->_run_hook($val, $params);
            }
        } else {
            $this->_run_hook($this->hooks[$which], $params);
        }

        return TRUE;
    }

    /**
     * Run Hook
     *
     * Runs a particular hook
     *
     * @param    array $data Hook details
     * @param null $params
     * @return    bool    TRUE on success or FALSE on failure
     */
    protected function _run_hook($data, $params = NULL)
    {
        // Closures/lambda functions and array($object, 'method') callables
        if (is_callable($data)) {
            is_array($data)
                ? $data[0]->{$data[1]}()
                : $data();

            return TRUE;
        } elseif (!is_array($data)) {
            return FALSE;
        }

        // -----------------------------------
        // Safety - Prevents run-away loops
        // -----------------------------------

        // If the script being called happens to have the same
        // hook call within it a loop can happen
        if ($this->_in_progress === TRUE) {
            return;
        }

        // -----------------------------------
        // Set file path
        // -----------------------------------

        if (!isset($data['filepath'], $data['filename'])) {
            return FALSE;
        }

        $filepath = APPPATH . $data['filepath'] . '/' . $data['filename'];

        if (!file_exists($filepath)) {
            return FALSE;
        }

        // Determine and class and/or function names
        $class = empty($data['class']) ? FALSE : $data['class'];
        $function = empty($data['function']) ? FALSE : $data['function'];
        //$params = isset($data['params']) ? $data['params'] : '';

        if (empty($function)) {
            return FALSE;
        }

        // Set the _in_progress flag
        $this->_in_progress = TRUE;

        // Call the requested class and/or function
        if ($class !== FALSE) {
            // The object is stored?
            if (isset($this->_objects[$class])) {
                if (method_exists($this->_objects[$class], $function)) {
                    $this->_objects[$class]->$function($params);
                } else {
                    return $this->_in_progress = FALSE;
                }
            } else {
                class_exists($class, FALSE) OR require_once($filepath);

                if (!class_exists($class, FALSE) OR !method_exists($class, $function)) {
                    return $this->_in_progress = FALSE;
                }

                // Store the object and execute the method
                $this->_objects[$class] = new $class();
                $this->_objects[$class]->$function($params);
            }
        } else {
            function_exists($function) OR require_once($filepath);

            if (!function_exists($function)) {
                return $this->_in_progress = FALSE;
            }

            $function($params);
        }

        $this->_in_progress = FALSE;
        return TRUE;
    }

}