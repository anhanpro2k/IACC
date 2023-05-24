export default function LetterModule() {
    var textWrapper = document.querySelectorAll('.letter-animation');
    if (textWrapper) {
        textWrapper.forEach(item => {
            item.innerHTML = textWrapper.textContent.replace(/\S/g, "<span class='letter'>$&</span>");
            const letter = item.querySelector('.letter')

            function LetterAnimation() {
                anime.timeline({ loop: false })
                    .add({
                        targets: letter,
                        scale: [4, 1],
                        opacity: [0, 1],
                        translateZ: 0,
                        easing: "easeOutExpo",
                        duration: 600,
                        delay: (el, i) => 50 * i
                    }).add({
                        targets: '.title',
                        opacity: 0,
                        duration: 1000,
                        easing: "easeOutExpo",
                        delay: 200
                    });

            }
            setTimeout(() => {
                LetterAnimation();
            }, 1200)
        })
    }
   
}