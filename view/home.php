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
                <input required type="text" class="form-control" name="questionTitle" placeholder="What is your question?">
                <textarea required class="form-control" name="questionDetails" placeholder="Describe your question" rows="6"></textarea>
                <select required size="5" class="form-control" name="category">
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
            <button type="reset" class="btn btn-outline-secondary btn-lg float-right">Reset</button>
        </form>


    </div>
</div>