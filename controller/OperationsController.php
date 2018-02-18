<?php
//require_once "./libraries/php/functions.php";
class OperationsController
{
    public $db_connection;
    public function __construct($db_connection)
    {
        $this->db_connection = $db_connection;
    }

    public function doOperation($data)
    {
        $operation = $data['operation'];

        switch ($operation){
            case 'signUp':
                if(strlen($data['name']) < 3 || !filter_var($data['email'], FILTER_VALIDATE_EMAIL) || strcmp($data['password'], $data['password-confirmation']) !== 0)
                {
                    return json_encode(['success' => false]);
                }else
                {
                    $result = $this->db_connection->add_new_user($data);
                    return json_encode(['success' => $result]);
                }

                break;
        }
    }
}

$operationController = new OperationsController($params['db_connection']);
echo $operationController->doOperation($_POST);