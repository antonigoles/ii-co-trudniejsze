<div class="question-count"></div>

<h1 class="question-header opacity-transition"> Co twoim zdaniem było trudniejsze? </h1>
<a class="option-button option-a opacity-transition faded-out-button">Przedmiot A</a>
<a class="option-button option-b opacity-transition faded-out-button">Przedmiot B</a>
<a class="option-button option-button-idk opacity-transition faded-out-button">Nie wiem :(</a>

<script>
    let buttons_locked = false;

    function toggle_fade_item(selector) {
        document.querySelector(selector).classList.toggle('faded-out-button');
    }

    function toggle_lock_button(selector, is_red) {
        buttons_locked = !buttons_locked;
        document.querySelector(selector).classList.toggle(is_red ? 'locked-button-red' : 'locked-button');
    }

    async function load_next_question() {
        // fade_out_item('.question-header');
        
        setTimeout(async () => {
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
        }, 1500)
    }

    load_next_question();

    async function answer_with(option) {
        const response = await fetch(`/question_answer.php?option=${option}`);
        if (response.status == 200) {
            await load_next_question();
        } else {
            window.location.href = '/';
        }
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