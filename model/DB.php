<?php

class DB
{
    private $db_host, $db_user, $db_password, $db_name, $db_connection;

    public function __construct($db_host, $db_user, $db_password, $db_name)
    {
        $this->db_host = $db_host;
        $this->db_user = $db_user;
        $this->db_password = $db_password;
        $this->db_name = $db_name;

        try
        {
            $param = array(PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES UTF8');
            $this->db_connection = new PDO('mysql:host='.$this->db_host.'; dbname='.$this->db_name, $this->db_user, $this->db_password, $param);
        }catch(Exception $e)
        {
            print $e->getMessage();
        }
    }

    public function answer_question($commentText, $questionId)
    {
    	$sql = "INSERT INTO comments(commentText, commentOwner, commentInPost)
				VALUES (:commentText, :commentOwner, :commentInPost)";

    	$prepared_sql = $this->db_connection->prepare($sql);

	    $prepared_sql->bindParam(':commentText', $commentText);
	    $prepared_sql->bindParam(':commentOwner', $_SESSION['user_id']);
	    $prepared_sql->bindParam(':commentInPost', $questionId);

	    if($prepared_sql->execute())
	    {
		    $questionId = $this->db_connection->lastInsertId();
	    }else $questionId = false;

	    return $questionId;
    }

    public function submit_question($data)
    {

        $sql = "INSERT INTO posts(postTitle, postDetails, postCategory, postOwner, postCreationTime) 
                VALUES (:questionTitle, :questionDetails, :questionCategory, :questionOwner, CURRENT_TIMESTAMP)";

        $prepared_sql = $this->db_connection->prepare($sql);

        $prepared_sql->bindParam(':questionTitle', $data['questionTitle']);
        $prepared_sql->bindParam(':questionDetails', $data['questionDetails']);
        $prepared_sql->bindParam(':questionCategory', $data['category']);
        $prepared_sql->bindParam(':questionOwner', $_SESSION['user_id']);

        if($prepared_sql->execute())
        {
        	$questionId = $this->db_connection->lastInsertId();
        }else $questionId = false;

        return $questionId;
    }

    public function add_new_user($data)
    {
        $sql = "INSERT INTO users(userName, userEmail, userPassword) 
                VALUES(:username, :useremail, :userpassword)";

        $prepared_sql = $this->db_connection->prepare($sql);

        $password = password_hash($data['password'], PASSWORD_BCRYPT);

        $prepared_sql->bindParam(":username", $data['name']);
        $prepared_sql->bindParam(":useremail", $data['email']);
        $prepared_sql->bindParam(":userpassword", $password);

        return $prepared_sql->execute();
    }

    public function get_user($email)
    {
        $sql = "SELECT userId, userEmail, userPassword FROM users WHERE userEmail = :useremail";

        $prepared_sql = $this->db_connection->prepare($sql);

        $prepared_sql->bindParam(":useremail", $email);

        $prepared_sql->execute();

        return $prepared_sql->fetch(PDO::FETCH_ASSOC);
    }

    public function get_questions($offset = 0, $how_many = 15, $params = null)
    {
        $limit = " LIMIT ".$how_many;
	    if($offset != 0)
	    {
	    	$offset = " OFFSET ".$offset;
	    }else $offset = '';

        $sql = "SELECT * FROM posts ORDER BY postCreationTime DESC".$limit.$offset;
        $prepared_sql = $this->db_connection->prepare($sql);
        $prepared_sql->execute();

        return $prepared_sql->fetchAll(PDO::FETCH_ASSOC);
    }

    public function get_question($question_id)
    {
    	$sql = "SELECT * FROM posts WHERE postId = ".$question_id;

    	$prepared_sql = $this->db_connection->prepare($sql);
	    $prepared_sql->execute();

	    return $prepared_sql->fetchAll(PDO::FETCH_ASSOC);
    }
}