export default function TextAniModule() {
  try {
    function reveal() {
      var reveals = document.querySelectorAll(".ani");
      for (var i = 0; i < reveals.length; i++) {
        var windowHeight = window.innerHeight;
        var elementTop = reveals[i].getBoundingClientRect().top;
        var elementVisible = 50;
  
        if (elementTop < windowHeight - elementVisible) {
          reveals[i].classList.add("active");
        } else {
          // reveals[i].classList.remove("active");
        }
      }
    }
    reveal();
    window.addEventListener("scroll", reveal);
    const DistritItem = document.querySelectorAll(".fade-up");
    var slideUp = {
      distance: "100%",
      origin: "bottom",
      opacity: 0,
      interval: 260,
      easing: "cubic-bezier(0, 0, 0, 1)",
      mobile: false,
      cleanup: false,
      // rotate: {
      //   x: 0,
      //   z: 40,
      // },
    };
    if (DistritItem) {
      ScrollReveal().reveal(DistritItem, slideUp);
    }
    const windowHeight = () => {
      const doc = document.documentElement;
      doc.style.setProperty("--window-height", `${window.innerHeight}px`);
    };
    window.addEventListener("resize", windowHeight);
    windowHeight();
  } catch (error) {
    console.log(error)
  }
}
