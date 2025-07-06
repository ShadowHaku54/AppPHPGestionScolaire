<?php


use App\Config\View;
use App\EnumDomain\Role;
use App\EnumDomain\StatutInscription;
use App\EnumDomain\TypeInscription;
use App\Model\AnneeScolaire;
use App\Model\Etudiant;
use App\Model\Utilisateur;


/** @var Utilisateur|Etudiant $utilisateur */
$utilisateur = $_SESSION["utilisateur"];

$totalInscriptions = $_SESSION["totalInscriptions"];

$nb_par_page = 10;
if (isset($_POST["per_page"])) {
    if (is_numeric($_POST["per_page"])) {
        $nb_par_page = (int)$_POST["per_page"];
    } else {
        $nb_par_page = $totalInscriptions;
    }
}

$toltalPages = ($nb_par_page === 0) ? 0 : ceil($totalInscriptions / $nb_par_page);

/** @var AnneeScolaire[] $annees */
$annees = $_SESSION["annees"] ?? [];

$types = TypeInscription::cases();
$statuts = StatutInscription::cases();


function renderInscriptionsTableBody(Utilisateur|Etudiant $utilisateur): string
{
    $html = '';
    foreach ($_SESSION['inscriptionsView'] as $inscription) {
        $typeLabel = $inscription['type'];
        $statutLabel = $inscription['statut'];
        $typeColor = TypeInscription::style($typeLabel);
        $statutColor = StatutInscription::style($statutLabel);
        $statutIcon = StatutInscription::icon($statutLabel);

        $html .= '<tr>';
        $html .= '<td class="num"></td>';

        /* Inscription */
        $html .= '<td><span class="badge orange">'
            . htmlspecialchars($inscription['id_inscription'])
            . '</span></td>';

        if ($utilisateur->getRole() === Role::ETU) {
            $html .= '<td><span class="badge blue">' . htmlspecialchars($inscription["anneeScolaire"]) . '</span></td>';
        }
        /* Date */
        $html .= '<td><span class="badge blue">'
            . htmlspecialchars($inscription['date_inscription'])
            . '</span></td>';

        /* Étudiant = matricule + nom/prénom */
        if ($utilisateur->getRole() !== Role::ETU) {
            list($matricule, $nomPrenom) = explode(' ', $inscription['etudiant'], 2);
            $html .= '<td>'
                . '<span class="badge gray">' . htmlspecialchars($matricule) . '</span> '
                . htmlspecialchars($nomPrenom)
                . '</td>';
        }
        /* Classe */
        $html .= '<td><span class="badge orange">'
            . htmlspecialchars($inscription['classe'])
            . '</span></td>';

        /* Statut */
        $html .= '<td><span class="badge ' . $statutColor . '">'
            . '<i class="fa-solid ' . $statutIcon . '"></i> '
            . htmlspecialchars($statutLabel)
            . '</span></td>';

        if ($utilisateur->getRole() !== Role::ETU) {
            /* Type */
            $html .= '<td><span class="badge ' . $typeColor . '">'
                . htmlspecialchars($typeLabel)
                . '</span></td>';


        }
        $html .= '</tr>';
    }

    return $html;
}

?>

    <form method="POST" class="container">
        <div class="filter-bar">
            <div class="search"><i class="fa-solid fa-magnifying-glass"></i>
                <input type="search"
                       name="search"
                       value='<?= $_POST["search"] ?? '' ?>'
                       placeholder="Recherche une inscription ou un étudiant...">
            </div>
            <?php if ($utilisateur->getRole() !== Role::ETU): ?>
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
            <?php endif; ?>
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
            <?php echo View::renderPerPageSelect($totalInscriptions, $nb_par_page) ?>
            <button class="btn" title="Appliquer les filtres"><i class="fa-solid fa-filter"></i></button>
            <?php if ($utilisateur->getRole() !== Role::ETU): ?>
                <button class="btn add inscription" name="action" value="show_form">
                    <i class="fa-solid fa-user-plus"></i><span>Inscrire</span>
                </button>
            <?php endif; ?>
        </div>
        <div class="table-card">
            <table>
                <thead>
                <tr>
                    <th>#</th>
                    <th>Id</th>
                    <?php if ($utilisateur->getRole() === Role::ETU): ?>
                        <th>Année scolaire</th>
                    <?php endif; ?>
                    <th>Date</th>
                    <?php if ($utilisateur->getRole() !== Role::ETU): ?>
                        <th>Étudiant</th>
                    <?php endif; ?>
                    <th>Classe</th>
                    <th>Statut</th>
                    <?php if ($utilisateur->getRole() !== Role::ETU): ?>
                        <th>Type</th>
                    <?php endif; ?>
                </tr>
                </thead>
                <tbody>
                <?= renderInscriptionsTableBody($utilisateur); ?>
                </tbody>
            </table>
        </div>
        <?php echo View::renderPagination($_POST["page"] ?? 1, $toltalPages); ?>
    </form>
<?php
unset($_SESSION["inscriptionsView"], $_SESSION["totalInscriptions"], $_SESSION["annees"]);
?>