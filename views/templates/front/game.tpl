<div class="jeu-gagnant-game">
    <h1>🎯 Trouvez le numéro gagnant !</h1>
    <p>Choisissez un nombre entre 1 et 10 :</p>
    
    <form method="post" action="">
        <div class="number-selector">
            {for $i=1 to 10}
                <label class="number-option">
                    <input type="radio" name="number_chosen" value="{$i}" required>
                    <span class="number-circle">{$i}</span>
                </label>
            {/for}
        </div>
        <input type="hidden" name="jeu_action" value="play">
        <button type="submit" class="btn-validate">Valider mon choix</button>
    </form>
    
    <p><small>Email : {$email}</small></p>
</div>

<style>
.number-selector {
    display: flex;
    flex-wrap: wrap;
    gap: 10px;
    margin: 20px 0;
    justify-content: center;
}
.number-option input {
    display: none;
}
.number-circle {
    display: inline-block;
    width: 50px;
    height: 50px;
    border: 2px solid #25b9d7;
    border-radius: 50%;
    text-align: center;
    line-height: 46px;
    cursor: pointer;
    font-weight: bold;
    transition: all 0.3s;
}
.number-option input:checked + .number-circle {
    background-color: #25b9d7;
    color: white;
    transform: scale(1.1);
}
.btn-validate {
    background: #25b9d7;
    color: white;
    border: none;
    padding: 12px 30px;
    border-radius: 5px;
    font-size: 16px;
    cursor: pointer;
    margin-top: 20px;
}
.btn-validate:hover {
    background: #1ea0c1;
}
</style>