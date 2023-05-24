export default function LoadModule() {
  try {
    const load = document.querySelector(".load");
    if (load) {
      setTimeout(() => {
        $(load).fadeOut();
      }, 1200);
    }
  } catch (error) {
    console.log(error);
  }
}
