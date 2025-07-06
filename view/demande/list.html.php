<?php

use App\Config\View;
use App\EnumDomain\Role;
use App\EnumDomain\StatutDemande;
use App\EnumDomain\TypeDemande;
use App\Model\AnneeScolaire;
use App\Model\Etudiant;
use App\Model\Utilisateur;

/** @var Utilisateur|Etudiant $utilisateur */
$utilisateur = $_SESSION["utilisateur"];

$demandesView = $_SESSION["demandesView"];

$totalDemades = $_SESSION["totalDemandes"];

$nb_par_page = 10;
if (isset($_POST["per_page"])) {
    if (is_numeric($_POST["per_page"])) {
        $nb_par_page = (int)$_POST["per_page"];
    } else {
        $nb_par_page = $totalDemades;
    }
}

$toltalPages = ($nb_par_page === 0) ? 0 : ceil($totalDemades / $nb_par_page);

/** @var AnneeScolaire[] $annees */
$annees = $_SESSION["annees"] ?? [];


$types = TypeDemande::cases();
$statuts = StatutDemande::cases();

function renderDemandesTableBody(Utilisateur|Etudiant $utilisateur): string
{
    $html = '';
    foreach ($_SESSION["demandesView"] as $demande) {
        $typeLabel = $demande['type'];
        $statutLabel = $demande['statut'];
        $typeColor = TypeDemande::style($typeLabel);
        $statutColor = StatutDemande::style($statutLabel);

        $html .= '<tr>';
        $html .= '<td class="num"></td>';
        $html .= '<td><span class="badge orange">' . htmlspecialchars($demande['id_demande']) . '</span></td>';
        $html .= '<td><span class="badge blue">' . htmlspecialchars($demande['date_demande']) . '</span></td>';
        if ($utilisateur->getRole() !== Role::ETU) {
            list($matEtudiant, $nomEtPrenom) = explode(' ', $demande['etudiant'], 2);
            $html .= '<td><span class="badge gray">' . htmlspecialchars($matEtudiant) . '</span> ' . htmlspecialchars($nomEtPrenom) . '</td>';
        }
        $html .= '<td><span class="badge orange">' . htmlspecialchars($demande['id_inscription']) . '</span></td>';
        $html .= '<td><span class="badge ' . htmlspecialchars($typeColor) . '">' . htmlspecialchars($typeLabel) . '</span></td>';
        $html .= '<td><span class="badge ' . $statutColor . '">' . htmlspecialchars($statutLabel) . '</span></td>';
        $html .= '<td><button type="button" class="act" data-msg="' . htmlspecialchars($demande['motif']) . '"><i class="fa-solid fa-comment-dots"></i></button></td>';

        if ($utilisateur->getRole() === Role::RP) {
            $dis = ($statutLabel !== StatutDemande::EN_ATTENTE->value) ? ' dis' : '';
            $html .= '<td style="display:flex;gap:8px">
                          <button title="Accepter la demande" class="acc' . $dis . '" name="idAcceptDemande" value="' . htmlspecialchars($demande['id_demande']) . '">
                            <i class="fa-solid fa-check"></i>
                          </button>
                          <button title="Refuser la demande" class="ref' . $dis . '" name="idDeclineDemande" value="' . htmlspecialchars($demande['id_demande']) . '">
                            <i class="fa-solid fa-xmark"></i>
                          </button>';
            if ($typeLabel === TypeDemande::SUSPENSION->value && $statutLabel === StatutDemande::ACCEPTEE->value) {
                $html .= '<button title="Renouveler la demande" class="renew" name="idRenewDemande" value="' . htmlspecialchars($demande['id_demande']) . '">
                              <i class="fa-solid fa-arrows-rotate"></i>
                          </button>';
            }
            $html .= '</td>';
        }

        $html .= '</tr>';
    }
    return $html;
}



?>

<form method="POST" class="container">
    <input type="hidden" name="controller" value="demande" />
    <input type="hidden" name="action" value="show_list" />

    <div class="filter-bar">
        <!-- Champ de recherche -->
        <div class="search">
            <i class="fa-solid fa-magnifying-glass"></i>
            <input name="search" placeholder="Recherche demande…"
                   value="<?php echo htmlspecialchars($_POST['search'] ?? ''); ?>">
        </div>

        <!-- Sélection Année -->
        <div class="sel">
            <select name="idAnneeCourante">
                <?php foreach ($annees as $annee): ?>
                    <option value="<?= htmlspecialchars($annee->getIdAnneeScolaire()) ?>"
                        <?= (isset($_POST['idAnneeCourante']) && $_POST['idAnneeCourante'] == $annee->getIdAnneeScolaire()) ? 'selected' : '' ?>>
                        <?= htmlspecialchars($annee->format()) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <!-- Sélection Type -->
        <div class="sel">
            <select name="type">
                <option value="all">Tous types</option>
                <?php foreach ($types as $type): ?>
                    <option value="<?= htmlspecialchars($type->value) ?>"
                        <?= (isset($_POST['type']) && $_POST['type'] == $type->value) ? 'selected' : '' ?>>
                        <?= htmlspecialchars($type->value) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <!-- Sélection Statut -->
        <div class="sel">
            <select name="statut">
                <option value="all">Tous statuts</option>
                <?php foreach ($statuts as $statut): ?>
                    <option value="<?= htmlspecialchars($statut->value) ?>"
                        <?= (isset($_POST['statut']) && $_POST['statut'] == $statut->value) ? 'selected' : '' ?>>
                        <?= htmlspecialchars($statut->value) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <?php echo View::renderPerPageSelect($totalDemades, $nb_par_page) ?>

        <!-- Boutons -->
        <button title="Appliquer les filtres" type="submit" class="btn" name="applyFilter">
            <i class="fa-solid fa-filter"></i>
        </button>
        <?php if ($utilisateur->getRole() === Role::ETU): ?>
            <button class="btn add demande" name="action" value="show_form">
                <i class="fa-solid fa-plus"></i><span>Demande</span>
            </button>
        <?php endif; ?>
    </div>

    <div class="table-card">
        <table>
            <thead>
            <tr>
                <th>#</th>
                <th>ID</th>
                <th>Date</th>
                <?php if ($utilisateur->getRole() !== Role::ETU): ?>
                    <th>Étudiant</th>
                <?php endif; ?>
                <th>ID inscr.</th>
                <th>Type</th>
                <th>Statut</th>
                <th>Motif</th>
                <?php if ($utilisateur->getRole() === Role::RP): ?>
                    <th>Action</th>
                <?php endif; ?>
            </tr>
            </thead>
            <tbody>
            <?php echo renderDemandesTableBody($utilisateur); ?>
            </tbody>
        </table>
    </div>
    <?php echo View::renderPagination($_POST["page"] ?? 1, $toltalPages); ?>
</form>
<div id="tooltip" class="tip"></div>

<?php
unset($_SESSION["demandesView"], $_SESSION["totalDemandes"], $_SESSION["annees"]);
?>