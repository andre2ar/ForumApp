<?php
$question = $params['question'];
$answers  = $params['comments'];
?>
<div class="row">
    <div class="col-lg-12">
        <h1 class="text-center">
		    <?php echo $question['questionTitle'];?>
        </h1>
        <hr>

        <p class="text-justify show-question-details">
            <?php echo $question['questionDetails']?>
        </p>
        <button id="answerQuestion" type="button" class="btn btn-primary float-right">
            <i class="fas fa-reply"></i> Answer
        </button>

        <div id="answerSpace" style="display: none;">
            <form method="post" id="answerForm">
                <div class="form-group">
                    <textarea class="form-control" name="answerText" placeholder="Write your answer" id="answerText"></textarea>

                    <button type="submit" class="btn btn-primary btn-sm float-right">Post answer <i class="far fa-share-square"></i></button>
                </div>
                <input type="hidden" name="questionId" value="<?php echo $question['questionId'] ?>">
            </form>
        </div>
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

        ?>
        <div class="alert alert-primary text-center" role="alert" style="display: none;">
            Be the first to <a class="alert-link">answer</a> this question!
        </div>
    </div>
</div>
