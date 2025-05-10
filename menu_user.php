<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <title>CaterLez - Catering Services Website</title>
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <meta content="" name="keywords">
    <meta content="" name="description">

    <!-- Google Web Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@400;600&family=Playball&display=swap" rel="stylesheet">

    <!-- Icon Font Stylesheet -->
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.15.4/css/all.css" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.4.1/font/bootstrap-icons.css" rel="stylesheet">

    <!-- Libraries Stylesheet -->
    <link href="lib/animate/animate.min.css" rel="stylesheet">
    <link href="lib/lightbox/css/lightbox.min.css" rel="stylesheet">
    <link href="lib/owlcarousel/owl.carousel.min.css" rel="stylesheet">

    <!-- Customized Bootstrap Stylesheet -->
    <link href="css/bootstrap.min.css" rel="stylesheet">


    <!-- Template Stylesheet -->
    <link href="css/style.css" rel="stylesheet">
</head>

<body>

    <!-- Spinner Start -->
    <div id="spinner" class="show w-100 vh-100 bg-white position-fixed translate-middle top-50 start-50  d-flex align-items-center justify-content-center">
        <div class="spinner-grow text-primary" role="status"></div>
    </div>
    <!-- Spinner End -->


    <!-- Navbar start -->
    <div class="container-fluid nav-bar">
        <div class="container">
            <nav class="navbar navbar-light navbar-expand-lg py-4">
                <a href="index.php" class="navbar-brand">
                    <h1 class="text-primary fw-bold mb-0">Cater<span class="text-dark">Lez</span> </h1>
                </a>
                <button class="navbar-toggler py-2 px-3" type="button" data-bs-toggle="collapse" data-bs-target="#navbarCollapse">
                    <span class="fa fa-bars text-primary"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarCollapse">
                    <div class="navbar-nav mx-auto">
                        <a href="index.php" class="nav-item nav-link">Beranda</a>
                        <a href="about.html" class="nav-item nav-link">Tentang Kami</a>
                        <a href="service.html" class="nav-item nav-link">Layanan</a>
                        <a href="event.html" class="nav-item nav-link">Acara</a>
                        <a href="menu_user.php" class="nav-item nav-link active">Menu</a>
                        <div class="nav-item dropdown">
                            <a href="#" class="nav-link dropdown-toggle" data-bs-toggle="dropdown">Pages</a>
                            <div class="dropdown-menu bg-light">
                                <a href="book.html" class="dropdown-item">Booking</a>
                                <a href="blog.html" class="dropdown-item">Blog Kami</a>
                                <a href="team.html" class="dropdown-item">Tim Kami</a>
                                <a href="testimonial.html" class="dropdown-item">Testimonial</a>
                            </div>
                        </div>
                        <a href="contact.html" class="nav-item nav-link">Kontak</a>
                    </div>
                    <button class="btn-search btn btn-primary btn-md-square me-4 rounded-circle d-none d-lg-inline-flex" data-bs-toggle="modal" data-bs-target="#searchModal"><i class="fas fa-search"></i></button>
                    <a href="menu_user.php" class="btn btn-primary py-2 px-4 d-none d-xl-inline-block rounded-pill">Pesan Sekarang</a>
                </div>
            </nav>
        </div>
    </div>
    <!-- Navbar End -->


    <!-- Modal Search Start -->
    <div class="modal fade" id="searchModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-fullscreen">
            <div class="modal-content rounded-0">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Cari berdasarkan kata kunci</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body d-flex align-items-center">
                    <div class="input-group w-75 mx-auto d-flex">
                        <input type="search" class="form-control bg-transparent p-3" placeholder="keywords" aria-describedby="search-icon-1">
                        <span id="search-icon-1" class="input-group-text p-3"><i class="fa fa-search"></i></span>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Modal Search End -->


    <div class="container-fluid bg-light py-6 mt-0">
    <div class="container text-center animated bounceInDown">
        <h1 class="display-1 mb-4">Menu</h1>
        <ol class="breadcrumb justify-content-center mb-0 animated bounceInDown">
            <li class="breadcrumb-item"><a href="index.php">Beranda</a></li>
            <li class="breadcrumb-item"><a href="menu_user.php">Pages</a></li>
            <li class="breadcrumb-item text-dark" aria-current="page">Menu</li>
        </ol>
    </div>
</div>
<?php
include 'db.php';

// Ambil kategori dari database
$query_categories = "SELECT * FROM categories";
$result_categories = mysqli_query($conn, $query_categories);

// Ambil semua menu dari database
$query_menu = "SELECT * FROM menu";
$result_menu = mysqli_query($conn, $query_menu);
?>

