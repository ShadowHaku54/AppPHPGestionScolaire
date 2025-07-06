<?php

use App\Config\View;
use App\EnumDomain\GradeProfesseur;

$grades = GradeProfesseur::cases();
?>
<form method="post">
    <h1>Ajouter un professeur</h1>
    <div>
        <label>Nom</label>
        <span class="field <?php View::getClasseByNameChamp("nomEmpty") ?>">
                <i class="fa-solid fa-user"></i>
                <input
                    type="text"
                    name="nom"
                    value="<?= $_POST['nom'] ?? '' ?>"
                    placeholder="Nom de famille">
            </span>
        <?php View::displayErreur("nomEmpty"); ?>
    </div>
    <div>
        <label>Prénom(s)</label>
        <span class="field <?php View::getClasseByNameChamp("prenomEmpty") ?>">
                <i class="fa-solid fa-user"></i>
                <input
                    type="text"
                    name="prenom"
                    value="<?= $_POST['prenom'] ?? '' ?>"
                    placeholder="Prénom(s)">
            </span>
        <?php View::displayErreur("prenomEmpty"); ?>
    </div>
    <div>
        <label>Grade</label>
        <span class="field">
                <i class="fa-solid fa-graduation-cap"></i>
                <select name="grade">
                    <?php foreach ($grades as $g): ?>
                        <option value="<?= htmlspecialchars($g->value) ?>"
                            <?= (isset($_POST['grade']) && $_POST['grade'] == $g->value) ? 'selected' : '' ?>>
                            <?= htmlspecialchars($g->value) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </span>
    </div>
    <button class="btn" name="action" value="post_form"><i class="fa-solid fa-save"></i>Enregistrer</button>
</form>
</body>

</html>
