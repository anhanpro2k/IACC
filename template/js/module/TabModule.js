import ShowHideModule from "./ShowHideModule.js";

export default function TabModule() {
 try {
  let tab = document.querySelectorAll(".tabJS");
  if (tab) {
    tab.forEach((t) => {
      let tBtn = t.querySelectorAll(".tabBtn");
      let tPanelList = t.querySelectorAll(".tabPanelList");
      tPanelList.forEach((ele, index) => {
        let tPanel = ele.querySelectorAll(".tabPanel");
        if (tBtn.length !== 0 && tPanel.length === tBtn.length) {
          if (!t.classList.contains('tab-not')) {
            tBtn[0].classList.add("active");
            tPanel[0].classList.add("open");
          } 

          for (let i = 0; i < tBtn.length; i++) {
            tBtn[i].addEventListener("click", showPanel);

            function showPanel(e) {
              e.preventDefault();
              for (let a = 0; a < tBtn.length; a++) {
                tBtn[a].classList.remove("active");
                tPanel[a].classList.remove("open");
              }
              tBtn[i].classList.add("active");
              tPanel[i].classList.add("open");
            }
          }
        }
      });
    });
  }
  
  /////////////////////////////////////////////////////////////////////////////////////

  $(".tabJS2 .tabs").addClass("active");
  $(".tab-item").first().addClass("current");
  const listTab = document.querySelectorAll(".tabJS2 .tab-item");

  $(".tabJS2 .tabs .tab-item").click(function (g) {
    var index = $(this).index();
    var tab = $(this).closest(".tabJS2");

    tab.find(".tabs > .tab-item").removeClass("current");
    $(this).closest(".tab-item").addClass("current");

    listTab.forEach((ele, i) => {
      if (i == index) {
        ele.classList.add("current");
      } else {
        ele.classList.remove("current");
      }
    });

    tab
      .find(".tab_content")
      .find("div.tab_item")
      .not("div.tab_item:eq(" + index + ")")
      .stop()
      .slideUp(0);
    tab
      .find(".tab_content")
      .find("div.tab_item:eq(" + index + ")")
      .stop()
      .slideDown(0);

    ShowHideModule();
  });

  //////////////////////////////////////////////////////
  $(document).ready(function () {
    $(".toggleHide").hide();
    $(".toggleOnclick").click(function (e) {
      e.preventDefault();

      if (
        !$(this).closest(".toggleItem").find(".toggleHide").is(":visible")
      ) {
        $(".toggleHide").slideUp();
        $(".toggleOnclick").removeClass("active");
      }

      $(this).toggleClass("active");
      $(this).next(".toggleHide").stop().slideToggle();
    });

    if ($(".toggleOnclick").hasClass("active")) {
      $(".toggleOnclick.active").next(".toggleHide").stop().slideToggle();
    }
  });
 } catch (error) {
  console.log(error)
 }
}
