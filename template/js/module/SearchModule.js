export default function SearchModule() {
  try {
    //////////////////////////////////////////
    const hds = document.querySelectorAll(".header-search");
    if (hds) {
      hds.forEach((ele, i) => {
        const searchBtn = ele.querySelector(".header-search .icon");
        const searchCT = ele.querySelector(".form-search");

        if (searchBtn && searchCT) {
          // const seacrForm = searchCT.querySelector(".searchSec-form");
          searchBtn.addEventListener("click", () => {
            searchCT.classList.toggle("active");
            searchBtn.classList.toggle("active");
          });

          document.addEventListener("click", (e) => {
            const isClickInsideElement = searchBtn.contains(e.target);
            if (
              e.target.matches(
                ".header-search .form-search, .header-search .form-search *"
              ) ||
              isClickInsideElement
            ) {
              return;
            }
            searchCT.classList.remove("active");
            searchBtn.classList.remove("active");
          });
        }
      });
    }
  } catch (error) {
    console.log(error);
  }
}
