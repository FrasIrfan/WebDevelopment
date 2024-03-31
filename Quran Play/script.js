document.addEventListener("DOMContentLoaded", function () {
  const qariSelect = document.getElementById("qari-select");
  const surahSelect = document.getElementById("surah-select");
  const audioPlayer = document.getElementById("audio-player");
  const bookmarkBtn = document.getElementById("bookmark-btn");

  // Simulated qari data
  const qariData = [
    { name: "Qari 1", identifier: "qari1" },
    { name: "Qari 2", identifier: "qari2" },
    // Add more qari data as needed
  ];

  // Simulated surah data
  const surahData = {
    qari1: [
      { name: "Surah 1", number: 1 },
      { name: "Surah 2", number: 2 },
      // Add more surah data for qari1
    ],
    qari2: [
      { name: "Surah 3", number: 3 },
      { name: "Surah 4", number: 4 },
      // Add more surah data for qari2
    ],
    // Add more surah data for other qaris as needed
  };

  // Function to populate qari select options
  function populateQariSelect() {
    qariData.forEach((qari) => {
      const option = document.createElement("option");
      option.text = qari.name;
      option.value = qari.identifier;
      qariSelect.add(option);
    });
  }

  // Function to fetch surahs based on selected qari
  function fetchSurahs(qariIdentifier) {
    surahSelect.innerHTML = "";
    const surahs = surahData[qariIdentifier];
    surahs.forEach((surah) => {
      const option = document.createElement("option");
      option.text = surah.name;
      option.value = surah.number;
      surahSelect.add(option);
    });
  }
  const toggleSwitch = document.querySelector(
    '.theme-switch input[type="checkbox"]'
  );

  function switchTheme(e) {
    if (e.target.checked) {
      document.body.classList.add("dark-mode");
    } else {
      document.body.classList.remove("dark-mode");
    }
  }

  toggleSwitch.addEventListener("change", switchTheme);

  // Populate qari select options on page load
  populateQariSelect();

  // Event listener for qari selection
  qariSelect.addEventListener("change", function () {
    const selectedQari = this.value;
    fetchSurahs(selectedQari);
  });
});
