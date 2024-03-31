document.addEventListener("DOMContentLoaded", function () {
  const qariSelect = document.getElementById("qari-select");
  const surahSelect = document.getElementById("surah-select");
  const audioPlayer = document.getElementById("audio-player");
  const bookmarkBtn = document.getElementById("bookmark-btn");

  const qariData = [
    { name: "Abdul Basit", identifier: "qari1" },
    { name: "Abdul Samad", identifier: "qari2" },
    { name: "Abdallah Humeid", identifier: "qari3" },
    { name: "Abdul Azeez Ar Rawdhaan", identifier: "qari4" },
    { name: "Abdul Majid Rahman", identifier: "qari5" },
    { name: "Abdul Kabeer Haidari", identifier: "qari6" },
    { name: "Abdulhadi Kanakeri", identifier: "qari7" },
    { name: "Abdulkabir Al Hadidi", identifier: "qari8" },
    { name: "Abdullah Al Buajan", identifier: "qari9" },
    { name: "Abdur Rahman As-Sudais", identifier: "qari10" },
  ];

  const surahData = [
    { name: "Surah AlFatiha", number: 1, audio: "audio/surah_Fatiha.mp3" },
    { name: "Surah AlBaqara", number: 2, audio: "audio/surah.mp3" },
    { name: "Surah AlBaqara", number: 3, audio: "audio/surah.mp3" },
    { name: "Surah AlBaqara", number: 4, audio: "audio/surah.mp3" },
    { name: "Surah AlBaqara", number: 5, audio: "audio/surah.mp3" },
    { name: "Surah AlBaqara", number: 6, audio: "audio/surah.mp3" },
    { name: "Surah AlBaqara", number: 7, audio: "audio/surah.mp3" },
    { name: "Surah AlBaqara", number: 8, audio: "audio/surah.mp3" },
    { name: "Surah AlBaqara", number: 9, audio: "audio/surah.mp3" },
    { name: "Surah AlBaqara", number: 10, audio: "audio/surah.mp3" },
  ];

  function playSurah(qariIdentifier, surahNumber) {
    const surah = surahData.find((surah) => surah.number == surahNumber);
    if (surah && surah.audio) {
      audioPlayer.src = surah.audio;
      audioPlayer.play();
    } else {
      console.error("Error: Surah audio not found or empty.");
    }
  }

  function populateQariSelect() {
    qariData.forEach((qari) => {
      const option = document.createElement("option");
      option.text = qari.name;
      option.value = qari.identifier;
      qariSelect.add(option);
    });
  }

  function fetchSurahs(qariIdentifier) {
    surahSelect.innerHTML = "";
    surahData.forEach((surah) => {
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

  populateQariSelect();

  qariSelect.addEventListener("change", function () {
    const selectedQari = this.value;
    fetchSurahs(selectedQari);
  });

  surahSelect.addEventListener("change", function () {
    const selectedSurah = this.value;
    const selectedQari = qariSelect.value;
    playSurah(selectedQari, selectedSurah);
  });
});
