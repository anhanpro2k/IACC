import CheckModule from "./CheckModule.js";

export default function inputForm() {
  try {
    const hideShowPass = () => {
      const inputPass = document.querySelectorAll(".pass");
      const show = document.querySelectorAll(".hide-pass");
      const hidden = document.querySelectorAll(".show-pass");

      if (inputPass) {
        inputPass.forEach((ele, i) => {
          if (show[i]) {
            $(show[i]).click(() => {
              ele.type = "text";
              hidden[i].style.display = "block";
              show[i].style.display = "none";
            });
          }

          if (hidden[i]) {
            $(hidden[i]).click(() => {
              ele.type = "password";
              show[i].style.display = "block";
              hidden[i].style.display = "none";
            });
          }
        });
      }
    };

    hideShowPass()

    const showRe = document.querySelector(".re-hide-pass");
    const hiddenRe = document.querySelector(".re-show-pass");
    const inputPassRe = document.querySelector(".re-pass");

    if (showRe) {
      showRe.addEventListener("click", () => {
        console.log("asdas");
        inputPassRe.type = "text";
        hiddenRe.style.display = "block";
        showRe.style.display = "none";
      });
    }

    if (hiddenRe) {
      hiddenRe.addEventListener("click", () => {
        console.log("asdas");
        inputPassRe.type = "password";
        showRe.style.display = "block";
        hiddenRe.style.display = "none";
      });
    }

    //////////////////////////////////////////////////////////////
    ////form edit

    const form = document.querySelectorAll(".form-edit");

    if (form) {
      form.forEach((ele, i) => {
        const btn = ele.querySelector(".form-edit-btn");
        const formct = ele.querySelectorAll(".form-edit-control");
        const layoutBtn = ele.querySelector(".btn-list");
        let btnhidden = ele.querySelector(".form-edit-btn-hidden");
        const getBtnsm = btnhidden.cloneNode(true);

        const removeBtn = () => {
          let btnhiddeni = ele.querySelector(".form-edit-btn-hidden");
          if (btnhiddeni) {
            btnhiddeni.remove();
          }
        };

        removeBtn();

        btn.addEventListener("click", (e) => {
          e.preventDefault();
          ele.classList.add("active");

          if (ele.classList.contains("active")) {
            const control = ele.querySelectorAll(".form-edit-control");

            layoutBtn.appendChild(getBtnsm);

            getBtnsm.addEventListener("click", () => {
              ele.submit();
              ele.classList.remove("active");
              removeBtn();
            });

            control.forEach((ctEl, j) => {
              const edit = ctEl.querySelector(".form-edit-icon");
              const input = ctEl.querySelector(".form-edit-input");

              if (edit) {
                input.removeAttribute("disabled");
                edit.addEventListener("click", () => {
                  input.focus();
                });
              }
            });
          }
        });
      });
    }
  } catch (error) {
    console.log(error);
  }
}
