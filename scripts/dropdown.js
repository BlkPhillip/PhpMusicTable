function myDrop() {
  const open = document.getElementById("myDropdown").classList.toggle("show");
  if (open) {
    document.getElementById("dropsign").textContent = "--";
  } else {
    document.getElementById("dropsign").textContent = "+";
  }
}
