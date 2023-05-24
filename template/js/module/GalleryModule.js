export default function gallery() {
  try {
    $(".gallery").each(function () {
      const $this = $(this);
      const $item = $this.find(".gItem");
      $(function () {
        $this.lightGallery({
          selector: $item,
          thumbnail: true,
          zoom: true,
        });
      });
    });
  
    $(".gallery-2").each(function () {
      const $this = $(this);
      const $item = $this.find(".gItem");
      $(function () {
        $this.lightGallery({
          selector: $item,
          thumbnail: true,
          zoom: true,
        });
      });
    });
  
    const GlImg2 = document.querySelectorAll(".gallery-2 .gItem");
  
    if (GlImg2.length > 0) {
      GlImg2.forEach((ele, i) => {
        const icon = document.createElement("span");
        icon.className = "icon-expand";
        icon.innerHTML = `<i class="ti-fullscreen"></i>`;
  
        ele.appendChild(icon);
      });
    }
  } catch (error) {
    console.log(error)
  }
}
