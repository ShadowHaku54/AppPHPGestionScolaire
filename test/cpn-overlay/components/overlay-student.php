<?php
$q = $_POST['q'] ?? '';
$filtered = array_filter($students, fn($s) => !$q || str_contains(strtoupper($s['name']), strtoupper($q)) || str_contains((string)$s['id'], $q));
?>
<div id="ov-student" class="ov">
    <div class="ov-modal">
        <div class="ov-header"><h2><i class="fa-solid fa-user-graduate"></i>Sélectionner un étudiant</h2></div>

        <div class="container">
            <form class="filter-bar" method="post">
                <input type="hidden" name="overlay" value="student">
                <input type="hidden" name="selectedClassId" value="<?= htmlspecialchars($classId ?? '') ?>">
                <div class="search"><i class="fa-solid fa-magnifying-glass"></i>
                    <input type="search" name="q" value="<?= htmlspecialchars($q) ?>" placeholder="Recherche…">
                </div>
                <button class="btn"><i class="fa-solid fa-filter"></i></button>
            </form>

            <div class="table-card">
                <table>
                    <thead>
                    <tr>
                        <th>#</th>
                        <th>ID</th>
                        <th>Nom</th>
                        <th></th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php foreach ($filtered as $s):$sel = $studentId == $s['id']; ?>
                        <tr class="<?= $sel ? 'ov-sel-row' : '' ?>">
                            <td class="ov-num"></td>
                            <td><span class="ov-badge"><?= $s['id'] ?></span></td>
                            <td><?= htmlspecialchars($s['name']) ?></td>
                            <td>
                                <form method="post">
                                    <input type="hidden" name="select_student" value="<?= $s['id'] ?>">
                                    <input type="hidden" name="selectedClassId"
                                           value="<?= htmlspecialchars($classId ?? '') ?>">
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
