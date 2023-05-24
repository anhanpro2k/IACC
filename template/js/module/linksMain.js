export default function LinksMain() {
  try {
    const scrollTop = document.querySelector(".scroll-to-top");

    if (scrollTop) {
      scrollTop.addEventListener("click", () => {
        document.body.scrollIntoView({ behavior: "smooth", block: "start" });
      });
    }

    const menuListLink = document.querySelector(".links-main");
    window.addEventListener("scroll", () => {
      if (menuListLink) {
        if (window.scrollY > 200) {
          menuListLink.classList.add("active");
        } else {
          menuListLink.classList.remove("active");
        }
      }
    });
  } catch (error) {
    console.log(error);
  }
}
