<?php
$question = $params['question'];
$answers  = $params['comments'];
$categories = returnCategories();
?>
<div class="row">
    <div class="col-lg-12 question-area">
        <h1 class="text-center question-title">
		    <span><?php echo $question['questionTitle'];?></span>
        </h1>
        <hr>

        <p class="text-justify show-question-details">
            <span><?php echo $question['questionDetails']?></span>
        </p>
        <span class="badge badge-primary category float-right">
                                <i class="fa fa-compass"></i>
            <span>
                <?php
                echo $question['questionCategory'];
                ?>
            </span>
        </span><br>

        <?php
	    if(isset($_SESSION['forumAppUserId']) && $_SESSION['forumAppUserId'] === $question['questionOwner']){
	    ?>
        <button id="editQuestionButton" class="btn btn-outline-primary btn-sm">
            <i class="far fa-edit"></i> Edit question
        </button>
        <?php } ?>

        <button id="answerQuestion" type="button" class="btn btn-primary float-right">
            <i class="fas fa-reply"></i> Answer
        </button>

        <div id="answerSpace" style="display: none;">
            <form method="post" id="answerForm">
                <div class="form-group">
                    <textarea required class="form-control" name="answerText" placeholder="Write your answer" id="answerText"></textarea>

                    <button type="submit" class="btn btn-primary btn-sm float-right">Post answer <i class="far fa-share-square"></i></button>
                </div>
                <input id="questionId" type="hidden" name="questionId" value="<?php echo $question['questionId'] ?>">
            </form>
        </div>

        <?php
        if(isset($_SESSION['forumAppUserId']) && $_SESSION['forumAppUserId'] === $question['questionOwner']){
        ?>
        <div id="editQuestionArea" style="display: none;">
            <form id="editQuestion" name="editQuestion" method="post">
                <div class="form-group">
                    <input required id="editQuestionTitle" name="editQuestionTitle" type="text" class="form-control" value="<?php echo $question['questionTitle']?>">
                </div>

                <div class="form-group">
                    <textarea class="form-control" id="editQuestionDetails" name="editQuestionDetails"><?php echo $question['questionDetails']?></textarea>
                </div>


                <select id="editQuestionCategory" required size="5" class="form-control" name="editQuestionCategory">
		            <?php
		            foreach($categories as $category)
		            {
		                if(strtolower($category) === $question['questionCategory'])
		                    $selected = 'selected';
		                else $selected = '';
			            ?>
                        <option <?php echo $selected; ?> value="<?= strtolower($category); ?>"><?= $category; ?></option>
			            <?php
		            }
		            ?>

                </select>

                <button type="submit" id="submitEdit" class="btn btn-primary btn-sm float-right">Save question <i class="far fa-save"></i></button>
            </form>
        </div>
        <?php } ?>
    </div>
</div>

<div class="row">
    <div class="col-lg-12">
        <h3>Answers</h3><br>
        <?php
        genAnswerBox('', '','', '', 'none');
        foreach ($answers as $answer)
        {
	        genAnswerBox($answer['userId'],explode("@", $answer['userEmail'])[0], $answer['answerText'], $answer['answerId']);
        }
        if(count($answers) === 0){
        ?>
        <div class="alert alert-primary text-center" role="alert">
            Be the first to <a class="alert-link">answer</a> this question!
        </div>
        <?php } ?>
    </div>
</div>
