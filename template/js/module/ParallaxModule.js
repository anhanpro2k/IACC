export default function ParallaxModule() {
  try {
    $(window).scroll(function () {
      var scrollTop = $(window).scrollTop();
      $(".parallaxJS >img").each(function () {
        var parallaxPosition = scrollTop * 0.5; // tỷ lệ 0.5
        $(this).css("transform", "translateY(" + parallaxPosition + "px)");
      });

      $(".parallaxJS >video").each(function () {
        var parallaxPosition = scrollTop * 0.5; // tỷ lệ 0.5
        $(this).css("transform", "translateY(" + parallaxPosition + "px)");
      });
    });
  } catch (error) {
    console.log(error);
  }
}
