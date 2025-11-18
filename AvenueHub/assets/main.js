document.addEventListener("copy", e => {
  e.preventDefault();
  e.clipboardData.setData("text/plain", "© AvenueHub — Content Protected");
});