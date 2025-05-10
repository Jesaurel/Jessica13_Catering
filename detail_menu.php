<?php
session_start();
include 'db.php';

// Cek apakah user sudah login
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Ambil data menu berdasarkan ID, termasuk nama kategori
if (isset($_GET['id'])) {
    $menu_id = $_GET['id'];
    $stmt = $conn->prepare("SELECT menu.*, categories.name AS category_name
                                 FROM menu
                                 JOIN categories ON menu.category_id = categories.id
                                 WHERE menu.id = ?");
    $stmt->bind_param("i", $menu_id);
    $stmt->execute();
    $menu = $stmt->get_result()->fetch_assoc();
    $stmt->close();
}

// Ambil review dari database
$stmt = $conn->prepare("SELECT r.review, r.rating, u.username FROM reviews r JOIN users u ON r.user_id = u.id WHERE r.menu_id = ?");
$stmt->bind_param("i", $menu_id);
$stmt->execute();
$ulasan = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
$stmt->close();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Detail Menu</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        body {
            background-color: #f3f4f6;
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
        }

        .button:hover,
        .submit-review:hover {
            transform: scale(1.02);
            transition: all 0.2s ease-in-out;
        }

        @keyframes fade-in {
            from {
                opacity: 0;
                transform: translateY(10px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .animate-fade {
            animation: fade-in 0.4s ease-out;
        }

        @keyframes whoosh {
            0% {
                transform: translateX(0);
                opacity: 1;
            }

            50% {
                transform: translateX(10px) scale(1.2);
                opacity: 0.8;
            }

            100% {
                transform: translateX(20px) scale(0.5);
                opacity: 0;
            }
        }

        .animate-whoosh {
            animation: none;
            /* Initially no animation */
        }

        .whoosh-active {
            animation: whoosh 0.3s ease-in-out forwards;
        }
    </style>
</head>

<body class="bg-gray-100">

    <div class="max-w-5xl mx-auto mt-8 p-6 bg-white rounded-lg shadow-md animate-fade">
        <div class="md:flex md:space-x-8">
            <div class="md:w-1/2 mb-4 md:mb-0">
                <img src="data:image/jpeg;base64,<?php echo base64_encode($menu['image']); ?>"
                    alt="<?php echo htmlspecialchars($menu['name']); ?>"
                    class="w-full rounded-lg shadow-sm object-cover h-64 md:h-auto">
            </div>

            <div class="md:w-1/2">
                <div class="mb-4">
                    <span class="inline-block bg-pink-100 text-pink-600 rounded-full px-3 py-1 text-sm font-semibold mb-2">
                        🍽️ Kategori: <?php echo htmlspecialchars($menu['category_name']); ?>
                    </span>
                    <h1 class="text-3xl font-bold text-gray-800 mb-2 leading-tight">
                        <?php echo htmlspecialchars($menu['name']); ?>
                    </h1>
                    <div class="text-2xl font-semibold text-pink-600 mb-3">
                        Rp<?php echo number_format($menu['price'], 0, ',', '.'); ?>
                    </div>
                    <p class="text-gray-700 leading-relaxed mb-4">
                        <?php echo nl2br(htmlspecialchars($menu['description'])); ?>
                    </p>
                </div>

                <div class="space-y-2">
                    <form action="checkout.php" method="GET" class="flex items-center space-x-3 mb-3">
                        <input type="hidden" name="menu_id" value="<?php echo $menu_id; ?>">
                        <label for="quantity" class="block text-gray-700 text-sm font-bold">Jumlah:</label>
                        <input type="number" id="quantity" name="quantity" value="1" min="1" class="shadow appearance-none border rounded w-20 py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                        <button type="submit"
                            class="bg-pink-500 hover:bg-pink-600 text-white py-2 px-4 rounded-full font-semibold shadow-md transition duration-200 flex items-center gap-2">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" class="w-5 h-5">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
                            </svg>
                            Lanjut ke Checkout
                        </button>
                    </form>

                    <a href="menu_user.php"
                        class="w-full inline-block text-center bg-gray-300 hover:bg-gray-400 text-gray-800 py-3 rounded-full font-medium shadow-sm transition duration-200">
                        🔙 Kembali ke Menu
                    </a>

                    <button onclick="openReviewModal()"
                        class="w-full bg-purple-600 hover:bg-purple-700 text-white py-3 rounded-full font-semibold shadow-md transition duration-200">
                        ✍️ Tambah Review
                    </button>
                </div>
            </div>
        </div>

        <div class="mt-8 border-t pt-6">
            <h3 class="text-xl font-bold mb-4 text-gray-800">Ulasan Pelanggan</h3>
            <ul>
                <?php if (empty($ulasan)): ?>
                    <li class="text-gray-500">Belum ada ulasan untuk menu ini.</li>
                <?php else: ?>
                    <?php foreach ($ulasan as $u): ?>
                        <li class="py-3 border-b border-gray-200 last:border-b-0 flex items-start space-x-2">
                            <div class="flex-shrink-0">
                                <span class="text-xl text-gray-600">👤</span>
                            </div>
                            <div>
                                <div class="font-semibold text-gray-800"><?php echo htmlspecialchars($u['username']); ?></div>
                                <div class="text-yellow-500 mb-1"><?php echo str_repeat("⭐", $u['rating']); ?></div>
                                <p class="text-gray-700 text-sm"><?php echo htmlspecialchars($u['review']); ?></p>
                            </div>
                        </li>
                    <?php endforeach; ?>
                <?php endif; ?>
            </ul>
        </div>

        <div class="mt-8 border-t pt-6">
            <h3 class="text-xl font-bold mb-4 text-gray-800">Rekomendasi Menu Lain</h3>
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
                <?php
                $query_recommendations = "SELECT id, name, price, image FROM menu
                                                     WHERE category_id = ? AND id != ? LIMIT 4";
                $stmt_recommendations = mysqli_prepare($conn, $query_recommendations);
                mysqli_stmt_bind_param($stmt_recommendations, "ii", $menu['category_id'], $menu_id);
                mysqli_stmt_execute($stmt_recommendations);
                $result_recommendations = mysqli_stmt_get_result($stmt_recommendations);

                if (mysqli_num_rows($result_recommendations) > 0):
                    while ($rec = mysqli_fetch_assoc($result_recommendations)):
                        echo '
                        <div class="bg-gray-50 hover:bg-yellow-50 border border-gray-200 rounded-lg p-4 shadow-sm transition duration-200">
                            <img src="data:image/jpeg;base64,' . base64_encode($rec['image']) . '" alt="' . htmlspecialchars($rec['name']) . '" class="w-full h-32 object-cover rounded mb-3">
                            <h4 class="text-lg font-semibold text-gray-700">' . htmlspecialchars($rec['name']) . '</h4>
                            <p class="text-sm text-gray-600 mt-1">Rp' . number_format($rec['price'], 0, ',', '.') . '</p>
                            <a href="detail_menu.php?id=' . $rec['id'] . '" class="mt-2 inline-block text-sm text-blue-500 hover:underline">Lihat Detail</a>
                        </div>';
                    endwhile;
                else:
                    echo '<p class="text-gray-500">Belum ada rekomendasi untuk kategori ini.</p>';
                endif;
                mysqli_stmt_close($stmt_recommendations);
                ?>
            </div>
        </div>
    </div>

    <div id="reviewModal" class="fixed inset-0 bg-black bg-opacity-50 hidden flex items-center justify-center z-50">
        <div class="bg-white p-6 rounded-lg shadow-lg w-full max-w-sm mx-4 animate-fade">
            <h3 class="text-lg font-bold mb-4">Tambah Review</h3>
            <form action="submit_review.php" method="POST">
                <input type="hidden" name="menu_id" value="<?php echo $menu_id; ?>">
                <label class="block mt-2 font-semibold text-gray-700">Rating:</label>
                <select name="rating" class="w-full p-2 border rounded shadow-sm focus:ring focus:ring-indigo-200 focus:border-indigo-300" required>
                    <option value="5">⭐️⭐️⭐️⭐️⭐️</option>
                    <option value="4">⭐️⭐️⭐️⭐️</option>
                    <option value="3">⭐️⭐️⭐️</option>
                    <option value="2">⭐️⭐️</option>
                    <option value="1">⭐️</option>
                </select>
                <label class="block mt-4 font-semibold text-gray-700">Review:</label>
                <textarea name="review" class="w-full p-2 border rounded shadow-sm focus:ring focus:ring-indigo-200 focus:border-indigo-300" required></textarea>
                <button type="submit" class="w-full bg-blue-500 text-white py-3 rounded-full mt-4 hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 submit-review">Kirim</button>
                <button type="button" onclick="closeReviewModal()" class="w-full bg-gray-300 text-black py-3 rounded-full mt-2 hover:bg-gray-400 focus:outline-none focus:ring-2 focus:ring-gray-300 focus:ring-offset-2">Batal</button>
            </form>
        </div>
    </div>

    <script>
        function openReviewModal() {
            document.getElementById("reviewModal").classList.remove("hidden");
        }

        function closeReviewModal() {
            document.getElementById("reviewModal").classList.add("hidden");
        }

        const pesanSekarangBtn = document.getElementById('pesanSekarangBtn');

        pesanSekarangBtn.addEventListener('click', function(event) {
            event.preventDefault(); // Prevent default link behavior
            const cartIcon = this.querySelector('svg');

            if (cartIcon) {
                cartIcon.classList.add('whoosh-active');

                // Redirect to checkout after the animation
                setTimeout(() => {
                    window.location.href = this.getAttribute('href');
                }, 300); // Match the animation duration
            } else {
                window.location.href = this.getAttribute('href'); // Redirect immediately if no cart icon
            }
        });
    </script>

</body>

</html>