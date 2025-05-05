/**
 * Utiility functions script
 */

// Toggle for recent purchases
document.addEventListener("DOMContentLoaded", function () {
  const toggleButton = document.getElementById("toggleRecent");
  const pastPurchase = document.getElementById("pastPurchase");

  // check if button and content exist
  if (toggleButton && pastPurchase) {
    // Make sure its initally expanded
    let isCollapsed = false;

    // Original height of the section
    const originalHeight = pastPurchase.scrollHeight;
    pastPurchase.style.maxHeight = originalHeight + "px";

    // Toggle function
    function toggleRecent() {
      if (isCollapsed) {
        // Expand section
        pastPurchase.style.maxHeight = originalHeight + "px";
        pastPurchase.style.opacity = "1";
        toggleButton.classList.remove("collapsed");

        // Save state
        localStorage.setItem("recentCollapsed", "false");
      } else {
        // Collapse the section
        pastPurchase.style.maxHeight = "0";
        pastPurchase.style.opacity = "0";
        toggleButton.classList.add("collapsed");

        // Save state
        localStorage.setItem("recentCollapsed", "true");
      }

      // Toggle state
      isCollapsed = !isCollapsed;
    }

    // Add event listener to the button
    toggleButton.addEventListener("click", toggleRecent);

    // Check for saved state
    const savedState = localStorage.getItem("recentCollapsed");

    // Apply state if it exists
    if (savedState === "true") {
      isCollapsed = false;
      toggleRecent();
    }
  }
});

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

function formatCardNumber() {
  const input = document.getElementById("card-number");
  let v = input.value.replace(/\D/g, "");
  let out = "";
  for (let i = 0; i < v.length; i++) {
    if (i > 0 && i % 4 === 0) out += " ";
    out += v[i];
  }
  input.value = out.slice(0, 19);
}

function formatExpiryDate() {
  const input = document.getElementById("expire");
  let v = input.value.replace(/\D/g, "");

  // Format as MM/YY
  if (v.length > 0) {
    // First limit month to valid range (01-12)
    if (v.length >= 1) {
      if (v[0] > 1) {
        v = "0" + v[0];
      }
    }
    if (v.length >= 2) {
      if (v[0] == 1 && v[1] > 2) {
        v = "12";
      }
    }

    // Format with slash
    if (v.length > 2) {
      v = v.substring(0, 2) + "/" + v.substring(2, 4);
    } else if (v.length == 2) {
      v = v + "/";
    }
  }

  input.value = v;
}

function copyShippingDetails() {
  document.getElementById("form-ship-name").value =
    document.getElementById("ship-name").value;
  document.getElementById("form-ship-line1").value =
    document.getElementById("ship-line1").value;
  document.getElementById("form-ship-line2").value =
    document.getElementById("ship-line2").value;
  document.getElementById("form-ship-city").value =
    document.getElementById("ship-city").value;
  document.getElementById("form-ship-postal").value =
    document.getElementById("ship-postal").value;
  document.getElementById("form-ship-phone").value =
    document.getElementById("ship-phone").value;
  document.getElementById("form-ship-email").value =
    document.getElementById("ship-email").value;
}
function payLater() {
  copyShippingDetails();
  document.getElementById("payment-form").submit();
}
