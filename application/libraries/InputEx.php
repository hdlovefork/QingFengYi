<?php

/**
 * Created by PhpStorm.
 * User: hdlovefork
 * Date: 2017-12-14
 * Time: 21:45
 */

/**
 * 类似于内置Input类，用于读取输入的数据
 * Class InputEx
 */
class InputEx
{
    //Format 对象
    private $format;

    protected $allowed_http_methods = ['get', 'delete', 'post', 'put', 'options', 'patch', 'head'];

    /**
     * List all supported methods, the first will be the default format
     *
     * @var array
     */
    protected $_supported_formats = [
        'json' => 'application/json',
        'array' => 'application/json',
        'csv' => 'application/csv',
        'html' => 'text/html',
        'jsonp' => 'application/javascript',
        'php' => 'text/plain',
        'serialized' => 'application/vnd.php.serialized',
        'xml' => 'application/xml'
    ];
    /**
     * Contains details about the request
     * Fields: body, format, method, ssl
     * Note: This is a dynamic object (stdClass)
     *
     * @var object
     */
    protected $request = NULL;

    /**
     * The arguments for the GET request method
     *
     * @var array
     */
    protected $_get_args = [];

    /**
     * The arguments for the POST request method
     *
     * @var array
     */
    protected $_post_args = [];

    /**
     * The arguments for the PUT request method
     *
     * @var array
     */
    protected $_put_args = [];

    /**
     * The arguments for the DELETE request method
     *
     * @var array
     */
    protected $_delete_args = [];

    /**
     * The arguments for the PATCH request method
     *
     * @var array
     */
    protected $_patch_args = [];

    /**
     * The arguments for the HEAD request method
     *
     * @var array
     */
    protected $_head_args = [];

    /**
     * The arguments for the OPTIONS request method
     *
     * @var array
     */
    protected $_options_args = [];

    /**
     * The arguments for the query parameters
     *
     * @var array
     */
    protected $_query_args = [];

    /**
     * The arguments from GET, POST, PUT, DELETE, PATCH, HEAD and OPTIONS request methods combined
     *
     * @var array
     */
    protected $_args = [];

    protected $CI;

    public function __construct()
    {
        $this->CI=& get_instance();
        $this->_init();
    }

    private function _init()
    {
        $this->request = new stdClass();

        // At present the library is bundled with REST_Controller 2.5+, but will eventually be part of CodeIgniter (no citation)
        if (class_exists('Format')) {
            $this->format = new Format();
        } else {
            $this->CI->load->library('Format', NULL, 'libraryFormat');
            $this->format = $this->libraryFormat;
        }

        // Create an argument container if it doesn't exist e.g. _get_args
        if (isset($this->{'_' . $this->request->method . '_args'}) === FALSE) {
            $this->{'_' . $this->request->method . '_args'} = [];
        }

        // Set up the query parameters
        $this->_parse_query();
        $this->_get_args = array_merge($this->_get_args, $this->CI->uri->ruri_to_assoc());

        $this->request->method = $this->_detect_method();

        // Try to find a format for the request (means we have a request body)
        $this->request->format = $this->_detect_input_format();

        // Not all methods have a body attached with them
        $this->request->body = NULL;

        $this->{'_parse_' . $this->request->method}();

        // Fix parse method return arguments null
        if($this->{'_'.$this->request->method.'_args'} === null)
        {
            $this->{'_'.$this->request->method.'_args'} = [];
        }

        // Now we know all about our request, let's try and parse the body if it exists
        if ($this->request->format && $this->request->body)
        {
            $this->request->body = $this->format->factory($this->request->body, $this->request->format)->to_array();
            // Assign payload arguments to proper method container
            $this->{'_'.$this->request->method.'_args'} = $this->request->body;
        }

        //get header vars
        $this->_head_args = $this->CI->input->request_headers();

        // Merge both for one mega-args variable
        $this->_args = array_merge(
            $this->_get_args,
            $this->_options_args,
            $this->_patch_args,
            $this->_head_args,
            $this->_put_args,
            $this->_post_args,
            $this->_delete_args,
            $this->{'_'.$this->request->method.'_args'}
        );
    }

    /**
     * Parse the GET request arguments
     *
     * @access protected
     * @return void
     */
    protected function _parse_get()
    {
        // Merge both the URI segments and query parameters
        $this->_get_args = array_merge($this->_get_args, $this->_query_args);
    }

