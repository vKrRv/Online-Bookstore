// ===============================
// Help Popup Script for product-details.php
// ===============================

document.addEventListener("DOMContentLoaded", () => {
  const helpBtn = document.getElementById("helpBtn");
  const popup = document.getElementById("helpPopup");
  const closeBtn = document.getElementById("closePopup");
  const faqQuestions = document.querySelectorAll(".faq-question");

  if (helpBtn && popup && closeBtn) {
    helpBtn.addEventListener("click", () => {
      popup.style.display = "block";
    });

    closeBtn.addEventListener("click", () => {
      popup.style.display = "none";
    });

    window.addEventListener("click", (e) => {
      if (e.target === popup) {
        popup.style.display = "none";
      }
    });

    faqQuestions.forEach((question) => {
      question.addEventListener("click", () => {
        const answer = question.nextElementSibling;
        const isVisible = answer.style.display === "block";

        document
          .querySelectorAll(".faq-answer")
          .forEach((ans) => (ans.style.display = "none"));

        if (!isVisible) {
          answer.style.display = "block";
        }
      });
    });
  }
});
