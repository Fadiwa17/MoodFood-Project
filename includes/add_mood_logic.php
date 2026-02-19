<!--
Names of Submitters:
Fadi Waheb I.D: 211629282
Odaya Ifrach I.D: 212777155
Adam Abd Elhaq I.D: 318239571
-->

<?php

// פרטי התחברות לשרת 
$server_name = "localhost";
$user_name = "fadiwa_db_user";     
$password = "MoodFood2026!";       
$database_name = "fadiwa_addmood"; 

// יצירת החיבור למסד הנתונים
$conn = new mysqli($server_name, $user_name, $password, $database_name);

// בדיקת תקינות החיבור
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// קבלת מידע מהטופס תוך מניעת SQL Injection בסיסית (אבטחת מידע)
$uploaderName = $conn->real_escape_string($_POST['uploader_name']);
$dishName = $conn->real_escape_string($_POST['dish_name']);
$moodCategory = $conn->real_escape_string($_POST['mood_category']);
$prepTime = (int)$_POST['prep_time'];
$difficulty = (int)$_POST['difficulty'];
$ingredients = $conn->real_escape_string($_POST['ingredients']);
$instructions = $conn->real_escape_string($_POST['instructions']);

// קליטת נתון בוליאני (1 או 0) לבחירת מנה בריאה
$isHealthy = isset($_POST['is_healthy']) ? (int)$_POST['is_healthy'] : 0;

// יצירת מזהה ייחודי למנה
$id = uniqid('dish_');

// טיפול מאובטח בהעלאת תמונת המנה
$uploadDir = '../uploads/';
$dish_image = "";
if (isset($_FILES['dish_image']) && $_FILES['dish_image']['error'] === UPLOAD_ERR_OK) {
    $dish_image = 'uploads/' . time() . '_' . basename($_FILES['dish_image']['name']);
    move_uploaded_file($_FILES['dish_image']['tmp_name'], '../' . $dish_image);
}

// טיפול מאובטח בהעלאת תמונת השף (אופציונלי)
$uploader_image = "";
if (isset($_FILES['uploader_image']) && $_FILES['uploader_image']['error'] === UPLOAD_ERR_OK) {
    $uploader_image = 'uploads/' . time() . '_' . basename($_FILES['uploader_image']['name']);
    move_uploaded_file($_FILES['uploader_image']['tmp_name'], '../' . $uploader_image);
}

// בניית שאילתת ההכנסה (INSERT) למסד הנתונים
$sql = "INSERT INTO dishes (id, uploader_name, uploader_image, dish_name, ingredients, instructions, difficulty, mood, prep_time, dish_image, is_healthy) 
        VALUES ('$id', '$uploaderName', '$uploader_image', '$dishName', '$ingredients', '$instructions', $difficulty, '$moodCategory', $prepTime, '$dish_image', $isHealthy)";

// ביצוע השאילתה ובדיקת שגיאות
if ($conn->query($sql) === FALSE) {
    echo "Can not add new dish. Error is: " . $conn->error;
    exit();
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="he" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mood Food - המתכון באוויר!</title>
    
    <link href="https://fonts.googleapis.com/css2?family=Heebo:wght@400;700&family=Poppins:wght@400;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../css/general-styling.css">
    <link rel="stylesheet" href="../css/response_addmood.css">
    
    <link rel="icon" href="data:image/svg+xml,<svg xmlns=%22http://www.w3.org/2000/svg%22 viewBox=%220 0 100 100%22><text y=%22.9em%22 font-size=%2290%22>🍔</text></svg>">
</head>
<body>

    <header>
        <div class="logo-container">
            <a href="../index.html" class="logo-link">
                <img src="../images/logo.png" alt="לוגו Mood Food" class="logo-img">
            </a>
        </div>
        <nav>
            <ul>
                <li><a href="../index.html">בית</a></li>
                <li><a href="moods.html">תפריט מנות</a></li>
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

    <main class="success-container">
        <div class="success-box">
            <span class="party-emoji">🎉</span>
            <h1>כל הכבוד, שף <?php echo htmlspecialchars($uploaderName); ?>!</h1>
            <p>המתכון <strong>"<?php echo htmlspecialchars($dishName); ?>"</strong> עלה בהצלחה למערכת.</p>
            
            <?php if ($dish_image && file_exists('../' . $dish_image)): ?>
                <img src="../<?php echo $dish_image; ?>" alt="תמונת המנה" class="dish-preview-circle">
            <?php else: ?>
                <div class="dish-preview-circle no-image-placeholder">
                    <span class="no-image-text">לא הועלתה תמונה</span>
                </div>
            <?php endif; ?>
            
            <div class="button-group">
                <a href="moods.html" class="btn">חזרה לתפריט</a>
                <a href="mood_results.php?mood=<?php echo $moodCategory; ?>" class="btn btn-view">צפה במנות דומות</a>
            </div>
        </div>
    </main>

    <footer>
        <p>© 2026 Mood Food - פאדי, אדם ואודיה</p>
    </footer>

    <script src="../js/script.js"></script>
</body>
</html>