    /**
     * Parse the POST request arguments
     *
     * @access protected
     * @return void
     */
    protected function _parse_post()
    {
        $this->_post_args = $_POST;

        if ($this->request->format)
        {
            $this->request->body = $this->CI->input->raw_input_stream;
        }
    }

    /**
     * Parse the PUT request arguments
     *
     * @access protected
     * @return void
     */
    protected function _parse_put()
    {
        if ($this->request->format)
        {
            $this->request->body = $this->CI->input->raw_input_stream;
            if ($this->request->format === 'json')
            {
                $this->_put_args = json_decode($this->CI->input->raw_input_stream);
            }
        }
        else if ($this->CI->input->method() === 'put')
        {
            // If no file type is provided, then there are probably just arguments
            $this->_put_args = $this->CI->input->input_stream();
        }
    }

    /**
     * Parse the HEAD request arguments
     *
     * @access protected
     * @return void
     */
    protected function _parse_head()
    {
        // Parse the HEAD variables
        parse_str(parse_url($this->CI->input->server('REQUEST_URI'), PHP_URL_QUERY), $head);

        // Merge both the URI segments and HEAD params
        $this->_head_args = array_merge($this->_head_args, $head);
    }

    /**
     * Parse the OPTIONS request arguments
     *
     * @access protected
     * @return void
     */
    protected function _parse_options()
    {
        // Parse the OPTIONS variables
        parse_str(parse_url($this->CI->input->server('REQUEST_URI'), PHP_URL_QUERY), $options);

        // Merge both the URI segments and OPTIONS params
        $this->_options_args = array_merge($this->_options_args, $options);
    }

    /**
     * Parse the PATCH request arguments
     *
     * @access protected
     * @return void
     */
    protected function _parse_patch()
    {
        // It might be a HTTP body
        if ($this->request->format)
        {
            $this->request->body = $this->CI->input->raw_input_stream;
        }
        else if ($this->CI->input->method() === 'patch')
        {
            // If no file type is provided, then there are probably just arguments
            $this->_patch_args = $this->CI->input->input_stream();
        }
    }

    /**
     * Parse the DELETE request arguments
     *
     * @access protected
     * @return void
     */
    protected function _parse_delete()
    {
        // These should exist if a DELETE request
        if ($this->CI->input->method() === 'delete')
        {
            $this->_delete_args = $this->CI->input->input_stream();
        }
    }

    /**
     * Parse the query parameters
     *
     * @access protected
     * @return void
     */
    protected function _parse_query()
    {
        $this->_query_args = $this->CI->input->get();
    }

    // INPUT FUNCTION --------------------------------------------------------------

    /**
     * Retrieve a value from a GET request
     *
     * @access public
     * @param NULL $key Key to retrieve from the GET request
     * If NULL an array of arguments is returned
     * @param NULL $xss_clean Whether to apply XSS filtering
     * @return array|string|NULL Value from the GET request; otherwise, NULL
     */
    public function get($key = NULL, $xss_clean = NULL)
    {
        if ($key === NULL)
        {
            return $this->_get_args;
        }

        return isset($this->_get_args[$key]) ? $this->_xss_clean($this->_get_args[$key], $xss_clean) : NULL;
    }

    /**
     * Retrieve a value from a OPTIONS request
     *
     * @access public
     * @param NULL $key Key to retrieve from the OPTIONS request.
     * If NULL an array of arguments is returned
     * @param NULL $xss_clean Whether to apply XSS filtering
     * @return array|string|NULL Value from the OPTIONS request; otherwise, NULL
     */
    public function options($key = NULL, $xss_clean = NULL)
    {
        if ($key === NULL)
        {
            return $this->_options_args;
        }

        return isset($this->_options_args[$key]) ? $this->_xss_clean($this->_options_args[$key], $xss_clean) : NULL;
    }

    /**
     * Retrieve a value from a HEAD request
     *
     * @access public
     * @param NULL $key Key to retrieve from the HEAD request
     * If NULL an array of arguments is returned
     * @param NULL $xss_clean Whether to apply XSS filtering
     * @return array|string|NULL Value from the HEAD request; otherwise, NULL
     */
    public function head($key = NULL, $xss_clean = NULL)
    {
        if ($key === NULL)
        {
            return $this->_head_args;
        }

        return isset($this->_head_args[$key]) ? $this->_xss_clean($this->_head_args[$key], $xss_clean) : NULL;
    }

