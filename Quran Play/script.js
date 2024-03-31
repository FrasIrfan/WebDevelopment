document.addEventListener("DOMContentLoaded", function () {
  const qariSelect = document.getElementById("qari-select");
  const surahSelect = document.getElementById("surah-select");
  const audioPlayer = document.getElementById("audio-player");
  const bookmarkBtn = document.getElementById("bookmark-btn");

  // Simulated qari data
  const qariData = [
    { name: "Abdul Basit", identifier: "qari1" },
    { name: "Abdul Samad", identifier: "qari2" },
    // Add more qari data as needed
  ];

  // Simulated surah data
  const surahData = {
    qari1: [
      { name: "Surah AlFatiha", number: 1, audio: "audio/surah_Fatiha.mp3" },
      { name: "Surah AlBaqara", number: 2, audio: "audio/surah2.mp3" },
      // Add more surah data for qari1
    ],
    qari2: [
      { name: "Surah 3", number: 3, audio: "" },
      { name: "Surah 4", number: 4, audio: "surah4.mp3" },
      // Add more surah data for qari2
    ],
  };

  // Function to play selected surah
  function playSurah(qariIdentifier, surahNumber) {
    const surahs = surahData[qariIdentifier];
    const surah = surahs.find((surah) => surah.number == surahNumber);
    if (surah && surah.audio) {
      audioPlayer.src = surah.audio;
      audioPlayer.play();
    } else {
      console.error("Error: Surah audio not found or empty.");
    }
  }

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

  // Event listener for surah selection
  surahSelect.addEventListener("change", function () {
    const selectedQari = qariSelect.value;
    const selectedSurah = this.value;
    playSurah(selectedQari, selectedSurah);
  });
});
