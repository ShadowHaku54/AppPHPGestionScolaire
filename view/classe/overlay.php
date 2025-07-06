<?php

use App\EnumDomain\Filiere;
use App\EnumDomain\Niveau;

$search = $_POST['search'] ?? '';
$fil = $_POST['filiere'] ?? '';
$niv = $_POST['niveau'] ?? '';
$classes = $_POST['classes'] ?? '';
$rowSelected = $_POST['rowSelected'] ?? '';

$filieres = Filiere::cases();
$niveaux = Niveau::cases();
?>
<div id="ov-class" class="ov">
    <div class="ov-modal">
        <div class="ov-header"><h2><i class="fa-solid fa-school"></i>Sélectionner une classe</h2></div>

        <div class="container">
            <form class="filter-bar" method="post">
                <input type="hidden" name="overlay" value="class">
                <input type="hidden" name="selectedStudentId" value="<?= htmlspecialchars($studentId ?? '') ?>">
                <div class="search"><i class="fa-solid fa-magnifying-glass"></i>
                    <input type="search" name="search" value="<?= htmlspecialchars($search) ?>" placeholder="Recherche…">
                </div>
                <div class="sel"><select name="filiere">
                        <option value="all">Toutes filières</option>
                        <?php foreach ($filieres as $f): ?>
                            <option value="<?= htmlspecialchars($f->value) ?>"
                                <?= (isset($_POST['filiere']) && $_POST['filiere'] == $f->value) ? 'selected' : '' ?>>
                                <?= htmlspecialchars($f->value) ?>
                            </option>
                        <?php endforeach; ?>
                        </select></div>
                <div class="sel"><select name="niveau">
                        <option value="all">Tous niveaux</option>
                        <?php foreach ($niveaux as $n): ?>
                            <option value="<?= htmlspecialchars($n->value) ?>"
                                <?= (isset($_POST['niveau']) && $_POST['niveau'] == $n->value) ? 'selected' : '' ?>>
                                <?= htmlspecialchars($n->value) ?>
                            </option>
                        <?php endforeach; ?>
                    </select></div>
                <button class="btn"><i class="fa-solid fa-filter"></i></button>
            </form>

            <div class="table-card">
                <table>
                    <thead>
                    <tr>
                        <th>#</th>
                        <th>ID</th>
                        <th>Classe</th>
                        <th>Filière</th>
                        <th>Période</th>
                        <th></th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php foreach ($classes as $c):$sel = $classId == $c['id']; ?>
                        <tr class="<?= $rowSelected ? 'ov-sel-row' : '' ?>">
                            <td class="ov-num"></td>
                            <td><span class="ov-badge"><?= $c['id'] ?></span></td>
                            <td><?= htmlspecialchars($c['name']) ?></td>
                            <td><span class="ov-badge"><?= htmlspecialchars($c['filiere']) ?></span></td>
                            <td><span class="ov-badge"><?= $c['start'] ?>–<?= $c['end'] ?></span></td>
                            <td>
                                <form method="post">
                                    <input type="hidden" name="select_class" value="<?= $c['id'] ?>">
                                    <input type="hidden" name="selectedStudentId"
                                           value="<?= htmlspecialchars($studentId ?? '') ?>">
                                    <button class="ov-pick"><i class="fa-solid fa-circle-check"></i>Choisir</button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
        <form method="post">
            <button class="ov-close"><i class="fa-solid fa-xmark"></i></button>
        </form>
    </div>
</div>