    /**
     * Retrieve a value from a POST request
     *
     * @access public
     * @param NULL $key Key to retrieve from the POST request
     * If NULL an array of arguments is returned
     * @param NULL $xss_clean Whether to apply XSS filtering
     * @return array|string|NULL Value from the POST request; otherwise, NULL
     */
    public function post($key = NULL, $xss_clean = NULL)
    {
        if ($key === NULL)
        {
            return $this->_post_args;
        }

        return isset($this->_post_args[$key]) ? $this->_xss_clean($this->_post_args[$key], $xss_clean) : NULL;
    }

    /**
     * Retrieve a value from a PUT request
     *
     * @access public
     * @param NULL $key Key to retrieve from the PUT request
     * If NULL an array of arguments is returned
     * @param NULL $xss_clean Whether to apply XSS filtering
     * @return array|string|NULL Value from the PUT request; otherwise, NULL
     */
    public function put($key = NULL, $xss_clean = NULL)
    {
        if ($key === NULL)
        {
            return $this->_put_args;
        }

        return isset($this->_put_args[$key]) ? $this->_xss_clean($this->_put_args[$key], $xss_clean) : NULL;
    }

    /**
     * Retrieve a value from a DELETE request
     *
     * @access public
     * @param NULL $key Key to retrieve from the DELETE request
     * If NULL an array of arguments is returned
     * @param NULL $xss_clean Whether to apply XSS filtering
     * @return array|string|NULL Value from the DELETE request; otherwise, NULL
     */
    public function delete($key = NULL, $xss_clean = NULL)
    {
        if ($key === NULL)
        {
            return $this->_delete_args;
        }

        return isset($this->_delete_args[$key]) ? $this->_xss_clean($this->_delete_args[$key], $xss_clean) : NULL;
    }

    /**
     * Retrieve a value from a PATCH request
     *
     * @access public
     * @param NULL $key Key to retrieve from the PATCH request
     * If NULL an array of arguments is returned
     * @param NULL $xss_clean Whether to apply XSS filtering
     * @return array|string|NULL Value from the PATCH request; otherwise, NULL
     */
    public function patch($key = NULL, $xss_clean = NULL)
    {
        if ($key === NULL)
        {
            return $this->_patch_args;
        }

        return isset($this->_patch_args[$key]) ? $this->_xss_clean($this->_patch_args[$key], $xss_clean) : NULL;
    }

    /**
     * Retrieve a value from the query parameters
     *
     * @access public
     * @param NULL $key Key to retrieve from the query parameters
     * If NULL an array of arguments is returned
     * @param NULL $xss_clean Whether to apply XSS filtering
     * @return array|string|NULL Value from the query parameters; otherwise, NULL
     */
    public function query($key = NULL, $xss_clean = NULL)
    {
        if ($key === NULL)
        {
            return $this->_query_args;
        }

        return isset($this->_query_args[$key]) ? $this->_xss_clean($this->_query_args[$key], $xss_clean) : NULL;
    }

    /**
     * Sanitizes data so that Cross Site Scripting Hacks can be
     * prevented
     *
     * @access protected
     * @param string $value Input data
     * @param bool $xss_clean Whether to apply XSS filtering
     * @return string
     */
    protected function _xss_clean($value, $xss_clean)
    {
        is_bool($xss_clean) || $xss_clean = $this->_enable_xss;

        return $xss_clean === TRUE ? $this->CI->security->xss_clean($value) : $value;
    }

    /**
     * Get the HTTP request string e.g. get or post
     *
     * @access protected
     * @return string|NULL Supported request method as a lowercase string; otherwise, NULL if not supported
     */
    protected function _detect_method()
    {
        // Declare a variable to store the method
        $method = NULL;

        // Determine whether the 'enable_emulate_request' setting is enabled
        if ($this->CI->config->item('enable_emulate_request') === TRUE)
        {
            $method = $this->CI->input->post('_method');
            if ($method === NULL)
            {
                $method = $this->CI->input->server('HTTP_X_HTTP_METHOD_OVERRIDE');
            }

            $method = strtolower($method);
        }

        if (empty($method))
        {
            // Get the request method as a lowercase string
            $method = $this->CI->input->method();
        }

        return in_array($method, $this->allowed_http_methods) && method_exists($this, '_parse_' . $method) ? $method : 'get';
    }

