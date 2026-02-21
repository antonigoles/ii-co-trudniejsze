<?php  
    require_once __DIR__ . '/../../vendor/autoload.php';

    use App\ArrayUtilities;
    use App\ClassResolver;
    use App\OAuth;
    use App\Questions;
?>

<div class="profile-page">
    <div class="cat-gif"></div>

    <h1 style="user-select: none; text-align: center;"> 
        Dzień dobry numerze USOS #<?php echo OAuth::fetch_user_id() ?>
    </h1>
    <p style="text-align: center; user-select: none;">
        Czy wiesz że odpowiedziałaś/łeś już na <?php echo Questions::get_answered_question_count() ?> pytań?
    </p>


    <h3>Twoje przedmioty</h3>
    <p style="font-size: small;">
    <?php 

        try {
            $classes_grouped = ArrayUtilities::group_by('major_name', OAuth::fetch_user_courses_filtered());
        } catch (\Throwable $th) {
            $classes_grouped = [];
        }
        
        foreach ($classes_grouped as $class_group => $classes) {
            echo "<b>$class_group</b></br>";
            foreach ($classes as $class) {
                echo "- ".$class['course_name']." </br>";
            }
        }

    ?>
    </p>
</div>

<script>

let random_id = Math.floor(Math.random() * 11) + 1 
document.querySelector(".cat-gif").style.backgroundImage = `url("/assets/cat-gifs/cat-${random_id}.gif")`
</script>