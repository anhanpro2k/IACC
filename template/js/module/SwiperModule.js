export default function SwiperModule() {
  try {
    const bannerHome = document.querySelector(".banner-home");
    if (bannerHome) {
      const main = bannerHome.querySelector(".banner-slide");
      const swiperContainer = main.querySelector(".swiper");
      var swiper1 = new Swiper(swiperContainer, {
        slidesPerView: 1,
        spaceBetween: 0,
        loopedSlides: 4,
        lazy: !0,
        watchSlidesVisibility: !0,
        watchSlidesProgress: !0,
        loop: swiperContainer.classList.contains("--loop"),
        pagination: {
          el: main.querySelector(".swiper-pagination"),
          clickable: !0,
          renderBullet: function (index, className) {
            return (
              '<span class="' +
              className +
              '">' +
              '<span class="inner"></span>' +
              "</span>"
            );
          },
        },
        navigation: {
          nextEl: main.querySelector(".next"),
          prevEl: main.querySelector(".prev"),
        },
        speed: 1000,
        autoplay: { delay: 5000 },
        effect: "coverflow",
        parallax: !0,
        coverflowEffect: {
          rotate: 0.05,
          depth: 0,
          stretch: 0,
          modifier: 1,
          slideShadows: !1,
        },
        on: {
          init: function () {
            let swiper = this;
            for (let i = 0; i < swiper.slides.length; i++) {
              const slide = swiper.slides[i];
              const bannerMedia = slide.querySelector(".banner-media");
              const bannerdc = slide.querySelector(".banner-dc");
              const bannerct = slide.querySelector(".content");
              const bannerRight = slide.querySelector(".banner-right");
              if (bannerMedia) {
                bannerMedia.setAttribute(
                  "data-swiper-parallax",
                  0.9 * swiper.width
                );
                bannerMedia.setAttribute("data-swiper-paralalx-opacity", 0.1);
              }
              if (bannerdc) {
                bannerdc.setAttribute(
                  "data-swiper-parallax",
                  0.7 * swiper.width
                );
                bannerdc.setAttribute("data-swiper-paralalx-opacity", 0.1);
              }
              if (bannerct) {
                bannerct.setAttribute(
                  "data-swiper-parallax",
                  0.7 * swiper.width
                );
                bannerct.setAttribute("data-swiper-paralalx-opacity", 0.1);
              }
              if (bannerRight) {
                bannerRight.setAttribute(
                  "data-swiper-parallax",
                  0.7 * swiper.width
                );
                bannerRight.setAttribute("data-swiper-paralalx-opacity", 0.1);
              }
            }
          },
          slideChange: function () {
            var activeIndex = this.activeIndex;
            var realIndex = this.slides
              .eq(activeIndex)
              .attr("data-swiper-slide-index");
            $(".banner-home .swiper-slide").removeClass("my-active");
            $(
              '.banner-home .swiper-slide[data-swiper-slide-index="' +
                realIndex +
                '"]'
            ).addClass("my-active");
          },
          resize: function () {
            this.update();
          },
        },
      });
    }

    const eleFree = document.querySelectorAll(".free-slide");

    if (eleFree) {
      eleFree.forEach((ele, i) => {
        const numSl = ele.querySelectorAll(".swiper-slide");
        let wEle = ele.offsetWidth;
        let wSL = numSl[0].offsetWidth;
        
        var swiper2 = new Swiper(ele.querySelector(".swiper"), {
          slidesPerView: "auto",
          lazy: !0,
          pagination: {
            el: ele.querySelector(".swiper-pagination"),
            type: ele
              .querySelector(".swiper")
              .classList.contains("--progressbar")
              ? "progressbar"
              : "bullets",
            clickable: !0,
          },
          loop: ele.querySelector(".swiper").classList.contains("--loop"),
          navigation: {
            nextEl: ele.querySelector(".next"),
            prevEl: ele.querySelector(".prev"),
          },
          speed: 1000,
          autoplay: {
            delay: 5000,
            disableOnInteraction: false,
          },
          on: {
            // init: function () {
            //   if (
            //     ele.querySelector(".swiper").classList.contains("--progressbar")
            //   ) {
            //     console.log(wEle / wSL);
            //   }
            //   var progressbarFill = ele.querySelector(
            //     ".swiper-pagination-progressbar-fill"
            //   );
            //   $(progressbarFill).css(
            //     "transform",
            //     `translate3d(0px, 0px, 0px) scaleX(${
            //       1 / (numSl.length / (wEle / wSL))
            //     }) scaleY(1)`
            //   );
            // },
            // slideChange: function (e) {
            //   // wEle = ele.offsetWidth;
            //   // wSL = numSl[0].offsetWidth;

            //   if (ele
            //     .querySelector(".swiper")
            //     .classList.contains("--progressbar")) {
                  
            //     }
            //     var progressbarFill = ele.querySelector(
            //       ".swiper-pagination-progressbar-fill"
            //     );
            //     $(progressbarFill).css(
            //       "transform",
            //       `translate3d(${
            //         (e.realIndex / numSl.length) * 100
            //       }%, 0px, 0px) scaleX(${
            //         1 / (numSl.length / (wEle / wSL))
            //       }) scaleY(1)`
            //     );
            // },
            resize: function () {
              this.update();
            },
          },
        });
        if (!ele.querySelector(".swiper").classList.contains("--auto")) {
          swiper2.autoplay.stop();
        }
      });
    }

    const proDt = document.querySelector(".prodt-slide");

    if (proDt) {
      const thumvbs = proDt.querySelector(".prodt-thumbs");
      const main = proDt.querySelector(".prodt-main");

      var slthumbs = new Swiper(thumvbs.querySelector(".swiper"), {
        slidesPerView: 4,
        spaceBetween: 8,
        lazy: !0,
        pagination: {
          el: thumvbs.querySelector(".swiper-pagination"),
          clickable: !0,
        },
        // navigation: {
        //   nextEl: thumvbs.querySelector(".next"),
        //   prevEl: thumvbs.querySelector(".prev"),
        // },
        speed: 1000,
        breakpoints: {
          801: {
            spaceBetween: 16,
          },
        },
      });

      var slmain = new Swiper(main.querySelector(".swiper"), {
        slidesPerView: 1,
        spaceBetween: 0,
        loopedSlides: 4,
        lazy: !0,
        watchSlidesVisibility: !0,
        watchSlidesProgress: !0,
        speed: 1000,
        effect: "coverflow",
        parallax: !0,
        navigation: {
          nextEl: main.parentElement.querySelector(".next"),
          prevEl: main.parentElement.querySelector(".prev"),
        },
        coverflowEffect: {
          rotate: 0.05,
          depth: 0,
          stretch: 0,
          modifier: 1,
          slideShadows: !1,
        },
        on: {
          init: function () {
            let swiper = this;
            for (let i = 0; i < swiper.slides.length; i++) {
              const slide = swiper.slides[i];
              const bannerMedia = slide.querySelector(".prodt-img");
              if (bannerMedia) {
                bannerMedia.setAttribute(
                  "data-swiper-parallax",
                  0.9 * swiper.width
                );
                bannerMedia.setAttribute("data-swiper-paralalx-opacity", 0.1);
              }
            }
          },
          resize: function () {
            this.update();
          },
        },
        thumbs: {
          swiper: slthumbs,
        },
      });
    }
  } catch (error) {
    console.log(error);
  }
}
