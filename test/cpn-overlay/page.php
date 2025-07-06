<?php
$classes = [
    ['id'=>12,'name'=>'GLRSA','level'=>'L2','filiere'=>'GLRS','start'=>'15/09/25','end'=>'20/12/25'],
    ['id'=>13,'name'=>'GLRSB','level'=>'L1','filiere'=>'GLRS','start'=>'20/09/25','end'=>'22/12/25'],
    ['id'=>25,'name'=>'TTL2','level'=>'L2','filiere'=>'TTL','start'=>'10/10/25','end'=>'18/12/25'],
    ['id'=>30,'name'=>'CDSD3','level'=>'L3','filiere'=>'CDSD','start'=>'03/10/24','end'=>'15/12/24'],
    ['id'=>30,'name'=>'CDSD3','level'=>'L3','filiere'=>'CDSD','start'=>'03/10/24','end'=>'15/12/24'],
    ['id'=>30,'name'=>'CDSD3','level'=>'L3','filiere'=>'CDSD','start'=>'03/10/24','end'=>'15/12/24'],
    ['id'=>30,'name'=>'CDSD3','level'=>'L3','filiere'=>'CDSD','start'=>'03/10/24','end'=>'15/12/24'],
    ['id'=>30,'name'=>'CDSD3','level'=>'L3','filiere'=>'CDSD','start'=>'03/10/24','end'=>'15/12/24'],
    ['id'=>30,'name'=>'CDSD3','level'=>'L3','filiere'=>'CDSD','start'=>'03/10/24','end'=>'15/12/24'],
    ['id'=>30,'name'=>'CDSD3','level'=>'L3','filiere'=>'CDSD','start'=>'03/10/24','end'=>'15/12/24'],
    ['id'=>30,'name'=>'CDSD3','level'=>'L3','filiere'=>'CDSD','start'=>'03/10/24','end'=>'15/12/24'],
    ['id'=>30,'name'=>'CDSD3','level'=>'L3','filiere'=>'CDSD','start'=>'03/10/24','end'=>'15/12/24'],
    ['id'=>30,'name'=>'CDSD3','level'=>'L3','filiere'=>'CDSD','start'=>'03/10/24','end'=>'15/12/24'],
    ['id'=>30,'name'=>'CDSD3','level'=>'L3','filiere'=>'CDSD','start'=>'03/10/24','end'=>'15/12/24'],
    ['id'=>30,'name'=>'CDSD3','level'=>'L3','filiere'=>'CDSD','start'=>'03/10/24','end'=>'15/12/24'],
    ['id'=>44,'name'=>'ETSE1','level'=>'M1','filiere'=>'ETSE','start'=>'01/09/24','end'=>'30/11/24']
];
$students = [
    ['id'=>101,'name'=>'Ndiaye Mamadou'],
    ['id'=>102,'name'=>'Traoré Fatou'],
    ['id'=>103,'name'=>'Ouédraogo Salif'],
    ['id'=>103,'name'=>'Ouédraogo Salif'],
    ['id'=>103,'name'=>'Ouédraogo Salif'],
    ['id'=>103,'name'=>'Ouédraogo Salif'],
    ['id'=>103,'name'=>'Ouédraogo Salif'],
    ['id'=>103,'name'=>'Ouédraogo Salif'],
    ['id'=>103,'name'=>'Ouédraogo Salif'],
    ['id'=>103,'name'=>'Ouédraogo Salif'],
    ['id'=>103,'name'=>'Ouédraogo Salif'],
    ['id'=>103,'name'=>'Ouédraogo Salif'],
    ['id'=>103,'name'=>'Ouédraogo Salif'],
    ['id'=>103,'name'=>'Ouédraogo Salif'],
    ['id'=>103,'name'=>'Ouédraogo Salif'],
    ['id'=>103,'name'=>'Ouédraogo Salif'],
    ['id'=>103,'name'=>'Ouédraogo Salif'],
    ['id'=>103,'name'=>'Ouédraogo Salif'],
    ['id'=>103,'name'=>'Ouédraogo Salif'],
    ['id'=>103,'name'=>'Ouédraogo Salif'],
    ['id'=>103,'name'=>'Ouédraogo Salif'],
    ['id'=>104,'name'=>'Diallo Aïssatou']
];

$open      = $_POST['overlay']          ?? null;
$classId   = $_POST['select_class']     ?? ($_POST['selectedClassId']   ?? null);
$studentId = $_POST['select_student']   ?? ($_POST['selectedStudentId'] ?? null);

$selectedClass   = array_values(array_filter($classes,  fn($c)=>$c['id']==$classId))[0]   ?? null;
$selectedStudent = array_values(array_filter($students, fn($s)=>$s['id']==$studentId))[0] ?? null;
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8"><meta name="viewport" content="width=device-width,initial-scale=1">
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="components/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <title>Sélections multiples</title>
</head>
<body>
<h1>Sélections multiples</h1>

<form class="page-form" method="post">
    <input type="hidden" name="selectedClassId"   id="selectedClassId"   value="<?= htmlspecialchars($classId   ?? '') ?>">
    <input type="hidden" name="selectedStudentId" id="selectedStudentId" value="<?= htmlspecialchars($studentId ?? '') ?>">

    <div class="field" data-section="class">
        <label>Classe</label>

        <?php if($selectedClass): ?>
            <div class="select-card content">
                <div class="class-tag"><?= htmlspecialchars($selectedClass['id']) ?></div>
                <div class="info"><span class="name"><?= htmlspecialchars($selectedClass['name']) ?></span></div>
                <button type="button" class="remove" data-remove="class"><i class="fa-solid fa-xmark"></i></button>
            </div>
        <?php else: ?>
            <button name="overlay" value="class" class="placeholder-card content" type="submit">Sélectionner une classe</button>
        <?php endif; ?>

        <button name="overlay" value="class" class="btn trigger"><i class="fa-solid fa-chalkboard"></i> Classe</button>
    </div>

    <div class="field" data-section="student">
        <label>Étudiant</label>

        <?php if($selectedStudent): ?>
            <div class="select-card content">
                <div class="class-tag"><?= htmlspecialchars($selectedStudent['id']) ?></div>
                <div class="info"><span class="name"><?= htmlspecialchars($selectedStudent['name']) ?></span></div>
                <button type="button" class="remove" data-remove="student"><i class="fa-solid fa-xmark"></i></button>
            </div>
        <?php else: ?>
            <button name="overlay" value="student" class="placeholder-card content" type="submit">Sélectionner un étudiant</button>
        <?php endif; ?>

        <button name="overlay" value="student" class="btn trigger"><i class="fa-solid fa-user"></i> Étudiant</button>
    </div>
</form>

<?php if($open==='class')   include __DIR__.'/components/overlay-class.php'; ?>
<?php if($open==='student') include __DIR__.'/components/overlay-student.php'; ?>

<script src="script.js"></script>
<script src="components/script.js"></script>
</body>
</html>
