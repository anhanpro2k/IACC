export default function TimerModule() {
  try {
    const proItem = document.querySelectorAll(".count");
  
    proItem.forEach((item) => {
      const daysEl = item.querySelector(".days");
      const hoursEl = item.querySelector(".hours");
      const minsEl = item.querySelector(".mins");
      const secondsEl = item.querySelector(".seconds");
  
      if (daysEl || hoursEl || minsEl || secondsEl) {
        // const getDate = item.querySelector('.count');
        const dates = item.getAttribute("data-time");
        // if(getDate){
        console.log(dates);
  
        // }
        const newYears = dates;
  
        function countdown() {
          const newYearsDate = new Date(newYears);
          const currentDate = new Date();
  
          const totalSeconds = (newYearsDate - currentDate) / 1000;
  
          const days = Math.floor(totalSeconds / 3600 / 24);
          const hours = Math.floor(totalSeconds / 3600) % 24;
          const mins = Math.floor(totalSeconds / 60) % 60;
          const seconds = Math.floor(totalSeconds) % 60;
  
          if (daysEl) {
            daysEl.innerHTML = days;
          }
          if (hoursEl) {
            hoursEl.innerHTML = formatTime(hours);
          }
          if (minsEl) {
            minsEl.innerHTML = formatTime(mins);
          }
          if (secondsEl) {
            secondsEl.innerHTML = formatTime(seconds);
          }
  
          if (days < 0) {
            if (daysEl) {
              daysEl.innerHTML = 0;
            }
            if (hoursEl) {
              hoursEl.innerHTML = 0;
            }
            if (minsEl) {
              minsEl.innerHTML = 0;
            }
            if (secondsEl) {
              secondsEl.innerHTML = 0;
            }
          }
        }
  
        function formatTime(time) {
          return time < 10 ? `0${time}` : time;
        }
  
        // initial call
        countdown();
  
        setInterval(countdown, 1000);
      }
    });
  } catch (error) {
    console.log(error)
  }
}
