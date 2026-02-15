<h1 class="question-header"> Co twoim zdaniem było trudniejsze? </h1>
<a class="option-button option-a"></a>
<a class="option-button option-b"></a>
<a class="option-button option-button-idk">Nie wiem :(</a>

<script>
    async function load_next_question() {
        const data = await (await fetch('/get_question.php')).json();
        document.querySelector('.option-a').innerHTML = data['option_a'];
        document.querySelector('.option-b').innerHTML = data['option_b'];
    }

    load_next_question();

    async function answer_with(option) {
        const response = await fetch(`/question_answer.php?option=${option}`);
        if (response.status == 200) {
            await load_next_question();
        } else {
            alert("Błąd przy odpowiadaniu!");
        }
    }

    document.querySelector('.option-a').addEventListener('click', () => {
        answer_with('a')
    })

    document.querySelector('.option-b').addEventListener('click', () => {
        answer_with('b')
    })

    document.querySelector('.option-button-idk').addEventListener('click', () => {
        answer_with('none')
    })
</script>