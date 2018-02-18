<?php
//require_once "./libraries/php/functions.php";
define("HOUR", 60*60);
define("DAY", HOUR*24);
define("MONTH", DAY*30);
define("YEAR", MONTH*12);

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
            case 'login':
                if(
                    !isset($data['email']) ||
                    !isset($data['password'])||
                    !filter_var($data['email'], FILTER_VALIDATE_EMAIL)
                )
                {
                    return json_encode(['success' => false]);
                }else
                {

                    $userDetails = $this->db_connection->get_user($data['email']);
                    if(password_verify($data['password'], $userDetails['userPassword']))
                    {
                        setcookie("logged_user", $data['email'], time() + MONTH, '/');
                        setcookie("user_id", $userDetails['userId'], time() + MONTH, '/');

                        $_SESSION['logged_user'] = $data['email'];
                        $_SESSION['user_id'] = $userDetails['userId'];

                        return json_encode(['success' => true]);
                    }
                    else
                    {
                        return json_encode(['success' => false]);
                    }
                }
                break;
            case 'logout':
                if(isset($_SESSION['logged_user']) || isset($_COOKIE['logged_user'])){
                    unset($_SESSION['logged_user']);
                    unset($_SESSION['user_id']);

                    unset($_COOKIE['logged_user']);
                    unset($_COOKIE['user_id']);

                    setcookie('logged_user', '', time() -1, '/');
                    setcookie('user_id', '', time() -1, '/');

                    return json_encode(['success' => true]);
                }else
                {
                    return json_encode(['success' => false]);
                }
                break;
            case 'signUp':
                if(
                    !isset($data['name']) ||
                    !isset($data['email']) ||
                    !isset($data['password']) ||
                    !isset($data['password-confirmation']) ||
                    strlen($data['name']) < 3 ||
                    !filter_var($data['email'], FILTER_VALIDATE_EMAIL) ||
                    strcmp($data['password'], $data['password-confirmation']) !== 0
                )
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