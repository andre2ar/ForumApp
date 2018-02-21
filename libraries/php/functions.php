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

function genQuestionBox($title, $details, $category, $question_id, $comments_count = 0, $visibility = 'block')
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
					<span class="badge badge-secondary float-left"><span class="count_answers"><?php echo $comments_count?></span> answers</span>
					<span class="badge badge-primary category">
                        <i class="fa fa-compass"></i>
                        <span>
						<?php
						echo $category;
						?>
                        </span>
                    </span>

					<a href="open_question?question_id=<?php echo $question_id; ?>" class="card-link float-right">Open</a>
				</div>
			</div>
		</div>
	</div>
	<?php
}

function genAnswerBox($userId, $userName, $answerText, $answerId, $visibility = 'block')
{
    ?>
    <div class="answer-box" data-answer-id="<?php echo $answerId; ?>" data-user-id="<?php echo $userId; ?>" style="display: <?php echo $visibility; ?>">
        <h4><?php echo $userName; ?></h4>
        <p>
            <?php echo $answerText; ?>
        </p>
        <hr>
    </div>
    <?php
}