<?php

class MysqliExtensions
{
    /**
     * Open a new connection to the MySQL server.
     * @return mysqli
     * @throws Exception
     */
    public static function getDatabaseConnection(): mysqli
    {
        $mysql = new mysqli();
        //$mysql->ssl_set(MYSQL_CLIENT_KEY, MYSQL_CLIENT_CERT, MYSQL_CA_CERT, null, null);
        $mysql->real_connect(MYSQL_HOST, MYSQL_USERNAME, MYSQL_PASSWORD, MYSQL_DATABASE, MYSQL_PORT, null, MYSQLI_CLIENT_SSL_DONT_VERIFY_SERVER_CERT);

        if ($mysql->connect_error)
            throw new Exception($mysql->connect_error, $mysql->connect_errno);

        return $mysql;
    }

    /**
     * Performs a query on the database.
     * @param mysqli $db
     * @param string $query
     * @param mixed ...$params
     * @return bool|mysqli_result
     * @throws Exception
     */
    public static function query(mysqli $db, string $query, ...$params)
    {
        if (!isset($db))
            throw new InvalidArgumentException('db is null.');

        $stmt = $db->prepare($query);

        if (!$stmt)
            throw new mysqli_sql_exception($db->error, $db->errno);

        if (count($params) > 0) {
            $paramTypes = self::getParamTypes($params);
            $temp = [$paramTypes];

            foreach ($params as &$value)
                $temp[] = &$value;

            call_user_func_array([$stmt, 'bind_param'], $temp);
        }

        if (!$stmt->execute())
            throw new mysqli_sql_exception($stmt->error, $stmt->errno);

        return $stmt->get_result();
    }

    private static function getParamTypes($params): string
    {
        if (!is_array($params)) {
            if (is_string($params))
                return 's';
            elseif (is_int($params))
                return 'i';
            elseif (is_double($params) || is_float($params))
                return 'd';
            else
                return 'b';
        } else {
            $result = '';

            foreach ($params as $value)
                $result .= self::getParamTypes($value);

            return $result;
        }
    }
}