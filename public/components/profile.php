<?php  
    require_once __DIR__ . '/../../vendor/autoload.php';

    use App\ClassResolver;
    use App\OAuth;
    use App\Questions;
?>

<div class="profile-page">

    <h1 style="user-select: none; text-align: center;"> 
        Dzień dobry numerze USOS #<?php echo OAuth::fetch_user_id() ?>
    </h1>
    <p style="text-align: center; user-select: none;">
        Czy wiesz że odpowiedziałaś/łeś już na <?php echo Questions::get_answered_question_count() ?> pytań?
    </p>

    <div class="cat-gif"></div>
    <p>
        Zobacz tego gifa z kotem
    </p>

    <h3>Twoje przedmioty</h3>
    <p style="font-size: small;">
    <?php 

        try {
            $classes = ClassResolver::match_classes_from_usos_to_local();
        } catch (\Throwable $th) {
            $classes = [];
        }
        
        foreach ($classes as $class => $class_name) {
            echo "- $class_name</br>";
        }

    ?>
    </p>
</div>

<script>

let random_id = Math.floor(Math.random() * 11) + 1 
document.querySelector(".cat-gif").style.backgroundImage = `url("/assets/cat-gifs/cat-${random_id}.gif")`
</script>