<?php

use App\Config\View;
use App\EnumDomain\Sexe;

$totalEtudiants = $_SESSION["totalEtudiants"];

$nb_par_page = 10;
if (isset($_POST["per_page"])) {
    if (is_numeric($_POST["per_page"])) {
        $nb_par_page = (int)$_POST["per_page"];
    } else {
        $nb_par_page = $totalEtudiants;
    }
}

$toltalPages = ($nb_par_page === 0) ? 0 : ceil($totalEtudiants / $nb_par_page);


$sexes = Sexe::cases();

function renderEtudiantsTableBody(): string
{
    $html = '';
    foreach ($_SESSION["etudiantsView"] as $etu) {
        $avatar = substr($etu['prenom'], 0, 1) . substr($etu['nom'], 0, 1);
        $sexeLabel = $etu['sexe'];
        $sexeColor = Sexe::style($sexeLabel);
        $sexeIcon = Sexe::icon($sexeLabel);

        $html .= '<tr>'
            . '<td class="num"></td>'
            . '<td><span class="badge red">' . htmlspecialchars($etu["mat_etudiant"]) . '</span></td>'
            . '<td><span class="avatar">' . htmlspecialchars($avatar) . '</span>' . htmlspecialchars($etu["nom"]) . " " . htmlspecialchars($etu["prenom"]) . '</td>'
            . '<td><span class="badge ' . htmlspecialchars($sexeColor) . '">' . '<i class="fa-solid ' . htmlspecialchars($sexeIcon) . '"></i> ' . substr($sexeLabel, 0, 1) . '</span></td>'
            . '<td>' . htmlspecialchars($etu["adresse"]) . '</td>'
            . '<td>' . htmlspecialchars($etu["email_of_school"]) . '</td>'
            . '<td><button class="action-view"><i class="fa-solid fa-eye"></i></button></td>'
            . '</tr>';
    }
    return $html;
}

?>

<form class="container" method="POST">
    <input type="hidden" name="controller" value="etudiant">
    <input type="hidden" name="action" value="show_list">
    <div class="filter-bar">
        <div class="search"><i class="fa-solid fa-magnifying-glass"></i>
            <input type="search" name="search"
                   value='<?= $_POST["search"] ?? '' ?>'
                   placeholder="Recherche étudiant…"></div>
        <div class="sel"><select name="sexe">
                <option value="all">Tout sexe</option>
                <?php foreach ($sexes as $sexe): ?>
                    <option value="<?= htmlspecialchars($sexe->value) ?>"
                        <?= (isset($_POST['sexe']) && $_POST['sexe'] == $sexe->value) ? 'selected' : '' ?>>
                        <?= htmlspecialchars($sexe->value) ?>
                    </option>
                <?php endforeach; ?>
            </select></div>
        <?php echo View::renderPerPageSelect($totalEtudiants, $nb_par_page) ?>
        <button class="btn"><i class="fa-solid fa-filter"></i></button>
        <a type="button" class="btn add inscription">
            <i class="fa-solid fa-user-plus"></i><span>Inscrire</span>
        </a>
    </div>
    <div class="table-card">
        <table>
            <thead>
            <tr>
                <th>#</th>
                <th>Matricule</th>
                <th>Nom & Prénom(s)</th>
                <th>Sexe</th>
                <th>Adresse</th>
                <th>Email école</th>
                <th>Action</th>
            </tr>
            </thead>
            <tbody>
            <?= renderEtudiantsTableBody(); ?>
            </tbody>
        </table>

    </div>
    <?php echo View::renderPagination($_POST["page"] ?? 1, $toltalPages); ?>
</form>
<?php
unset($_SESSION["etudiantsView"], $_SESSION["totalEtudiants"]);
?>