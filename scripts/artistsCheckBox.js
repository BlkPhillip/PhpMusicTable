let checkedArtists = [];
let checkedAlbums = [];

document.addEventListener("DOMContentLoaded", function () {
  const dropdownContent = document.getElementById("myDropdown");
  const artistCheckboxes = dropdownContent.querySelectorAll(".artist-check");
  const albumCheckboxes = dropdownContent.querySelectorAll(".album-check");

  dropdownContent.addEventListener("change", function (event) {
    const target = event.target;

    if (target.classList.contains("artist-check")) {
      updateCheckedArtists();
      updateAlbumOptions();
    } else if (target.classList.contains("album-check")) {
      updateCheckedAlbums();
      filterByArtist();
    }
  });

  artistCheckboxes.forEach(function (checkbox) {
    checkbox.addEventListener("change", function () {
      updateCheckedArtists(); // Call the function to update the checked artists
      updateAlbumOptions();
      filterByArtist(); // Call the filtering function whenever a checkbox is changed
    });
  });

  albumCheckboxes.forEach(function (checkbox) {
    checkbox.addEventListener("change", function () {
      updateCheckedArtists();
      updateCheckedAlbums(); // Call the function to update the checked albums
      filterByArtist();
    });
  });

  // Function to update the checked artists array
  function updateCheckedArtists() {
    checkedArtists = Array.from(
      dropdownContent.querySelectorAll(".artist-check:checked")
    ).map(function (checkbox) {
      return checkbox.value;
    });
  }

  // Function to update the checked albums array
  function updateCheckedAlbums() {
    checkedAlbums = Array.from(
      document.querySelectorAll(".album-check:checked")
    ).map(function (checkbox) {
      return checkbox.value;
    });
  }

  async function filterByArtist() {
    const artistsParam = encodeURIComponent(JSON.stringify(checkedArtists));
    const albumsParam = encodeURIComponent(JSON.stringify(checkedAlbums));

    const url = "index.php?artists=" + artistsParam + "&albums=" + albumsParam;

    try {
      const response = await fetch(url);
      const data = await response.text();

      const parser = new DOMParser();
      const doc = parser.parseFromString(data, "text/html");
      const filteredTable = doc.getElementById("musicTable");

      // Replace the existing music table with the filtered table
      const currentTable = document.getElementById("musicTable");
      currentTable.parentNode.replaceChild(filteredTable, currentTable);
    } catch (error) {
      console.error("Filtering failed", error);
    }
  }

  async function updateAlbumOptions() {
    const artistCheckboxes = document.querySelectorAll(".artist-check:checked");
    const selectedArtists = Array.from(artistCheckboxes).map(
      (checkbox) => checkbox.value
    );
    const url =
      "filter.php?artists=" +
      encodeURIComponent(JSON.stringify(selectedArtists));

    try {
      const response = await fetch(url);
      const data = await response.text();

      const parser = new DOMParser();
      const doc = parser.parseFromString(data, "text/html");
      const filteredOptions = doc.getElementById("options2");

      // Get the current options2 div
      const currentOptions = document.getElementById("options2");

      // Remove all existing checkboxes inside the options2 div
      while (currentOptions.firstChild) {
        currentOptions.removeChild(currentOptions.firstChild);
      }

      // Append the filtered checkboxes to the options2 div
      currentOptions.innerHTML = filteredOptions.innerHTML;
    } catch (error) {
      console.error("Updating album options failed", error);
    }
  }
});
