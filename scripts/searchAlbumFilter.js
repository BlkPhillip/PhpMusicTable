document.addEventListener("DOMContentLoaded", function () {
  var searchInput = document.getElementById("searchAlbumFilter");
  var artistOptions = document.querySelectorAll("#myDropdown .options2 label");

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
