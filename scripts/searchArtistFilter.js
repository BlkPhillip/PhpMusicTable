document.addEventListener("DOMContentLoaded", function () {
  var searchInput = document.getElementById("searchArtistFilter");
  var artistOptions = document.querySelectorAll("#myDropdown .options label");

  searchInput.addEventListener("input", function () {
    var searchValue = this.value.toLowerCase();

    artistOptions.forEach(function (option) {
      var artistName = option.innerText.toLowerCase();
      if (artistName.indexOf(searchValue) > -1) {
        option.style.display = "";
      } else {
        option.style.display = "none";
      }
    });
  });
});
