<?php
// $classes et $openOverlay doivent être définis par la page appelante
$overlayId = 'ov';
?>
<div id="<?= $overlayId ?>" class="overlay <?= $openOverlay ? 'show' : '' ?>">
    <div class="modal">
        <header>
            <h2><i class="fa-solid fa-school"></i> Sélectionner une classe</h2>
            <p>Recherchez une classe (ID, nom, niveau ou filière) de l’année scolaire courante.</p>
        </header>

        <!-- fermeture via lien (sans JS) -->
        <a href="page.php" class="close"><i class="fa-solid fa-xmark"></i></a>

        <div class="search-wrap">
            <i class="fa-solid fa-magnifying-glass"></i>
            <input id="query" placeholder="Recherche classe…">
            <button class="search-btn" onclick="showClasses()">
                <i class="fa-solid fa-arrow-right"></i>
            </button>
        </div>

        <div class="content-area">
            <div id="wait" class="waiting">
                <i class="fa-solid fa-circle-info"></i>
                Effectuez une recherche pour voir les classes ouvertes.
            </div>
            <div id="res" class="results">
                <div class="table-box">
                    <table>
                        <thead>
                        <tr>
                            <th class="id">ID</th>
                            <th class="class-name">Classe</th>
                            <th>Niveau</th>
                            <th>Filière</th>
                            <th>Période</th>
                            <th></th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php foreach ($classes as $c):
                            // déterminer si la classe est encore active
                            $endDate = \DateTime::createFromFormat('d/m/y', substr($c['end'], 0, 8));
                            $isActive = $endDate && $endDate > new DateTime();
                            $periodClass = $isActive ? 'green' : 'gray';
                            ?>
                            <tr>
                                <td class="id"><?= htmlspecialchars($c['id']) ?></td>
                                <td class="class-name"><?= htmlspecialchars($c['name']) ?></td>
                                <td><span class="badge lvl"><?= htmlspecialchars($c['level']) ?></span></td>
                                <td>
                                    <span class="badge fil<?= htmlspecialchars($c['filiere']) ?>">
                                        <?= htmlspecialchars($c['filiere']) ?>
                                    </span>
                                </td>
                                <td>
                                    <span class="badge <?= $periodClass ?>">
                                        <i class="fa-solid <?= $isActive ? 'fa-calendar-check' : 'fa-calendar-xmark' ?>"></i>
                                        <?= htmlspecialchars($c['start']) ?>–<?= htmlspecialchars($c['end']) ?>
                                    </span>
                                </td>
                                <td>
                                    <?php if ($isActive): ?>
                                        <a href="?select=<?= urlencode($c['id']) ?>" class="select-btn act">
                                            Sélectionner
                                        </a>
                                    <?php else: ?>
                                        <button class="select-btn disabled">
                                            <i class="fa-solid fa-lock"></i> Clôturé
                                        </button>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
