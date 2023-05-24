export default function LoadMoreModule() {
  try {
    $(document).ready(function () {
      const loadContainer = document.querySelectorAll(".load-container");
      if (loadContainer) {
        loadContainer.forEach((item) => {
          const loadNumberInit = parseInt(item.getAttribute("data-load-init"));
          const loadNumberSl = parseInt(item.getAttribute("data-load-sl"));
          const loadItems = item.querySelectorAll(".load-item");
          const loadBtn = item.querySelector(".load-btn");
          loadItems.forEach((item) => {
            item.classList.add("load-hidden");
          });
          if (loadItems.length <= loadNumberInit) {
            $(loadBtn).hide();
          }
          $(loadItems).slice(0, loadNumberInit).show();
          $(loadItems).slice(0, loadNumberInit).removeClass("load-hidden");
          $(loadBtn).on("click", function (e) {
            e.preventDefault();
            const loadItemsAffter = item.querySelectorAll(
              ".load-item.load-hidden"
            );
            if (
              $(item.querySelectorAll(".load-item.load-hidden")).length !== 0
            ) {
              $(loadItemsAffter).slice(0, loadNumberSl).slideDown();
              $(loadItemsAffter)
                .slice(0, loadNumberSl)
                .removeClass("load-hidden");
            }
            if (
              $(item.querySelectorAll(".load-item.load-hidden")).length == 0
            ) {
              loadBtn.classList.add("load-collapse");
              loadBtn.innerHTML = `<span class="seemore cl-pri center">
                        Thu gọn
                        <span class="icon">
                            <i class="fa-solid fa-caret-up"></i>
                        </span>
                    </span>`;
              if (loadItemsAffter.length == 0) {
                $(loadItems).slice(loadNumberInit).slideUp();
                loadItems.forEach((item) => {
                  item.classList.add("load-hidden");
                });
                $(loadItems)
                  .slice(0, loadNumberInit)
                  .removeClass("load-hidden");
                loadBtn.innerHTML = `<span class="seemore cl-pri center">
              Xem thêm
              <span class="icon">
                  <i class="fa-solid fa-caret-down"></i>
              </span>
          </span>`;
              }
            }
          });
        });
      }
    });
  } catch (error) {
    console.log(error);
  }
}
