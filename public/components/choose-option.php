<h1 class="question-header opacity-transition"> Co twoim zdaniem było trudniejsze? </h1>
<span class="progress-count" style="margin-top: -25px">0 z 10</span>
<div class="option-button option-a opacity-transition faded-out-button">Przedmiot A</div>
<div class="option-button option-b opacity-transition faded-out-button">Przedmiot B</div>
<div class="option-button option-button-idk opacity-transition faded-out-button">Nie wiem :(</div>

<div class="faq-modal">
    <div class="fag-modal-actual">
        <div class="faq-modal-content" style="display: flex; flex-direction: column; align-items: center;">
            <h1>Dziękuje :)</h1>
            <p style="text-align: center;">Odpowiedziałeś w tej sesji już na 10 pytań, możesz zrobić sobie przerwę albo kontynować</p>
            <div class="cat-gif"></div>
        </div>
        <div class="faq-modal-close-btn">Zamknij</div>
    </div>
</div>

<script>
let random_id = Math.floor(Math.random() * 11) + 1 
document.querySelector(".cat-gif").style.backgroundImage = `url("/assets/cat-gifs/cat-${random_id}.gif")`
</script>

<script>
    document.querySelector(".faq-modal-close-btn").addEventListener("click", () => {
        document.querySelector(".faq-modal").style.visibility = 'hidden'; 
    })

    let buttons_locked = false;
    let progress_count = 0;

    function add_to_progress_count() {
        if (progress_count < 10) {
            document.querySelector(".progress-count").innerHTML = `${++progress_count} z 10`
            if (progress_count == 10) {
                document.querySelector(".progress-count").innerHTML = '';
                document.querySelector('.faq-modal').style.visibility = 'visible';
            }
        }
    }

    function toggle_fade_item(selector) {
        document.querySelector(selector).classList.toggle('faded-out-button');
    }

    function toggle_lock_button(selector, is_red) {
        buttons_locked = !buttons_locked;
        document.querySelector(selector).classList.toggle(is_red ? 'locked-button-red' : 'locked-button');
    }

    async function load_next_question(instant) {
        const action = async () => {
            const data = await (await fetch('/get_question.php')).json();
            if (data['error']) {
                window.location.href = '/';
                return;
            }
            document.querySelector('.option-a').innerHTML = data['option_a'];
            document.querySelector('.option-b').innerHTML = data['option_b'];

            // fade_in_item('.question-header');
            toggle_fade_item('.option-a');
            toggle_fade_item('.option-b');
            toggle_fade_item('.option-button-idk');
        };

        if (instant) {
            await action();
            return;
        }
        
        setTimeout(action, 1500)
    }

    load_next_question(true);

    async function answer_with(option) {
        const response = await fetch(`/question_answer.php?option=${option}`);
        if (response.status == 200) {
            await load_next_question();
        } else {
            window.location.href = '/';
        }
        add_to_progress_count();
    }

    document.querySelector('.option-a').addEventListener('click', () => {
        if (buttons_locked) return;
        setTimeout(() => toggle_fade_item('.option-a'), 1000);
        setTimeout(() => toggle_lock_button('.option-a'), 1450);
        toggle_lock_button('.option-a');
        toggle_fade_item('.option-b');
        toggle_fade_item('.option-button-idk');
        answer_with('a')
    })

    document.querySelector('.option-b').addEventListener('click', () => {
        if (buttons_locked) return;
        setTimeout(() => toggle_fade_item('.option-b'), 1000);
        setTimeout(() => toggle_lock_button('.option-b'), 1450);
        toggle_lock_button('.option-b');
        toggle_fade_item('.option-a');
        toggle_fade_item('.option-button-idk');
        answer_with('b')
    })

    document.querySelector('.option-button-idk').addEventListener('click', () => {
        if (buttons_locked) return;
        setTimeout(() => toggle_fade_item('.option-button-idk'), 1000);
        setTimeout(() => toggle_lock_button('.option-button-idk', true), 1450);
        toggle_lock_button('.option-button-idk', true);
        toggle_fade_item('.option-a');
        toggle_fade_item('.option-b');
        answer_with('none')
    })
</script>