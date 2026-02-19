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

// יצירת חיבור
$conn = new mysqli($server_name, $user_name, $password, $database_name);

// בדיקת תקינות החיבור
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// קבלת פרמטרים וביצוע אבטחה בסיסית לשאילתות 
$selected_mood = isset($_GET['mood']) ? $conn->real_escape_string($_GET['mood']) : '';
$filter = isset($_GET['filter']) ? $conn->real_escape_string($_GET['filter']) : '';

// הגדרות ברירת מחדל
$sql = "SELECT * FROM dishes";
$page_title = 'התפריט שלנו';

// הגדרת משתני כפתור החזרה הדינמי 
$back_url = "moods.html"; 
$back_text = "חזור לתפריט המנות ⬅️"; 

/**
 * לוגיקת סינון נתונים (Filtering Logic)
 * ביצוע שאילתות דינמיות בהתאם לפרמטרים שהתקבלו ב-URL.
 */
if ($selected_mood) {
    $sql = "SELECT * FROM dishes WHERE mood = '$selected_mood'";
    $mood_titles = [
        'happy' => 'מנות שעושות שמח 😀',
        'party' => 'אוכל לאווירה חגיגית 🥳',
        'angry' => 'אוכל להירגע איתו 😡',
        'stressed' => 'אוכל מנחם לזמן לחץ 😰',
        'sad' => 'חיבוק בצלחת 😢',
        'random' => 'הפתעות טעימות 🎲'
    ];
    $page_title = isset($mood_titles[$selected_mood]) ? $mood_titles[$selected_mood] : 'התפריט שלנו';
    
    // ניתוב חזרה לדף מצבי הרוח
    $back_url = "moods.html";
    $back_text = "חזור לתפריט המנות ⬅️";
} 
elseif ($filter == 'fast') {
    $sql = "SELECT * FROM dishes WHERE prep_time <= 20 ORDER BY prep_time ASC";
    $page_title = "מנות מהירות (עד 20 דקות) 🚀";
    
    // ניתוב חזרה לדף הבית עבור פילטרים ראשיים
    $back_url = "../index.html";
    $back_text = "חזור לדף הבית ⬅️";
} 
elseif ($filter == 'healthy') {
    $sql = "SELECT * FROM dishes WHERE is_healthy = 1";
    $page_title = "מנות בריאות וקלילות ❤️";
    
    $back_url = "../index.html";
    $back_text = "חזור לדף הבית ⬅️";
} 
elseif ($filter == 'top') {
    $sql = "SELECT * FROM dishes WHERE difficulty >= 4 OR is_healthy = 0 ORDER BY id DESC";
    $page_title = "המנות הכי טעימות ומושקעות 😋";
    
    $back_url = "../index.html";
    $back_text = "חזור לדף הבית ⬅️";
}

$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="he" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mood Food - <?php echo $page_title; ?></title>
    
    <link href="https://fonts.googleapis.com/css2?family=Heebo:wght@400;700&family=Poppins:wght@400;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../css/general-styling.css">
    <link rel="stylesheet" href="../css/mood_results.css">
    
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

    <main>
        <section class="results-header">
            <h1><?php echo $page_title; ?></h1>
        </section>

        <section class="dishes-grid">
            <?php
            if ($result && $result->num_rows > 0) {
                while($row = $result->fetch_assoc()) {
                    // הצגת כרטיסייה עבור כל מנה (Article semantic tag)
                    echo '<article class="dish-card">';
                    echo '<img src="../' . htmlspecialchars($row["dish_image"]) . '" alt="' . htmlspecialchars($row["dish_name"]) . '" class="card-img">';
                    echo '<div class="card-content">';
                    echo '<h2>' . htmlspecialchars($row["dish_name"]) . '</h2>';
                    
                    echo '<div class="chef-line">';
                    if (!empty($row["uploader_image"])) {
                        echo '<img src="../' . htmlspecialchars($row["uploader_image"]) . '" alt="שף" class="chef-mini-pic">';
                    } else {
                        echo '<span style="font-size: 1.2rem;">👨‍🍳</span>';
                    }
                    echo '<p style="margin: 0;"><strong>שף:</strong> ' . htmlspecialchars($row["uploader_name"]) . '</p>';
                    echo '</div>';
                    
                    echo '<p><strong>⏱️ זמן הכנה:</strong> ' . htmlspecialchars($row["prep_time"]) . ' דקות</p>';
                    echo '<p><strong>⭐ רמת קושי:</strong> ' . htmlspecialchars($row["difficulty"]) . ' / 5</p>';
                    
                    echo '<a href="recipe_view.php?id=' . htmlspecialchars($row["id"]) . '" class="btn-read-more">צפה במתכון המלא</a>';
                    
                    echo '</div>';
                    echo '</article>';
                }
            } else {
                // הודעה במידה ולא נמצאו תוצאות
                echo '<div class="no-results">';
                echo '<p>לא נמצאו מתכונים מתאימים... 😔</p>';
                echo '<a href="add-mood.html" class="btn-read-more">היו הראשונים להוסיף!</a>';
                echo '</div>';
            }
            $conn->close();
            ?>
        </section>

        <div class="bottom-back-container">
            <a href="<?php echo $back_url; ?>" class="btn-back-dynamic"><?php echo $back_text; ?></a>
        </div>

    </main>

    <footer>
        <p>© 2026 Mood Food - פאדי, אדם ואודיה</p>
    </footer>

    <script src="../js/script.js"></script>
</body>
</html>