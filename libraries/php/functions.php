<?php
function load_view($view_name, $params = null)
{
    include './view/site.php';
}

function load_model($model_name, $params = null)
{
    include './controller/'.$model_name.'.php';
}

function gen_card_box($title, $details, $category, $question_id, $comments_count = 0, $visibility = 'block')
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
						<?php
						echo $category;
						?>
                    </span>

					<a href="open_question?question_id=<?php echo $question_id; ?>" class="card-link float-right">Open</a>
				</div>
			</div>
		</div>
	</div>
	<?php
}