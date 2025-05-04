/**
 * JavaScript for toggling the Recently Purchased section
 */
document.addEventListener('DOMContentLoaded', function() {
    const toggleButton = document.getElementById('toggleRecentPurchases');
    const recentPurchasesContent = document.getElementById('recentPurchasesContent');
    
    // Check if elements exist (only on pages with recent purchases)
    if (toggleButton && recentPurchasesContent) {
        // Set initial state (expanded)
        let isCollapsed = false;
        
        // Get the original height of the content
        const originalHeight = recentPurchasesContent.scrollHeight;
        recentPurchasesContent.style.maxHeight = originalHeight + 'px';
        
        // Function to toggle the section visibility
        function toggleRecentPurchases() {
            if (isCollapsed) {
                // Expand the section
                recentPurchasesContent.style.maxHeight = originalHeight + 'px';
                recentPurchasesContent.style.opacity = '1';
                toggleButton.classList.remove('collapsed');
                
                // Save state to localStorage
                localStorage.setItem('recentPurchasesCollapsed', 'false');
            } else {
                // Collapse the section
                recentPurchasesContent.style.maxHeight = '0';
                recentPurchasesContent.style.opacity = '0';
                toggleButton.classList.add('collapsed');
                
                // Save state to localStorage
                localStorage.setItem('recentPurchasesCollapsed', 'true');
            }
            
            // Toggle the state
            isCollapsed = !isCollapsed;
        }
        
        // Add click event listener to the toggle button
        toggleButton.addEventListener('click', toggleRecentPurchases);
        
        // Check if there's a saved state in localStorage
        const savedState = localStorage.getItem('recentPurchasesCollapsed');
        
        // Apply the saved state if it exists
        if (savedState === 'true') {
            // Trigger the toggle to collapse
            isCollapsed = false; // Set to false so the toggle function will collapse it
            toggleRecentPurchases();
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
