<script>
document.addEventListener('DOMContentLoaded', function() {
    // Créer le popup dynamiquement
    const popupHTML = `
        <div id="jeugagnant-popup" style="position:fixed;top:0;left:0;width:100%;height:100%;background:rgba(0,0,0,0.5);z-index:9999;display:none;">
            <div style="background:white;margin:100px auto;padding:30px;border-radius:10px;width:90%;max-width:500px;position:relative;">
                <span class="jeugagnant-close" style="float:right;font-size:28px;cursor:pointer;color:#aaa;">&times;</span>
                <h2 style="text-align:center;">🎲 Tentez votre chance !</h2>
                <form method="POST" action="{$jeugagnant_url}">
                    <div style="margin-bottom:20px;">
                        <label style="display:block;margin-bottom:5px;font-weight:bold;">Votre email :</label>
                        <input type="email" name="email" required style="width:100%;padding:10px;border:1px solid #ddd;border-radius:4px;">
                    </div>
                    <button type="submit" style="width:100%;padding:12px;background:#25b9d7;color:white;border:none;border-radius:4px;font-size:16px;cursor:pointer;">
                        Jouer
                    </button>
                </form>
            </div>
        </div>
    `;
    
    // Ajouter le popup au body
    document.body.insertAdjacentHTML('beforeend', popupHTML);
    
    const popup = document.getElementById('jeugagnant-popup');
    const closeBtn = document.querySelector('.jeugagnant-close');
    
    // Afficher le popup après 3 secondes
    setTimeout(() => {
        popup.style.display = 'block';
    }, 3000);

    // Fermer le popup
    closeBtn.addEventListener('click', function() {
        popup.style.display = 'none';
    });

    // Fermer en cliquant à l'extérieur
    window.addEventListener('click', function(event) {
        if (event.target === popup) {
            popup.style.display = 'none';
        }
    });
});
</script>