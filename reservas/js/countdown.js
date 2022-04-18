const getRemainTime = (deadline) => {
  let now = new Date(),
    remainTime = (new Date(deadline) - now + 1000) / 1000,
    remainSeconds = ('0' + Math.floor(remainTime % 60)).slice(-2),
    remainMinutes = ('0' + Math.floor((remainTime / 60) % 60)).slice(-2),
    remainHours = ('0' + Math.floor((remainTime / 3600) % 24)).slice(-2),
    remainDays = Math.floor(remainTime / (3600 * 24));

  return {
    remainTime,
    remainSeconds,
    remainMinutes,
    remainHours,
    remainDays,
  };
};

// eslint-disable-next-line no-unused-vars
const countdown = (deadline, elem, finalMessage) => {
  const el = document.getElementById(elem);

  const timerUpdate = setInterval(() => {
    let t = getRemainTime(deadline);
    el.innerHTML = `${t.remainDays} días | ${t.remainHours} horas | ${t.remainMinutes} minutos | ${t.remainSeconds} segundos`;

    if (t.remainTime <= 1) {
      clearInterval(timerUpdate);
      window.location.href = 'index.php';
    }
  }, 500);
};

countdown('Apr 12 2022 10:00:00 GMT+0200', 'clock', 'acabou a contagem');
