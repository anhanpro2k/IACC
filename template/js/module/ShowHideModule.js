export default function ShowHideModule() {
  try {
    const showhideBlocks = document.querySelectorAll(".show-hide-block");
    if (showhideBlocks) {
      showhideBlocks.forEach((item) => {
        const dataShow = item.getAttribute("data-show-hide-number");
        const dataShowText = item.getAttribute("data-show-hide-show-text");
        const dataHideText = item.getAttribute("data-show-hide-hide-text");
        const showhideContents = item.querySelector(".show-hide-content");
        const div = document.createElement("div");
        div.classList = "show-hide-toggle";
        div.innerText = dataShowText;
        var itemHeight = $(showhideContents).height();
        var lineHeight = parseInt($(showhideContents).css("line-height"), 10);
        var numberLine = Math.round(itemHeight / lineHeight);
        if (numberLine > dataShow) {
          item.appendChild(div);
          showhideContents.style = `display:-webkit-box;-webkit-line-clamp:${dataShow};-webkit-box-orient: vertical;overflow: hidden;text-overflow: ellipsis; word-break: break-word;`;
        }
      });
    }
    const showhideToggles = document.querySelectorAll(".show-hide-toggle");
    if (showhideToggles) {
      showhideToggles.forEach((item) => {
        const iconDr = document.createElement("span");
        iconDr.className = "icon-dr";
        iconDr.innerHTML = `<i class="fa-solid fa-caret-down"></i>`;

        item.appendChild(iconDr);

        item.addEventListener("click", () => {
          const dataShowText = item.parentElement.getAttribute(
            "data-show-hide-show-text"
          );
          const dataHideText = item.parentElement.getAttribute(
            "data-show-hide-hide-text"
          );
          const dataShow = item.parentElement.getAttribute(
            "data-show-hide-number"
          );
          if (
            item.parentElement.querySelector(".show-hide-content").style
              .overflow == "hidden"
          ) {
            item.parentElement.querySelector(
              ".show-hide-content"
            ).style = `display: inline-block;-webkit-line-clamp: 0;-webkit-box-orient: vertical;overflow: auto;text-overflow: ellipsis; word-break: break-word;`;
            item.innerText = dataHideText;
          } else {
            item.parentElement.querySelector(
              ".show-hide-content"
            ).style = `display:-webkit-box;-webkit-line-clamp:${dataShow};-webkit-box-orient: vertical;overflow: hidden;text-overflow: ellipsis; word-break: break-word;`;
            item.innerText = dataShowText;
          }
          iconDr.className = "icon-dr show";
          item.appendChild(iconDr);
        });
      });
    }

    // ////////////////////////////////////////////////////////////
    const contentCL = document.querySelectorAll(".reamoreJS");

    if (contentCL.length > 0) {
      contentCL.forEach((ele, i) => {});
    }
    const listReadmore = document.querySelectorAll(".reamoreCTJS");
    if (listReadmore.length > 0) {
      listReadmore.forEach((Child, j) => {
        const showtext = Child.getAttribute("data-show-text");
        const hidetext = Child.getAttribute("data-hide-text");
        const dataHeight = Child.getAttribute("data-height");
        const height = dataHeight == "" ? 800 : dataHeight * 1;
        $(Child).readmore({
          speed: 200,
          collapsedHeight: height,
          moreLink: `<button class="btn icon-fourth readmoreBtn t-white">
        <span class="btn-inner">
        ${showtext}
        </span>
        </button>`,
          lessLink: `<button class="btn icon-fourth readmoreBtn t-third no-bg bd-third">
        <span class="btn-inner">
        ${hidetext}
        </span>
        </button>`,
        });
      });
    }
  } catch (error) {
    console.log(error);
  }
}
