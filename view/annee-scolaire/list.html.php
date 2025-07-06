<?php


use App\EnumDomain\Role;
use App\Model\Utilisateur;

/** @var Utilisateur $utilisateur */
$utilisateur = $_SESSION['utilisateur'];

function renderAnneesTableBody(): string
{
    $html = '';
    foreach ($_SESSION['anneesScolaireView'] as $a) {
        $html .= '<tr>';
        $html .=   '<td class="num"></td>';

        /* Identifiant */
        $html .=   '<td><span class="badge orange">'
            . htmlspecialchars($a['id_annee_scolaire'])
            . '</span></td>';

        /* Date de définition */
        $html .=   '<td><span class="badge blue">'
            . htmlspecialchars($a['date_definition'])
            . '</span></td>';

        /* Début et fin */
        $html .=   '<td>' . htmlspecialchars($a['debut']) . '</td>';
        $html .=   '<td>' . htmlspecialchars($a['fin'])   . '</td>';

        /* Filles / Garçons / Effectif / Demandes */
        $html .=   '<td><span class="badge pink">'   . htmlspecialchars($a['nbFilles'])   . '</span></td>';
        $html .=   '<td><span class="badge violet">' . htmlspecialchars($a['nbGarcons'])  . '</span></td>';
        $html .=   '<td><span class="badge gray">'   . htmlspecialchars($a['effectif'])   . '</span></td>';
        $html .=   '<td><span class="badge blue">'   . htmlspecialchars($a['nbDemandes']) . '</span></td>';

        $html .= '</tr>';
    }

    return $html;
}

?>
<form class="container" method="POST">
    <input type="hidden" name="controller" value="annee_scolaire">
    <div class="filter-bar">
        <div class="search"><i class="fa-solid fa-magnifying-glass"></i>
            <input
                    name="search"
                    value="<?= $_POST['search'] ?? '' ?>"
                    placeholder="Recherche année scolaire…">
        </div>
        <button class="btn" title="Appliquer les filtres">
            <i class="fa-solid fa-filter"></i>
        </button>
        <?php if($utilisateur->getRole() === Role::RP): ?>
        <button type="submit" class="btn add annee" name="action" value="show_form">
            <i class="fa-solid fa-plus"></i><span>Nouvelle année</span>
        </button>
        <?php endif; ?>
    </div>
    <div class="table-card">
        <table>
            <thead>
            <tr>
                <th>#</th>
                <th>ID</th>
                <th>Date de définition</th>
                <th>Début</th>
                <th>Fin</th>
                <th>Filles</th>
                <th>Garçons</th>
                <th>Effectif</th>
                <th>Demandes</th>
            </tr>
            </thead>
            <tbody>
            <?= renderAnneesTableBody() ?>
            </tbody>
        </table>
    </div>
</form>
<?php

unset($_SESSION['anneesScolaireView']);