export default function SelectCusModule() {
  try {
    const select = document.querySelector(".selectJS");
    var event = new Event("change");
    const frmWarehouse = document.querySelector("#frmWarehouse");

    if (select) {
      const content = select.querySelector(".selectCtJS");
      const itemCt = content.querySelectorAll(".selecItemtJS");
      const textSelect = select.querySelector(".selectedJS .text");
      const input = select.querySelector("input");

      select.addEventListener("click", (e) => {
        $(content).stop().slideToggle(300);
      });

      if (input.value !== "") {
        textSelect.innerHTML = input.value;
      }

      itemCt.forEach((item, i) => {
        item.addEventListener("click", (e) => {
          textSelect.innerHTML = item.textContent;
          input.value = item.textContent * 1;
          input.dispatchEvent(event);
          if (frmWarehouse) {
            console.log("submit");
            frmWarehouse.submit();
          }
          $(content).stop().slideUp(300);
        });
      });
    }

    const filterList = document.querySelectorAll(".filterJS");

    if (filterList.length > 0) {
      filterList.forEach((ele, i) => {
        const seletedText = ele.querySelector(".filterSelectedTextJS");
        const filterList = ele.querySelector(".filterListJS");
        const filterItemList = ele.querySelectorAll(".filterItemJS");
        const click = ele.querySelector(".filterSelectedJS");
        const activeEle = filterList.querySelector(".active");
        const removeActive = () => {
          filterItemList.forEach((filterItem) => {
            filterItem.classList.remove("active");
          });
        };

        if (activeEle && seletedText) {
          const text = activeEle.getAttribute("data-text");
          const slug = activeEle.getAttribute("data-slug");

          seletedText.setAttribute("data-slug", slug);
          seletedText.innerHTML = text;
        }

        click.addEventListener("click", (e) => {
          filterList.classList.toggle("active");
        });

        filterItemList.forEach((item, j) => {
          const slugItem = item.getAttribute("data-slug");
          const datatext = item.getAttribute("data-text");

          if (seletedText) {
            let slugSelected1 = seletedText.getAttribute("data-slug");
            if (slugItem === slugSelected1) {
              item.classList.add("active");
            }

            item.addEventListener("click", (e) => {
              filterList.classList.remove("active");
              removeActive();
              item.classList.add("active");

              seletedText.setAttribute("data-slug", slugItem);
              seletedText.innerHTML = datatext;
            });
          }
        });

        document.addEventListener("click", (e) => {
          const isClickInsideElement = ele.contains(e.target);
          if (
            e.target.matches(".filterJS, .filterJS *") ||
            isClickInsideElement
          ) {
            return;
          }
          filterList.classList.remove("active");
        });
      });
    }

    const newsSelect = document.querySelector(".newsH-box");

    if (newsSelect) {
      const select = newsSelect.querySelector(".newsH-box-select");
      const list = newsSelect.querySelector(".newsH-list");

      if (select && list) {
        const items = newsSelect.querySelectorAll(".newsH-item");
        select.addEventListener("click", () => {
          list.classList.toggle("open");
          $(list).stop().slideToggle();
        });

        $(items).each((i, ele) => {
          $(ele).click(() => {
            $(list).stop().slideUp();
          });
        });

        document.addEventListener("click", (e) => {
          const click = select.contains(e.target);

          if (click) {
            return;
          }
          $(list).stop().slideUp();
        });
      }
    }
  } catch (error) {
    console.log(error);
  }
}
