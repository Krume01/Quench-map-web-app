<?php
include 'db.php';


$sql = "SELECT id, ime, adresa, sostojba,hemiski_neispravni,ispitani_primeroci,bakterioloski_neispravni,slika,opis FROM cesmi";
$result = $conn->query($sql);

// земи ги сите градови
$gradovi = $conn->query("SELECT * FROM grad");

// ако е селектиран град
$selected_grad = isset($_GET['grad_id']) ? (int)$_GET['grad_id'] : 0;

?>

<!DOCTYPE html>
<html lang="en">

    <head>
        <meta charset="utf-8">
        <title>QuenchMap</title>
        <meta content="width=device-width, initial-scale=1.0" name="viewport">
        <meta name="description" content="Веб апликација за следење на квалитетот на водата од јавните чешми. Пребарување по локација, соопштенија и пријавување на проблеми.">
        <meta name="keywords" content="јавни чешми, квалитет на вода, бактериолошка анализа, Google Maps, јавни води, пријава на проблем">

        

        <!-- Google Web Fonts -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Open+Sans:ital,wdth,wght@0,75..100,300..800;1,75..100,300..800&family=Playfair+Display:ital,wght@0,400..900;1,400..900&display=swap" rel="stylesheet"> 

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

        <!-- Spinner Start -->
        <div id="spinner" class="show bg-white position-fixed translate-middle w-100 vh-100 top-50 start-50 d-flex align-items-center justify-content-center">
            <div class="spinner-border text-primary" style="width: 3rem; height: 3rem;" role="status">
                <span class="sr-only">Loading...</span>
            </div>
        </div>
        <!-- Spinner End -->

        <!-- Navbar & Hero Start -->
        <div class="container-fluid position-relative p-0">
            <nav class="navbar navbar-expand-lg navbar-light px-4 px-lg-5 py-3 py-lg-0">
                <a href="" class="navbar-brand p-0">
                    <h1 class="text-primary"><i class="fas fa-hand-holding-water me-3"></i>QuenchMap</h1>
                    <!-- <img src="img/logo.png" alt="Logo"> -->
                </a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarCollapse">
                    <span class="fa fa-bars"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarCollapse">
                    <div class="navbar-nav ms-auto py-0">
                        <a href="index.php" class="nav-item nav-link active">Почетна</a>
                        <form method="GET" class="nav-item">
  <select id="grad" name="grad_id" class="nav-link nav-select">
    <option value="">Град</option>
    <?php while($g = $gradovi->fetch_assoc()) { ?>
      <option value="<?php echo $g['id']; ?>" 
        <?php if($selected_grad == $g['id']) echo 'selected'; ?>>
        <?php echo htmlspecialchars($g['ime']); ?>
      </option>
    <?php } ?>
  </select>
</form>
<style>
.nav-select {
    background: transparent;
    border: none;
    padding: .5rem 1rem;
    color: #6c757d; /* текст боја исто како другите */
    cursor: pointer;
}

.nav-select:focus {
    outline: none;
    box-shadow: none;
}

