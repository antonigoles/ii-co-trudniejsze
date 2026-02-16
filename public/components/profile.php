<?php  
    require_once __DIR__ . '/../../vendor/autoload.php';

    use App\OAuth;
    use App\Questions;
?>

<h1 style="user-select: none; text-align: center;"> 
    Dzień dobry numerze USOS #<?php echo OAuth::fetch_user_id() ?>
</h1>
<p>
    Czy wiesz że odpowiedziałaś/łeś już na <?php echo Questions::get_answered_question_count() ?> pytań?
</p>

<div class="cat-gif"></div>
<p>
    Zobacz tego gifa z kotem
</p>

<script>

let random_id = Math.floor(Math.random() * 11) + 1 
document.querySelector(".cat-gif").style.backgroundImage = `url("/assets/cat-gifs/cat-${random_id}.gif")`
</script>