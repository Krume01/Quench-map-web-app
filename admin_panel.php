<?php
include 'db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['actionFountain']; // add или edit
    $ime = $_POST['ime'];
    $adresa = $_POST['adresa'];
    $opis = $_POST['opis'];
    $hemiski = intval($_POST['hemiski']);
    $bakterioloski = intval($_POST['bakterioloski']);
    $primeroci = intval($_POST['primeroci']);
    $grad_id = intval($_POST['grad_id']);
    $sostojba=$_POST['sostojba'];

    // upload слика
    $slika = "";
    if (!empty($_FILES['slika']['name'])) {
        $target = "img/" . basename($_FILES['slika']['name']);
        move_uploaded_file($_FILES['slika']['tmp_name'], $target);
        $slika = $target;
    }

    if ($action === 'add') {
        $sql = "INSERT INTO cesmi (ime, adresa, sostojba, opis, hemiski_neispravni, bakterioloski_neispravni, ispitani_primeroci, grad_id, slika)
                VALUES ('$ime', '$adresa', '$sostojba', '$opis', $hemiski, $bakterioloski, $primeroci, $grad_id, '$slika')";
    } elseif ($action === 'edit') {
        $id = intval($_POST['id']);
        $sql = "UPDATE cesmi SET ime='$ime', adresa='$adresa', sostojba='$sostojba', opis='$opis',
                hemiski_neispravni=$hemiski, bakterioloski_neispravni=$bakterioloski,
                ispitani_primeroci=$primeroci, grad_id=$grad_id, slika='$slika'
                WHERE id=$id";
    }

    if ($conn->query($sql)) {
        // по успешна операција можеш да редиректирајш назад со порака
        header("Location: admin_panel.php?msg=Успешно+зачувано");
        exit;
    } else {
        echo "Грешка: " . $conn->error;
    }
}
?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>QuenchMap Admin Panel</title>
    <meta content="width=device-width, initial-scale=1.0" name="viewport">

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@300;400;600;700&family=Playfair+Display:wght@400;700&display=swap" rel="stylesheet">

    <!-- Icon Font Stylesheet -->
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.15.4/css/all.css"/>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.4.1/font/bootstrap-icons.css" rel="stylesheet">

    <!-- Libraries Stylesheet -->
    <link href="lib/animate/animate.min.css" rel="stylesheet">
    <link href="lib/owlcarousel/assets/owl.carousel.min.css" rel="stylesheet">

    <!-- Customized Bootstrap Stylesheet -->
    <link href="css/bootstrap.min.css" rel="stylesheet">

    <!-- Template Stylesheet -->
    <link href="css/style.css" rel="stylesheet">

</head>
<body>
    <!-- Navbar -->
    <div class="container-fluid position-relative p-0 bg-light">
        <nav class="navbar navbar-expand-lg navbar-light px-4 px-lg-5 py-3 py-lg-0">
            <a href="#" class="navbar-brand p-0">
                <h1 class="text-primary"><i class="fas fa-hand-holding-water me-3"></i>QuenchMap Admin</h1>
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarCollapse">
                <span class="fa fa-bars"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarCollapse">
                <div class="navbar-nav ms-auto py-0">                                  
                </div>
            </div>
        </nav>
    </div>
    <!-- Admin Panel Start -->
    <div class="container-fluid py-5">
        <div class="container py-5">
            <div class="text-center mx-auto pb-5" style="max-width:800px;">
                <!--<h4 class="text-uppercase text-primary wow fadeInUp">Админ Панел</h4>-->
                <h4 class="text-uppercase text-primary">Управување со јавни чешми</h4>
                <h4 class="section-title">Додавање, менување и бришење на податоци за чешмите, типови на проблеми и локации.</h4>
    <!-- ========================================= -->
    <!-- 1. ФОРМА: ДОДАЈ / ИЗМЕНИ ЧЕШМА            -->
    <!-- ========================================= -->
    <h6 class="display-6  mb-3">Управување со Чешми</h6>

<?php
include 'db.php';

