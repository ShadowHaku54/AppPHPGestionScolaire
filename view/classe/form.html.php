<?php


use App\Config\View;
use App\EnumDomain\Filiere;
use App\EnumDomain\Niveau;

$niveaux = Niveau::cases();
$filieres = Filiere::cases();

?>

<form class="container" method="post">
    <input type="hidden" name="controller" value="classe">
    <h1>Créer une nouvelle classe</h1>
    <div class="year"><i class="fa-solid fa-calendar-days"></i>2024&nbsp;–&nbsp;2025</div>
    <div>
        <label>Libellé de la classe</label>
        <span class="field <?php View::getClasseByNameChamp("libelleEmpty"); View::getClasseByNameChamp("libelleAlreadyExist") ?>">
            <input name="libelle" value='<?= $_POST["libelle"] ?? '' ?>' placeholder="Ex : L2 GL">
            <i class="fa-solid fa-chalkboard"></i>
        </span>
        <?php View::displayErreur("libelleEmpty"); ?>
        <?php View::displayErreur("libelleAlreadyExist", "metier"); ?>
    </div>
    <div>
        <label>Niveau</label>
        <span class="field">
                <i class="fa-solid fa-graduation-cap"></i>
                <select name="niveau">
                    <?php foreach ($niveaux as $n): ?>
                        <option value="<?= htmlspecialchars($n->value) ?>"
                            <?= (isset($_POST['niveau']) && $_POST['niveau'] == $n->value) ? 'selected' : '' ?>>
                            <?= htmlspecialchars($n->value) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </span>
    </div>
    <div>
        <label>Filière</label>
        <span class="field">
                <i class="fa-solid fa-sitemap"></i>
                <select name="filiere">
                    <?php foreach ($filieres as $f): ?>
                        <option value="<?= htmlspecialchars($f->value) ?>"
                            <?= (isset($_POST['filiere']) && $_POST['filiere'] == $f->value) ? 'selected' : '' ?>>
                            <?= htmlspecialchars($f->value) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
        </span>
    </div>
    <div class="champ">
        <div class="double">
            <div>
                <label>Date début inscription</label>
                <span class="field <?php View::getClasseByNameChamp("debutInscExpired") ?>  <?php View::getClasseByNameChamp("finInscSupDebut") ?>">
                        <i class="fa-solid fa-calendar-day"></i>
                        <input
                                name="debut_inscription"
                                value="<?= $_POST['debut_inscription'] ?? '' ?>"
                                type="date">
                    </span>
            </div>

            <div>
                <label>Date fin inscription</label>
                <span class="field <?php View::getClasseByNameChamp("finInscSupDebut") ?>">
                        <i class="fa-solid fa-calendar-day"></i>
                        <input
                                name="fin_inscription"
                                value="<?= $_POST['fin_inscription'] ?? '' ?>"
                                type="date">
                    </span>
            </div>
        </div>
        <?php View::displayErreur("debutInscExpired"); ?>
        <?php View::displayErreur("finInscSupDebut"); ?>
    </div>
    <button class="btn" name="action" value="post_form"><i class="fa-solid fa-save"></i>Enregistrer</button>
</form>