<div class="container-fluid menu bg-light py-6 my-6" style="max-height: 150px; overflow: hidden; margin-top: -20px;">
    <div class="container">
        <div class="text-center wow bounceInUp" data-wow-delay="0.1s">
            <small class="d-inline-block fw-bold text-dark text-uppercase bg-light border border-primary rounded-pill px-4 py-1 mb-3">Menu Kami</small>
            <h1 class="display-5 text-center" style="margin-bottom: 10px;">Hidangan Paling Populer di Dunia</h1>
        </div>
    </div>
</div>
<div class="tab-class text-center">
    <ul class="nav nav-pills d-inline-flex justify-content-center" style="margin-top: -20px;">
        <?php
        $first_category = true;
        while ($category = mysqli_fetch_assoc($result_categories)) {
        ?>
            <li class="nav-item p-2">
                <a class="d-flex py-2 mx-2 border border-primary bg-white rounded-pill
                    <?php echo $first_category ? 'active' : ''; ?>"
                   data-bs-toggle="pill" href="#tab-<?php echo $category['id']; ?>">
                    <span class="text-dark" style="width: 150px;"><?php echo $category['name']; ?></span>
                </a>
            </li>
        <?php
            $first_category = false;
        }
        ?>
    </ul>
    <div class="tab-content">
        <?php
        mysqli_data_seek($result_categories, 0); // Kembalikan pointer ke awal
        $first_category = true;
        while ($category = mysqli_fetch_assoc($result_categories)) {
        ?>
            <div id="tab-<?php echo $category['id']; ?>" class="tab-pane fade show p-0 <?php echo $first_category ? 'active' : ''; ?>">
                <div class="row g-4">
                    <?php
                    mysqli_data_seek($result_menu, 0);
                    $menu_found = false; // Untuk cek apakah kategori punya menu
                    while ($menu = mysqli_fetch_assoc($result_menu)) {
                        if ($menu['category_id'] == $category['id']) {
                            $menu_found = true;
                    ?>
                                <div class="col-lg-6">
                                    <div class="menu-item d-flex align-items-center">
                                        <img class="flex-shrink-0 img-fluid rounded-circle"
                                             src="data:image/jpeg;base64,<?php echo base64_encode($menu['image']); ?>"
                                             alt="" width="80">
                                        <div class="w-100 d-flex flex-column text-start ps-4">
                                            <div class="d-flex justify-content-between border-bottom pb-2 mb-2">
                                                <h4><?php echo $menu['name']; ?></h4>
                                                <div class="d-flex align-items-center">
                                                    <h4 class="text-primary me-2">Rp<?php echo number_format($menu['price'], 0, ',', '.'); ?></h4>
                                                    <a href="detail_menu.php?id=<?php echo $menu['id']; ?>" class="btn btn-sm btn-primary">Detail</a>
                                                </div>
                                            </div>
                                            <p class="mb-0"><?php echo $menu['description']; ?></p>
                                        </div>
                                    </div>
                                </div>
                    <?php
                        }
                    }
                    ?>

<?php if (!$menu_found) { ?>

<div class="col-12 text-center">

<p class="text-muted">Belum ada menu untuk kategori ini.</p>

</div>

<?php } ?>
                </div>
            </div>
        <?php
            $first_category = false;
        }
        ?>
    </div>
