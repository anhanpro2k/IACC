export default function Amount() {
  try {
    var event = new Event("change");
    document.addEventListener("click", (e) => {
      const count = e.target.closest(".qtyJS");
      const countBtnPlus = e.target.closest(".plus");
      const countBtnMinus = e.target.closest(".minus");

      if (count) {
        const countInput = count.querySelector(".amount");
        const max = parseInt(countInput.max);
        if (countBtnPlus) {
          max
            ? countInput.value < max
              ? countInput.value++
              : countInput.value
            : countInput.value++;
          countInput.dispatchEvent(event);
          $(countInput).trigger("change");
        }
        if (countBtnMinus) {
          if (parseInt(countInput.value) > 1) {
            countInput.value--;
            countInput.dispatchEvent(event);
            $(countInput).trigger("change");
          }
        }

        countInput.addEventListener("change",()=> {
            countInput.value > max ? countInput.value = max : countInput.value
        })
      }
    });
  } catch (error) {
    console.log(error);
  }
}
