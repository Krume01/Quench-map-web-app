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
        <style>
/* Отстранување на padding од главните контејнери на футерот */
.footer.container-fluid, 
.copyright.container-fluid {
    padding-left: 0 !important;
    padding-right: 0 !important;
    margin-left: 0 !important;
    margin-right: 0 !important;
    width: 100% !important;
    max-width: 100% !important;
}

/* Осигурајте се дека нема хоризонтален скрол поради ова */
body {
    overflow-x: hidden;
}






/* =========================
   NAVBAR BASE
========================= */
.navbar {
    transition: all 0.3s ease;
    min-height: 80px;
}

/* =========================
   NAVBAR – TOP (OVER IMAGE)
========================= */
.navbar.navbar-top {
    background: transparent;
}

.navbar.navbar-top .nav-link,
.navbar.navbar-top .navbar-brand,
.navbar.navbar-top .nav-select {
    color: #ffffff !important;
}

/* =========================
   NAVBAR – SCROLLED
========================= */
.navbar.navbar-scrolled {
    background: #ffffff;
    box-shadow: 0 2px 10px rgba(0,0,0,0.1);
}

.navbar.navbar-scrolled .nav-link,
.navbar.navbar-scrolled .navbar-brand,
.navbar.navbar-scrolled .nav-select {
    color: #000000 !important;
}

/* =========================
   LOGO FIX
========================= */
.navbar-brand {
    display: flex;
    align-items: center;
    height: 100%;
}

.navbar-brand h1 {
    margin: 0;
    line-height: 1;
    display: flex;
    align-items: center;
}



/* =========================
   SELECT (GENRE DROPDOWN)
========================= */
.nav-select {
    background: transparent;
    border: none;
    color: inherit;
    font-size: 1rem;

    width: 120px;                /* 🔑 фиксна ширина */
    padding: 0.2rem 1.2rem 0.2rem 0.4rem;

    cursor: pointer;
    line-height: 1.2;

    appearance: none;
    -webkit-appearance: none;
    -moz-appearance: none;

    background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='10' height='10' fill='currentColor' viewBox='0 0 16 16'%3E%3Cpath d='M1.5 5.5l6 6 6-6'/%3E%3C/svg%3E");
    background-repeat: no-repeat;
    background-position: right 0.3rem center;
    background-size: 10px;
}


.nav-select:focus {
    outline: none;
    box-shadow: none;
}

.nav-select option {
    color: #000000;
}

/* =========================
   BUTTON FIX
========================= */
.navbar .btn {
    display: flex;
    align-items: center;
    justify-content: center;
    height: 40px;
    line-height: 1;
}
</style>
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
        <!-- Navbar & Hero Start -->
<div class="container-fluid position-relative p-0">
    <!-- Navbar Start -->
<nav class="navbar navbar-expand-lg fixed-top navbar-top px-4 px-lg-5">
    <a href="index.php" class="navbar-brand p-0 ms-3">
        <h1 class="text-primary">
            <i class="fas fa-hand-holding-water me-3"></i>QuenchMap
        </h1>
    </a>

    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarCollapse">
        <span class="fa fa-bars"></span>
    </button>

    <div class="collapse navbar-collapse" id="navbarCollapse">
        <div class="navbar-nav ms-auto align-items-center">
            <a href="index.php" class="nav-item nav-link active">Почетна</a>

            <form method="GET" class="nav-item">
                <select id="grad" name="grad_id" class="nav-select" onchange="this.form.submit()">
                    <option value="">Град</option>
                    <?php while($g = $gradovi->fetch_assoc()) { ?>
                        <option value="<?= $g['id'] ?>" <?= $selected_grad == $g['id'] ? 'selected' : '' ?>>
                            <?= htmlspecialchars($g['ime']) ?>
                        </option>
                    <?php } ?>
                </select>
            </form>

            <a href="review.php" class="nav-item nav-link">Преглед</a>
            <a href="notifications.html" class="nav-item nav-link">Соопштенија</a>
            <a href="report.html" class="nav-item nav-link">Пријави проблем</a>

            <button class="btn btn-primary btn-md-square rounded-circle ms-3"
                data-bs-toggle="modal" data-bs-target="#searchModal">
                <i class="fas fa-search"></i>
            </button>

            <a href="login.html" class="btn btn-primary rounded-pill ms-3">
                Најави се
            </a>
        </div>
    </div>
