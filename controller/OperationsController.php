<?php
//require_once "./libraries/php/functions.php";
define("HOUR", 60*60);
define("DAY", HOUR*24);
define("MONTH", DAY*30);
define("YEAR", MONTH*12);

class OperationsController
{
    public $dbConnection;
    public function __construct($dbConnection)
    {
        $this->dbConnection = $dbConnection;
    }

    public function doOperation($data)
    {
        $operation = $data['operation'];

        switch ($operation){
	        case 'search':
	        	if(isset($data['searchText']) && isset($data['where']))
		        {
		        	if(!isset($data['after']))
			        {
			        	$result = $this->dbConnection->search($data['searchText'], $data['where']);
			        }else
			        {
				        $result = $this->dbConnection->search($data['searchText'], $data['where'], $data['after']);
			        }
			        if(count($result) > 0)
			        	$success = true;
		        	else $success = false;
			        return json_encode(['success' => $success,'questions' => $result]);
		        }else
		        {
			        return json_encode(['success' => false]);
		        }
	        	break;
	        case 'editAnswer':
	        	if(isset($data['answerText']) && isset($data['answerId']))
		        {
		        	$result = $this->dbConnection->editAnswer($data['answerId'], $data['answerText']);
			        return json_encode(['success' => $result]);
		        }else
		        {
			        return json_encode(['success' => false]);
		        }
	        	break;
	        case 'editQuestion':
	        	if(isset($data['editQuestionTitle']) && isset($data['questionId']))
		        {
		        	$result = $this->dbConnection->editQuestion($data['editQuestionTitle'], $data['editQuestionDetails'], $data['editQuestionCategory'], $data['questionId']);
			        return json_encode(['success' => $result]);
		        }else
		        {
			        return json_encode(['success' => false]);
		        }
	        	break;
	        case 'answerQuestion':
	        	if(isset($_SESSION) && isset($_SESSION['forumAppUserId']))
		        {
		        	$comment_id = $this->dbConnection->answerQuestion($data['answerText'], $data['questionId']);
		        	return json_encode(['userName' => explode("@",  $_SESSION['forumAppLoggedUser'])[0], 'userId' => $_SESSION['forumAppUserId'],'commentId' => $comment_id]);
		        }
	        	break;
	        case 'getMoreQuestions':
				$questions = $this->dbConnection->getQuestions($data['after']);
				if($questions)
					return json_encode(['success' => true, 'questions' => $questions]);
				else return json_encode(['success' => false]);
	        	break;
	        case 'getMoreAnswers':
	        	$answers = $this->dbConnection->getAnswers($data['questionId'], $data['after']);
	        	if($answers)
			        return json_encode(['success' => true, 'answers' => $answers]);
		        else return json_encode(['success' => false]);
		        break;
            case 'submitQuestion':
                if(
                    !isset($data['questionTitle']) ||
                    !isset($data['questionDetails']) ||
                    !isset($data['questionCategory'])
                )
                {
                    return json_encode(['questionId' => false]);
                }else
                {
                    $result = $this->dbConnection->submitQuestion($data);
                    return json_encode(['questionId' => $result]);
                }
                break;
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
                    $userDetails = $this->dbConnection->getUser($data['email']);
                    if(password_verify($data['password'], $userDetails['userPassword']))
                    {
                        setcookie("forumAppLoggedUser", $data['email'], time() + MONTH, '/');
                        setcookie("forumAppUserId", $userDetails['userId'], time() + MONTH, '/');

                        $_SESSION['forumAppLoggedUser'] = $data['email'];
                        $_SESSION['forumAppUserId'] = $userDetails['userId'];

                        return json_encode(['success' => true]);
                    }
                    else
                    {
                        return json_encode(['success' => false]);
                    }
                }
                break;
            case 'logout':
                if(isset($_SESSION['forumAppLoggedUser']) || isset($_COOKIE['forumAppLoggedUser'])){
                    unset($_SESSION['forumAppLoggedUser']);
                    unset($_SESSION['forumAppUserId']);

                    unset($_COOKIE['forumAppLoggedUser']);
                    unset($_COOKIE['forumAppUserId']);

                    setcookie('forumAppLoggedUser', '', time() -1, '/');
                    setcookie('forumAppUserId', '', time() -1, '/');

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
                    $result = $this->dbConnection->addNewUser($data);
                    return json_encode(['success' => $result]);
                }

                break;
        }
    }
}

$operationController = new OperationsController($params['dbConnection']);
echo $operationController->doOperation($_POST);