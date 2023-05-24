export default function textCount() {
  try {
    const textarea = document.querySelectorAll(".form-control-input textarea");
  
    if (textarea) {
      $(textarea).each((ele1, ele2) => {
        if ($(ele2).parent().find(".word .total")) {
          const maxlenght = $(ele2).parent().find(".word .total");
          $(ele2).attr("maxlength", $(maxlenght).text());
  
          $(ele2).on("input", () => {
            const length = $(ele2).val().length;
            $(ele2).parent().find(".word .num").html(length);
          });
        }
      });
    }
  } catch (error) {
    console.log(error)
  }
}