</nav>
<!-- Navbar End -->



           

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
        


<!-- TUKA BESE GATORADE-OT -->





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
      <div class="container-fluid footer py-5 wow fadeIn" data-wow-delay="0.2s">
            <div class="container py-5">
                <div class="row g-5 mb-5 align-items-center">
                    <div class="col-lg-7">
                        <div class="position-relative mx-auto">
                            <input class="form-control rounded-pill w-100 py-3 ps-4 pe-5" type="text" placeholder="Email address to Subscribe">
                            <button type="button" class="btn btn-secondary rounded-pill position-absolute top-0 end-0 py-2 px-4 mt-2 me-2">Subscribe</button>
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
                                <h3 class="text-white mb-4"><i class="fas fa-hand-holding-water text-primary me-3"></i>Acuas</h3>
                                <p class="mb-3">Dolor amet sit justo amet elitr clita ipsum elitr est.Lorem ipsum dolor sit amet, consectetur adipiscing elit consectetur adipiscing elit.</p>
                            </div>
                            <div class="position-relative">
                                <input class="form-control rounded-pill w-100 py-3 ps-4 pe-5" type="text" placeholder="Enter your email">
                                <button type="button" class="btn btn-secondary rounded-pill position-absolute top-0 end-0 py-2 mt-2 me-2">SignUp</button>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 col-lg-6 col-xl-3">
                        <div class="footer-item d-flex flex-column">
                            <h4 class="text-white mb-4">About Us</h4>
                            <a href="#"><i class="fas fa-angle-right me-2"></i> Why Choose Us</a>
                            <a href="#"><i class="fas fa-angle-right me-2"></i> Free Water Bottles</a>
                            <a href="#"><i class="fas fa-angle-right me-2"></i> Water Dispensers</a>
                            <a href="#"><i class="fas fa-angle-right me-2"></i> Bottled Water Coolers</a>
                            <a href="#"><i class="fas fa-angle-right me-2"></i> Contact us</a>
                            <a href="#"><i class="fas fa-angle-right me-2"></i> Terms & Conditions</a>
                        </div>
                    </div>
                    <div class="col-md-6 col-lg-6 col-xl-3">
                        <div class="footer-item d-flex flex-column">
                            <h4 class="text-white mb-4">Business Hours</h4>
                            <div class="mb-3">
                                <h6 class="text-muted mb-0">Mon - Friday:</h6>
                                <p class="text-white mb-0">09.00 am to 07.00 pm</p>
                            </div>
                            <div class="mb-3">
                                <h6 class="text-muted mb-0">Saturday:</h6>
                                <p class="text-white mb-0">10.00 am to 05.00 pm</p>
                            </div>
                            <div class="mb-3">
                                <h6 class="text-muted mb-0">Vacation:</h6>
                                <p class="text-white mb-0">All Sunday is our vacation</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 col-lg-6 col-xl-3">
                        <div class="footer-item d-flex flex-column">
                            <h4 class="text-white mb-4">Contact Info</h4>
                            <a href="#"><i class="fa fa-map-marker-alt me-2"></i> 123 Street, New York, USA</a>
                            <a href="mailto:info@example.com"><i class="fas fa-envelope me-2"></i> info@example.com</a>
                            <a href="mailto:info@example.com"><i class="fas fa-envelope me-2"></i> info@example.com</a>
                            <a href="tel:+012 345 67890"><i class="fas fa-phone me-2"></i> +012 345 67890</a>
                            <a href="tel:+012 345 67890" class="mb-3"><i class="fas fa-print me-2"></i> +012 345 67890</a>
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
                        <span class="text-body"><a href="#" class="border-bottom text-white"><i class="fas fa-copyright text-light me-2"></i>Your Site Name</a>, All right reserved.</span>
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

<script>
window.addEventListener("scroll", function () {
    const navbar = document.querySelector(".navbar");

    if (window.scrollY > 50) {
        navbar.classList.remove("navbar-top");
        navbar.classList.add("navbar-scrolled");
    } else {
        navbar.classList.add("navbar-top");
        navbar.classList.remove("navbar-scrolled");
    }
});
</script>

    </body>

</html>