    /**
     * Get the input format e.g. json or xml
     *
     * @access protected
     * @return string|NULL Supported input format; otherwise, NULL
     */
    protected function _detect_input_format()
    {
        // Get the CONTENT-TYPE value from the SERVER variable
        $content_type = $this->CI->input->server('CONTENT_TYPE');

        if (empty($content_type) === FALSE)
        {
            // If a semi-colon exists in the string, then explode by ; and get the value of where
            // the current array pointer resides. This will generally be the first element of the array
            $content_type = (strpos($content_type, ';') !== FALSE ? current(explode(';', $content_type)) : $content_type);

            // Check all formats against the CONTENT-TYPE header
            foreach ($this->_supported_formats as $type => $mime)
            {
                // $type = format e.g. csv
                // $mime = mime type e.g. application/csv

                // If both the mime types match, then return the format
                if ($content_type === $mime)
                {
                    return $type;
                }
            }
        }

        return NULL;
    }
}

/**
 * Format class
 * Help convert between various formats such as XML, JSON, CSV, etc.
 *
 * @author    Phil Sturgeon, Chris Kacerguis, @softwarespot
 * @license   http://www.dbad-license.org/
 */
class Format {

    /**
     * Array output format
     */
    const ARRAY_FORMAT = 'array';

    /**
     * Comma Separated Value (CSV) output format
     */
    const CSV_FORMAT = 'csv';

    /**
     * Json output format
     */
    const JSON_FORMAT = 'json';

    /**
     * HTML output format
     */
    const HTML_FORMAT = 'html';

    /**
     * PHP output format
     */
    const PHP_FORMAT = 'php';

    /**
     * Serialized output format
     */
    const SERIALIZED_FORMAT = 'serialized';

    /**
     * XML output format
     */
    const XML_FORMAT = 'xml';

    /**
     * Default format of this class
     */
    const DEFAULT_FORMAT = self::JSON_FORMAT; // Couldn't be DEFAULT, as this is a keyword

    /**
     * CodeIgniter instance
     *
     * @var object
     */
    private $_CI;

    /**
     * Data to parse
     *
     * @var mixed
     */
    protected $_data = [];

    /**
     * Type to convert from
     *
     * @var string
     */
    protected $_from_type = NULL;

    /**
     * DO NOT CALL THIS DIRECTLY, USE factory()
     *
     * @param NULL $data
     * @param NULL $from_type
     * @throws Exception
     */

    public function __construct($data = NULL, $from_type = NULL)
    {
        // Get the CodeIgniter reference
        $this->_CI = &get_instance();

        // Load the inflector helper
        $this->_CI->load->helper('inflector');

        // If the provided data is already formatted we should probably convert it to an array
        if ($from_type !== NULL)
        {
            if (method_exists($this, '_from_'.$from_type))
            {
                $data = call_user_func([$this, '_from_'.$from_type], $data);
            }
            else
            {
                throw new Exception('Format class does not support conversion from "'.$from_type.'".');
            }
        }

        // Set the member variable to the data passed
        $this->_data = $data;
    }

    /**
     * Create an instance of the format class
     * e.g: echo $this->format->factory(['foo' => 'bar'])->to_csv();
     *
     * @param mixed $data Data to convert/parse
     * @param string $from_type Type to convert from e.g. json, csv, html
     *
     * @return object Instance of the format class
     */
    public function factory($data, $from_type = NULL)
    {
        // $class = __CLASS__;
        // return new $class();

        return new static($data, $from_type);
    }

    // FORMATTING OUTPUT ---------------------------------------------------------

    /**
     * Format data as an array
     *
     * @param mixed|NULL $data Optional data to pass, so as to override the data passed
     * to the constructor
     * @return array Data parsed as an array; otherwise, an empty array
     */
    public function to_array($data = NULL)
    {
        // If no data is passed as a parameter, then use the data passed
        // via the constructor
        if ($data === NULL && func_num_args() === 0)
        {
            $data = $this->_data;
        }

        // Cast as an array if not already
        if (is_array($data) === FALSE)
        {
            $data = (array) $data;
        }

        $array = [];
        foreach ((array) $data as $key => $value)
        {
            if (is_object($value) === TRUE || is_array($value) === TRUE)
            {
                $array[$key] = $this->to_array($value);
            }
            else
            {
                $array[$key] = $value;
            }
        }

        return $array;
    }

