<h1 style="user-select: none;">
    Co trudniejsze na II
</h1>

<a class="option-button option-button-home-screen" href="/game.php">
    Klikaj pytania
</a>

<a class="option-button option-button-home-screen faq-btn">
    Co to jest
</a>

<div class="faq-modal">
    <div class="fag-modal-actual">
        <div class="faq-modal-content">
            <h3>W skrócie?</h3>
            <p>
                Chcemy zebrać jak najwięcej "małych" porównań między przedmiotami 
                żeby mniej więcej uzyskać jakiś porzadek trudności
            </p>
            <h3>Po co ten porządek?</h3>
            <p>
                Żeby mieć jakiś uśredniony consensus 
                na temat tego co tak naprawde jest "trudne" na naszej uczelni
            </p>
            <h3>To ma sens?</h3>
            <p>
                Może
            </p>
            <h3>Po co logowanie USOSem?</h3>
            <p>
                Inaczej ktoś by mógł łatwo zaspamić. Teraz jest to przynajmniej utrudnione
            </p>
            <h3>Jakie dane ciągniecie z USOSa</h3>
            <p>
                Tylko ID użytkownika (nie numer albumu)
            </p>
        </div>
        <div class="faq-modal-close-btn">Zamknij</div>
    </div>
</div>

<script>
document.querySelector(".faq-modal-close-btn").addEventListener("click", () => {
    document.querySelector(".faq-modal").style.visibility = 'hidden'; 
})

document.querySelector(".faq-btn").addEventListener("click", () => {
    document.querySelector(".faq-modal").style.visibility = 'visible'; 
})
</script>