// load cities for dropdown
$gradovi = $conn->query("SELECT id, ime FROM grad ORDER BY ime ASC");

// load fountains for edit mode dropdown
$cesmi_list = $conn->query("SELECT id, ime FROM cesmi ORDER BY ime ASC");

// optional feedback message
$feedback = isset($_GET['msg']) ? $_GET['msg'] : '';
?>

<div class="card p-4 mb-4">
  <?php if ($feedback): ?>
    <div class="alert alert-info mb-3"><?php echo htmlspecialchars($feedback); ?></div>
  <?php endif; ?>

  <div class="mb-3">
    <label class="form-label fw-semibold">Дејство:</label>
    <div>
      <input type="radio" name="mode" id="modeAdd" value="add" checked>
      <label for="modeAdd" class="me-3">Додади чешма</label>

      <input type="radio" name="mode" id="modeEdit" value="edit">
      <label for="modeEdit">Измени чешма</label>
    </div>
  </div>

  <form id="fountainForm" method="POST" action="admin_panel.php" enctype="multipart/form-data">
    <!-- action for PHP (add/edit) -->
    <input type="hidden" name="actionFountain" id="actionFountain" value="add">

    <!-- existing fountain (only for edit mode) -->
    <div id="existingFountainGroup" class="mb-3 d-none">
      <label class="form-label">Избери чешма за уредување</label>
      <select class="form-select" name="id" id="editFountainId">
        <option value="">-- Одбери --</option>
        <?php while($f = $cesmi_list->fetch_assoc()) { ?>
          <option value="<?php echo $f['id']; ?>">
            <?php echo htmlspecialchars($f['ime']); ?>
          </option>
        <?php } ?>
      </select>
    </div>

    <!-- name -->
    <div class="mb-3">
      <label class="form-label">Име на чешма</label>
      <input type="text" class="form-control" name="ime" id="ime" placeholder="Внеси име"><!--SE GUBE IMETO PRI UPDATE-->
    </div>

    <!-- address -->
    <div class="mb-3">
      <label class="form-label">Адреса / Координати</label>
      <input type="text" class="form-control" name="adresa" id="adresa" placeholder="Пр: https://maps.google.com/... или 41.74, 22.19">
    </div>

     <div class="mb-3">
      <label class="form-label">Состојба на чешма</label>
      <textarea class="form-control" rows="1" name="sostojba" id="sostojba"></textarea>
    </div>

    <!-- description -->
    <div class="mb-3">
      <label class="form-label">Опис / Белешка</label>
      <textarea class="form-control" rows="2" name="opis" id="opis"></textarea>
    </div>

    <!-- metrics -->
    <div class="row">
      <div class="col-md-4 mb-3">
        <label class="form-label">Хемиски неисправни</label>
        <input type="number" class="form-control" min="0" name="hemiski" id="hemiski" value="0">
      </div>
      <div class="col-md-4 mb-3">
        <label class="form-label">Бактериолошки неисправни</label>
        <input type="number" class="form-control" min="0" name="bakterioloski" id="bakterioloski" value="0">
      </div>
      <div class="col-md-4 mb-3">
        <label class="form-label">Испитани примероци</label>
        <input type="number" class="form-control" min="0" name="primeroci" id="primeroci" value="0">
      </div>
    </div>

    <!-- city -->
    <div class="mb-3">
      <label class="form-label">Град</label>
      <select class="form-select" name="grad_id" id="grad_id" required>
        <option value="">-- Одбери град --</option>
        <?php while($g = $gradovi->fetch_assoc()) { ?>
          <option value="<?php echo $g['id']; ?>">
            <?php echo htmlspecialchars($g['ime']); ?>
          </option>
        <?php } ?>
      </select>
    </div>

    <!-- image -->
    <div class="mb-3">
      <label class="form-label">Слика</label>
      <input type="file" class="form-control" name="slika" id="slika" accept="image/*">
    </div>

    <button class="btn btn-primary w-100" type="submit">Зачувај</button>
  </form>
</div>

