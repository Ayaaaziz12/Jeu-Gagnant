<!-- TEST ULTRA SIMPLE -->
<div style="position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.8); z-index: 999999; display: block;">
    <div style="position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); background: white; padding: 30px; border-radius: 10px; width: 400px;">
        <h2 style="text-align: center; color: #333;">🎲 TENTEZ VOTRE CHANCE !</h2>
        <p style="text-align: center;">Gagnez un code promo exclusif</p>
        
        <form action="{$link->getModuleLink('jeugagnant', 'game')}" method="post">
            <div style="margin: 20px 0;">
                <input type="email" name="email" required placeholder="votre@email.com" 
                       style="width: 100%; padding: 12px; border: 2px solid #25b9d7; border-radius: 5px; font-size: 16px;">
            </div>
            
            <button type="submit" style="width: 100%; background: #25b9d7; color: white; border: none; padding: 15px; border-radius: 5px; font-size: 18px; cursor: pointer;">
                🎯 JOUER MAINTENANT
            </button>
        </form>
        
        <p style="text-align: center; margin-top: 15px;">
            <a href="#" onclick="this.parentElement.parentElement.parentElement.style.display='none'; return false;" 
               style="color: #666; text-decoration: underline;">Fermer</a>
        </p>
    </div>
</div>

<script type="text/javascript">
console.log('🎯 POPUP Jeu Gagnant chargé avec succès!');
</script>