document.addEventListener('DOMContentLoaded', function() {
    // --- PARTIE 1 : Login & Register (inchangée) ---
    var registerModal = document.getElementById("registerModal");
    var openRegisterBtn = document.getElementById("openRegisterBtn");
    var closeRegisterBtn = document.getElementById("closeRegister");
    var loginModal = document.getElementById("loginModal");
    var openLoginBtn = document.getElementById("openLoginBtn");
    var closeLoginBtn = document.getElementById("closeLogin");

    if (openRegisterBtn && openLoginBtn) {
        openRegisterBtn.onclick = () => registerModal.style.display = "block";
        closeRegisterBtn.onclick = () => registerModal.style.display = "none";
        openLoginBtn.onclick = () => loginModal.style.display = "block";
        closeLoginBtn.onclick = () => loginModal.style.display = "none";
    }

    // --- PARTIE 2 : Dropdown username ---
    const username = document.getElementById('usernameContainer');
    const dropdown = document.getElementById('usernameDropdown');

    if (username && dropdown) {
        // Clic sur le nom → toggle du menu
        username.addEventListener('click', (e) => {
            e.stopPropagation();
            dropdown.classList.toggle('show');
        });

        document.addEventListener('click', (e) => {
            if (!username.contains(e.target) && !dropdown.contains(e.target)) {
                dropdown.classList.remove('show');
            }
        });
    }
});
