export default function AnimationModule() {
  try {
    
    const solItem = document.querySelectorAll(".solution-item");
  
    if (solItem.length > 0) {
      solItem.forEach((ele, i) => {
        ele.addEventListener("mouseenter", (e) => {
          solItem.forEach((ele1, j) => {
            if (j < i) {
              ele1.classList.add("hover");
            }
          });
        });
  
        ele.addEventListener("mouseleave", (e) => {
          solItem.forEach((ele1, j) => {
            ele1.classList.remove("hover");
          });
        });
      });
    }
  
    function reveal() {
      var reveals = document.querySelectorAll(".reveal");
      for (var i = 0; i < reveals.length; i++) {
        var windowHeight = window.innerHeight;
        var elementTop = reveals[i].getBoundingClientRect().top;
        var elementVisible = 150;
  
        if (elementTop < windowHeight - elementVisible) {
          reveals[i].classList.add("active");
        } else {
        }
      }
    }
  
    reveal();
  
    window.addEventListener("scroll", reveal);
  
    const textAni = document.querySelectorAll(".txt-ani");
    if (textAni) {
  
      textAni.forEach((item) => {
        const textAniItems = item.querySelectorAll(".txt-ani-item");
        let arrs = [];
        if (textAniItems) {
          textAniItems.forEach((item, index) => {
            arrs.push(item.outerHTML);
            arrs.push(
              `${index == textAniItems.length - 1 ? "" : "<span>&nbsp;</span>"}`
            );
          });
        }
        item.innerHTML = arrs.join("");
      });
     
      textAni.forEach((item) => {
        const textAniItems = item.querySelectorAll(".txt-ani-item");
        if (textAniItems) {
          textAniItems.forEach((item) => {
            let arrText = item.textContent.trim().split(" ");
            let arrTextSecond = [];
            arrText.forEach((item) => {
              arrTextSecond.push(item.split(""));
            });
            let textSecond = "";
            arrTextSecond.forEach((item, index) => {
              let textFirst = "";
              item.forEach((item, index) => {
                textFirst += `<span><span class="letter">${item}</span></span>`;
              });
  
              textSecond += `<p>${textFirst}</p>${
                index == arrTextSecond.length - 1 ? "" : "<span>&nbsp;</span>"
              }`;
            });
            item.innerHTML = textSecond;
            const txtAni = document.querySelectorAll(".txt-ani");
            if (txtAni) {
              txtAni.forEach((item) => {
                const letter = item.querySelectorAll(".letter");
                if (letter) {
                  letter.forEach((item, index) => {
                    let delay = index * 0.05;
                    item.style = `transition-delay:${delay}s`;
                  });
                }
              });
            }
          });
        }
      });
    }
  
    let $window = $(window);
  
    function scrollAddClass(el, className) {
      $(el).each(function () {
        let el = this;
        if (
          $(el).offset().top <
          $window.scrollTop() + ($window.height() / 10) * 8
        ) {
          $(el).addClass(className);
        }
      });
    }
  
    function bindImageAnimations() {
      scrollAddClass(".txt-ani .letter", "run");
      scrollAddClass(".txt-ani-second .txt-ani-second-item", "run");
      scrollAddClass(".load-ele", "is-inview");
      scrollAddClass(".text-effect", "is-inview");
      scrollAddClass(".load-ele-second", "is-inview");
      scrollAddClass(".load-ele-third", "is-inview");
  
      $window.on("scroll", function () {
        scrollAddClass(".txt-ani .letter", "run");
        scrollAddClass(".txt-ani-second .txt-ani-second-item", "run");
        scrollAddClass(".load-ele", "is-inview");
        scrollAddClass(".text-effect", "is-inview");
        scrollAddClass(".load-ele-second", "is-inview");
        scrollAddClass(".load-ele-third", "is-inview");
      });
    }
    bindImageAnimations();
  } catch (error) {
    console.log(error)
  }
}
