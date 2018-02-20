<?php
$question = $params['question'];
//print_r($question);
?>
<div class="row">
    <div class="col-lg-12">
        <h1 class="text-center">
		    <?php echo $question['postTitle'];?>
        </h1>
        <hr>

        <p class="text-justify" style="min-height: 10rem;">
            <?php echo $question['postDetails']?>
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
                <input type="hidden" name="question_id" value="<?php echo $question['postId'] ?>">
            </form>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-lg-12">
        <h3>Answers</h3>

        <div class="alert alert-primary text-center" role="alert" style="display: none;">
            Be the first to <a class="alert-link">answer</a> this question!
        </div>
    </div>
</div>
