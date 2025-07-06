<?php

use App\Config\Validator;
use App\Config\View;

?>

<form method="post">
    <h1>Définir une nouvelle année scolaire</h1>
    <input type="hidden" name="controller" value="annee_scolaire">
    <div class="double">
        <div class="field"><label>Année de début</label><i class="fa-solid fa-calendar-days"></i><input id="start"
                                                                                                        type="number"
                                                                                                        min="1900" name="debut">
        </div>
        <div class="field"><label>Année de fin</label><i class="fa-solid fa-calendar-days"></i><input id="end"
                                                                                                      type="number"
                                                                                                      min="1901" name="fin"></div>
        <?php View::displayErreur("debutIsInt"); ?>
        <?php View::displayErreur("anneeAlreadyExist"); ?>
    </div>
    <button class="btn" name="action" value="post_form"><i class="fa-solid fa-save"></i>Enregistrer</button>
</form>
