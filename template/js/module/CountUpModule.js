export default function CountUpModule() {
  $(document).ready(function () {
    $(".dataSec-num .num").counterUp({
      delay: 3,
      time: 1500,
      triggerOnce: true,
    });

    setTimeout(function () {
      // this.destroy();
    }, 1501);
  });

  $('.dataSecJS .num').countUp({
    'time': 2000,
    'delay': 10
  });
}
