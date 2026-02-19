/* Names of Submitters:
Fadi Waheb I.D: 211629282
Odaya Ifrach I.D: 212777155
Adam Abd Elhaq I.D: 318239571 
*/

document.addEventListener('DOMContentLoaded', () => {
    
    /*  מניפולציות DOM - תפריט מובייל (Hamburger) */
    const hamburger = document.querySelector('.hamburger');
    const nav = document.querySelector('nav');

    if (hamburger && nav) {
        // פתיחה וסגירה של התפריט במסכים קטנים
        hamburger.addEventListener('click', () => {
            hamburger.classList.toggle('active');
            nav.classList.toggle('active');
        });

        // סגירת התפריט אוטומטית בעת לחיצה על אחד מקישורי הניווט
        const navLinks = document.querySelectorAll('nav ul li a');
        navLinks.forEach(link => {
            link.addEventListener('click', () => {
                hamburger.classList.remove('active');
                nav.classList.remove('active');
            });
        });
    }

    /*  אימות נתונים (Form Validation) - דף תמיכה */
    const supportForm = document.getElementById('supportForm');
    
    if (supportForm) {
        supportForm.addEventListener('submit', function(event) {
            // מניעת שליחת הטופס כברירת מחדל לשם ביצוע אימות נתונים צד-לקוח
            event.preventDefault(); 
            
            const emailInput = supportForm.querySelector('input[type="email"]');
            const emailValue = emailInput.value;

            // אימות כתובת דוא"ל לוגי: חובה שתהיה נקודה לאחר סימן ה-@
            if (!emailValue.includes('.') || emailValue.lastIndexOf('.') < emailValue.indexOf('@')) {
                alert('כתובת הדוא"ל אינה תקינה. אנא ודא שהיא כוללת נקודה לאחר סימן ה-@ (לדוגמה: .com).');
                emailInput.focus(); // החזרת המיקוד לשדה השגוי
                return; // עצירת פונקציית השליחה
            }
            
            // הצגת משוב הצלחה למשתמש ואיפוס הטופס
            alert('הפנייה נשלחה בהצלחה! ניצור קשר בהקדם.');
            supportForm.reset(); 
        });
    }

    /*  טיפול בקישורים חסרים */
    // מתן חיווי למשתמש עבור קישורים עתידיים (טרם מומשו)
    const dummyLinks = document.querySelectorAll('a[href="#"]');
    dummyLinks.forEach(link => {
        link.setAttribute('title', 'דף זה טרם מומש');
        
        // עצירת הלחיצה והקפצת משוב מתאים למשתמש
        link.addEventListener('click', function(e) {
            e.preventDefault();
            alert('אופציה זו טרם מומשה ותתווסף בגרסאות הבאות של המערכת.');
        });
    });

    /*  קריאת נתונים ממסך אחר (LocalStorage)    */
    // שליפת שם המשתמש מהזיכרון המקומי והצגתו באופן דינמי בדף הבית
    const savedChefName = localStorage.getItem('savedChefName');
    const heroTitle = document.getElementById('mainGreeting');

    // אם קיים שם שמור בזיכרון, ואנחנו נמצאים בדף הרלוונטי
    if (savedChefName && heroTitle) {
        heroTitle.textContent = 'היי ' + savedChefName + ', בא לך לאכול לפי מצב הרוח?';
    }

});