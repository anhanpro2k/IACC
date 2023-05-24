export default function ChangeTypeInputModule() {
    const reEyes = document.querySelectorAll(".f-eye");
    if (reEyes) {
        reEyes.forEach((item) => {
            item.classList.add("active");
            item.addEventListener("click", () => {
                const rePass = item.parentElement.querySelector(".re-pass");
                if (rePass.type === "password") {
                    rePass.type = "text";
                    item.classList.remove("active");
                } else {
                    rePass.type = "password";
                    item.classList.add("active");
                }
            });
        });
    }
}