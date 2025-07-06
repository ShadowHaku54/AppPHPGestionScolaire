<?php

namespace App\Config;

use App\EnumDomain\Role;
use App\Model\AnneeScolaire;

class View
{
    public static function renderPagination(int $currentPage, int $totalPages): string
    {
        if ($totalPages <= 1) return '';

        $html = '<div class="page">';

        // bouton page précédente
        $prevPage = max(1, $currentPage - 1);
        $prevDisabled = $currentPage <= 1 ? ' disabled' : '';
        $html .= '<button type="submit" name="page" value="' . $prevPage . '"' . $prevDisabled . '><i class="fa-solid fa-chevron-left"></i></button>';

        // boutons de pages numériques
        for ($i = 1; $i <= $totalPages; $i++) {
            $current = ($i == $currentPage) ? ' class="current"' : '';
            $html .= '<button type="submit" name="page" value="' . $i . '"' . $current . '>' . $i . '</button>';
        }

        // bouton page suivante
        $nextPage = min($totalPages, $currentPage + 1);
        $nextDisabled = $currentPage >= $totalPages ? ' disabled' : '';
        $html .= '<button type="submit" name="page" value="' . $nextPage . '"' . $nextDisabled . '><i class="fa-solid fa-chevron-right"></i></button>';

        $html .= '</div>';

        return $html;
    }

    public static function renderPerPageSelect(int $totalItems, int $current): string
    {
        $steps = [10, 20, 50];
        $allLabel = 'Tout afficher';
        $html = '<div class="sel"><select name="per_page">';

        if ($totalItems < $steps[0]) {
            $label = $allLabel . ' (' . $totalItems . ')';
            $html .= '<option value="all" selected>' . htmlspecialchars($label) . '</option>';
        } else {
            foreach ($steps as $step) {
                if ($totalItems > $step) {
                    $selected = ($current == $step) ? 'selected' : '';
                    $html .= '<option value="' . $step . '" ' . $selected . '>' . $step . '/page</option>';
                }
            }

            $selectedAll = ($current == $totalItems) ? 'selected' : '';
            $labelAll = $allLabel . ' (' . $totalItems . ')';
            $html .= '<option value="all" ' . $selectedAll . '>' . htmlspecialchars($labelAll) . '</option>';
        }

        $html .= '</select></div>';
        return $html;
    }

    public static function ongletsVisibles(Role $role): array
    {
        return array_values(array_filter(
            ONGLETS,
            fn(array $o) => in_array($role, $o['users'], true)
        ));
    }


    public static function estActif(string $href): bool
    {
        parse_str(parse_url($href, PHP_URL_QUERY) ?? '', $cible);
        return ($cible['controller'] ?? null) === ($_REQUEST['controller'] ?? null);
    }

    public static function displayErreur(string $name, string $type = "technique"): void
    {
        if (isset($_SESSION["errors"])) {
            $error = $_SESSION["errors"];
            if (!isset($error[$name])) return;
            $message = htmlspecialchars($error[$name]);
            $classe = "message-erreur message-erreur--$type";
            $icone = $type === 'metier'
                ? '<i class="fa-solid fa-triangle-exclamation"></i>'
                : '<i class="fa-solid fa-circle-exclamation"></i>';
            echo "<div class=\"$classe\">$icone $message</div>";

            if (count($_SESSION["errors"]) > 1) {
                unset($_SESSION["errors"][$name]);
            } else {
                unset($_SESSION["errors"]);
            }

        }
    }

    public static function getClasseByNameChamp(string $name): void
    {
        if (isset($_SESSION["errors"])) {
            $error = $_SESSION["errors"];
            if (isset($error[$name])) {
                echo 'error';
            }
        }
    }

    public static function renderActionInputHidden(array $actionForm){
        $html = '';
        foreach ($actionForm as $key => $value) {
            $html .= '<input type="hidden" name="' . $key . '" value="' . $value . '">';
        }
        return $html;
    }
}