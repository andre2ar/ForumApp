<?php
function loadView($view_name, $params = null)
{
    include './view/site.php';
}

function loadModel($model_name, $params = null)
{
    include './controller/'.$model_name.'.php';
}

function returnCategories()
{
	return $categories = [
		"Animal",
		"Art",
		"Automotive",
		"Aviation",
		"Biology",
		"Chemistry",
		"Computer",
		"Travel",
		"Nature",
		"Sport",
		"Geek",
		"Geograph",
		"Language",
		"Movie",
		"Science",
		"Television",
		"Video game",
		"Other"
	];
}

function genQuestionBox($title, $details, $category, $questionId, $answersCount = 0, $visibility = 'block')
{
	?>
	<div class="col-lg-4 card_edit" style="display: <?php echo $visibility;?>">
		<div class="card" style="width: 23rem; height: 18rem">
			<div class="card-body">
				<div style="height: 3rem;">
					<h5 class="card-title module line-clamp-title"><?php echo $title; ?></h5>
				</div>

				<p class="card-text module line-clamp-text" style="height: 9.3rem"><?php echo $details; ?></p>

				<div class="text-center">
					<span class="badge badge-secondary float-left"><span class="answersCount"><?php echo $answersCount?></span> answers</span>
					<span class="badge badge-primary category">
                        <i class="fa fa-compass"></i>
                        <span>
						<?php
						echo $category;
						?>
                        </span>
                    </span>

					<a target="_blank" href="open_question?question_id=<?php echo $questionId; ?>" class="float-right">
                        <button type="button" class="btn btn-outline-primary">Open</button>
                    </a>
				</div>
			</div>
		</div>
	</div>
	<?php
}

function genAnswerBox($userName, $answerText, $answerId, $userId = null, $visibility = 'block')
{
    if($userId === null)
        $editVisibility = 'none';
    else $editVisibility = 'block';

    ?>
    <div class="answer-box" data-answer-id="<?php echo $answerId; ?>" data-user-id="<?php echo $userId; ?>" style="display: <?php echo $visibility; ?>">
        <h4><?php echo $userName; ?></h4>
	    <?php
	    if($userId === null || (isset($_SESSION['forumAppUserId']) && $_SESSION['forumAppUserId'] === $userId)){
		    ?>
            <button data-answer-id="<?php echo $answerId; ?>" class="btn btn-outline-primary btn-sm float-right editAnswerButton" style="display: <?php echo $editVisibility; ?>;">
                <i class="far fa-edit"></i> Edit answer
            </button>
	    <?php } ?>

        <p data-answer-id="<?php echo $answerId;?>">
            <span><?php echo $answerText; ?></span>
        </p>
        <?php
        if($userId === null || (isset($_SESSION['forumAppUserId']) && $_SESSION['forumAppUserId'] === $userId)){
            ?>
            <div class="editAnswerArea" data-answer-id="<?php echo $answerId;?>" style="display: none;">
                <form>
                    <div class="form-group">
                        <textarea rows="4" data-answer-id="<?php echo $answerId; ?>" class="form-control" name="editAnswer"><?php echo $answerText; ?></textarea>
                    </div>
                    <button type="button" data-answer-id="<?php echo $answerId; ?>" class="btn btn-primary btn-sm float-right saveEditAnswerButton">Save answer <i class="far fa-save"></i></button>
                </form>
            </div>
        <?php } ?>
        <hr>
    </div>
    <?php
}