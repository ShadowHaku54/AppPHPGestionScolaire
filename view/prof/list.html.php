<?php


use App\Config\View;
use App\EnumDomain\GradeProfesseur;

$totalProfesseurs = $_SESSION["totalProfesseurs"];

$nb_par_page = 10;
if (isset($_POST["per_page"])) {
    if (is_numeric($_POST["per_page"])) {
        $nb_par_page = (int)$_POST["per_page"];
    } else {
        $nb_par_page = $totalProfesseurs;
    }
}

$toltalPages = ($nb_par_page === 0) ? 0 : ceil($totalProfesseurs / $nb_par_page);

$grades = GradeProfesseur::cases();

function renderProfTableBody(): string
{
    $html = '';
    foreach ($_SESSION["professeursView"] as $p) {
        $nomPrenoms = $p["nom"] . " " . $p["prenom"];
        $gradeLabel = $p["grade"];
        $gradeColor = GradeProfesseur::style($gradeLabel);
        $html .= '<tr>'
            . '<td class="num"></td>'
            . '<td><span class="badge orange">' . htmlspecialchars($p['id_professeur']) . '</span></td>'
            . '<td>' . htmlspecialchars($nomPrenoms) . '</td>'
            . '<td><span class="badge ' . $gradeColor . '">' . htmlspecialchars($p['grade']) . '</span></td>'
            . '<td><button class="action-view"><i class="fa-solid fa-eye"></i></button></td>'
            . '</tr>';
    }
    return $html;
}


?>
    <form method="POST" class="container">
        <input type="hidden" name="controller" value="professeur">
        <div class="filter-bar">
            <div class="search"><i class="fa-solid fa-magnifying-glass"></i>
                <input type="search"
                       name="search"
                       value='<?= $_POST["search"] ?? '' ?>'
                       placeholder="Recherche un prof...">
            </div>
            <div class="sel">
                <select name="grade">
                    <option value="all">Tous grades</option>
                    <?php foreach ($grades as $grade): ?>
                        <option value="<?= htmlspecialchars($grade->value) ?>"
                            <?= (isset($_POST['grade']) && $_POST['grade'] == $grade->value) ? 'selected' : '' ?>>
                            <?= htmlspecialchars($grade->value) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <?php echo View::renderPerPageSelect($totalProfesseurs, $nb_par_page) ?>
            <button class="btn" title="Appliquer les filtres"><i class="fa-solid fa-filter"></i></button>
            <button class="btn add prof" name="action" value="show_form">
                <i class="fa-solid fa-plus"></i><span>Ajouter un prof</span>
            </button>
        </div>
        <div class="table-card">
            <table>
                <thead>
                <tr>
                    <th>#</th>
                    <th>Id</th>
                    <th>Nom & Prénom(s)</th>
                    <th>Grade</th>
                    <th>Action</th>
                </tr>
                </thead>
                <tbody>
                <?= renderProfTableBody() ?>
                </tbody>
            </table>
        </div>
        <?php echo View::renderPagination($_POST["page"] ?? 1, $toltalPages); ?>
    </form>
<?php
unset($_SESSION["professeursView"], $_SESSION["totalProfesseurs"]);
?>