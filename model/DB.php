<?php

class DB
{
    private $dbHost, $dbUser, $dbPassword, $dbName, $dbConnection;

    public function __construct($dbHost, $dbUser, $dbPassword, $dbName)
    {
        $this->dbHost     = $dbHost;
        $this->dbUser     = $dbUser;
        $this->dbPassword = $dbPassword;
        $this->dbName     = $dbName;

        try
        {
            $param              = array(PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES UTF8');
            $this->dbConnection = new PDO( 'mysql:host=' . $this->dbHost . '; dbname=' . $this->dbName, $this->dbUser, $this->dbPassword, $param);
        }catch(Exception $e)
        {
            print $e->getMessage();
        }
    }

    public function answerQuestion($answerText, $questionId)
    {
    	$sql = "INSERT INTO answers(answerText, answerOwner, answerInQuestion, answerCreationTime)
				VALUES (:answerText, :answerOwner, :answerInQuestion, CURRENT_TIMESTAMP)";

    	$preparedSQL = $this->dbConnection->prepare($sql);

	    $preparedSQL->bindParam(':answerText', $answerText);
	    $preparedSQL->bindParam(':answerOwner', $_SESSION['forumAppUserId']);
	    $preparedSQL->bindParam(':answerInQuestion', $questionId);

	    if($preparedSQL->execute())
	    {
		    $questionId = $this->dbConnection->lastInsertId();
	    }else $questionId = false;

	    return $questionId;
    }

    public function submitQuestion($data)
    {
        $sql = "INSERT INTO questions(questionTitle, questionDetails, questionCategory, questionOwner, questionCreationTime) 
                VALUES (:questionTitle, :questionDetails, :questionCategory, :questionOwner, CURRENT_TIMESTAMP)";

        $preparedSQL = $this->dbConnection->prepare($sql);

        $preparedSQL->bindParam(':questionTitle', $data['questionTitle']);
        $preparedSQL->bindParam(':questionDetails', $data['questionDetails']);
        $preparedSQL->bindParam(':questionCategory', $data['questionCategory']);
        $preparedSQL->bindParam(':questionOwner', $_SESSION['forumAppUserId']);

        if($preparedSQL->execute())
        {
        	$questionId = $this->dbConnection->lastInsertId();
        }else $questionId = false;

        return $questionId;
    }

    public function addNewUser($data)
    {
        $sql = "INSERT INTO users(userName, userEmail, userPassword) 
                VALUES(:username, :useremail, :userpassword)";

        $preparedSQL = $this->dbConnection->prepare($sql);

        $password = password_hash($data['password'], PASSWORD_BCRYPT);

        $preparedSQL->bindParam(":username", $data['name']);
        $preparedSQL->bindParam(":useremail", $data['email']);
        $preparedSQL->bindParam(":userpassword", $password);

        return $preparedSQL->execute();
    }

    public function editQuestion($questionTitle, $questionDetails, $questionCategory, $questionId)
    {
		$sql = "UPDATE questions SET questionTitle = '".$questionTitle."', questionDetails = '".$questionDetails."', questionCategory = '".$questionCategory."' WHERE questionId = $questionId";

		$preparedSQL = $this->dbConnection->prepare($sql);

	    return $preparedSQL->execute();
    }

    public function editAnswer($answerId, $answerText)
    {
	    $sql = "UPDATE answers SET answerText = '".$answerText."' WHERE answerId = $answerId";

	    $preparedSQL = $this->dbConnection->prepare($sql);

	    return $preparedSQL->execute();
    }

    public function getUser($email)
    {
        $sql = "SELECT userId, userEmail, userPassword FROM users WHERE userEmail = :useremail";

        $preparedSQL = $this->dbConnection->prepare($sql);

        $preparedSQL->bindParam(":useremail", $email);

        $preparedSQL->execute();

        return $preparedSQL->fetch(PDO::FETCH_ASSOC);
    }

    public function getQuestions($offset = 0, $how_many = 15, $params = null)
    {
        $limit = " LIMIT ".$how_many;
	    if($offset != 0)
	    {
	    	$offset = " OFFSET ".$offset;
	    }else $offset = '';

        $sql = "SELECT questions.*, COUNT(answerInQuestion) as answersCount 
				FROM questions LEFT OUTER JOIN answers
				ON questions.questionId = answers.answerInQuestion
				GROUP BY questionId
				ORDER BY questionCreationTime DESC".$limit.$offset;

        $preparedSQL = $this->dbConnection->prepare($sql);
        $preparedSQL->execute();

        return $preparedSQL->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getAnswers($questionId, $offset = 0, $how_many = 15)
    {
	    $limit = " LIMIT ".$how_many;
	    if($offset != 0)
	    {
		    $offset = " OFFSET ".$offset;
	    }else $offset = '';

    	$sql = "SELECT userEmail, userId, answerText, answerId FROM answers, users 
					WHERE answerInQuestion = $questionId AND userId = answers.answerOwner
					ORDER BY answerCreationTime DESC".$limit.$offset;

	    $preparedSQL = $this->dbConnection->prepare($sql);
	    $preparedSQL->execute();

	    return $preparedSQL->fetchAll(PDO::FETCH_ASSOC);
    }

    public function search($searchText, $where, $offset = 0, $how_many = 15)
    {
	    $limit = " LIMIT ".$how_many;
	    if($offset != 0)
	    {
		    $offset = " OFFSET ".$offset;
	    }else $offset = '';

    	if($where == 'text')
	    {
		    $sql = "SELECT * FROM questions WHERE questionTitle LIKE '%".$searchText."%' OR questionDetails LIKE '%".$searchText."%'";
	    }else if($where == 'category')
	    {
		    $sql = "SELECT * FROM questions WHERE questions.questionCategory LIKE '".$searchText."'";
	    }

	    $sql .= " ORDER BY questionCreationTime DESC".$limit.$offset;

	    $preparedSQL = $this->dbConnection->prepare($sql);

	    $preparedSQL->execute();

	    return $preparedSQL->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getQuestion($question_id)
    {
    	$sql = "SELECT * FROM questions WHERE questionId = ".$question_id;

    	$preparedSQL = $this->dbConnection->prepare($sql);
	    $preparedSQL->execute();

	    return $preparedSQL->fetchAll(PDO::FETCH_ASSOC);
    }
}