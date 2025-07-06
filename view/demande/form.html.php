<?php

use App\Config\Validator;
use App\Config\View;
use App\EnumDomain\TypeDemande;
use App\Model\AnneeScolaire;
use App\Model\Inscription;


/** @var Inscription|null $inscription */
$inscription = $_SESSION['inscription'];
/** @var AnneeScolaire $annee */
$annee = $_SESSION['annee'];


function renderTypeToggle(string|null $selected): string
{
    $html = '<div class="toggle">';
    $selected = TypeDemande::tryFrom($selected);
    $selected = $selected === null ? TypeDemande::SUSPENSION->value : $selected->value;
    foreach (TypeDemande::cases() as $case) {
        $id = $case->name;
        $value = $case->value;
        $checked = $value === $selected ? ' checked' : '';
        $icon = TypeDemande::icon($value);

        $html .= "<input id='$id' type='radio' name='type' value='$value'$checked>";
        $html .= "<label for='$id' class='opt'><i class='$icon'></i> $value</label>";
    }

    $html .= '</div>';
    return $html;
}

?>

<form class="container" method="POST">
    <input type="hidden" name="controller" value="demande"/>
    <input type="hidden" name="action" value="show_form"/>
    <h1>Soumettre une demande</h1>
    <?php if ($inscription !== null): ?>
        <div class="context">
        <span class="tag">
            <i class="fa-solid fa-calendar-days"></i>
            Année&nbsp;scolaire&nbsp;:&nbsp;<?= htmlspecialchars($annee->format()) ?>
            <input type="hidden" name="idAnneeScolaire" value="<?= $annee->getIdAnneeScolaire() ?>">
        </span>
            <span class="tag">
            <i class="fa-solid fa-id-badge"></i>
            IdInscription&nbsp;:&nbsp;<?= htmlspecialchars($inscription->getIdInscription()) ?>
            <input type="hidden" name="idInscription" value="<?= $inscription->getIdInscription() ?>">
        </span>
            <span class="tag">
            <i class="fa-solid fa-clock"></i>
            Date&nbsp;:&nbsp;<?= htmlspecialchars(date('d/m/Y')) ?>
        </span>
        </div>
        <?= renderTypeToggle($_POST["type"] ?? null) ?>
        <div class="field">
            <label for="motif">Motif de la demande</label>
            <textarea id="motif"
                      name="motif"
                      placeholder="Décrivez le motif de votre demande…"
            ><?= $_POST["motif"] ?? null ?></textarea>
        </div>
        <?php View::displayErreur("motifEmpty"); ?>
        <div class="actions">
            <button class="btn btn-primary" name="action" value="post_form"><i
                        class="fa-solid fa-paper-plane"></i>Soumettre
            </button>
            <button type="submit" class="btn btn-cancel" name="action" value="show_list"><i
                        class="fa-solid fa-xmark"></i>Annuler
            </button>
        </div>
    <?php else: ?>
        <div class="context">
            <span class="tag">
                <i class="fa-solid fa-calendar-days"></i>
                Année&nbsp;scolaire&nbsp;:&nbsp;---------
            </span>
            <span class="tag">
                <i class="fa-solid fa-id-badge"></i>
                IdInscription&nbsp;:&nbsp;--
            </span>
            <span class="tag">
                <i class="fa-solid fa-clock"></i>
                Date&nbsp;:&nbsp;<?= htmlspecialchars(date('d/m/Y')) ?>
            </span>
        </div>
        <?php View::displayErreur("noCurrentInscription"); ?>
        <div class="actions">
            <button type="submit" class="btn btn-cancel" name="action" value="show_list"><i
                        class="fa-solid fa-xmark"></i>Annuler
            </button>
        </div>
    <?php endif; ?>
</form>
