<!--
Names of Submitters:
Fadi Waheb I.D: 211629282
Odaya Ifrach I.D: 212777155
Adam Abd Elhaq I.D: 318239571
-->

<?php

// פרטי התחברות למסד הנתונים
$server_name = "localhost";
$user_name = "fadiwa_db_user";     
$password = "MoodFood2026!";  
$database_name = "fadiwa_addmood"; 

// יצירת החיבור
$conn = new mysqli($server_name, $user_name, $password, $database_name);

// בדיקת תקינות החיבור
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$recipe_id = isset($_GET['id']) ? $_GET['id'] : '';
$recipe_id = $conn->real_escape_string($recipe_id);

// שליפת נתוני המתכון הספציפי ממסד הנתונים
$sql = "SELECT * FROM dishes WHERE id = '$recipe_id'";
$result = $conn->query($sql);
$recipe = $result->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="he" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mood Food - <?php echo $recipe ? htmlspecialchars($recipe['dish_name']) : 'מתכון לא נמצא'; ?></title>
    
    <link href="https://fonts.googleapis.com/css2?family=Heebo:wght@400;700&family=Poppins:wght@400;700&display=swap" rel="stylesheet">
    
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.rtl.min.css">
    
    <link rel="stylesheet" href="../css/general-styling.css">
    
    <link rel="stylesheet" href="../css/recipe_view.css">

    <link rel="icon" href="data:image/svg+xml,<svg xmlns=%22http://www.w3.org/2000/svg%22 viewBox=%220 0 100 100%22><text y=%22.9em%22 font-size=%2290%22>🍔</text></svg>">
</head>
<body>

    <header>
        <a href="../index.html" class="logo-link">
            <img src="../images/logo.png" alt="לוגו Mood Food" class="logo-img">
        </a>
        <nav>
            <ul>
                <li><a href="../index.html">בית</a></li>
                <li><a href="moods.html" class="active">תפריט מנות</a></li>
                <li><a href="add-mood.html">הוספת מתכון</a></li>
                <li><a href="about.html">אודות</a></li>
                <li><a href="support.html">תמיכה</a></li>
            </ul>
        </nav>
        <button class="hamburger" aria-label="תפריט ניווט">
            <span></span>
            <span></span>
            <span></span>
        </button>
    </header>

    <main class="container recipe-container">
        <?php if ($recipe): ?>
            <div class="row align-items-start">
                
                <div class="col-md-5 mb-4">
                    <img src="../<?php echo htmlspecialchars($recipe['dish_image']); ?>" class="img-fluid rounded shadow-lg w-100 recipe-main-image" alt="תמונת המנה">
                </div>

                <div class="col-md-7">
                    <h1 class="display-4 text-theme fw-bold mb-3"><?php echo htmlspecialchars($recipe['dish_name']); ?></h1>
                    
                    <div class="d-flex align-items-center mb-3">
                        <?php if(!empty($recipe['uploader_image'])): ?>
                            <img src="../<?php echo htmlspecialchars($recipe['uploader_image']); ?>" alt="שף" class="rounded-circle me-2 chef-avatar">
                        <?php else: ?>
                            <span class="fs-2 me-2">👨‍🍳</span>
                        <?php endif; ?>
                        <h4 class="m-0 ms-2">שף: <?php echo htmlspecialchars($recipe['uploader_name']); ?></h4>
                    </div>
                    
                    <div class="mb-4">
                        <span class="badge bg-success fs-6 ms-2 p-2 shadow-sm">⏱️ זמן הכנה: <?php echo htmlspecialchars($recipe['prep_time']); ?> דקות</span>
                        <span class="badge bg-warning text-dark fs-6 p-2 shadow-sm">⭐ רמת קושי: <?php echo htmlspecialchars($recipe['difficulty']); ?> / 5</span>
                    </div>

                    <div class="card card-custom mb-4 shadow">
                        <div class="card-header border-success">
                            <h4 class="mb-0 text-theme">🛒 מצרכים:</h4>
                        </div>
                        <div class="card-body">
                            <p class="card-text preserve-lines"><?php echo htmlspecialchars($recipe['ingredients']); ?></p>
                        </div>
                    </div>

                    <div class="card card-custom mb-4 shadow">
                        <div class="card-header border-success">
                            <h4 class="mb-0 text-theme">📝 אופן ההכנה:</h4>
                        </div>
                        <div class="card-body">
                            <p class="card-text preserve-lines"><?php echo htmlspecialchars($recipe['instructions']); ?></p>
                        </div>
                    </div>

                    <button onclick="history.back()" class="btn btn-theme btn-lg w-100 mt-2 shadow">⬅️ חזור לתוצאות הקודמות</button>
                </div>
            </div>

        <?php else: ?>
            <div class="alert alert-custom-error text-center shadow" role="alert">
                <h2 class="alert-heading">אופס! מתכון לא נמצא 😕</h2>
                <p>נראה שהמנה שחיפשת לא קיימת במערכת.</p>
                <hr>
                <button onclick="history.back()" class="btn btn-theme">חזור אחורה</button>
            </div>
        <?php endif; ?>
    </main>

    <footer>
        <p>© 2026 Mood Food - פאדי, אדם ואודיה</p>
    </footer>

    <script src="../js/script.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>