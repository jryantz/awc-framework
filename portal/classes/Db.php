<?php

class Db {
    private $con;
    
    /*
     * Connect function:
     *
     * gets values from global variable for login,
     * connects with mysqli,
     * checks to see if connection was successful.
     *
     */
    private function connect() {

        $this->con = mysqli_connect(DB_HOST, DB_USER, DB_PASS, DB_NAME);
        
        if(!$this->con) {
            die('Connection not successful.');
        }
    }
    
    /*
     * Close function:
     *
     * closes the connection,
     * sets the variable to null.
     *
     */
    private function close() {
        
        mysqli_close($this->con);
        $this->con = null;
    }
    
    /*
     * Query function:
     *
     * splits the sets array into seperate arrays of data,
     * splits that array into variables,
     * checks if the operator given is a valid one,
     * creates a total list of query-able values,
     * connect to db,
     * querys the db with provided data,
     * returns the query.
     *
     */
    public function query($table, $sets = array()) {
        
        if(isset($sets[0])) {
            $total = null;
            $items = count($sets);

            foreach($sets as $set) {
                if(count($set) === 4) {
                    $operators = array('=', '>', '<', '>=', '<=');

                    $field = $set[0];
                    $operator = $set[1];
                    $value = $set[2];
                    $bool = $set[3];

                    if($items > 1) {
                        if(in_array($operator, $operators)) {
                            $total .= $field . $operator . '\'' . $value . '\'' . $bool . ' ';
                        }
                    } else {
                        
                        if(in_array($operator, $operators)) {
                            $total .= $field . $operator . '\'' . $value . '\'';
                        }
                    }
                    
                    $items -= 1;
                }
            }

            $this->connect();
            $query = mysqli_query($this->con, "SELECT * FROM $table WHERE $total");
            $this->close();
        } else {
            
            $this->connect();
            $query = mysqli_query($this->con, "SELECT * FROM $table");
            $this->close();
        }
            
        if(!$query) {
            return;
        }

        return $query;
    }
    
    /*
     * Insert function:
     *
     * get all of the data from the array,
     * create a string with data separated by commas and surrounded by single quotes,
     * connect to db,
     * insert data,
     * return true if complete, false if not.
     *
     */
    public function insert($table, $data = array()) {
        
        $values = '';
        $x = 1;
        
        foreach($data as $item) {
            $values .= '\'' . $item . '\'';
			if($x < count($data)) {
				$values .= ', ';
			}
			$x++;
		}
        
        $this->connect();
        $insert = mysqli_query($this->con, "INSERT INTO $table VALUES ($values)");
        $this->close();
        
        if(!$insert) {
            return;
        }
        
        return $insert;
    }
    
    /*
     * Update function:
     *
     * gets all the data from each array,
     * checks what the data is that we are setting by getting the information from the data array and parsing it to a readable string,
     * checks if the operator is valid,
     * gets all data,
     * creates string, 
     * then checks the sets array to see what we are finding in the table to specify the row to update,
     * checks if the operator is valid,
     * gets data,
     * creates string, 
     * updates value in the database.
     *
     */
    public function update($table, $data = array(), $sets = array()) {
        
        $totalData = null;
        
        if(count($data) === 3) {
            $operators = array('=');

            $field = $data[0];
            $operator = $data[1];
            $value = $data[2];

            if(in_array($operator, $operators)) {
                $totalData .= $field . $operator . '\'' . $value . '\'';
            }
        }
        
        $totalSets = null;
        $items = count($sets);

        foreach($sets as $set) {
            if(count($set) === 4) {
                $operators = array('=', '>', '<', '>=', '<=');

                $field = $set[0];
                $operator = $set[1];
                $value = $set[2];
                $bool = $set[3];

                if($items > 1) {
                    if(in_array($operator, $operators)) {
                        $totalSets .= $field . $operator . '\'' . $value . '\'' . $bool . ' ';
                    }
                } else {

                    if(in_array($operator, $operators)) {
                        $totalSets .= $field . $operator . '\'' . $value . '\'';
                    }
                }
                
                $items -= 1;
            }
        }
        
        $this->connect();
        $update = mysqli_query($this->con, "UPDATE $table SET $totalData WHERE $totalSets");
        $this->close();
        
        if(!$update) {
            return;
        }
        
        return $update;
    }
    
    /*
     * Delete function:
     *
     * splits the sets array into seperate arrays of data,
     * splits that array into variables,
     * checks if the operator given is a valid one,
     * creates a total list of query-able values,
     * connect to db,
     * deletes the rows in the db that corresponds to provided data,
     * returns the query.
     *
     */
    public function delete($table, $sets = array()) {
        
        $total = null;
        $items = count($sets);
        
        foreach($sets as $set) {
            if(count($set) === 4) {
                $operators = array('=', '>', '<', '>=', '<=');

                $field = $set[0];
                $operator = $set[1];
                $value = $set[2];
                $bool = $set[3];

                if($items > 1) {
                    if(in_array($operator, $operators)) {
                        $total .= $field . $operator . '\'' . $value . '\'' . $bool . ' ';
                    }
                } else {
                    
                    if(in_array($operator, $operators)) {
                        $total .= $field . $operator . '\'' . $value . '\'';
                    }
                }
                
                $items -= 1;
            }
        }

        $this->connect();
        $delete = mysqli_query($this->con, "DELETE FROM $table WHERE $total");
        $this->close();
        
        if(!$delete) {
            return;
        }

        return $delete;
    }
    
}

?>