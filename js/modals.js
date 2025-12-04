document.addEventListener('DOMContentLoaded', function() {
    console.log('modals.js loaded');
    
    // --- PARTIE 1 : Login & Register ---
    var registerModal = document.getElementById("registerModal");
    var openRegisterBtn = document.getElementById("openRegisterBtn");
    var closeRegisterBtn = document.getElementById("closeRegister");
    var loginModal = document.getElementById("loginModal");
    var openLoginBtn = document.getElementById("openLoginBtn");
    var closeLoginBtn = document.getElementById("closeLogin");
    var forgotModal = document.getElementById("forgotModal");
    var openForgotBtn = document.getElementById("openForgotPassword");
    var closeForgotBtn = document.getElementById("closeForgot");

    if (openRegisterBtn && registerModal) {
        openRegisterBtn.onclick = function() { registerModal.style.display = "block"; };
        closeRegisterBtn.onclick = function() { registerModal.style.display = "none"; };
    }
    
    if (openLoginBtn && loginModal) {
        openLoginBtn.onclick = function() { loginModal.style.display = "block"; };
        closeLoginBtn.onclick = function() { loginModal.style.display = "none"; };
    }
    
    if (openForgotBtn && forgotModal) {
        openForgotBtn.onclick = function(e) { 
            e.preventDefault();
            if (loginModal) loginModal.style.display = "none"; 
            forgotModal.style.display = "block";
        };
        if (closeForgotBtn) {
            closeForgotBtn.onclick = function() { forgotModal.style.display = "none"; };
        }
    }
    
    // Back to Login link
    var backToLoginBtn = document.getElementById('backToLogin');
    if (backToLoginBtn && loginModal && forgotModal) {
        backToLoginBtn.onclick = function(e) {
            e.preventDefault();
            forgotModal.style.display = "none";
            loginModal.style.display = "block";
        };
    }
    
    // --- PARTIE 2 : Dropdown username ---
    var usernameContainer = document.getElementById('usernameContainer');
    var usernameDropdown = document.getElementById('usernameDropdown');

    console.log('usernameContainer:', usernameContainer);
    console.log('usernameDropdown:', usernameDropdown);

    if (usernameContainer && usernameDropdown) {
        // Clic sur le container → toggle du menu
        usernameContainer.addEventListener('click', function(e) {
            e.stopPropagation();
            console.log('Username clicked, toggling dropdown');
            usernameDropdown.classList.toggle('show');
        });

        // Clic ailleurs → fermer le menu
        document.addEventListener('click', function(e) {
            if (!usernameContainer.contains(e.target)) {
                usernameDropdown.classList.remove('show');
            }
        });
    }
});