    /**
     * Format data as XML
     *
     * @param mixed|NULL $data Optional data to pass, so as to override the data passed
     * to the constructor
     * @param NULL $structure
     * @param string $basenode
     * @return mixed
     */
    public function to_xml($data = NULL, $structure = NULL, $basenode = 'xml')
    {
        if ($data === NULL && func_num_args() === 0)
        {
            $data = $this->_data;
        }

        if ($structure === NULL)
        {
            $structure = simplexml_load_string("<?xml version='1.0' encoding='utf-8'?><$basenode />");
        }

        // Force it to be something useful
        if (is_array($data) === FALSE && is_object($data) === FALSE)
        {
            $data = (array) $data;
        }

        foreach ($data as $key => $value)
        {

            //change false/true to 0/1
            if (is_bool($value))
            {
                $value = (int) $value;
            }

            // no numeric keys in our xml please!
            if (is_numeric($key))
            {
                // make string key...
                $key = (singular($basenode) != $basenode) ? singular($basenode) : 'item';
            }

            // replace anything not alpha numeric
            $key = preg_replace('/[^a-z_\-0-9]/i', '', $key);

            if ($key === '_attributes' && (is_array($value) || is_object($value)))
            {
                $attributes = $value;
                if (is_object($attributes))
                {
                    $attributes = get_object_vars($attributes);
                }

                foreach ($attributes as $attribute_name => $attribute_value)
                {
                    $structure->addAttribute($attribute_name, $attribute_value);
                }
            }
            // if there is another array found recursively call this function
            elseif (is_array($value) || is_object($value))
            {
                $node = $structure->addChild($key);

                // recursive call.
                $this->to_xml($value, $node, $key);
            }
            else
            {
                // add single node.
                $value = htmlspecialchars(html_entity_decode($value, ENT_QUOTES, 'UTF-8'), ENT_QUOTES, 'UTF-8');

                $structure->addChild($key, $value);
            }
        }

        return $structure->asXML();
    }

    /**
     * Format data as HTML
     *
     * @param mixed|NULL $data Optional data to pass, so as to override the data passed
     * to the constructor
     * @return mixed
     */
    public function to_html($data = NULL)
    {
        // If no data is passed as a parameter, then use the data passed
        // via the constructor
        if ($data === NULL && func_num_args() === 0)
        {
            $data = $this->_data;
        }

        // Cast as an array if not already
        if (is_array($data) === FALSE)
        {
            $data = (array) $data;
        }

        // Check if it's a multi-dimensional array
        if (isset($data[0]) && count($data) !== count($data, COUNT_RECURSIVE))
        {
            // Multi-dimensional array
            $headings = array_keys($data[0]);
        }
        else
        {
            // Single array
            $headings = array_keys($data);
            $data = [$data];
        }

        // Load the table library
        $this->_CI->load->library('table');

        $this->_CI->table->set_heading($headings);

        foreach ($data as $row)
        {
            // Suppressing the "array to string conversion" notice
            // Keep the "evil" @ here
            $row = @array_map('strval', $row);

            $this->_CI->table->add_row($row);
        }

        return $this->_CI->table->generate();
    }

    /**
     * @link http://www.metashock.de/2014/02/create-csv-file-in-memory-php/
     * @param mixed|NULL $data Optional data to pass, so as to override the data passed
     * to the constructor
     * @param string $delimiter The optional delimiter parameter sets the field
     * delimiter (one character only). NULL will use the default value (,)
     * @param string $enclosure The optional enclosure parameter sets the field
     * enclosure (one character only). NULL will use the default value (")
     * @return string A csv string
     */
    public function to_csv($data = NULL, $delimiter = ',', $enclosure = '"')
    {
        // Use a threshold of 1 MB (1024 * 1024)
        $handle = fopen('php://temp/maxmemory:1048576', 'w');
        if ($handle === FALSE)
        {
            return NULL;
        }

        // If no data is passed as a parameter, then use the data passed
        // via the constructor
        if ($data === NULL && func_num_args() === 0)
        {
            $data = $this->_data;
        }

        // If NULL, then set as the default delimiter
        if ($delimiter === NULL)
        {
            $delimiter = ',';
        }

        // If NULL, then set as the default enclosure
        if ($enclosure === NULL)
        {
            $enclosure = '"';
        }

        // Cast as an array if not already
        if (is_array($data) === FALSE)
        {
            $data = (array) $data;
        }

        // Check if it's a multi-dimensional array
        if (isset($data[0]) && count($data) !== count($data, COUNT_RECURSIVE))
        {
            // Multi-dimensional array
            $headings = array_keys($data[0]);
        }
        else
        {
            // Single array
            $headings = array_keys($data);
            $data = [$data];
        }

        // Apply the headings
        fputcsv($handle, $headings, $delimiter, $enclosure);

        foreach ($data as $record)
        {
            // If the record is not an array, then break. This is because the 2nd param of
            // fputcsv() should be an array
            if (is_array($record) === FALSE)
            {
                break;
            }

            // Suppressing the "array to string conversion" notice.
            // Keep the "evil" @ here.
            $record = @ array_map('strval', $record);

            // Returns the length of the string written or FALSE
            fputcsv($handle, $record, $delimiter, $enclosure);
        }

        // Reset the file pointer
        rewind($handle);

        // Retrieve the csv contents
        $csv = stream_get_contents($handle);

        // Close the handle
        fclose($handle);

        // Convert UTF-8 encoding to UTF-16LE which is supported by MS Excel
        $csv = mb_convert_encoding($csv, 'UTF-16LE', 'UTF-8');

        return $csv;
    }

