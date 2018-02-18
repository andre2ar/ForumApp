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

            <button type="submit" class="btn btn-primary btn-lg float-right">Share</button>
            <button id="clearFields" type="reset" class="btn btn-outline-secondary btn-lg float-right">Reset</button>
        </form>


    </div>
</div>

<div class="row">
    <h1>Questions</h1>
    <div class="col-lg-12">
        <div class="row">
        <?php
        $questions = $params['questions'];
        foreach($questions as $question)
        {
            ?>
            <div class="col-lg-4 card_edit">
                <div class="card" style="width: 23rem; height: 18rem">
                    <div class="card-body">
                        <div style="height: 3rem;">
                            <h5 class="card-title module line-clamp-title"><?php echo $question['postTitle']; ?></h5>
                        </div>

                        <p class="card-text module line-clamp-text" style="height: 9.3rem">
                            <?php echo $question['postDetails']; ?>
                        </p>
                        <div>
                            <span class="badge badge-secondary">4 answers</span>
                            <a href="#" class="card-link float-right">Open</a>
                        </div>


                    </div>
                </div>
            </div>
            <?php
        }
        ?>
        </div>
    </div>
</div>