<?php

use App\EnumDomain\Sexe;
use App\EnumDomain\TypeInscription;

function renderSexToggle(string $selected = ''): string
{
    $mChecked = $selected === Sexe::M->value ? ' checked' : '';
    $fChecked = $selected === Sexe::M->value ? ' checked' : '';

    return '<div class="sex-toggle">'
        . '<input id="sexM" name="sexe" type="radio" value="' . Sexe::M->value . '"' . $mChecked . '>'
        . '<label for="sexM"><i class="fa-solid fa-mars"></i>' . Sexe::M->value . '</label>'
        . '<input id="sexF" name="sexe" type="radio" value="' . Sexe::F->value . '"' . $fChecked . '>'
        . '<label for="sexF"><i class="fa-solid fa-venus"></i>' . Sexe::F->value . '</label>'
        . '</div>';
}

?>
<form method="post">
    <input type="hidden" name="controller" value="inscription">
    <div class="mode-toggle">
        <input  type="radio"
                id="modeNew"
                name="typeInscription"
                value="<?= TypeInscription::INSCRIPTION->value ?>"
            <?= ($_POST['typeInscription'] ?? TypeInscription::INSCRIPTION->value) === TypeInscription::INSCRIPTION->value ? 'checked' : '' ?>>
        <label for="modeNew"><i class="fa-solid fa-user-plus"></i>Nouvelle inscription</label>

        <input  type="radio"
                id="modeRe"
                name="typeInscription"
                value="<?= TypeInscription::REINSCRIPTION->value ?>"
            <?= ($_POST['typeInscription'] ?? '') === TypeInscription::REINSCRIPTION->value ? 'checked' : '' ?>>
        <label for="modeRe"><i class="fa-solid fa-repeat"></i>Réinscription</label>
    </div>
    <div  id="newSec" class="section">
        <div class="field">
            <label>Nom</label>
            <input
                    type="text"
                    name="nom"
                    value="<?= $_SESSION['nom'] ?? '' ?>"
                    placeholder="Nom de l'étudiant">
        </div>
        <div class="field">
            <label>Prénom(s)</label>
            <input
                    type="text"
                    name="prenom"
                    value="<?= $_SESSION['prenom'] ?? '' ?>"
                    placeholder="Prénom(s) de l'étudiant">
        </div>
        <div class="field"><label>Sexe</label>
            <?= renderSexToggle($_POST["sexe"] ?? Sexe::M->value) ?>
        </div>
        <div class="field">
            <label>Adresse</label>
            <input
                    type="text"
                    name="adresse"
                    value="<?= $_SESSION['adresse'] ?? '' ?>"
                    PLACEHOLDER="Addresse de l'étudiant">
        </div>
        <div class="field">
            <label>Email personnel</label>
            <input type="email" name="personal_email" value="<?= $_POST["personal_email"] ?? '' ?>"
                   placeholder="Email personnel de l'utilisateur"></div>
    </div>
    <div id="reSec" class="section">
        <div class="field">
            <label>Matricule étudiant</label>
            <input name="matEtudiant" value="<?= $_POST["matEtudiant"] ?? '' ?>" placeholder="Ex : ISM-2425/DK-18">
        </div>
    </div>
    <div class="field">
        <label>ID de la classe</label>
        <input name="idClasse" value="<?= $_POST["idClasse"] ?? '' ?>" placeholder="Ex : 12"></div>
    <button type="submit"
            class="btn grad-orange" name="action" value="post_form"><i class="fa-solid fa-save"></i>Enregistrer
    </button>
</form>