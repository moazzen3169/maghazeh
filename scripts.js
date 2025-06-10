// Mobile menu toggle
document.addEventListener('DOMContentLoaded', function() {
    const mobileMenuBtn = document.querySelector('.mobile-menu-btn'); // اضافه کردن کلاس به دکمه منو در HTML
    if (mobileMenuBtn) {
        mobileMenuBtn.addEventListener('click', function() {
            document.querySelector('.sidebar').classList.toggle('hidden');
        });
    }

    // Form input animations (حذف تکراری)
    const formInputs = document.querySelectorAll('.form-input');
    formInputs.forEach(input => {
        input.addEventListener('focus', function() {
            const label = this.parentElement.querySelector('label');
            if (label) label.classList.add('text-blue-500');
        });
        input.addEventListener('blur', function() {
            const label = this.parentElement.querySelector('label');
            if (label && !this.value) label.classList.remove('text-blue-500');
        });
    });

    // Fetch date - نسخه اصلاح شده
    fetch('https://api.keybit.ir/time/')
        .then(response => {
            if (!response.ok) throw new Error('Network response was not ok');
            return response.json();
        })
        .then(data => {
            // نمایش تاریخ فارسی
            if (data?.date?.full?.official?.usual?.fa) {
                const dateText = data.date.full.official.usual.fa;
                const dateContainer = document.getElementById('date-container');
                if (dateContainer) {
                    dateContainer.innerText = `تاریخ امروز: ${dateText}`;
                }
            }

            // تنظیم تاریخ انگلیسی برای input
            if (data?.date?.full?.unofficial?.usual?.en) {
                const todayDate = data.date.full.unofficial.usual.en;
                const dateInput = document.getElementById('date-input');
                if (dateInput) {
                    dateInput.value = todayDate;
                }
            }
        })
        .catch(error => {
            console.error('خطا در دریافت تاریخ:', error);
            const dateContainer = document.getElementById('date-container');
            if (dateContainer) {
                dateContainer.innerText = 'خطا در دریافت تاریخ';
            }
        });
});