</div>


        <!-- Footer Start -->
        <div class="container-fluid footer py-6 my-6 mb-0 bg-light wow bounceInUp" data-wow-delay="0.1s">
            <div class="container">
                <div class="row">
                    <div class="col-lg-3 col-md-6">
                        <div class="footer-item">
                            <h1 class="text-primary">Cater<span class="text-dark">Zat</span></h1>
                            <p class="lh-lg mb-4">Ada berbagai pilihan hidangan lezat yang siap disajikan untuk acara spesial Anda. Kami menghadirkan kualitas terbaik dengan cita rasa yang menggugah selera.</p>
                            <div class="footer-icon d-flex">
                                <a class="btn btn-primary btn-sm-square me-2 rounded-circle" href=""><i class="fab fa-facebook-f"></i></a>
                                <a class="btn btn-primary btn-sm-square me-2 rounded-circle" href=""><i class="fab fa-twitter"></i></a>
                                <a href="#" class="btn btn-primary btn-sm-square me-2 rounded-circle"><i class="fab fa-instagram"></i></a>
                                <a href="#" class="btn btn-primary btn-sm-square rounded-circle"><i class="fab fa-linkedin-in"></i></a>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-6">
                        <div class="footer-item">
                            <h4 class="mb-4">Special Facilities</h4>
                            <div class="d-flex flex-column align-items-start">
                                <a class="text-body mb-3" href=""><i class="fa fa-check text-primary me-2"></i>Cheese Burger</a>
                                <a class="text-body mb-3" href=""><i class="fa fa-check text-primary me-2"></i>Sandwich</a>
                                <a class="text-body mb-3" href=""><i class="fa fa-check text-primary me-2"></i>Panner Burger</a>
                                <a class="text-body mb-3" href=""><i class="fa fa-check text-primary me-2"></i>Special Sweets</a>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-6">
                        <div class="footer-item">
                            <h4 class="mb-4">Hubungi Kami</h4>
                            <div class="d-flex flex-column align-items-start">
                                <p><i class="fa fa-map-marker-alt text-primary me-2"></i> 123 Street, Abc, Abc</p>
                                <p><i class="fa fa-phone-alt text-primary me-2"></i> (+62) 812 2625 2172</p>
                                <p><i class="fas fa-envelope text-primary me-2"></i> jessicaaurel283@gmail.com</p>
                                <p><i class="fa fa-clock text-primary me-2"></i> Layanan 24/7 Jam</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-6">
                        <div class="footer-item">
                            <h4 class="mb-4">Galeri Sosial</h4>
                            <div class="row g-2">
                                <div class="col-4">
                                    <img src="img/menu-01.jpg" class="img-fluid rounded-circle border border-primary p-2" alt="">
                                </div>
                                <div class="col-4">
                                    <img src="img/menu-02.jpg" class="img-fluid rounded-circle border border-primary p-2" alt="">
                                </div>
                                <div class="col-4">
                                    <img src="img/menu-03.jpg" class="img-fluid rounded-circle border border-primary p-2" alt="">
                                </div>
                                <div class="col-4">
                                    <img src="img/menu-04.jpg" class="img-fluid rounded-circle border border-primary p-2" alt="">
                                </div>
                                <div class="col-4">
                                    <img src="img/menu-05.jpg" class="img-fluid rounded-circle border border-primary p-2" alt="">
                                </div>
                                <div class="col-4">
                                    <img src="img/menu-06.jpg" class="img-fluid rounded-circle border border-primary p-2" alt="">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Footer End -->


        <!-- Copyright Start -->
        <div class="container-fluid copyright bg-dark py-4">
            <div class="container">
                <div class="row">
                    <div class="col-md-6 text-center text-md-start mb-3 mb-md-0">
                        <span class="text-light"><a href="#"><i class="fas fa-copyright text-light me-2"></i>CaterLez</a>, Semua Hak Dilindungi.</span>
                    </div>
                    <div class="col-md-6 my-auto text-center text-md-end text-white">
                        <!--/*** This template is free as long as you keep the below author’s credit link/attribution link/backlink. ***/-->
                        <!--/*** If you'd like to use the template without the below author’s credit link/attribution link/backlink, ***/-->
                        <!--/*** you can purchase the Credit Removal License from "https://htmlcodex.com/credit-removal". ***/-->
                        Designed By <a class="border-bottom" href="https://htmlcodex.com">HTML Codex</a>
                    </div>
                </div>
            </div>
        </div>
        <!-- Copyright End -->


        <!-- Back to Top -->
        <a href="#" class="btn btn-md-square btn-primary rounded-circle back-to-top"><i class="fa fa-arrow-up"></i></a>


        <!-- JavaScript Libraries -->
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/js/bootstrap.bundle.min.js"></script>
        <script src="lib/easing/easing.min.js"></script>
        <script src="lib/waypoints/waypoints.min.js"></script>
        <script src="lib/counterup/counterup.min.js"></script>
        <script src="lib/lightbox/js/lightbox.min.js"></script>
        <script src="lib/owlcarousel/owl.carousel.min.js"></script>

        <!-- Template Javascript -->
        <script src="js/main.js"></script>
        <script>
            document.addEventListener("DOMContentLoaded", function() {
                document.body.style.overflowX = "hidden";
            });

    document.addEventListener('DOMContentLoaded', function() {
        const tabPanes = document.querySelectorAll('.tab-pane');

        tabPanes.forEach(tabPane => {
            const noMenuMessage = tabPane.querySelector('.no-menu-message');
            const menuItems = tabPane.querySelectorAll('.menu-item');

            if (noMenuMessage && menuItems.length === 0) {
                noMenuMessage.classList.add('show');
            }
        });

        // Tambahkan event listener untuk menampilkan pesan saat tab diaktifkan (jika belum ada menu)
        const tabPills = document.querySelectorAll('.nav-pills .nav-link');
        tabPills.forEach(tabPill => {
            tabPill.addEventListener('shown.bs.tab', function(event) {
                const targetTabPane = document.querySelector(event.target.getAttribute('href'));
                const noMenuMessage = targetTabPane.querySelector('.no-menu-message');
                const menuItems = targetTabPane.querySelectorAll('.menu-item');

                if (noMenuMessage && menuItems.length === 0) {
                    // Delay sedikit agar animasi terlihat saat tab baru aktif
                    setTimeout(() => {
                        noMenuMessage.classList.add('show');
                    }, 100);
                } else if (noMenuMessage) {
                    noMenuMessage.classList.remove('show');
                }
            });
        });
    });
        </script>

</body>

</html>