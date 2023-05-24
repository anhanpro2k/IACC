export default function () {
  try {
    const iconEyes = document.querySelectorAll(".form-eye");
    if (iconEyes) {
      iconEyes.forEach((iconEye) => {
        iconEye.onmousedown = () => {
          showPass(iconEye);
        };

        iconEye.onmouseup = () => {
          hidePass(iconEye);
        };

        iconEye.ontouchstart = () => {
          showPass(iconEye);
        };

        iconEye.ontouchend = () => {
          hidePass(iconEye);
        };
      });

      function showPass(ele) {
        const inputTypeShow = ele
          .closest(".f-c")
          .querySelector("input[type=password]");
        inputTypeShow.setAttribute("type", "text");

        const iconImg = ele.querySelector(".iconImg-1");
        const iconFont = ele.querySelector(".iconImg-2");

        iconImg.style.opacity = 0;
        iconFont.style.opacity = 1;
      }

      function hidePass(ele) {
        const inputTypeHide = ele
          .closest(".f-c")
          .querySelector("input[type=text]");
        inputTypeHide.setAttribute("type", "password");

        const iconImg = ele.querySelector(".iconImg-1");
        const iconFont = ele.querySelector(".iconImg-2");

        iconImg.style.opacity = 1;
        iconFont.style.opacity = 0;
      }
    }

    const openPops = document.querySelectorAll(".open-popup");
    const popChangePass = document.querySelector(".popChangePass");
    const popChangePassBg = document.querySelector(".popChangePassBg");
    const body = document.querySelector("body");

    if (openPops.length > 0) {
      openPops.forEach((openPop) => {
        openPop.onclick = () => {
          popChangePass.classList.add("open");
          body.classList.add("no-scroll");
        };
      });

      popChangePassBg.onclick = () => {
        popChangePass.classList.remove("open");
        body.classList.remove("no-scroll");
      };
    }
  } catch (error) {
    console.log(error);
  }
}
