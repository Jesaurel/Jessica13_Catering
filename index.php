<?php
include 'db.php';

// Ambil kategori dari database
$query_categories = "SELECT * FROM categories";
$result_categories = mysqli_query($conn, $query_categories);

// Ambil semua menu dari database
$query_menu = "SELECT * FROM menu";
$result_menu = mysqli_query($conn, $query_menu);

// Cek apakah query berhasil
if (!$result_categories) {
    die("Error mengambil kategori: " . mysqli_error($conn));
}

if (!$result_menu) {
    die("Error mengambil menu: " . mysqli_error($conn));
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <title>CaterLez - Catering Services Website Template</title>
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
                <a href="index.html" class="navbar-brand">
                    <h1 class="text-primary fw-bold mb-0">Cater<span class="text-dark">Lez</span></h1>
                </a>
                <button class="navbar-toggler py-2 px-3" type="button" data-bs-toggle="collapse" data-bs-target="#navbarCollapse">
                    <span class="fa fa-bars text-primary"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarCollapse">
                    <div class="navbar-nav mx-auto">
                        <a href="index.php" class="nav-item nav-link active">Beranda</a>
                        <a href="about.html" class="nav-item nav-link">Tentang Kami</a>
                        <a href="service.html" class="nav-item nav-link">Layanan</a>
                        <a href="event.html" class="nav-item nav-link">Acara</a>
                        <a href="menu_user.php" class="nav-item nav-link">Menu</a>
                        <div class="nav-item dropdown">
                            <a href="#" class="nav-link dropdown-toggle" data-bs-toggle="dropdown">Pages</a>
                            <div class="dropdown-menu bg-light">
                                <a href="menu_user.php" class="dropdown-item">Booking</a>
                                <a href="blog.html" class="dropdown-item">Blog Kami</a>
                                <a href="team.html" class="dropdown-item">Tim Kami</a>
                                <a href="testimonial.html" class="dropdown-item">Testimonial</a>
                            </div>
                        </div>
                        <a href="contact.html" class="nav-item nav-link">Kontak</a>
                    </div>
                    <button class="btn-search btn btn-primary btn-md-square me-4 rounded-circle d-none d-lg-inline-flex" data-bs-toggle="modal" data-bs-target="#searchModal">
                        <i class="fas fa-search"></i>
                    </button>
                    <a href="menu_user.php" class="btn btn-primary py-2 px-4 d-none d-xl-inline-block rounded-pill">Pesan Sekarang</a>
                    <a href="profile.php" class="ms-3 d-flex align-items-center justify-content-center bg-primary text-white rounded-circle" style="width: 40px; height: 40px;">
                        <i class="fas fa-user"></i>
                    </a>
                </div>
            </nav>
        </div>
    </div>
    <!-- Navbar End -->



    
    <!-- Modal Search End -->


    <!-- Hero Start -->
    <div class="container-fluid bg-light py-6 my-6 mt-0">
        <div class="container">
            <div class="row g-5 align-items-center">
                <div class="col-lg-7 col-md-12">
                    <small class="d-inline-block fw-bold text-dark text-uppercase bg-light border border-primary rounded-pill px-4 py-1 mb-4 animated bounceInDown">Selamat Datang di CaterLez</small>
                    <h1 class="display-1 mb-4 animated bounceInDown">Pesan <span class="text-primary">Cater</span>Lez untuk Acara Impian Anda</h1>
                    <a href="menu_user.php" class="btn btn-primary border-0 rounded-pill py-3 px-4 px-md-5 me-4 animated bounceInLeft">Pesan Sekarang</a>
                    <a href="about.html" class="btn btn-primary border-0 rounded-pill py-3 px-4 px-md-5 animated bounceInLeft">Know More</a>
                </div>
                <div class="col-lg-5 col-md-12">
                    <img src="img/hero.png" class="img-fluid rounded animated zoomIn" alt="">
                </div>
            </div>
        </div>
    </div>
    <!-- Hero End -->


    <!-- About Start -->
    <div class="container-fluid py-6">
        <div class="container">
            <div class="row g-5 align-items-center">
                <div class="col-lg-5 wow bounceInUp" data-wow-delay="0.1s">
                    <img src="img/about.jpg" class="img-fluid rounded" alt="">
                </div>
                <div class="col-lg-7 wow bounceInUp" data-wow-delay="0.3s">
                    <small class="d-inline-block fw-bold text-dark text-uppercase bg-light border border-primary rounded-pill px-4 py-1 mb-3">Tentang Kami</small>
                    <h1 class="display-5 mb-4">Dipercaya oleh 200+ klien yang puas</h1>
                    <p class="mb-4">Kami berkomitmen untuk memberikan layanan katering terbaik dengan bahan berkualitas tinggi. Dengan pengalaman bertahun-tahun, kami siap menyajikan hidangan lezat untuk berbagai acara spesial Anda.</p>
                    <div class="row g-4 text-dark mb-5">
                        <div class="col-sm-6">
                            <i class="fas fa-share text-primary me-2"></i>Pengiriman Makanan Segar & Cepat
                        </div>
                        <div class="col-sm-6">
                            <i class="fas fa-share text-primary me-2"></i>Dukungan Pelanggan 24/7
                        </div>
                        <div class="col-sm-6">
                            <i class="fas fa-share text-primary me-2"></i>Pilihan Kustomisasi yang Mudah
                        </div>
                        <div class="col-sm-6">
                            <i class="fas fa-share text-primary me-2"></i>Penawaran Lezat untuk Hidangan Lezat
                        </div>
                    </div>
                    <a href="" class="btn btn-primary py-3 px-5 rounded-pill">Tentang Kami<i class="fas fa-arrow-right ps-2"></i></a>
                </div>
            </div>
        </div>
    </div>
    <!-- About End -->


    <!-- Fact Start-->
    <div class="container-fluid faqt py-6">
        <div class="container">
            <div class="row g-4 align-items-center">
                <div class="col-lg-7">
                    <div class="row g-4">
                        <div class="col-sm-4 wow bounceInUp" data-wow-delay="0.3s">
                            <div class="faqt-item bg-primary rounded p-4 text-center">
                                <i class="fas fa-users fa-4x mb-4 text-white"></i>
                                <h1 class="display-4 fw-bold" data-toggle="counter-up">689</h1>
                                <p class="text-dark text-uppercase fw-bold mb-0">Pelanggan Puas</p>
                            </div>
                        </div>
                        <div class="col-sm-4 wow bounceInUp" data-wow-delay="0.5s">
                            <div class="faqt-item bg-primary rounded p-4 text-center">
                                <i class="fas fa-users-cog fa-4x mb-4 text-white"></i>
                                <h1 class="display-4 fw-bold" data-toggle="counter-up">107</h1>
                                <p class="text-dark text-uppercase fw-bold mb-0">Koki Ahli</p>
                            </div>
                        </div>
                        <div class="col-sm-4 wow bounceInUp" data-wow-delay="0.7s">
                            <div class="faqt-item bg-primary rounded p-4 text-center">
                                <i class="fas fa-check fa-4x mb-4 text-white"></i>
                                <h1 class="display-4 fw-bold" data-toggle="counter-up">253</h1>
                                <p class="text-dark text-uppercase fw-bold mb-0">Acara Selesai</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-5 wow bounceInUp" data-wow-delay="0.1s">
                    <div class="video">
                        <button type="button" class="btn btn-play" data-bs-toggle="modal" data-src="https://www.youtube.com/embed/DWRcNpR6Kdc" data-bs-target="#videoModal">
                            <span></span>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Modal Video -->
    <div class="modal fade" id="videoModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content rounded-0">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Video Youtube</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <!-- 16:9 aspect ratio -->
                    <div class="ratio ratio-16x9">
                        <iframe class="embed-responsive-item" src="" id="video" allowfullscreen allowscriptaccess="always"
                            allow="autoplay"></iframe>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Fact End -->


    <!-- Service Start -->
    <div class="container-fluid service py-6">
        <div class="container">
            <div class="text-center wow bounceInUp" data-wow-delay="0.1s">
                <small class="d-inline-block fw-bold text-dark text-uppercase bg-light border border-primary rounded-pill px-4 py-1 mb-3">Layanan Kami</small>
                <h1 class="display-5 mb-5">Apa yang Kami Tawarkan</h1>
            </div>
            <div class="row g-4">
                <div class="col-lg-3 col-md-6 col-sm-12 wow bounceInUp" data-wow-delay="0.1s">
                    <div class="bg-light rounded service-item">
                        <div class="service-content d-flex align-items-center justify-content-center p-4">
                            <div class="service-content-icon text-center">
                                <i class="fas fa-cheese fa-7x text-primary mb-4"></i>
                                <h4 class="mb-3">Layanan Pernikahan</h4>
                                <p class="mb-4">Menyediakan paket catering lengkap untuk acara pernikahan, mulai dari makanan prasmanan hingga hidangan istimewa untuk tamu yang hadir.</p>
                                <a href="#" class="btn btn-primary px-4 py-2 rounded-pill">Read More</a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6 col-sm-12 wow bounceInUp" data-wow-delay="0.3s">
                    <div class="bg-light rounded service-item">
                        <div class="service-content d-flex align-items-center justify-content-center p-4">
                            <div class="service-content-icon text-center">
                                <i class="fas fa-pizza-slice fa-7x text-primary mb-4"></i>
                                <h4 class="mb-3">Catering Korporat</h4>
                                <p class="mb-4">Menyediakan solusi catering profesional untuk acara perusahaan, seperti seminar, rapat, atau event korporat lainnya, dengan pilihan menu yang sesuai dengan kebutuhan bisnis.</p>
                                <a href="#" class="btn btn-primary px-4 py-2 rounded-pill">Read More</a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6 col-sm-12 wow bounceInUp" data-wow-delay="0.5s">
                    <div class="bg-light rounded service-item">
                        <div class="service-content d-flex align-items-center justify-content-center p-4">
                            <div class="service-content-icon text-center">
                                <i class="fas fa-hotdog fa-7x text-primary mb-4"></i>
                                <h4 class="mb-3">Resepsi Koktail</h4>
                                <p class="mb-4">Catering untuk acara koktail dengan pilihan minuman dan makanan ringan yang elegan, cocok untuk acara formal atau informal.</p>
                                <a href="#" class="btn btn-primary px-4 py-2 rounded-pill">Read More</a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6 col-sm-12 wow bounceInUp" data-wow-delay="0.7s">
                    <div class="bg-light rounded service-item">
                        <div class="service-content d-flex align-items-center justify-content-center p-4">
                            <div class="service-content-icon text-center">
                                <i class="fas fa-hamburger fa-7x text-primary mb-4"></i>
                                <h4 class="mb-3">Catering Bento</h4>
                                <p class="mb-4">Menyediakan box bento dengan berbagai pilihan menu yang praktis dan higienis, cocok untuk acara perusahaan atau pertemuan kecil.</p>
                                <a href="#" class="btn btn-primary px-4 py-2 rounded-pill">Read More</a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6 col-sm-12 wow bounceInUp" data-wow-delay="0.1s">
                    <div class="bg-light rounded service-item">
                        <div class="service-content d-flex align-items-center justify-content-center p-4">
                            <div class="service-content-icon text-center">
                                <i class="fas fa-wine-glass-alt fa-7x text-primary mb-4"></i>
                                <h4 class="mb-3">Pesta Pub</h4>
                                <p class="mb-4">Catering untuk acara pesta dengan suasana santai, menyajikan makanan ringan dan minuman yang cocok untuk acara pub atau gathering bersama teman.</p>
                                <a href="#" class="btn btn-primary px-4 py-2 rounded-pill">Read More</a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6 col-sm-12 wow bounceInUp" data-wow-delay="0.3s">
                    <div class="bg-light rounded service-item">
                        <div class="service-content d-flex align-items-center justify-content-center p-4">
                            <div class="service-content-icon text-center">
                                <i class="fas fa-walking fa-7x text-primary mb-4"></i>
                                <h4 class="mb-3">Pengiriman ke Rumah</h4>
                                <p class="mb-4">Menyediakan layanan catering dengan pengantaran langsung ke rumah, ideal untuk acara pribadi atau perayaan di rumah dengan pilihan menu yang variatif.</p>
                                <a href="#" class="btn btn-primary px-4 py-2 rounded-pill">Read More</a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6 col-sm-12 wow bounceInUp" data-wow-delay="0.5s">
                    <div class="bg-light rounded service-item">
                        <div class="service-content d-flex align-items-center justify-content-center p-4">
                            <div class="service-content-icon text-center">
                                <i class="fas fa-wheelchair fa-7x text-primary mb-4"></i>
                                <h4 class="mb-3">Catering Duduk</h4>
                                <p class="mb-4">Layanan catering dengan sistem prasmanan yang dihidangkan langsung ke meja, cocok untuk acara formal seperti pernikahan atau makan malam perusahaan.</p>
                                <a href="#" class="btn btn-primary px-4 py-2 rounded-pill">Read More</a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6 col-sm-12 wow bounceInUp" data-wow-delay="0.7s">
                    <div class="bg-light rounded service-item">
                        <div class="service-content d-flex align-items-center justify-content-center p-4">
                            <div class="service-content-icon text-center">
                                <i class="fas fa-utensils fa-7x text-primary mb-4"></i>
                                <h4 class="mb-3">Catering Buffet</h4>
                                <p class="mb-4">Menyediakan hidangan prasmanan dengan berbagai pilihan makanan yang bisa dinikmati secara bebas, cocok untuk acara besar seperti seminar, pesta, atau pertemuan keluarga.</p>
                                <a href="#" class="btn btn-primary px-4 py-2 rounded-pill">Read More</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Service End -->


    <!-- Events Start -->
    <div class="container-fluid event py-6">
        <div class="container">
            <div class="text-center wow bounceInUp" data-wow-delay="0.1s">
                <small class="d-inline-block fw-bold text-dark text-uppercase bg-light border border-primary rounded-pill px-4 py-1 mb-3">Latest Events</small>
                <h1 class="display-5 mb-5">Our Social & Professional Events Gallery</h1>
            </div>
            <div class="tab-class text-center">
                <ul class="nav nav-pills d-inline-flex justify-content-center mb-5 wow bounceInUp" data-wow-delay="0.1s" id="eventTabs">
                    <li class="nav-item p-2">
                        <a class="nav-link active" data-bs-toggle="pill" href="#tab-all">All Events</a>
                    </li>
                    <li class="nav-item p-2">
                        <a class="nav-link" data-bs-toggle="pill" href="#tab-wedding">Wedding</a>
                    </li>
                    <li class="nav-item p-2">
                        <a class="nav-link" data-bs-toggle="pill" href="#tab-corporate">Corporate</a>
                    </li>
                    <li class="nav-item p-2">
                        <a class="nav-link" data-bs-toggle="pill" href="#tab-cocktail">Cocktail</a>
                    </li>
                    <li class="nav-item p-2">
                        <a class="nav-link" data-bs-toggle="pill" href="#tab-buffet">Buffet</a>
                    </li>
                </ul>
                <div class="tab-content">
                    <div id="tab-all" class="tab-pane fade show active">
                        <div class="row g-4" id="event-container">
                            <!-- Event Items Here (Dynamically Loaded) -->
                        </div>
                    </div>
                    <div id="tab-wedding" class="tab-pane fade">
                        <div class="row g-4" id="event-wedding"></div>
                    </div>
                    <div id="tab-corporate" class="tab-pane fade">
                        <div class="row g-4" id="event-corporate"></div>
                    </div>
                    <div id="tab-cocktail" class="tab-pane fade">
                        <div class="row g-4" id="event-cocktail"></div>
                    </div>
                    <div id="tab-buffet" class="tab-pane fade">
                        <div class="row g-4" id="event-buffet"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Events End -->

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const eventData = [{
                    id: 1,
                    category: "Wedding",
                    img: "img/event-1.jpg"
                },
                {
                    id: 2,
                    category: "Corporate",
                    img: "img/event-2.jpg"
                },
                {
                    id: 3,
                    category: "Wedding",
                    img: "img/event-3.jpg"
                },
                {
                    id: 4,
                    category: "Buffet",
                    img: "img/event-4.jpg"
                },
                {
                    id: 5,
                    category: "Cocktail",
                    img: "img/event-5.jpg"
                },
                {
                    id: 6,
                    category: "Wedding",
                    img: "img/event-6.jpg"
                },
                {
                    id: 7,
                    category: "Buffet",
                    img: "img/event-7.jpg"
                },
                {
                    id: 8,
                    category: "Corporate",
                    img: "img/event-8.jpg"
                }
            ];

            function renderEvents(category, containerId) {
                const container = document.getElementById(containerId);
                container.innerHTML = "";
                eventData.forEach(event => {
                    if (category === "All" || event.category === category) {
                        container.innerHTML += `
                        <div class="col-md-6 col-lg-3">
                            <div class="event-img position-relative">
                                <img class="img-fluid rounded w-100" src="${event.img}" alt="">
                                <div class="event-overlay d-flex flex-column p-4">
                                    <h4 class="me-auto">${event.category}</h4>
                                    <a href="${event.img}" data-lightbox="event-${event.id}" class="my-auto">
                                        <i class="fas fa-search-plus text-dark fa-2x"></i>
                                    </a>
                                </div>
                            </div>
                        </div>`;
                    }
                });
            }

            renderEvents("All", "event-container");
            renderEvents("Wedding", "event-wedding");
            renderEvents("Corporate", "event-corporate");
            renderEvents("Cocktail", "event-cocktail");
            renderEvents("Buffet", "event-buffet");
        });
    </script>



    <?php
    include 'db.php';

    // Ambil kategori dari database
    $query_categories = "SELECT * FROM categories";
    $result_categories = mysqli_query($conn, $query_categories);

    // Ambil semua menu dari database
    $query_menu = "SELECT * FROM menu";
    $result_menu = mysqli_query($conn, $query_menu);
    ?>

    <!-- Menu Start -->
    <div class="container-fluid menu bg-light py-6 my-6">
        <div class="container">
            <div class="text-center wow bounceInUp" data-wow-delay="0.1s">
                <small class="d-inline-block fw-bold text-dark text-uppercase bg-light border border-primary rounded-pill px-4 py-1 mb-3">Menu Kami</small>
                <h1 class="display-5 mb-5">Hidangan Paling Populer di Dunia</h1>
            </div>
        </div>
    </div>
    <div class="tab-class text-center">
        <ul class="nav nav-pills d-inline-flex justify-content-center mb-5 wow bounceInUp" data-wow-delay="0.1s">
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
        <!-- Konten Menu -->
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
                                                    <!-- Tombol Detail -->
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

                        <!-- Jika tidak ada menu dalam kategori ini -->
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


        <!-- Team Start -->
        <div class="container-fluid team py-6">
            <div class="container">
                <div class="text-center wow bounceInUp" data-wow-delay="0.1s">
                    <small class="d-inline-block fw-bold text-dark text-uppercase bg-light border border-primary rounded-pill px-4 py-1 mb-3">Tim Kami</small>
                    <h1 class="display-5 mb-5">Kami memiliki tim chef yang berpengalaman</h1>
                </div>
                <div class="row g-4">
                    <div class="col-lg-3 col-md-6 wow bounceInUp" data-wow-delay="0.1s">
                        <div class="team-item rounded">
                            <img class="img-fluid rounded-top " src="img/team-1.jpg" alt="">
                            <div class="team-content text-center py-3 bg-dark rounded-bottom">
                                <h4 class="text-primary">Henry</h4>
                                <p class="text-white mb-0">Chef Dekorasi</p>
                            </div>
                            <div class="team-icon d-flex flex-column justify-content-center m-4">
                                <a class="share btn btn-primary btn-md-square rounded-circle mb-2" href=""><i class="fas fa-share-alt"></i></a>
                                <a class="share-link btn btn-primary btn-md-square rounded-circle mb-2" href=""><i class="fab fa-facebook-f"></i></a>
                                <a class="share-link btn btn-primary btn-md-square rounded-circle mb-2" href=""><i class="fab fa-twitter"></i></a>
                                <a class="share-link btn btn-primary btn-md-square rounded-circle mb-2" href=""><i class="fab fa-instagram"></i></a>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-6 wow bounceInUp" data-wow-delay="0.3s">
                        <div class="team-item rounded">
                            <img class="img-fluid rounded-top " src="img/team-2.jpg" alt="">
                            <div class="team-content text-center py-3 bg-dark rounded-bottom">
                                <h4 class="text-primary">Jemes Born</h4>
                                <p class="text-white mb-0">Chef Eksekutif</p>
                            </div>
                            <div class="team-icon d-flex flex-column justify-content-center m-4">
                                <a class="share btn btn-primary btn-md-square rounded-circle mb-2" href=""><i class="fas fa-share-alt"></i></a>
                                <a class="share-link btn btn-primary btn-md-square rounded-circle mb-2" href=""><i class="fab fa-facebook-f"></i></a>
                                <a class="share-link btn btn-primary btn-md-square rounded-circle mb-2" href=""><i class="fab fa-twitter"></i></a>
                                <a class="share-link btn btn-primary btn-md-square rounded-circle mb-2" href=""><i class="fab fa-instagram"></i></a>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-6 wow bounceInUp" data-wow-delay="0.5s">
                        <div class="team-item rounded">
                            <img class="img-fluid rounded-top " src="img/team-3.jpg" alt="">
                            <div class="team-content text-center py-3 bg-dark rounded-bottom">
                                <h4 class="text-primary">Martin Hill</h4>
                                <p class="text-white mb-0">Kitchen Porter</p>
                            </div>
                            <div class="team-icon d-flex flex-column justify-content-center m-4">
                                <a class="share btn btn-primary btn-md-square rounded-circle mb-2" href=""><i class="fas fa-share-alt"></i></a>
                                <a class="share-link btn btn-primary btn-md-square rounded-circle mb-2" href=""><i class="fab fa-facebook-f"></i></a>
                                <a class="share-link btn btn-primary btn-md-square rounded-circle mb-2" href=""><i class="fab fa-twitter"></i></a>
                                <a class="share-link btn btn-primary btn-md-square rounded-circle mb-2" href=""><i class="fab fa-instagram"></i></a>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-6 wow bounceInUp" data-wow-delay="0.7s">
                        <div class="team-item rounded">
                            <img class="img-fluid rounded-top " src="img/team-4.jpg" alt="">
                            <div class="team-content text-center py-3 bg-dark rounded-bottom">
                                <h4 class="text-primary">Adam Smith</h4>
                                <p class="text-white mb-0">Head Chef</p>
                            </div>
                            <div class="team-icon d-flex flex-column justify-content-center m-4">
                                <a class="share btn btn-primary btn-md-square rounded-circle mb-2" href=""><i class="fas fa-share-alt"></i></a>
                                <a class="share-link btn btn-primary btn-md-square rounded-circle mb-2" href=""><i class="fab fa-facebook-f"></i></a>
                                <a class="share-link btn btn-primary btn-md-square rounded-circle mb-2" href=""><i class="fab fa-twitter"></i></a>
                                <a class="share-link btn btn-primary btn-md-square rounded-circle mb-2" href=""><i class="fab fa-instagram"></i></a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Team End -->


        <!-- Testimonial Start -->
        <div class="container-fluid py-6">
            <div class="container">
                <div class="text-center wow bounceInUp" data-wow-delay="0.1s">
                    <small class="d-inline-block fw-bold text-dark text-uppercase bg-light border border-primary rounded-pill px-4 py-1 mb-3">Testimonial</small>
                    <h1 class="display-5 mb-5">Apa kata pelanggan kami!</h1>
                </div>
                <div class="owl-carousel owl-theme testimonial-carousel testimonial-carousel-1 mb-4 wow bounceInUp" data-wow-delay="0.1s">
                    <div class="testimonial-item rounded bg-light">
                        <div class="d-flex mb-3">
                            <img src="img/testimonial-1.jpg" class="img-fluid rounded-circle flex-shrink-0" alt="">
                            <div class="position-absolute" style="top: 15px; right: 20px;">
                                <i class="fa fa-quote-right fa-2x"></i>
                            </div>
                            <div class="ps-3 my-auto">
                                <h4 class="mb-0">Andi Setiawan</h4>
                                <p class="m-0">Manager</p>
                            </div>
                        </div>
                        <div class="testimonial-content">
                            <div class="d-flex">
                                <i class="fas fa-star text-primary"></i>
                                <i class="fas fa-star text-primary"></i>
                                <i class="fas fa-star text-primary"></i>
                                <i class="fas fa-star text-primary"></i>
                                <i class="fas fa-star text-primary"></i>
                            </div>
                            <p class="fs-5 m-0 pt-3">Kami menggunakan layanan catering untuk acara kantor, dan semuanya sempurna! Menu yang disediakan sangat beragam dan disukai oleh semua tamu. Terima kasih untuk Catering Lezat yang telah membuat acara kami sukses!</p>
                        </div>
                    </div>
                    <div class="testimonial-item rounded bg-light">
                        <div class="d-flex mb-3">
                            <img src="img/testimonial-2.jpg" class="img-fluid rounded-circle flex-shrink-0" alt="">
                            <div class="position-absolute" style="top: 15px; right: 20px;">
                                <i class="fa fa-quote-right fa-2x"></i>
                            </div>
                            <div class="ps-3 my-auto">
                                <h4 class="mb-0">Siti Rahma</h4>
                                <p class="m-0">Ibu Rumah Tangga</p>
                            </div>
                        </div>
                        <div class="testimonial-content">
                            <div class="d-flex">
                                <i class="fas fa-star text-primary"></i>
                                <i class="fas fa-star text-primary"></i>
                                <i class="fas fa-star text-primary"></i>
                                <i class="fas fa-star text-primary"></i>
                                <i class="fas fa-star text-primary"></i>
                            </div>
                            <p class="fs-5 m-0 pt-3">Paket catering untuk pernikahan saya sangat memuaskan. Semua tamu menikmati hidangannya, dan saya sangat senang dengan layanan yang diberikan. Catering Lezat benar-benar mengerti selera para tamu.</p>
                        </div>
                    </div>
                    <div class="testimonial-item rounded bg-light">
                        <div class="d-flex mb-3">
                            <img src="img/testimonial-3.jpg" class="img-fluid rounded-circle flex-shrink-0" alt="">
                            <div class="position-absolute" style="top: 15px; right: 20px;">
                                <i class="fa fa-quote-right fa-2x"></i>
                            </div>
                            <div class="ps-3 my-auto">
                                <h4 class="mb-0">Budi Santoso</h4>
                                <p class="m-0">Event Organizer</p>
                            </div>
                        </div>
                        <div class="testimonial-content">
                            <div class="d-flex">
                                <i class="fas fa-star text-primary"></i>
                                <i class="fas fa-star text-primary"></i>
                                <i class="fas fa-star text-primary"></i>
                                <i class="fas fa-star text-primary"></i>
                                <i class="fas fa-star text-primary"></i>
                            </div>
                            <p class="fs-5 m-0 pt-3">Makanan enak dan pelayanan yang sangat baik! Catering Lezat membuat acara pesta saya menjadi lebih istimewa. Saya pasti akan merekomendasikan mereka kepada klien saya di masa depan.</p>
                        </div>
                    </div>
                    <div class="testimonial-item rounded bg-light">
                        <div class="d-flex mb-3">
                            <img src="img/testimonial-4.jpg" class="img-fluid rounded-circle flex-shrink-0" alt="">
                            <div class="position-absolute" style="top: 15px; right: 20px;">
                                <i class="fa fa-quote-right fa-2x"></i>
                            </div>
                            <div class="ps-3 my-auto">
                                <h4 class="mb-0">Rina Anggraini</h4>
                                <p class="m-0">Wirausaha</p>
                            </div>
                        </div>
                        <div class="testimonial-content">
                            <div class="d-flex">
                                <i class="fas fa-star text-primary"></i>
                                <i class="fas fa-star text-primary"></i>
                                <i class="fas fa-star text-primary"></i>
                                <i class="fas fa-star text-primary"></i>
                                <i class="fas fa-star text-primary"></i>
                            </div>
                            <p class="fs-5 m-0 pt-3">Kami memesan nasi box untuk acara perusahaan, dan semuanya tepat waktu dan enak! Sangat profesional dan ramah. Terima kasih Catering Lezat!</p>
                        </div>
                    </div>
                </div>
                <div class="owl-carousel testimonial-carousel testimonial-carousel-2 wow bounceInUp" data-wow-delay="0.3s">
                    <div class="testimonial-item rounded bg-light">
                        <div class="d-flex mb-3">
                            <img src="img/testimonial-1.jpg" class="img-fluid rounded-circle flex-shrink-0" alt="">
                            <div class="position-absolute" style="top: 15px; right: 20px;">
                                <i class="fa fa-quote-right fa-2x"></i>
                            </div>
                            <div class="ps-3 my-auto">
                                <h4 class="mb-0">Dwi Prasetyo</h4>
                                <p class="m-0">Penyelenggara Acara</p>
                            </div>
                        </div>
                        <div class="testimonial-content">
                            <div class="d-flex">
                                <i class="fas fa-star text-primary"></i>
                                <i class="fas fa-star text-primary"></i>
                                <i class="fas fa-star text-primary"></i>
                                <i class="fas fa-star text-primary"></i>
                                <i class="fas fa-star text-primary"></i>
                            </div>
                            <p class="fs-5 m-0 pt-3">Layanan catering mereka sangat memuaskan! Pilihan menu yang beragam dan semuanya sangat lezat. Kami pasti akan terus menggunakan Catering Lezat untuk acara kami.</p>
                        </div>
                    </div>
                    <div class="testimonial-item rounded bg-light">
                        <div class="d-flex mb-3">
                            <img src="img/testimonial-2.jpg" class="img-fluid rounded-circle flex-shrink-0" alt="">
                            <div class="position-absolute" style="top: 15px; right: 20px;">
                                <i class="fa fa-quote-right fa-2x"></i>
                            </div>
                            <div class="ps-3 my-auto">
                                <h4 class="mb-0">Lina Saraswati</h4>
                                <p class="m-0">Freelancer</p>
                            </div>
                        </div>
                        <div class="testimonial-content">
                            <div class="d-flex">
                                <i class="fas fa-star text-primary"></i>
                                <i class="fas fa-star text-primary"></i>
                                <i class="fas fa-star text-primary"></i>
                                <i class="fas fa-star text-primary"></i>
                                <i class="fas fa-star text-primary"></i>
                            </div>
                            <p class="fs-5 m-0 pt-3">Paket catering dari Catering Lezat luar biasa! Rasanya enak dan disajikan dengan rapi. Kami sangat puas dengan pelayanan mereka.</p>
                        </div>
                    </div>
                    <div class="testimonial-item rounded bg-light">
                        <div class="d-flex mb-3">
                            <img src="img/testimonial-3.jpg" class="img-fluid rounded-circle flex-shrink-0" alt="">
                            <div class="position-absolute" style="top: 15px; right: 20px;">
                                <i class="fa fa-quote-right fa-2x"></i>
                            </div>
                            <div class="ps-3 my-auto">
                                <h4 class="mb-0">Eka Yuliana</h4>
                                <p class="m-0">Desainer</p>
                            </div>
                        </div>
                        <div class="testimonial-content">
                            <div class="d-flex">
                                <i class="fas fa-star text-primary"></i>
                                <i class="fas fa-star text-primary"></i>
                                <i class="fas fa-star text-primary"></i>
                                <i class="fas fa-star text-primary"></i>
                                <i class="fas fa-star text-primary"></i>
                            </div>
                            <p class="fs-5 m-0 pt-3">Makanan catering yang enak dan presentasi yang menarik! Kami sangat puas dengan layanan yang diberikan oleh Catering Lezat. Sangat cocok untuk acara apapun.</p>
                        </div>
                    </div>
                    <div class="testimonial-item rounded bg-light">
                        <div class="d-flex mb-3">
                            <img src="img/testimonial-4.jpg" class="img-fluid rounded-circle flex-shrink-0" alt="">
                            <div class="position-absolute" style="top: 15px; right: 20px;">
                                <i class="fa fa-quote-right fa-2x"></i>
                            </div>
                            <div class="ps-3 my-auto">
                                <h4 class="mb-0">Ardianto Pratama</h4>
                                <p class="m-0">CEO</p>
                            </div>
                        </div>
                        <div class="testimonial-content">
                            <div class="d-flex">
                                <i class="fas fa-star text-primary"></i>
                                <i class="fas fa-star text-primary"></i>
                                <i class="fas fa-star text-primary"></i>
                                <i class="fas fa-star text-primary"></i>
                                <i class="fas fa-star text-primary"></i>
                            </div>
                            <p class="fs-5 m-0 pt-3">Sebagai perusahaan besar, kami sering mengadakan acara dan selalu menggunakan Catering Lezat. Selalu memuaskan dan para tamu kami pun selalu memuji kualitas makanannya!</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Testimonial End -->


        <!-- Blog Start -->
        <div class="container-fluid blog py-6">
            <div class="container">
                <div class="text-center wow bounceInUp" data-wow-delay="0.1s">
                    <small class="d-inline-block fw-bold text-dark text-uppercase bg-light border border-primary rounded-pill px-4 py-1 mb-3">Blog Kami</small>
                    <h1 class="display-5 mb-5">Jadilah yang pertama untuk membaca berita</h1>
                </div>
                <div class="row gx-4 justify-content-center">
                    <div class="col-md-6 col-lg-4 wow bounceInUp" data-wow-delay="0.1s">
                        <div class="blog-item">
                            <div class="overflow-hidden rounded">
                                <img src="img/blog-1.jpg" class="img-fluid w-100" alt="">
                            </div>
                            <div class="blog-content mx-4 d-flex rounded bg-light">
                                <div class="text-dark bg-primary rounded-start">
                                    <div class="h-100 p-3 d-flex flex-column justify-content-center text-center">
                                        <p class="fw-bold mb-0">16</p>
                                        <p class="fw-bold mb-0">Sep</p>
                                    </div>
                                </div>
                                <a href="#" class="h5 lh-base my-auto h-100 p-3">How to get more test in your food from</a>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 col-lg-4 wow bounceInUp" data-wow-delay="0.3s">
                        <div class="blog-item">
                            <div class="overflow-hidden rounded">
                                <img src="img/blog-2.jpg" class="img-fluid w-100" alt="">
                            </div>
                            <div class="blog-content mx-4 d-flex rounded bg-light">
                                <div class="text-dark bg-primary rounded-start">
                                    <div class="h-100 p-3 d-flex flex-column justify-content-center text-center">
                                        <p class="fw-bold mb-0">16</p>
                                        <p class="fw-bold mb-0">Sep</p>
                                    </div>
                                </div>
                                <a href="#" class="h5 lh-base my-auto h-100 p-3">How to get more test in your food from</a>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 col-lg-4 wow bounceInUp" data-wow-delay="0.5s">
                        <div class="blog-item">
                            <div class="overflow-hidden rounded">
                                <img src="img/blog-3.jpg" class="img-fluid w-100" alt="">
                            </div>
                            <div class="blog-content mx-4 d-flex rounded bg-light">
                                <div class="text-dark bg-primary rounded-start">
                                    <div class="h-100 p-3 d-flex flex-column justify-content-center text-center">
                                        <p class="fw-bold mb-0">16</p>
                                        <p class="fw-bold mb-0">Sep</p>
                                    </div>
                                </div>
                                <a href="#" class="h5 lh-base my-auto h-100 p-3">How to get more test in your food from</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Blog End -->


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
                        <span class="text-light"><a href="#"><i class="fas fa-copyright text-light me-2"></i>CaterZat</a>, All right reserved.</span>
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
        </script>
</body>

</html>