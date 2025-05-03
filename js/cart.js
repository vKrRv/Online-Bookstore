// Toggle for available offers
document.addEventListener("DOMContentLoaded", function () {
  const toggleBtn = document.getElementById("toggle-offers");
  const offersSection = document.getElementById("available-offers");
  const chevronIcon = document.getElementById("offers-chevron");
  // If toggle button is clicked, display the offers sections
  if (toggleBtn) {
    toggleBtn.addEventListener("click", function (e) {
      e.preventDefault();
      // Toggle "available-offers"
      if (offersSection.style.display === "block") {
        // Hide offers
        offersSection.style.display = "none";
        chevronIcon.className = "fas fa-chevron-right";
      } else {
        // Show offers
        offersSection.style.display = "block";
        chevronIcon.className = "fas fa-chevron-down";
      }
    });
  }

  // Copy to clipboard
  window.copyToClipboard = function (text, btn) {
    // Clipboard API
    navigator.clipboard
      .writeText(text)
      .then(() => {
        const copyBtn = btn;
        const originalText = copyBtn.textContent;
        copyBtn.textContent = "COPIED!";
        copyBtn.style.backgroundColor = "#38a169";
        setTimeout(function () {
          copyBtn.textContent = originalText;
          copyBtn.style.backgroundColor = "#007bff";
        }, 1500);
      })
      .catch((err) => {
        console.error("Could not copy text: ", err);
      });
  };
});
