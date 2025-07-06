<?php


use App\Config\View;
use App\Model\Utilisateur;

/** @var Utilisateur $utilisateur */
$utilisateur = $_SESSION["utilisateur"];

$totalModules = $_SESSION["totalModules"];

$nb_par_page = 10;
if (isset($_POST["per_page"])) {
    if (is_numeric($_POST["per_page"])) {
        $nb_par_page = (int)$_POST["per_page"];
    } else {
        $nb_par_page = $totalModules;
    }
}

$toltalPages = ($nb_par_page === 0) ? 0 : ceil($totalModules / $nb_par_page);

function renderModulesTableBody(): string
{
    $html = '';
    foreach ($_SESSION["modulesView"] as $m) {
        $html .= '<tr>'
            . '<td class="num"></td>'
            . '<td><span class="badge orange">' . htmlspecialchars($m['id_module']) . '</span></td>'
            . '<td>' . htmlspecialchars($m['nom']) . '</td>'
            . '<td><span class="badge blue">' . htmlspecialchars($m['nb_heure']) . '</span></td>'
            . '<td><button class="action-view"><i class="fa-solid fa-eye"></i></button></td>'
            . '</tr>';
    }
    return $html;
}


?>
    <form method="POST" class="container">
        <input type="hidden" name="controller" value="module">
        <div class="filter-bar">
            <div class="search"><i class="fa-solid fa-magnifying-glass"></i>
                <input type="search"
                       name="search"
                       value='<?= $_POST["search"] ?? '' ?>'
                       placeholder="Recherche un module...">
            </div>
            <?php echo View::renderPerPageSelect($totalModules, $nb_par_page) ?>
            <button class="btn" title="Appliquer les filtres"><i class="fa-solid fa-filter"></i></button>
                <button class="btn add module" name="action" value="show_form">
                    <i class="fa-solid fa-plus"></i><span>Ajouter un module</span>
                </button>
        </div>
        <div class="table-card">
            <table>
                <thead>
                <tr>
                    <th>#</th>
                    <th>Id</th>
                    <th>Nom</th>
                    <th>Nb heures</th>
                    <th>Action</th>
                </tr>
                </thead>
                <tbody>
                    <?= renderModulesTableBody() ?>
                </tbody>
            </table>
        </div>
        <?php echo View::renderPagination($_POST["page"] ?? 1, $toltalPages); ?>
    </form>
<?php
unset($_SESSION["modulesView"], $_SESSION["totalModules"]);
?>