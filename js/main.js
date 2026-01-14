document.addEventListener("DOMContentLoaded", function () {
    const popup = document.getElementById("popupForm");
    const openBtn = document.querySelector(".open-popup");
    const closeBtn = popup.querySelector(".close");

    // bắt tất cả element có class cần mở
    document.querySelectorAll(".open-popup").forEach(el => {
        el.addEventListener("click", () => {
            popup.style.display = "block";
        });
    });

    // Đóng popup khi nhấn nút X
    closeBtn.addEventListener("click", () => {
        popup.style.display = "none";
    });

    // Đóng popup khi click ra ngoài
    window.addEventListener("click", (e) => {
        if (e.target === popup) {
            popup.style.display = "none";
        }
    });
});

document.querySelectorAll('.contact-form').forEach(function(form) {
    form.addEventListener('submit', function(e) {
        e.preventDefault();

        const overlay = document.getElementById('overlay');
        // const submitBtn = this.querySelector('.submitBtn');

        // Hiện overlay + disable nút
        overlay.style.display = 'flex';
        submitBtn.disabled = true;

        let formData = new FormData(this);

        fetch('sendmail.php', {
            method: 'POST',
            body: formData
        })
        .then(res => res.json())
        .then(data => {
            overlay.style.display = 'none';
            submitBtn.disabled = false;
            alert(data.message);
            if (data.status === 'success') {
                this.reset(); // reset form hiện tại
                location.reload(); // reload trang
            }
        })
        .catch(err => {
            overlay.style.display = 'none';
            submitBtn.disabled = false;
            alert('Có lỗi xảy ra khi gửi thông tin!');
            location.reload();
        });
    });
});