export default function plyrModule() {
  try {
    const player = new Plyr(".player");

    $(".mona-content video").each((i, ele) => {
      const player = new Plyr(ele);
      const controls = document.querySelector(".plyr");
      controls.classList.add("plyr--hide-controls");
      player.on("paused", () => {});
    });

    $(".play-plyr").each((i, ele) => {
      const player = new Plyr(ele);
      const controls = document.querySelector(".plyr");
      controls.classList.add("plyr--hide-controls");
      player.on("paused", () => {});
    });

    const Bnvideo = document.querySelectorAll(".banner-video");

    if (Bnvideo) {
      Bnvideo.forEach((ele, i) => {
        const playBtn = ele.querySelector(".play-vd");
        const pauseBtn = ele.querySelector(".pause-vd");
        const video = ele.querySelector("video");

        ele.addEventListener("click", function (e) {
          const palyEle = playBtn.contains(e.target);

          $(ele).mousemove(function (event) {
            var xPos = event.pageX;
            var yPos = event.pageY;

            $(ele)
              .find(".pause-vd")
              .css({
                left: xPos + "px",
                top: yPos - 10 + "px",
              });
          });

          if (palyEle) {
            video.play();
            playBtn.classList.add("hidden");
            pauseBtn.classList.remove("hidden");
            ele.classList.add("video-play");
            return;
          }
          video.pause();
          playBtn.classList.remove("hidden");
          pauseBtn.classList.add("hidden");
          ele.classList.remove("video-play");
        });

        // ele.addEventListener("mouse")
      });
    }
  } catch (error) {
    console.log(error);
  }
}
