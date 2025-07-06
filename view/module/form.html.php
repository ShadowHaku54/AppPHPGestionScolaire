<?php

use App\Config\View;

?>

<form method="post">
    <input type="hidden" name="controller" value="module">
    <h1>Ajouter un module</h1>
    <div>
        <label>Nom du module</label>
        <span class="field icon <?php View::getClasseByNameChamp("nomModuleEmpty") ?> <?php View::getClasseByNameChamp("nomModuleExiste") ?> ">
                <i class="fa-solid fa-book-open"></i>
                <input
                    type="text"
                    name="nom"
                    value="<?= $_POST['nom'] ?? '' ?>"
                    placeholder="Nom du module">
            </span>
        <?php View::displayErreur("nomModuleEmpty"); ?>
        <?php View::displayErreur("nomModuleExiste", "metier"); ?>
    </div>
    <div>
        <label>Nombre d’heures</label>
        <span class="field icon <?php View::getClasseByNameChamp("nbHeureEmpty") ?>">
                <i class="fa-solid fa-clock"></i>
                <input
                    type="number"
                    name="nb_heure"
                    value="<?= $_POST['nb_heure'] ?? '' ?>"
                    placeholder="Nombre d’heures" min="0">
            </span>
        <?php View::displayErreur("nbHeureEmpty"); ?>
    </div>
    <button class="btn" name="action" value="post_form"><i class="fa-solid fa-save"></i>Enregistrer</button>
</form>
