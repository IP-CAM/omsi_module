<?php
namespace DB;
final class MySQL {
	private $connection;

	public function __construct($hostname, $username, $password, $database, $port = '3306') {
		if (!$this->connection = mysqli_connect($hostname . ':' . $port, $username, $password)) {
			trigger_error('Error: Could not make a database link using ' . $username . '@' . $hostname);
			exit();
		}

		if (!mysqli_select_db($this->connection, $database)) {
			throw new \Exception('Error: Could not connect to database ' . $database);
		}

        mysqli_set_charset($this->connection, "utf8");

		mysqli_query($this->connection, "SET NAMES 'utf8'");
		mysqli_query($this->connection, "SET CHARACTER SET utf8");
		mysqli_query($this->connection, "SET CHARACTER_SET_CONNECTION=utf8");
		mysqli_query($this->connection, "SET SQL_MODE = ''");
	}

	public function query($sql, ...$params) {
        if ($this->connection) {
            $sql = $this->prepareSql($sql, $params);
		    //echo "Executing SQL: " . $sql . "<br>";
			$result = mysqli_query($this->connection, $sql);

			if ($result) {
			    if (is_bool($result) || $result->num_rows === 0) {
			        return $result;
                } else {
                    $i = 0;

                    $data = [];
                    while($row = mysqli_fetch_assoc($result)) {
                        $data[] = $row;
                        $i++;
                    }

                    mysqli_free_result($result);

                    $query = new \stdClass();
                    $query->row = isset($data[0]) ? $data[0] : 0;
                    $query->rows = $data;
                    $query->num_rows = $i;

                    unset($data);

                    return $query;
                }
			} else {
				$trace = debug_backtrace();

				throw new \Exception('Error: ' . mysqli_error($this->connection) . '<br />Error No: ' . mysqli_errno($this->connection) . '<br /> Error in: <b>' . $trace[1]['file'] . '</b> line <b>' . $trace[1]['line'] . '</b><br />' . $sql);
			}
		}
	}

	private function prepareSql($sql, ...$params) {
	    $sqlParamCount = count(explode("?", $sql)) - 1;
	    //var_dump($params);
	    //echo "param count = " . $sqlParamCount . "for " . $sql;
	    if ($sqlParamCount === 0) {
	        if ($params[0]) {
	            echo "Params count incorrect! Stopping!";
                debug_print_backtrace();
	            die();
            } else {
	            return $sql;
            }
        }
        if (count($params[0]) != $sqlParamCount) {
            echo "Params count incorrect! Stopping!";
            debug_print_backtrace();
            die();
        }

        foreach ($params[0] as $param) {
            $sql = preg_replace("/\?/", $param, $sql, 1);
        }
        return $sql;
    }

    public function closeTransaction() {
        mysqli_commit($this->connection);
    }

	public function escape($value) {
		if ($this->connection) {
			return mysqli_real_escape_string($value, $this->connection);
		}
	}

	public function countAffected() {
		if ($this->connection) {
			return mysqli_affected_rows($this->connection);
		}
	}

	public function getLastId() {
		if ($this->connection) {
			return mysqli_insert_id($this->connection);
		}
	}
	
	public function isConnected() {
		if ($this->connection) {
			return true;
		} else {
			return false;
		}
	}
	
	public function __destruct() {
		if ($this->connection) {
			mysqli_close($this->connection);
		}
	}
}