<script>
// toggle add/edit UI and set hidden action field
const modeAdd = document.getElementById('modeAdd');
const modeEdit = document.getElementById('modeEdit');
const existingFountainGroup = document.getElementById('existingFountainGroup');
const actionFountain = document.getElementById('actionFountain');

modeAdd.addEventListener('change', () => {
  existingFountainGroup.classList.add('d-none');
  actionFountain.value = 'add';
});

modeEdit.addEventListener('change', () => {
  existingFountainGroup.classList.remove('d-none');
  actionFountain.value = 'edit';
});
</script>


    <!-- ========================================= -->
    <!-- 2. ФОРМА: КРЕИРАЈ / АЖУРИРАЈ СОOПШТЕНИЕ   -->
    <!-- ========================================= -->
    <h6 class="display-6  mb-3">Соопштенија</h6>

    <div class="card p-4 mb-4">
        <div class="mb-3">
            <label class="form-label fw-semibold">Дејство:</label>
            <div>
                <input type="radio" name="msgAction" id="createMsg" checked>
                <label for="createMsg" class="me-3">Креирај соопштение</label>

                <input type="radio" name="msgAction" id="updateMsg">
                <label for="updateMsg">Ажурирај соопштение</label>
            </div>
        </div>

        <form id="messageForm">

            <!-- Select message (edit only) -->
            <div id="existingMessageGroup" class="mb-3 d-none">
                <label class="form-label">Избери соопштение за уредување</label>
                <select class="form-select">
                    <option>Недостиг на вода – Плоштад Кочани</option>
                    <option>Минова чешма – во поправка</option>
                    
                </select>
            </div>

            <!-- Message Title -->
            <div class="mb-3">
                <label class="form-label">Наслов</label>
                <input class="form-control">
            </div>

            <!-- Message Description -->
            <div class="mb-3">
                <label class="form-label">Опис</label>
                <textarea class="form-control" rows="3"></textarea>
            </div>

            <button class="btn btn-primary w-100">Зачувај</button>
        </form>
    </div>

    <!-- ========================================= -->
    <!-- 3. ЛИСТА ПРИЈАВЕНИ ПРОБЛЕМИ                -->
    <!-- ========================================= -->
    <h6 class="display-6  mb-3">Пријавени проблеми</h6>

    <div class="card p-4">
        <table class="table table-bordered table-striped">
            <thead>
                <tr>
                    <th>Опис</th>
                    <th>Датум</th>
                    <th>Статус</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>Стришанска чешма не работи</td>
                    <td>2025-12-01</td>
                    <td><span class="badge bg-danger">Нов</span></td>
                </tr>
                <tr>
                    <td>Низок притисок во Белска чешма</td>
                    <td>2025-12-02</td>
                    <td><span class="badge bg-warning text-dark">Во тек</span></td>
                </tr>
                <tr>
                    <td>Поправена помпа Бавчалак</td>
                    <td>2025-11-29</td>
                    <td><span class="badge bg-success">Санирано</span></td>
                </tr>
            </tbody>
        </table>
    </div>

</div>

<!-- JavaScript for section visibility -->
<script>
    const addFountain = document.getElementById('addFountain');
    const editFountain = document.getElementById('editFountain');

    const existingFountainGroup = document.getElementById('existingFountainGroup');
    const newFountainGroup = document.getElementById('newFountainGroup');

    addFountain.addEventListener('change', () => {
        existingFountainGroup.classList.add('d-none');
        newFountainGroup.classList.remove('d-none');
    });

    editFountain.addEventListener('change', () => {
        existingFountainGroup.classList.remove('d-none');
        newFountainGroup.classList.add('d-none');
    });

    // Messages
    const createMsg = document.getElementById('createMsg');
    const updateMsg = document.getElementById('updateMsg');
    const existingMessageGroup = document.getElementById('existingMessageGroup');

    createMsg.addEventListener('change', () => {
        existingMessageGroup.classList.add('d-none');
    });

    updateMsg.addEventListener('change', () => {
        existingMessageGroup.classList.remove('d-none');
    });
</script>

</body>
</html>




































































































































































































































































































































































































































































































































































































































































































































































































































































































































































































































































































































































































































































































