<?php


use App\Config\View;
use App\EnumDomain\Filiere;
use App\EnumDomain\Niveau;
use App\EnumDomain\Role;
use App\Model\AnneeScolaire;
use App\Model\Etudiant;
use App\Model\Utilisateur;

/** @var Utilisateur|Etudiant $utilisateur */
$utilisateur = $_SESSION["utilisateur"];

$classesView = $_SESSION["classesView"];

$totalClasses = $_SESSION["totalClasses"];

$nb_par_page = 10;
if (isset($_POST["per_page"])) {
    if (is_numeric($_POST["per_page"])) {
        $nb_par_page = (int)$_POST["per_page"];
    } else {
        $nb_par_page = $totalClasses;
    }
}

$toltalPages = ($nb_par_page === 0) ? 0 : ceil($totalClasses / $nb_par_page);

/** @var AnneeScolaire[] $annees */
$annees = $_SESSION["annees"] ?? [];


$niveaux = Niveau::cases();
$fileres = Filiere::cases();

function renderClassesTableBody(Utilisateur $utilisateur): string
{
    $html = '';
    foreach ($_SESSION["classesView"] as $d) {
        $filColor = Filiere::style($d["filiere"]);
        $period = $d["debut_inscription"] . " - " . $d["fin_inscription"];
        $periodColor = $d["inscr_valide"] ? "green" : "red";

        $html .= '<tr>'
            . '<td class="num"></td>'
            . '<td><span class="badge orange">' . htmlspecialchars($d['id_classe']) . '</span></td>'
            . '<td><span class="badge violet">' . htmlspecialchars($d['niveau']) . " " . htmlspecialchars($d['libelle']) . '</span></td>'
            . '<td><span class="badge ' . $filColor . '">' . htmlspecialchars($d['filiere']) . '</span></td>'
            . '<td><span class="badge ' . $periodColor . '">' . htmlspecialchars($period) . '</span></td>'
            . '<td><span class="badge pink">' . htmlspecialchars($d['nb_filles']) . '</span></td>'
            . '<td><span class="badge blue">' . htmlspecialchars($d['nb_garcons']) . '</span></td>'
            . '<td><span class="badge gray">' . htmlspecialchars($d['effectif']) . '</span></td>';
        if ($utilisateur->getRole() === Role::AC) {
            $html .= '<td><button class="action-view"><i class="fa-solid fa-eye"></i></button></td>';
        }
        $html .= '</tr>';
    }
    return $html;
}


?>

<form class="container" method="post">
    <div class="filter-bar">
        <div class="search"><i class="fa-solid fa-magnifying-glass"></i>
            <input type="search"
                   name="search"
                   value="<?= $_POST["search"] ?? '' ?>"
                   placeholder="Recherche classe…">
        </div>
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
            <select name="filiere">
                <option value="all">Toutes filières</option>
                <?php foreach ($fileres as $f): ?>
                    <option value="<?= htmlspecialchars($f->value) ?>"
                        <?= (isset($_POST['filiere']) && $_POST['filiere'] == $f->value) ? 'selected' : '' ?>>
                        <?= htmlspecialchars($f->value) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="sel">
            <select name="niveau">
                <option value="all">Tous niveaux</option>
                <?php foreach ($niveaux as $n): ?>
                    <option value="<?= htmlspecialchars($n->value) ?>"
                        <?= (isset($_POST['niveau']) && $_POST['niveau'] == $n->value) ? 'selected' : '' ?>>
                        <?= htmlspecialchars($n->value) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
        <?php echo View::renderPerPageSelect($totalClasses, $nb_par_page) ?>
        <button class="btn"><i class="fa-solid fa-filter"></i></button>
        <?php if ($utilisateur->getRole() === Role::RP): ?>
            <button class="btn add classe" name="action" value="show_form">
                <i class="fa-solid fa-plus"></i><span>Créer une classe</span>
            </button>
        <?php endif; ?>
    </div>
    <div class="table-card">
        <table>
            <thead>
            <tr>
                <th>#</th>
                <th>ID</th>
                <th>Classe</th>
                <th>Filière</th>
                <th>Période Insc.</th>
                <th>Filles</th>
                <th>Garçons</th>
                <th>Effectif</th>
                <?php if ($utilisateur->getRole() === Role::AC): ?>
                    <th>Action</th>
                <?php endif; ?>
            </tr>
            </thead>
            <tbody>
            <?= renderClassesTableBody($utilisateur) ?>
            </tbody>
        </table>
    </div>
    <?php echo View::renderPagination($_POST["page"] ?? 1, $toltalPages); ?>
</form>
<?php
unset($_SESSION["classesView"], $_SESSION["totalClasses"]);
?>
