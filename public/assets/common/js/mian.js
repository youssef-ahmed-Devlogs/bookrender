let myimg = document.querySelectorAll(".mainbook img");
let mypopp = document.querySelector("#popapupload");
let btnClose = document.querySelector("#close");
let notific = document.querySelector("#notific");
let bellIcon = document.querySelector("#bell-icon");
let notificperson = document.querySelector("#notific-person");
let personicon = document.querySelector("#person-icon");

document.addEventListener("DOMContentLoaded", function () {
    const bellIcon = document.querySelector("#bell-icon");
    const notific = document.querySelector("#notific");

    const personIcon = document.querySelector("#person-icon");
    const notificPerson = document.querySelector("#notific-person");

    function closeAllMenus() {
        if (notific) notific.classList.replace("d-flex", "d-none");
        if (notificPerson) notificPerson.classList.replace("d-flex", "d-none");
    }

    if (bellIcon && notific) {
        bellIcon.addEventListener("click", function (e) {
            e.stopPropagation();
            const isOpen = notific.classList.contains("d-flex");

            closeAllMenus();
            if (!isOpen) {
                notific.classList.replace("d-none", "d-flex");
            }
        });

        notific.addEventListener("click", function (e) {
            e.stopPropagation();
        });
    }

    if (personIcon && notificPerson) {
        personIcon.addEventListener("click", function (e) {
            e.stopPropagation();
            const isOpen = notificPerson.classList.contains("d-flex");

            closeAllMenus();
            if (!isOpen) {
                notificPerson.classList.replace("d-none", "d-flex");
            }
        });

        notificPerson.addEventListener("click", function (e) {
            e.stopPropagation();
        });
    }

    document.addEventListener("click", function () {
        closeAllMenus();
    });
});

if (myimg && mypopp) {
    for (let i = 0; i < myimg.length; i++) {
        myimg[i].addEventListener("click", function () {
            mypopp.classList.replace("d-none", "d-flex");
        });
    }
}

if (btnClose && mypopp) {
    btnClose.addEventListener("click", function () {
        mypopp.classList.replace("d-flex", "d-none");
    });
}

function changeFontSize(step) {
    let input = document.getElementById("fontSize");
    let size = parseInt(input.value) + step;
    if (size >= 8 && size <= 72) {
        input.value = size;
    }
}

// Show/hide password functionality for all forms

document
    .querySelectorAll('.position-relative input[type="password"]')
    .forEach(function (passwordInput) {
        var eyeIcon = passwordInput.parentElement.querySelector(".fa-eye");
        if (eyeIcon) {
            eyeIcon.addEventListener("click", function () {
                if (passwordInput.type === "password") {
                    passwordInput.type = "text";
                    eyeIcon.classList.remove("fa-eye");
                    eyeIcon.classList.add("fa-eye-slash");
                } else {
                    passwordInput.type = "password";
                    eyeIcon.classList.remove("fa-eye-slash");
                    eyeIcon.classList.add("fa-eye");
                }
            });
        }
    });
