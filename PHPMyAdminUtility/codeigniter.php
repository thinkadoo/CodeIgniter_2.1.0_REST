<?php

if (! defined('PHPMYADMIN')) {
    exit;
}

/**
 * This gets executed twice so avoid a notice
 */

if (! defined('CI_FORMAT_PHP')) {
    define("CI_FORMAT_PHP", "CodeIgniter 2.0 PHP");
    define("CI_HANDLER_PHP_BODY", "handleCodeIgniterPHPBody");
}

$PARSE_FORMATS = array(CI_FORMAT_PHP);
$PARSE_HANDLERS = array(CI_HANDLER_PHP_BODY);

$YAML_dataTypes;

/**
 * Export Options
 */
if (isset($plugin_list)) {
    $plugin_list['codeigniter'] = array(
        'text' => 'CodeIgniter',
        'extension' => '.php.txt',
        'mime_type' => 'text/php',
          'options' => array(
          	array('type' => 'hidden', 'name' => 'data'),
            array('type' => 'select', 'name' => 'format', 'text' => 'strFormat', 'values' => $PARSE_FORMATS),
            ),
        'options_text' => 'strOptions',
        );

    $plugin_list['codeigniter']['options'][] =
                array('type' => 'bool', 'name' => 'verbose', 'text' => 'Use Verbose Syntax');

    $plugin_list['codeigniter']['options'][] =
                array('type' => 'bool', 'name' => 'index', 'text' => 'Include Table Indexes', 'values'=> array('true', 'false'));

    $plugin_list['codeigniter']['options'][] =
                array('type' => 'bool', 'name' => 'relation', 'text' => 'Include Table Relations');



    $GLOBALS['cfg']['Export']['codeigniter_verbose'] = true;
    $GLOBALS['cfg']['Export']['codeigniter_index'] = false;
    $GLOBALS['cfg']['Export']['codeigniter_relation'] = true;


} else {


/**
 * Set of functions used to build exports of tables
 */

/**
 * Outputs comment
 *
 * @param   string      Text of comment
 *
 * @return  bool        Whether it suceeded
 */
function PMA_exportComment($text)
{
    return TRUE;
}

/**
 * Outputs export footer
 *
 * @return  bool        Whether it suceeded
 *
 * @access  public
 */
function PMA_exportFooter()
{
    return TRUE;
}

/**
 * Outputs export header
 *
 * @return  bool        Whether it suceeded
 *
 * @access  public
 *
    abstract 	Whether or not to make the generated class abstract. Defaults to false. When a class is abstract it is not exported to the database.
    className 	Name of the class to generate
    tableName 	Name of the table in your DBMS to use.
    connection 	Name of the CodeIgniter_Connection instance to bind the model to.
    columns 	Column definitions.
    relations 	Relationship definitions.
    indexes 	Index definitions.
    attributes 	Attribute definitions.
    actAs 	ActAs definitions.
    options 	Option definitions.
    inheritance 	Array for inheritance definition
    listeners 	Array defining listeners to attach
    checks 	Checks to run at application level as well as exporting to your DBMS
 */
function PMA_exportHeader()
{
    $str = "";
    return PMA_exportOutputHandler($str);
}

/**
 * Outputs database header
 *
 * @param   string      Database name
 *
 * @return  bool        Whether it suceeded
 *
 * @access  public
 */
function PMA_exportDBHeader($db)
{
    return TRUE;
}

/**
 * Outputs database footer
 *
 * @param   string      Database name
 *
 * @return  bool        Whether it suceeded
 *
 * @access  public
 */
function PMA_exportDBFooter($db)
{
    return TRUE;
}

/**
 * Outputs create database database
 *
 * @param   string      Database name
 *
 * @return  bool        Whether it suceeded
 *
 * @access  public
 */
function PMA_exportDBCreate($db)
{
    return TRUE;
}

/**
 * Outputs the content of a table in CodeIgniter 2 format
 *
 * @param   string      the database name
 * @param   string      the table name
 * @param   string      the end of line sequence
 * @param   string      the url to go back in case of error
 * @param   string      SQL query for obtaining data
 *
 * @return  bool        Whether it suceeded
 *
 * @access  public
 */
function PMA_exportData($db, $table, $crlf, $error_url, $sql_query)
{
	global $PARSE_FORMATS, $PARSE_HANDLERS;
	$format = cgGetOption("format");
	$index = array_search($format, $PARSE_FORMATS);
	if ($index >= 0)
    {
        $str = '';
        $str .= PMA_exportOutputHandler($PARSE_HANDLERS[$index]($db, $table, $crlf));
    }
		return $str;
	return PMA_exportOutputHandler(sprintf("%s is not supported.", $format));
}


function createYAML_dataTypeSchema()
{
     $type = array();
     $type['integer'] = 'integer';
     $type['tinyint'] = 'integer';
     $type['smallint'] = 'integer';
     $type['mediumint'] = 'integer';
     $type['int'] = 'integer';
     $type['bigint'] = 'integer';
     $type['double'] = 'float';
     $type['decimal'] = 'decimal';
     $type['char'] = 'char';
     $type['varchar'] = 'string';
     $type['text'] = 'array'; 
     $type['longblob'] = 'blob';
     $type['tinyblob'] = 'blob';
     $type['blob'] = 'blob';
     $type['mediumblob'] = 'blob';
     $type['longtext'] = 'clob';
     $type['tinytext'] = 'clob';
     $type['text'] = 'clob';
     $type['mediumtext'] = 'clob';
     $type['datetime'] = 'timestamp';
     $type['time'] = 'time';
     $type['date'] = 'date';
     $type['bit'] = 'bit';


    return $type;
}

/**
 *
 * @package phpMyAdmin-Export-CodeIgniter
 */
class TableProperty
{
    public $fields;

	function __construct($rowObj)
	{

        $this->fields = new stdClass();

        $this->fields->name = $this->mapEntity('COLUMN_NAME', 'name', $rowObj, false);
        $this->fields->columnKey = $this->mapEntity('COLUMN_KEY', 'columnKey', $rowObj);
        $this->fields->fixed = $this->mapEntity('', 'fixed', $rowObj, false);
        $this->fields->autoincrement = $this->mapEntity('EXTRA', 'autoincrement', $rowObj);
        $this->fields->type = $this->mapEntity('DATA_TYPE', 'type', $rowObj);
        $this->fields->length = $this->mapEntity('COLUMN_TYPE', 'length', $rowObj);
        $this->fields->default = $this->mapEntity('COLUMN_DEFAULT', 'default', $rowObj);
        $this->fields->scale = $this->mapEntity('NUMERIC_SCALE', 'scale', $rowObj, false);
        $this->fields->precision = $this->mapEntity('NUMERIC_PRECISION', 'precision', $rowObj, false);
        $this->fields->values =  $this->mapEntity('', 'enum', $rowObj, false);     // ??? needs identification
        $this->fields->comment = $this->mapEntity('COLUMN_COMMENT', 'comment', $rowObj, false);
        $this->fields->sequence = $this->mapEntity('', 'sequence', $rowObj, false);  // ??? needs identification
        $this->fields->zerofill = $this->mapEntity('', 'zerofill', $rowObj, false);  // ??? needs identification
        $this->fields->extra = $this->mapEntity('EXTRA', 'extra', $rowObj);
        $this->fields->unsigned = $this->mapEntity('', 'unsigned', $rowObj, false); // ??? needs identification
        $this->fields->nullable = $this->mapEntity('IS_NULLABLE', 'nullable', $rowObj);


        if($this->fields->type->schemaVal == "decimal")
        {
             $this->fields->scale->include = true;
             $this->fields->precision->include = true;
             $this->fields->length->include = false;
        }
	}

    function mapEntity($columnName, $columnSchemaName, $entityObj, $include=true)
    {
        global $YAML_dataTypes;

        $prop = new stdClass();

        $prop->schemaName = $columnSchemaName;
        $prop->include = $include;

        switch($columnSchemaName)
        {
             case "type":
                $prop->schemaVal = $YAML_dataTypes[$entityObj[$columnName]];
                break;

             case "length":
                $prop->schemaVal = $this->getPureLength($entityObj[$columnName]);
                break;

             case "autoincrement":
                $prop->schemaVal =  $entityObj[$columnName] == "auto_increment" ? "true" : "";
                $prop->include = false;
                break;

             case "columnKey":
                if($entityObj[$columnName] == "PRI")
                {
                    $prop->schemaName = 'primary';
                    $prop->schemaVal =  "true";
                    $prop->include = false;
                } else if($entityObj[$columnName] == "UNI")  {
                    $prop->schemaName = 'unique';
                    $prop->schemaVal =  "true";
                    $prop->include = true;
                }  else if($entityObj[$columnName] == "MUL") {
                    $prop->include = false;
                }

                break;


             case "nullable":
                $prop->schemaVal =  $entityObj[$columnName] == "YES" ? "true" : "false";
                break;

             case "extra":
                if($entityObj[$columnName] == "auto_increment")
                    $prop->include = false;
                break;

             default:
                $prop->schemaVal = $entityObj[$columnName];
                break;
        }


        return $prop;
    }



    function getPureLength($string)
	{
		$sS = strrpos($string , '(');
        $sE = strrpos($string , ')');

        if($sS && $sE)
            return substr($string , $sS+1, ($sE-$sS)-1);

		return '';
	}


    /*
     * INSERT COLUMN
     *
     * @param   boolean      use verbose syntax
     */
    function insertMetadata($useVerboseSyntax=true)
    {

        $deliminator = '';
        $count = 0;
        $lines = array();

        $lines[] =  "\t/**";

        if($this->fields->columnKey->schemaName == "primary")
              $lines[] = "\t * @Id";

        $str = "\t * @Column(";
        $str .= "name=\"".$this->fields->name->schemaVal."\"";

        foreach($this->fields as $field => $val)
        {
            if(!empty($val->schemaVal) && $val->include == true)
            {
                if($count > 0 )
                    $deliminator = ', ';

                // dont include non verbose properties
                if(!$useVerboseSyntax)
                {
                    if($val->schemaName == 'length' || $val->schemaName == 'precision' || $val->schemaName == 'scale')
                        continue;
                }

                switch($val->schemaName)
                {
                    case "type":
                        $str .= $deliminator.$val->schemaName."="."\"$val->schemaVal\"";
                        break;

                    default:
                        $str .= $deliminator.$val->schemaName."=".$val->schemaVal;
                        break;
                }
            }
            $count++;
        }
        $str .=  ")";
        $lines[] = $str;

        if($this->fields->autoincrement->schemaVal)
              $lines[] = "\t * @GeneratedValue(strategy=\"AUTO\")";

        $lines[] =  "\t */";
        return implode("\n", $lines);
    }

}


/**
 *
 * @package phpMyAdmin-Export-CodeIgniter
 */
class TableIndexes
{
    public $indexes;

	function __construct($db, $table)
	{
        $this->indexes = array();

        $raw_indexes = PMA_DBI_fetch_result('SHOW INDEX FROM ' . $db . '.' . $table);

        foreach ($raw_indexes as $each_index)
        {
            $index =  new stdClass();
            $index->name = $this->mapEntity('Key_name', 'name', $each_index);

            // make columns array for index
            $index->columns = new stdClass();
            $index->columns->schemaName = 'columns';
            $index->columns->schemaVal = array();
            $index->columns->include = true;

            // Only add this new index if it does not exist already
            // If not, add the duplicates columns to the existing index's columns
            $existingIndex = $this->isSameKeyName($index);
            if(!$existingIndex)
            {
               $index->columns->schemaVal[] = $each_index['Column_name'];
               $this->indexes[] = $index;

            }
            else
            {
               $existingIndex->columns->schemaVal[] = $each_index['Column_name'];
            }
        }//print_r($this->indexes);
	}

    /*
     * Checks if this index name already exists
     */
    private function isSameKeyName($index)
    {
        foreach ($this->indexes as $existingIndex)
        {
            if($existingIndex->name->schemaVal == $index->name->schemaVal)
            {
                return $existingIndex;
                break;
            }
        }
        return false;
    }

    private function mapEntity($columnName, $columnSchemaName, $entityObj, $include=true)
    {
        $prop = new stdClass();

        $prop->schemaName = $columnSchemaName;
        $prop->schemaVal = $entityObj[$columnName];
        $prop->include = $include;

        return $prop;
    }

    /**
     * Return a list of all indexes
     *
     * @param   boolean      return only foreign key indexes
     * @return  array index columns
     */

    public function getIndexes($foreignKeysOnly=true)
    {
        $list = array();
        foreach($this->indexes as $index)
        {
            if($foreignKeysOnly)
            {
                $found = false;

                foreach($this->getIndexChoices() as $choice)
                {
                     if($index->name->schemaVal == $choice)
                         $found = true;
                         continue;
                }
                if(!$found)
                    $list[] = $index;
            }
            else
            {
                $list[] = $index;
            }
        }
        return $list;
    }

    /**
     * Return a list of all index associated columns
     *
     * @return  array index columns
     */

    public function getIndexColumns($indexName)
    {
        $cols = array();
        foreach($this->indexes as $index)
        {
            if($index->name->schemaVal == $indexName)
            {
                $col[] = $index->column;
            }
        }

        return $cols;
    }

    /**
     * Return a list of all index choices
     *
     * @return  array index choices
     */
    private function getIndexChoices()
    {
        return array(
            'PRIMARY',
            'INDEX',
            'UNIQUE',
            'FULLTEXT',
        );
    }
}



    /*
     * ============================================================================
     */
	function handleCodeIgniterPHPBody($db, $table, $crlf)
	{
        global $YAML_dataTypes;

        $lines=array();

        /*
         * CodeIgniter offers the ability to specify schema in an abbreviated syntax.
         *
         * If verbose is set to false, a lot of the schema parameters have values they default to,
         * this allows us to abbreviate the syntax and let CodeIgniter just use its defaults.
         *
         * If verbose is set to true ALL schema parameters will be included. This is recomended!
         */
        $useVerboseSyntax = cgGetOption('verbose');

        /*
         * Show Table relations in header
         */
        $showIndexes = cgGetOption('index');

        // create schema
        $YAML_dataTypes = createYAML_dataTypeSchema();

        // get table info
        $sqlQuery = "SELECT * FROM information_schema.columns WHERE TABLE_SCHEMA = '$db' AND TABLE_NAME = '$table'";
		$result=PMA_DBI_query($sqlQuery);

        // get table relations
        $sqlQuery = "SELECT * FROM information_schema.KEY_COLUMN_USAGE WHERE TABLE_SCHEMA = '$db' AND TABLE_NAME = '$table'";
        $result_rel=PMA_DBI_query($sqlQuery);

        $tableRelations = array();
        while ($row = PMA_DBI_fetch_assoc($result_rel))
        {
            $tableRelations[] = $row;
            $referencedTable = $row["REFERENCED_TABLE_NAME"];
            $referencedTableClass = toCamelCase($referencedTable, true);
        }

        // build header
        if(!$useVerboseSyntax)
        {
         //   $lines[] = "detect_relations: true\n";
        }





        // build body
        if ($result)
		{
			$tableProperties=array();
			while ($row = PMA_DBI_fetch_assoc($result))
            {
               $tableProperties[] = new TableProperty($row); //$lines[] = print_r($row);
            }

             // insert table Class Headers
            $lines[] = "<?php defined('BASEPATH') OR exit('No direct script access allowed');\n";

            // insert table indexes
            if($showIndexes)
            {
                $tableIndexes = new TableIndexes($db, $table);
                $indexes = $tableIndexes->getIndexes(true);

                if(count($indexes) > 0)
                {
                    $indexCount = 0;
                    $indexDeliminator = '';

                    $str .= ", indexes={";
                    foreach ($indexes as $index)
                    {
                        $columnsCount = 0;
                        $columnDeliminator = '';

                        if($indexCount > 0 )
                            $indexDeliminator = ', ';

                        $str .=  $indexDeliminator."@index(name=\"".$index->name->schemaVal."\", columns={";

                        foreach ($index->columns->schemaVal as $col)
                        {
                            if($columnsCount > 0 )
                                $columnDeliminator = ', ';

                            $str .=  $columnDeliminator."\"".$col."\"";
                            $columnsCount ++;
                        }
                        $str .= "})";
                        $indexCount ++;
                    }
                    $str .= "}";
                }


            }

            // insert class name
			$lines[] = "class ".toCamelCase( $table, true )."_model extends CI_Model { \n";
			
			$lines[] = "   private \$".$table." = '".$table."';\n";
			
			$lines[] = "   public function __construct()";
			$lines[] = "   {";
			$lines[] = "        parent::__construct();";
			$lines[] = "   }";
			
			$lines[] = "\n";
			
			$lines[] = "   public function list_all()";
			$lines[] = "   {";
			$lines[] = "        \$this->db->order_by('id','asc');";
			$lines[] = "        return \$this->db->get(\$this->".$table.");";
			$lines[] = "   }";
			
			$lines[] = "\n";			

			$lines[] = "   public function count_all()";
			$lines[] = "   {";
			$lines[] = "        return \$this->db->count_all(\$this->".$table.");";
			$lines[] = "   }";
			
			$lines[] = "\n";			
			
			$lines[] = "   public function get_paged_list(\$limit = 10, \$offset = 0)";
			$lines[] = "   {";
			$lines[] = "        \$this->db->order_by('id','asc');";
			$lines[] = "        return \$this->db->get(\$this->".$table.", \$limit, \$offset);";
			$lines[] = "   }";
			
			$lines[] = "\n";			
			
			$lines[] = "   public function get_by_id(\$id)";
			$lines[] = "   {";
			$lines[] = "        \$this->db->where('id', \$id);";
			$lines[] = "        return \$this->db->get(\$this->".$table.");";
			$lines[] = "   }";
			
			$lines[] = "\n";			
			
			$lines[] = "   public function save(\$".$table.")";
			$lines[] = "   {";
			$lines[] = "        \$this->db->insert(\$this->".$table.", \$".$table.");";
			$lines[] = "        return \$this->db->insert_id();";
			$lines[] = "   }";
			
			$lines[] = "\n";
			
			$lines[] = "   public function update(\$id, \$".$table.")";
			$lines[] = "   {";
			$lines[] = "        \$this->db->where('id', \$id);";
			$lines[] = "        \$this->db->update(\$this->".$table.", \$".$table.");";
			$lines[] = "   }";
			
			$lines[] = "\n";
			
			$lines[] = "   public function delete(\$id)";
			$lines[] = "   {";
			$lines[] = "        \$this->db->where('id', \$id);";
			$lines[] = "        \$this->db->delete(\$this->".$table.");";
			$lines[] = "   }";		
			
			$lines[] = "\n";

			$lines[] = "}\n\n\n";

			PMA_DBI_free_result($result);
		}


		return implode("\n", $lines);

	}

	
	function toCapital($str)
   {
       $str = strtolower($str);
       $str[0] = strtoupper($str[0]);
       $func = create_function('$c', 'return strtoupper($c[1]);');
       return preg_replace_callback('/_([a-z])/', $func, $str);
   }

    /**
    * Translates a camel case string into a string with underscores
    */
    function fromCamelCase($str)
    {
       $str[0] = strtolower($str[0]);
       $func = create_function('$c', 'return "_" . strtolower($c[1]);');
       return preg_replace_callback('/([A-Z])/', $func, $str);
    }

   /**
    * Translates a string with underscores into camel case
    */
   function toCamelCase($str, $capFirstChar = false)
   {
       $str = strtolower($str);
       if($capFirstChar) {
         $str[0] = strtoupper($str[0]);
       }
       $func = create_function('$c', 'return strtoupper($c[1]);');
       return preg_replace_callback('/_([a-z])/', $func, $str);
   }


	function cgGetOption($optionName)
	{
		global $what;
		return $GLOBALS[$what . "_" . $optionName];
	}
	
}
?>