    /**
     * Encode data as json
     *
     * @param mixed|NULL $data Optional data to pass, so as to override the data passed
     * to the constructor
     * @return string Json representation of a value
     */
    public function to_json($data = NULL)
    {
        // If no data is passed as a parameter, then use the data passed
        // via the constructor
        if ($data === NULL && func_num_args() === 0)
        {
            $data = $this->_data;
        }

        // Get the callback parameter (if set)
        $callback = $this->_CI->input->get('callback');

        if (empty($callback) === TRUE)
        {
            return json_encode($data);
        }

        // We only honour a jsonp callback which are valid javascript identifiers
        elseif (preg_match('/^[a-z_\$][a-z0-9\$_]*(\.[a-z_\$][a-z0-9\$_]*)*$/i', $callback))
        {
            // Return the data as encoded json with a callback
            return $callback.'('.json_encode($data).');';
        }

        // An invalid jsonp callback function provided.
        // Though I don't believe this should be hardcoded here
        $data['warning'] = 'INVALID JSONP CALLBACK: '.$callback;

        return json_encode($data);
    }

    /**
     * Encode data as a serialized array
     *
     * @param mixed|NULL $data Optional data to pass, so as to override the data passed
     * to the constructor
     * @return string Serialized data
     */
    public function to_serialized($data = NULL)
    {
        // If no data is passed as a parameter, then use the data passed
        // via the constructor
        if ($data === NULL && func_num_args() === 0)
        {
            $data = $this->_data;
        }

        return serialize($data);
    }

    /**
     * Format data using a PHP structure
     *
     * @param mixed|NULL $data Optional data to pass, so as to override the data passed
     * to the constructor
     * @return mixed String representation of a variable
     */
    public function to_php($data = NULL)
    {
        // If no data is passed as a parameter, then use the data passed
        // via the constructor
        if ($data === NULL && func_num_args() === 0)
        {
            $data = $this->_data;
        }

        return var_export($data, TRUE);
    }

    // INTERNAL FUNCTIONS

    /**
     * @param string $data XML string
     * @return array XML element object; otherwise, empty array
     */
    protected function _from_xml($data)
    {
        return $data ? (array) simplexml_load_string($data, 'SimpleXMLElement', LIBXML_NOCDATA) : [];
    }

    /**
     * @param string $data CSV string
     * @param string $delimiter The optional delimiter parameter sets the field
     * delimiter (one character only). NULL will use the default value (,)
     * @param string $enclosure The optional enclosure parameter sets the field
     * enclosure (one character only). NULL will use the default value (")
     * @return array A multi-dimensional array with the outer array being the number of rows
     * and the inner arrays the individual fields
     */
    protected function _from_csv($data, $delimiter = ',', $enclosure = '"')
    {
        // If NULL, then set as the default delimiter
        if ($delimiter === NULL)
        {
            $delimiter = ',';
        }

        // If NULL, then set as the default enclosure
        if ($enclosure === NULL)
        {
            $enclosure = '"';
        }

        return str_getcsv($data, $delimiter, $enclosure);
    }

    /**
     * @param string $data Encoded json string
     * @return mixed Decoded json string with leading and trailing whitespace removed
     */
    protected function _from_json($data)
    {
        return json_decode(trim($data));
    }

    /**
     * @param string $data Data to unserialize
     * @return mixed Unserialized data
     */
    protected function _from_serialize($data)
    {
        return unserialize(trim($data));
    }

    /**
     * @param string $data Data to trim leading and trailing whitespace
     * @return string Data with leading and trailing whitespace removed
     */
    protected function _from_php($data)
    {
        return trim($data);
    }
}