.nav-select option {
    color: black; /* dropdown листата да биде читлива */
}
</style>



                        <a href="review.php" class="nav-item nav-link">Преглед</a>
                        <a href="notifications.html" class="nav-item nav-link">Соопштенија</a>
                        <a href="report.html" class="nav-item nav-link">Пријави проблем</a>
                    </div>
                    <button class="btn btn-primary btn-md-square d-flex flex-shrink-0 mb-3 mb-lg-0 rounded-circle me-3" data-bs-toggle="modal" data-bs-target="#searchModal"><i class="fas fa-search"></i></button>
                    <a href="login.html" class="btn btn-primary rounded-pill d-inline-flex flex-shrink-0 py-2 px-4">Најави се</a>
                </div>
            </nav>

            <!-- Carousel Start -->
            <div class="carousel-header">
                <div id="carouselId" class="carousel slide" data-bs-ride="carousel">
                    <ol class="carousel-indicators">
                        <li data-bs-target="#carouselId" data-bs-slide-to="0" class="active"></li>
                        <li data-bs-target="#carouselId" data-bs-slide-to="1"></li>
                        <li data-bs-target="#carouselId" data-bs-slide-to="2"></li>
                    </ol>
                    <div class="carousel-inner" role="listbox">
                        <div class="carousel-item active">
                            <img src="img/carousel-1.jpg" class="img-fluid w-100" alt="Image">
                            <div class="carousel-caption-1">
                                <div class="carousel-caption-1-content" style="max-width: 900px;">
                                    <h4 class="text-white text-uppercase fw-bold mb-4 fadeInLeft animated" data-animation="fadeInLeft" data-delay="1s" style="animation-delay: 1s;" style="letter-spacing: 3px;">Важноста на чистата вода</h4>
                                    <h1 class="display-2 text-capitalize text-white mb-4 fadeInLeft animated" data-animation="fadeInLeft" data-delay="1.3s" style="animation-delay: 1.3s;">Секогаш обезбедена чиста вода за здрав живот</h1>
                                    <p class="mb-5 fs-5 text-white fadeInLeft animated" data-animation="fadeInLeft" data-delay="1.5s" style="animation-delay: 1.5s;">Веб апликацијата нуди информации за квалитетот на водата од јавните чешми, со цел да се обезбеди чиста и здрава вода за сите граѓани. Следете ги соопштенијата, бактериолошките анализи и пријавете проблеми за секоја чешма лесно и брзо.
                                    </p>
                                </div>
                            </div>
                        </div>
                        <div class="carousel-item">
                            <img src="img/carousel-2.jpg" class="img-fluid w-100" alt="Image">
                            <div class="carousel-caption-2">
                                <div class="carousel-caption-2-content" style="max-width: 900px;">
                                    <h4 class="text-white text-uppercase fw-bold mb-4 fadeInRight animated" data-animation="fadeInRight" data-delay="1s" style="animation-delay: 1s;" style="letter-spacing: 3px;">Важноста на чистата вода</h4>
                                    <h1 class="display-2 text-capitalize text-white mb-4 fadeInRight animated" data-animation="fadeInRight" data-delay="1.3s" style="animation-delay: 1.3s;">Секогаш обезбедена чиста вода за здрав живот</h1>
                                    <p class="mb-5 fs-5 text-white fadeInRight animated" data-animation="fadeInRight" data-delay="1.5s" style="animation-delay: 1.5s;">Веб апликацијата нуди информации за квалитетот на водата од јавните чешми, со цел да се обезбеди чиста и здрава вода за сите граѓани. Следете ги соопштенијата, бактериолошките анализи и пријавете проблеми за секоја чешма лесно и брзо. 
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <button class="carousel-control-prev" type="button" data-bs-target="#carouselId" data-bs-slide="prev">
                        <span class="carousel-control-prev-icon btn btn-primary fadeInLeft animated" aria-hidden="true" data-animation="fadeInLeft" data-delay="1.1s" style="animation-delay: 1.3s;"> <i class="fa fa-angle-left fa-3x"></i></span>
                        <span class="visually-hidden">Previous</span>
                    </button>
                    <button class="carousel-control-next" type="button" data-bs-target="#carouselId" data-bs-slide="next">
                        <span class="carousel-control-next-icon btn btn-primary fadeInRight animated" aria-hidden="true" data-animation="fadeInLeft" data-delay="1.1s" style="animation-delay: 1.3s;"><i class="fa fa-angle-right fa-3x"></i></span>
                        <span class="visually-hidden">Next</span>
                    </button>
                </div>
            </div>
            <!-- Carousel End -->
        </div>
        <!-- Navbar & Hero End -->

        <!-- Modal Search Start -->
        <div class="modal fade" id="searchModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-fullscreen">
                <div class="modal-content rounded-0">
                    <div class="modal-header">
                        <h4 class="modal-title mb-0" id="exampleModalLabel">Пребарувај според клучен збор</h4>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body d-flex align-items-center">
                        <div class="input-group w-75 mx-auto d-flex">
                            <input type="search" class="form-control p-3" placeholder="keywords" aria-describedby="search-icon-1">
                            <span id="search-icon-1" class="input-group-text btn border p-3"><i class="fa fa-search text-white"></i></span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Modal Search End -->

        <!-- feature Start -->
        <div class="container-fluid feature bg-light py-5">
            <div class="container py-5">
                <div class="text-center mx-auto pb-5 wow fadeInUp" data-wow-delay="0.2s" style="max-width: 800px;">
                    <h4 class="text-uppercase text-primary">Нашата цел</h4>
                    <h1 class="display-6  mb-3">Да обезбедиме точни и проверени информации за квалитетот на водата од јавните чешми, со цел чиста и здрава вода за сите граѓани.</h1>
                </div>
                <div class="row g-4">
                    <div class=" col-md-6 col-lg-6 col-xl-3 wow fadeInUp" data-wow-delay="0.2s">
                        <div class="feature-item p-4">
                            <div class="feature-icon mb-3"><i class="fas fa-hand-holding-water text-white fa-3x"></i></div>
                            <a href="#" class="h4 mb-3">Контрола на квалитет</a>
                            <p class="mb-3">Секој јавен извор на вода редовно се проверува за да се осигура квалитетот и безбедноста. Нашата апликација овозможува корисниците да ги видат најновите податоци за бактериолошките и физичко-хемиските параметри на водата.</p>
                        </div>
                    </div>
                    <div class="col-md-6 col-lg-6 col-xl-3 wow fadeInUp" data-wow-delay="0.4s">
                        <div class="feature-item p-4">
                            <div class="feature-icon mb-3"><i class="fas fa-filter text-white fa-3x"></i></div>
                            <a href="#" class="h4 mb-3">Пет чекори на филтрација</a>
                            <p class="mb-3">Водата од јавните чешми поминува низ повеќестепена филтрација за да се отстранат нечистотиите и штетните материи. Овие пет чекори обезбедуваат чиста и безбедна вода за секој граѓанин.</p>
                        </div>
                    </div>
                    <div class="col-md-6 col-lg-6 col-xl-3 wow fadeInUp" data-wow-delay="0.6s">
                        <div class="feature-item p-4">
                            <div class="feature-icon mb-3"><i class="fas fa-recycle text-white fa-3x"></i></div>
                            <a href="#" class="h4 mb-3">Состав</a>
                            <p class="mb-3">Секој примерок на вода се анализира за хемиски и минерални компоненти. Следењето на составот на водата помага да се обезбеди балансирана и здрава вода која е безбедна за пиење.</p>
                        </div>
                    </div>
                    <div class="col-md-6 col-lg-6 col-xl-3 wow fadeInUp" data-wow-delay="0.8s">
                        <div class="feature-item p-4">
                            <div class="feature-icon mb-3"><i class="fas fa-microscope text-white fa-3x"></i></div>
                            <a href="#" class="h4 mb-3">Лабораториска контрола</a>
                            <p class="mb-3">Редовните лабораториски контроли гарантираат дека сите параметри на водата се во согласност со здравствените стандарди. Со помош на современа лабораториска опрема, се откриваат и најмалите промени кои можат да влијаат на квалитетот.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- feature End -->
        <!-- Service Start -->
        <div class="container-fluid service bg-light overflow-hidden py-5">
            <div class="container py-5">
                <div class="text-center mx-auto pb-5 wow fadeInUp" data-wow-delay="0.2s" style="max-width: 800px;">
                    <h4 class="text-uppercase text-primary">Последни новости и информации</h4>
                    <h1 class="display-6  mb-3">Следете ги најновите известувања за квалитетот на водата од јавните чешми и бидете сигурни дека секогаш користите чиста и здрава вода.</h1>
                </div>
                <div class="row gx-0 gy-4 align-items-center">
                    <div class="col-lg-6 col-xl-4 wow fadeInLeft" data-wow-delay="0.2s">
                        <div class="service-item rounded p-4 mb-4">
                            <div class="row">
                                <div class="col-12">
                                    <div class="d-flex">
                                        <div class="service-content text-end">
                                            <a href="#" class="h4 d-inline-block mb-3">Јавни чешми за домаќинства</a>
                                            <p class="mb-0">Редовно се проверува квалитетот на водата од јавните чешми во населбите, за да се обезбеди чиста и безбедна вода за домаќинствата.</p>
                                        </div>
                                        <div class="ps-4">
                                            <div class="service-btn"><i class="fas fa-hand-holding-water text-white fa-2x"></i></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="service-item rounded p-4 mb-4">
                            <div class="row">
                                <div class="col-12">
                                    <div class="d-flex">
                                        <div class="service-content text-end">
                                            <a href="#" class="h4 d-inline-block mb-3">Јавни чешми во комерцијални објекти</a>
                                            <p class="mb-0">Следиме и прикажуваме податоци за водата од јавните чешми во комерцијални објекти, за да се гарантира здравствена сигурност за вработените и посетителите.</p>
                                        </div>
                                        <div class="ps-4">
                                            <div class="service-btn"><i class="fas fa-dumpster-fire text-white fa-2x"></i></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="service-item rounded p-4 mb-0">
                            <div class="row">
                                <div class="col-12">
                                    <div class="d-flex">
                                        <div class="service-content text-end">
                                            <a href="#" class="h4 d-inline-block mb-3">Филтрациони постројки</a>
                                            <p class="mb-0">Информации за состојбата на филтрационите постројки кои снабдуваат јавни чешми се ажурираат редовно за да се осигура квалитетот на водата.</p>
                                        </div>
                                        <div class="ps-4">
                                            <div class="service-btn"><i class="fas fa-filter text-white fa-2x"></i></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6 col-xl-4 wow fadeInUp" data-wow-delay="0.3s">
                        <div class="bg-transparent">
                            <img src="img/water.png" class="img-fluid w-100" alt="">
                        </div>
                    </div>
                    <div class="col-lg-6 col-xl-4 wow fadeInRight" data-wow-delay="0.2s">
                        <div class="service-item rounded p-4 mb-4">
                            <div class="row">
                                <div class="col-12">
                                    <div class="d-flex">
                                        <div class="pe-4">
                                            <div class="service-btn"><i class="fas fa-assistive-listening-systems text-white fa-2x"></i></div>
                                        </div>
                                        <div class="service-content">
                                            <a href="#" class="h4 d-inline-block mb-3">Омекнување на вода</a>
                                            <p class="mb-0">Следиме и прикажуваме податоци за системите за омекнување на вода каде се применуваат, со цел да се намали тврдоста и да се обезбеди здрава вода за пиење.</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="service-item rounded p-4 mb-4">
                            <div class="row">
                                <div class="col-12">
                                    <div class="d-flex">
                                        <div class="pe-4">
                                            <div class="service-btn"><i class="fas fa-recycle text-white fa-2x"></i></div>
                                        </div>
                                        <div class="service-content">
                                            <a href="#" class="h4 d-inline-block mb-3">Истражување на квалитетот на вода</a>
                                            <p class="mb-0">Спроведуваме редовно истражување и анализи на квалитетот на водата од јавните чешми за да се откријат потенцијални проблеми и да се информираат граѓаните.</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="service-item rounded p-4 mb-0">
                            <div class="row">
                                <div class="col-12">
                                    <div class="d-flex">
                                        <div class="pe-4">
                                            <div class="service-btn"><i class="fas fa-project-diagram text-white fa-2x"></i></div>
                                        </div>
                                        <div class="service-content">
                                            <a href="#" class="h4 d-inline-block mb-3">Планирање на контрола и одржување</a>
                                            <p class="mb-0">Планираме годишни проверки и ажурирања за секоја јавна чешма, со цел да се обезбеди безбедна и квалитетна вода за сите корисници.</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Service End -->
        <!-- Blog Start -->
        <div class="container-fluid blog py-5">
    <div class="container py-5">
        <div class="text-center mx-auto pb-5 wow fadeInUp" data-wow-delay="0.2s" style="max-width: 800px;">
            <h4 class="text-uppercase text-primary">Каталог на чешми</h4>
            <h1 class="display-6  mb-3">Прегледајте ги сите јавни чешми со фотографии и основни податоци за вода.</h1>
        </div>
        <div class="row g-4 justify-content-center">
