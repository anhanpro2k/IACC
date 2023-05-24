export default function sliderCus() {
  // $('.slideJS').on('mousemove', function(event) {
  //     var distance = event.pageX - $(this).offset().left;
  //     $(this).css({
  //       'transform': 'translateX(-' + (distance / 3) + '%)'
  //     });
  //   });

  //   var isDragging = false;
  //   var currentX;
  //   var initialX;
  //   var xOffset = 0;

  //   $(".slideJS .slideJS-wrap").on("mousedown", function (event) {
  //     isDragging = true;
  //     initialX = event.clientX;
  //   });

  //   $(".slideJS .slideJS-wrap").on("mouseup", function () {
  //     isDragging = false;
  //   });

  //   $(".slideJS .slideJS-wrap").on("mousemove", function (event) {
  //     if (isDragging) {
  //       currentX = event.clientX;
  //       xOffset = currentX - initialX;
  //       $(this).css({
  //         transform: "translateX(" + xOffset + "px)",
  //       });
  //     }
  //   });

  // $('.slideJS').on('mousedown', function(event) {
  //     var startX = event.pageX - $(this).offset().left;
  //     $(document).on('mousemove', function(event) {
  //       var distance = event.pageX - startX;
  //       $('.slideJS-wrap').css({
  //         'transform': 'translateX(' + distance + 'px)'
  //       });
  //     });
  //   });

  //   $(document).on('mouseup', function() {
  //     $(document).off('mousemove');
  //   });

//   $('.slideJS').on('mousedown', function(event) {
//     var distance;
//     var startX = event.pageX;
  
//     $(document).on('mousemove', function(event) {
//       distance = event.pageX - startX;
//       $('.slideJS-wrap').css({
//         'transform': 'translateX(' + distance + 'px)'
//       });
//     });
  
//     $(document).on('mouseup', function() {
//       $(document).off('mousemove');
//     });
//   });
  
}
