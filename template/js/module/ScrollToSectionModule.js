export default function ScrollToSectionModule() {
  try {
    const speed = 800;
    // NẾU CÓ ĐỊA CHỈ ID TRÊN THANH URL THÌ SCROLL XUỐNG
    const hash = window.location.hash;
    if ($(hash).length) scrollToID(hash, speed);
    // TÌM ĐỊA CHỈ ID VÀ SCROLL XUỐNG NẾU CÓ CLASS
    $(".btn-scroll").on("click", function (e) {
      e.preventDefault();

      const href = $(this).find("> a").attr("href") || $(this).attr("href");
      const id = href.slice(href.lastIndexOf("#"));
      if ($(id).length) {
        scrollToID(id, speed);
      } else {
        // window.location.replace(`/${id}`);
        window.location.href = href;
      }
    });
    // HÀM SCROLL CHO MƯỢT MÀ
    function scrollToID(id, speed) {
      const offSet = $(".header").outerHeight();
      const section = $(id).offset();
      const targetOffset = section.top - offSet;
      $("html,body").animate({ scrollTop: targetOffset }, speed);
    }

    const mouse = document.querySelector(".scroll-down");
    const id_sl_pr = document.querySelector(".des-scroll");
    if (id_sl_pr) {
      mouse.addEventListener("click", () => {
        scrollToID(".des-scroll", 0);
      });
    }

    const scroll = document.querySelector(".scrolld-btn");

    if (scroll) {
      const parent = scroll.parentElement;

      scroll.addEventListener("click", () => {
        var topPos = $(parent).offset().top;

        // Scroll đến vị trí của phần tử parent
        $("html, body").animate({ scrollTop: topPos }, 500);
      });
    }
  } catch (error) {
    console.log(error);
  }
}
