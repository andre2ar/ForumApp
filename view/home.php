<?php
    $categories = [
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
?>
<div class="row">
    <div class="col-lg-12">
        <h1 class="text-center">What about to share your questions?</h1>
        <form method="post" id="shareQuestion">
            <div class="form-group">
                <input id="yourPostTitle" autofocus required type="text" class="form-control" name="questionTitle" placeholder="What is your question?">
                <textarea id="yourPostDetails" class="form-control" name="questionDetails" placeholder="Describe your question" rows="6"></textarea>
                <select id="yourPostCategory" required size="5" class="form-control" name="category">
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
    <div class="col-lg-12">
        <div class="row">
        <?php
        /***************** Enter point ***************/
        gen_card_box('', '', '', 0, 'none');

        $questions = $params['questions'];
        foreach($questions as $question)
        {
            gen_card_box($question['postTitle'], $question['postDetails'], $question['postCategory']);
        }
        ?>
        </div>
    </div>
</div>