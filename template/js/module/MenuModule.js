export default function MenuModule() {
  try {
    const listDr = document.querySelectorAll(".dropdown");

    listDr.forEach((ele, i) => {
      const icon = document.createElement("i");
      // <i class="fas fa-angle-down"></i>
      icon.className = "fas fa-chevron-down";
      ele.querySelectorAll("a")[0].appendChild(icon);
      icon.addEventListener("click", (e) => {
        e.preventDefault();
        icon.classList.toggle("active");
        $(ele.querySelectorAll(".menu-list")[0])
          .stop()
          .slideToggle(300, "linear");
      });
    });

    // const listDr2 = document.querySelectorAll(".header-ct .dropdown");

    // listDr2.forEach((ele, i) => {
    //     const icon = document.createElement("i")
    //     // <i class="fa-solid fa-caret-down"></i>
    //     icon.className = "ti-angle-right"
    //     ele.querySelectorAll("a")[0].appendChild(icon);
    //     icon.addEventListener("click", (e) => {
    //         e.preventDefault();
    //         icon.classList.toggle("active")
    //         $(ele.querySelectorAll(".menu-list")[0]).stop().slideToggle(300,);
    //     })

    // })
  } catch (error) {
    console.log(error);
  }
}
