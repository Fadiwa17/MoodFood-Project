/* Names of Submitters:
Fadi Waheb I.D: 211629282
Odaya Ifrach I.D: 212777155
Adam Abd Elhaq I.D: 318239571 
*/

document.addEventListener("DOMContentLoaded", () => {
    
    const prepTimeInput = document.getElementById('prepTime');
    const moodSelect = document.getElementById('moodSelect');
    const dishNameInput = document.getElementById('dishName');
    const dishImageInput = document.getElementById('dishImage');

    // סינון קלט: חסימת הקלדת תווים שאינם מספרים בשדה זמן ההכנה
    if (prepTimeInput) {
        prepTimeInput.addEventListener('input', function () {
            this.value = this.value.replace(/[^0-9]/g, '');
        });
    }

    // שינוי טקסט שומר המקום (Placeholder) דינמית לפי בחירת המשתמש
    if (moodSelect && dishNameInput) {
        moodSelect.addEventListener('change', () => {
            const val = moodSelect.value;
            if (val === 'happy') dishNameInput.placeholder = "למשל: המבורגר מושחת שעושה שמח";
            else if (val === 'sad') dishNameInput.placeholder = "למשל: מרק עוף מנחם של סבתא";
            else if (val === 'party') dishNameInput.placeholder = "למשל: מגש פיצות ענק לחבר'ה";
            else dishNameInput.placeholder = "איך קוראים למנה המטורפת הזו?";
        });
    }

    // יצירת תצוגה מקדימה לתמונה וכפתור מחיקה דינמי דרך DOM
    if (dishImageInput) {
        const previewImg = document.createElement('img');
        previewImg.style.maxWidth = '100%';
        previewImg.style.marginTop = '15px';
        previewImg.style.borderRadius = '10px';
        previewImg.style.border = '2px solid #66ff99';
        previewImg.style.display = 'none';
        dishImageInput.parentNode.appendChild(previewImg);

        const removeBtn = document.createElement('button');
        removeBtn.type = 'button';
        removeBtn.textContent = '❌ מחיקת תמונה';
        removeBtn.style.display = 'none';
        removeBtn.style.marginTop = '10px';
        removeBtn.style.background = '#ff4d4d';
        removeBtn.style.color = 'white';
        removeBtn.style.border = 'none';
        removeBtn.style.padding = '8px 15px';
        removeBtn.style.borderRadius = '5px';
        removeBtn.style.cursor = 'pointer';
        removeBtn.style.fontWeight = 'bold';
        dishImageInput.parentNode.appendChild(removeBtn);

        // אימות סוג קובץ והצגת התמונה בזמן אמת
        dishImageInput.addEventListener('change', function() {
            const file = this.files[0];
            if (file) {
                // בדיקה בצד הלקוח לוידוא העלאת קובץ תמונה בלבד
                if (!file.type.startsWith('image/')) {
                    alert('שגיאה: נא להעלות קובץ תמונה בלבד!');
                    this.value = ''; 
                    previewImg.style.display = 'none';
                    removeBtn.style.display = 'none';
                    return;
                }
                previewImg.src = URL.createObjectURL(file);
                previewImg.style.display = 'block';
                removeBtn.style.display = 'inline-block';
            } else {
                previewImg.style.display = 'none';
                removeBtn.style.display = 'none';
            }
        });

        // איפוס בחירת התמונה
        removeBtn.addEventListener('click', function() {
            dishImageInput.value = '';
            previewImg.style.display = 'none';
            removeBtn.style.display = 'none';
        });
    }

    /* אימות נתונים צד-לקוח */
    const form = document.getElementById('recipeForm');
    const errorBox = document.getElementById('errorBox');

    if (form) {
        form.addEventListener('submit', function(event) {
            
            // עצירת שליחת הטופס כברירת מחדל לטובת ביצוע בדיקות תקינות
            event.preventDefault(); 
            
            // איפוס מצב השגיאות ההתחלתי
            errorBox.style.display = 'none';
            errorBox.innerHTML = '';
            
            // הסרת סימוני שגיאה קודמים מהשדות
            const allInputs = form.querySelectorAll('input, select, textarea');
            allInputs.forEach(input => input.classList.remove('input-error'));
            
            const difficultyWrapper = document.querySelector('.difficulty-wrapper');
            if (difficultyWrapper) difficultyWrapper.classList.remove('input-error');

            const healthyContainer = document.getElementById('healthyRadioContainer');
            if (healthyContainer) {
                healthyContainer.style.border = "1px solid #333";
            }

            let errors = [];

            // איסוף נתוני השדות למשתנים
            const uploaderName = document.getElementById('uploaderName');
            const difficultyRadios = form.querySelectorAll('[name="difficulty"]');
            const healthyRadios = form.querySelectorAll('[name="is_healthy"]'); 
            const ingredients = document.getElementById('ingredients');
            const instructions = document.getElementById('instructions');
            
            // שרשרת בדיקות ולוגיקה לכל שדה בטופס
            if (!uploaderName.value.trim()) {
                errors.push("נא להזין את השם שלך.");
                uploaderName.classList.add('input-error');
            }

            if (!dishNameInput.value.trim()) {
                errors.push("נא להזין את שם המנה.");
                dishNameInput.classList.add('input-error');
            }

            if (!moodSelect.value) { 
                errors.push("חובה לבחור מצב רוח מתאים למנה.");
                moodSelect.classList.add('input-error');
            }

            if (!prepTimeInput.value.trim() || prepTimeInput.value <= 0) {
                errors.push("נא לציין זמן הכנה תקין.");
                prepTimeInput.classList.add('input-error');
            }

            let difficultySelected = false;
            difficultyRadios.forEach(radio => {
                if (radio.checked) difficultySelected = true;
            });
            if (!difficultySelected) {
                errors.push("נא לסמן רמת קושי מ-1 עד 5.");
                if (difficultyWrapper) difficultyWrapper.classList.add('input-error');
            }

            let healthySelected = false;
            healthyRadios.forEach(radio => {
                if (radio.checked) healthySelected = true;
            });
            if (!healthySelected) {
                errors.push("חובה לציין האם המנה בריאה או מושחתת.");
                if (healthyContainer) {
                    healthyContainer.style.border = "1px solid #ff4d4d"; 
                }
            }

            if (!ingredients.value.trim() || ingredients.value.trim().length < 5) {
                errors.push("אנא פרט קצת יותר על הרכיבים.");
                ingredients.classList.add('input-error');
            }

            if (!instructions.value.trim()) {
                errors.push("נא לפרט את הוראות ההכנה.");
                instructions.classList.add('input-error');
            }

            if (!dishImageInput.files || dishImageInput.files.length === 0) {
                errors.push("חובה להעלות תמונה של המנה.");
                dishImageInput.classList.add('input-error');
            }

            // ניהול שגיאות או שחרור הטופס לשרת
            if (errors.length > 0) {
                let errorHTML = '<p>רגע לפני שממשיכים... חסרים כמה פרטים:</p><ul>';
                errors.forEach(function(error) {
                    errorHTML += '<li>❌ ' + error + '</li>';
                });
                errorHTML += '</ul>';

                errorBox.innerHTML = errorHTML;
                errorBox.style.display = 'block';
                
                // גלילה חלקה לראש הדף להצגת השגיאות למשתמש
                window.scrollTo({ top: 0, behavior: 'smooth' });
            } else {
                // אם לא נמצאו שגיאות, שליחת הטופס באופן יזום
                form.submit();
            }
        });
    }

    /*  העברת נתונים בין מסכים (LocalStorage) */
    // שמירת שם המשתמש בזיכרון המקומי להצגה דינמית במסכים אחרים
    const uploaderNameInput = document.getElementById('uploaderName');
    if (uploaderNameInput) {
        uploaderNameInput.addEventListener('input', function() {
            localStorage.setItem('savedChefName', this.value.trim());
        });
    }

});