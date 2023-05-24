export default function SwipeFun() {
  
  const creatSwipe = (slidersEle) => {
    const sliders = document.querySelectorAll(slidersEle);
    
    if (sliders) {
      sliders.forEach((slider, i) => {
        let isDown = false;
        let startX;
        let scrollLeft;
        let desX;
  
        if (slider) {
          slider.addEventListener("mousedown", (e) => {
            isDown = true;
            slider.classList.add("active");
            startX = e.pageX - slider.offsetLeft;
            scrollLeft = slider.scrollLeft;
          });
          slider.addEventListener("mouseleave", () => {
            isDown = false;
            slider.classList.remove("active");
          });
  
          slider.addEventListener("mousemove", (e) => {
            if (!isDown) return;
            e.preventDefault();
            const x = e.pageX - slider.offsetLeft;
            desX = x;
            const walk = ((x - startX) * 1) / 2;
            slider.scrollLeft = scrollLeft - walk;
           
          });
  
          slider.addEventListener("mouseup", () => {
            isDown = false;
            slider.classList.remove("active");
            // const direction = desX > startX ? "right" : "left";
            // console.log(`Scrolling ${direction}`);
          });
  
          const animateScroll = () => {
            if (!isDown) {
              cancelAnimationFrame(animationId);
              return;
            }
            slider.scrollLeft = slider.scrollLeft - 1;
            animationId = requestAnimationFrame(animateScroll);
          };
  
          slider.addEventListener("mouseenter", () => {
            if (isDown) {
              animationId = requestAnimationFrame(animateScroll);
            }
          });
        }
      });
    }
  }
  creatSwipe(".scrollJS")
  creatSwipe(".uploadimg-box")
}