<?php
$style = "";
if ($result && $result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        if ($row['sostojba'] === 'неисправно') {
    $style = "color:crimson;  padding:5px; border-radius:4px;";
}
if ($row['sostojba'] === 'исправно') {
    $style = "color:07a7c7;  padding:5px; border-radius:4px;";}
        ?>
        <div class="col-lg-6 col-xl-4 wow fadeInUp" data-wow-delay="0.2s">
            <div class="blog-item">
                <div class="blog-img">
                    <!-- Слика од базата -->
                    <img src="<?php echo htmlspecialchars($row['slika']); ?>" 
                         class="img-fluid rounded-top w-100" alt="">
                    <!-- Име на чешмата -->
                    <div class="blog-date px-4 py-2">
                        <?php echo htmlspecialchars($row['ime']); ?>
                    </div>
                </div>
                <div class="blog-content rounded-bottom p-4">
                    <!-- Краток опис или состојба -->
                    <a href="#" class="h4 d-inline-block mb-3" style="<?php echo $style; ?>">
                    <?php echo htmlspecialchars($row['sostojba']); ?>

                    </a>
                    <p><?php echo htmlspecialchars($row['opis']); ?></p>

                    <!-- Линк кон деталната страница -->
                    <a href="cesma.php?id=<?php echo $row['id']; ?>" 
                       class="fw-bold text-secondary">
                        Дознај повеќе <i class="fa fa-angle-right"></i>
                    </a>
                </div>
            </div>
        </div>
        <?php
    }
} else {
    echo "<p>Нема внесени чешми во базата.</p>";
}
?>
</div>
 
        <!-- Blog End -->
        <!-- Footer Start -->
        <div class="container-fluid footer py-5 wow fadeIn" data-wow-delay="0.2s">
           <div class="container py-5">
               <div class="row g-5 mb-5 align-items-center">
                   <div class="col-lg-7">
                       <div class="position-relative mx-auto">
                           <input class="form-control rounded-pill w-100 py-3 ps-4 pe-5" type="text" placeholder="Внеси емаил за да не заследиш">
                           <button type="button" class="btn btn-secondary rounded-pill position-absolute top-0 end-0 py-2 px-4 mt-2 me-2">Заследи</button>
                       </div>
                   </div>
                   <div class="col-lg-5">
                       <div class="d-flex align-items-center justify-content-center justify-content-lg-end">
                           <a class="btn btn-secondary btn-md-square rounded-circle me-3" href=""><i class="fab fa-facebook-f"></i></a>
                           <a class="btn btn-secondary btn-md-square rounded-circle me-3" href=""><i class="fab fa-twitter"></i></a>
                           <a class="btn btn-secondary btn-md-square rounded-circle me-3" href=""><i class="fab fa-instagram"></i></a>
                           <a class="btn btn-secondary btn-md-square rounded-circle me-0" href=""><i class="fab fa-linkedin-in"></i></a>
                       </div>
                   </div>
               </div>
                <div class="row g-5">
                    <div class="col-md-6 col-lg-6 col-xl-3">
                        <div class="footer-item d-flex flex-column">
                            <div class="footer-item">
                                <h3 class="text-white mb-4"><i class="fas fa-hand-holding-water text-primary me-3"></i>QuenchMap</h3>
                                <p class="mb-3">Веб апликацијата нуди информации за квалитетот на водата од јавните чешми, со цел да се обезбеди чиста и здрава вода за сите граѓани. Следете ги соопштенијата, бактериолошките анализи и пријавете проблеми за секоја чешма лесно и брзо. </p>
                            </div>   
                        </div>
                    </div>
                    <div class="col-md-6 col-lg-6 col-xl-3">
                        <div class="footer-item d-flex flex-column">
                            <h4 class="text-white mb-4">За нас:</h4>
                            <a href="#"><i class="fas fa-angle-right me-2"></i> Нашата цел</a>
                            <a href="#"><i class="fas fa-angle-right me-2"></i> Квалитет на водата</a>
                            <a href="#"><i class="fas fa-angle-right me-2"></i> Преглед на чешми</a>
                            <a href="#"><i class="fas fa-angle-right me-2"></i> Соопштенија</a>
                            <a href="#"><i class="fas fa-angle-right me-2"></i> Пријави проблем</a>
                            <a href="#"><i class="fas fa-angle-right me-2"></i> Услови за користење</a>
                        </div>
                    </div>
                    <div class="col-md-6 col-lg-6 col-xl-3">
                        <div class="footer-item d-flex flex-column">
                            <h4 class="text-white mb-4">Работно време</h4>
                            <div class="mb-3">
                                <h6 class="text-muted mb-0">Понеделник - Петок:</h6>
                                <p class="text-white mb-0">09:00 - 19:00</p>
                            </div>
                            <div class="mb-3">
                                <h6 class="text-muted mb-0">Сабота:</h6>
                                <p class="text-white mb-0">10:00 - 17:00</p>
                            </div>
                            <div class="mb-3">
                                <h6 class="text-muted mb-0">Неработен:</h6>
                                <p class="text-white mb-0">Секоја Недела</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 col-lg-6 col-xl-3">
                        <div class="footer-item d-flex flex-column">
                            <h4 class="text-white mb-4">Контакт информации:</h4>
                            <a href="#"><i class="fa fa-map-marker-alt me-2"></i> ул: „Ленова“, бр.2, Штип</a>
                            <a href="mailto:info@example.com"><i class="fas fa-envelope me-2"></i> info@quenchmap.com</a>
                            <a href="mailto:info@example.com"><i class="fas fa-envelope me-2"></i> info@quenchmap.com</a>
                            <a href="tel:+38972656998"><i class="fas fa-phone me-2"></i> +38972656998</a>
                            <a href="tel:+389 72/656-998" class="mb-3"><i class="fas fa-print me-2"></i> +389 72/656-998</a>
                        </div>
                    </div>   
                </div>
            </div>
        </div>
        <!-- Footer End -->
        
        <!-- Copyright Start -->
        <div class="container-fluid copyright py-4">
            <div class="container">
                <div class="row g-4 align-items-center">
                    <div class="col-md-6 text-center text-md-start mb-md-0">
                        <span class="text-body"><a href="#" class="border-bottom text-white"><i class="fas fa-copyright text-light me-2"></i>QuenchMap</a>, All right reserved.</span>
                    </div>
                    <div class="col-md-6 text-center text-md-end text-body">
                        <!--/*** This template is free as long as you keep the below author’s credit link/attribution link/backlink. ***/-->
                        <!--/*** If you'd like to use the template without the below author’s credit link/attribution link/backlink, ***/-->
                        <!--/*** you can purchase the Credit Removal License from "https://htmlcodex.com/credit-removal". ***/-->
                        Designed By <a class="border-bottom text-white" href="https://htmlcodex.com">HTML Codex</a> Distributed By <a class="border-bottom text-white" href="https://themewagon.com">ThemeWagon</a>
                    </div>
                </div>
            </div>
        </div>
        <!-- Copyright End -->


        <!-- Back to Top -->
        <a href="#" class="btn btn-secondary btn-lg-square rounded-circle back-to-top"><i class="fa fa-arrow-up"></i></a>   

        
    <!-- JavaScript Libraries -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="lib/wow/wow.min.js"></script>
    <script src="lib/easing/easing.min.js"></script>
    <script src="lib/waypoints/waypoints.min.js"></script>
    <script src="lib/counterup/counterup.min.js"></script>
    <script src="lib/owlcarousel/owl.carousel.min.js"></script>
    

    <!-- Template Javascript -->
    <script src="js/main.js"></script>
    </body>

</html>