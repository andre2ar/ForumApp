<?php
    $categories = returnCategories();
?>
<div id="searchArea" class="row">
    <div class="col">
        <!------------------------------- Search bar ------------------------------------------------------------>
        <form class="form-inline" method="post" name="searchForm">
            <input id="searchText" class="form-control" required name="searchText" type="search" placeholder="Search" aria-label="Search">
            <button id="searchButton" class="btn btn-light" type="submit"><i class="fas fa-search"></i> Search</button>
        </form>
    </div>

    <div class="col">
        <form class="form-inline">
            <select id="categoryToSearch" required class="form-control" name="questionCategory">
		        <?php
		        foreach($categories as $category)
		        {
			        ?>
                    <option value="<?= strtolower($category); ?>"><?= $category; ?></option>
			        <?php
		        }
		        ?>
            </select>

            <button class="btn btn-light" id="searchCategoryButton"><i class="fas fa-search"></i> Search by category</button>
        </form>
    </div>
</div>

<div class="row">
    <div class="col-lg-12">
        <h1 class="text-center">What about to share your questions?</h1>
        <form method="post" id="shareQuestion">
            <div class="form-group">
                <input id="yourPostTitle" autofocus required type="text" class="form-control" name="questionTitle" placeholder="What is your question?">
                <textarea id="yourPostDetails" class="form-control" name="questionDetails" placeholder="Describe your question" rows="6"></textarea>
                <select id="yourPostCategory" required size="5" class="form-control" name="questionCategory">
		            <?php
		            foreach($categories as $category)
		            {
			            ?>
                        <option value="<?= strtolower($category); ?>"><?= $category; ?></option>
			            <?php
		            }
		            ?>

                </select>
            </div>

            <button type="submit" class="btn btn-primary btn-lg float-right">Post <i class="far fa-share-square"></i></button>
            <button id="clearFields" type="reset" class="btn btn-outline-secondary btn-lg float-right">Reset</button>
        </form>


    </div>
</div>

<div class="row">
    <h1>Questions</h1>
    <div id="questions" class="col-lg-12 col-sm-12 col-md-12">
        <div class="row">
        <?php
        /***************** Enter point ***************/
        genQuestionBox('', '', '', "", 0, 'none');

        $questions = $params['questions'];
        if(count($questions) > 0)
        {
	        foreach($questions as $question)
	        {
		        genQuestionBox($question['questionTitle'], $question['questionDetails'], $question['questionCategory'], $question['questionId'], $question['answersCount']);
	        }
        }else
        {
            ?>
            <div id="noQuestionsAlert" class="alert alert-primary text-center" role="alert">
                <h4 class="alert-heading">No questions :(</h4>
                <p>What about our first question be your?</p>
            </div>
            <?php
        }
        ?>
        </div>
    </div>

    <input type="hidden" id="searchMade" value="false" name="searchMade">
    <input type="hidden" id="whereSearch" value="" name="whereSearch">
    <input type="hidden" id="whatSearch" value="" name="whatSearch">

</div>