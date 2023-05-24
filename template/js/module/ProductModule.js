export default function ProductModule() {
  try {
    const HVJS = document.querySelectorAll(".HVJS");

    if (HVJS.length > 0) {
      HVJS.forEach((item, i) => {
        const itemHVlistJS = item.querySelectorAll(".itemHVJS");

        itemHVlistJS.forEach((ele, j) => {
          $(ele).hover(
            () => {
              $(ele.querySelector(".content")).stop().slideDown();
            },
            () => {
              $(ele.querySelector(".content")).stop().slideUp();
            }
          );
        });
      });
    }
  } catch (error) {
    console.log(error);
  }
}
