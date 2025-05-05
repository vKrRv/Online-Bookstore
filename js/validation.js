/**
 * Client-side validation script
 */

// Validator object
// This will contain all validation logic for different forms
const Validator = {
  // Form config
  forms: {
    contact: {
      formId: "contactForm",
      fields: {
        name: {
          required: true,
          errorMessage: "Please enter your name",
        },
        email: {
          required: true,
          validator: (value) => /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(value),
          errorMessage: "Please enter a valid email address",
        },
        message: {
          required: true,
          errorMessage: "Please enter your message",
        },
      },
    },
    signup: {
      formSelector: 'form[action="signup_process.php"]',
      fields: {
        name: {
          required: true,
          errorMessage: "Please enter your full name",
        },
        username: {
          required: true,
          errorMessage: "Please enter a username",
        },
        email: {
          required: true,
          validator: (value) => /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(value),
          errorMessage: "Please enter a valid email address",
        },
        password: {
          required: true,
          validator: (value) =>
            /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d).{8,}$/.test(value),
          errorMessage:
            "Password must be at least 8 characters with uppercase, lowercase, and numbers",
        },
        confirm_password: {
          required: true,
          validator: (value, allValues) => value === allValues["password"],
          errorMessage: "Passwords do not match",
        },
      },
    },
    login: {
      formSelector: 'form[action="login.php"]',
      fields: {
        username: {
          required: true,
          errorMessage: "Please enter your username",
        },
        password: {
          required: true,
          errorMessage: "Please enter your password",
        },
      },
    },
    checkout: {
      shipping: {
        formId: "shipping-form",
        fields: {
          ship_name: {
            required: true,
            errorMessage: "Please enter your full name",
          },
          ship_line1: {
            required: true,
            errorMessage: "Please enter your address",
          },
          ship_city: {
            required: true,
            errorMessage: "Please enter your city",
          },
          ship_postal: {
            required: true,
            validator: (value) => /^\d{5}$/.test(value),
            errorMessage: "Please enter a valid 5-digit postal code",
          },
          ship_phone: {
            required: true,
            validator: (value) => /^05\d{8}$/.test(value.replace(/\D/g, "")),
            errorMessage:
              "Please enter a valid Saudi phone number (05xxxxxxxx)",
          },
          ship_email: {
            required: true,
            validator: (value) => /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(value),
            errorMessage: "Please enter a valid email address",
          },
        },
      },
      payment: {
        formId: "payment-form",
        fields: {
          "card-number": {
            required: true,
            validator: (value) => {
              const cleaned = value.replace(/\D/g, "");
              return cleaned.length >= 13 && cleaned.length <= 19;
            },
            errorMessage: "Please enter a valid credit card number",
          },
          expire: {
            required: true,
            validator: function (value) {
              if (!/^\d{2}\/\d{2}$/.test(value)) return false;

              const parts = value.split("/");
              const month = parseInt(parts[0], 10);
              const year = parseInt("20" + parts[1], 10);

              if (month < 1 || month > 12) return false;

              const now = new Date();
              const currentMonth = now.getMonth() + 1;
              const currentYear = now.getFullYear();

              return !(
                year < currentYear ||
                (year === currentYear && month < currentMonth)
              );
            },
            errorMessage:
              "Please enter a valid expiry date in MM/YY format that is not expired",
          },
          cvv: {
            required: true,
            validator: (value) => /^\d{3,4}$/.test(value),
            errorMessage: "Please enter a valid CVV code",
          },
        },
      },
    },
    adminAddBook: {
      formSelector: 'form[action="dashboard.php"]',
      fields: {
        title: {
          required: true,
          validator: (value) => value.trim().length >= 2,
          errorMessage:
            "Please enter a valid book title (minimum 2 characters)",
        },
        description: {
          required: true,
          validator: (value) => value.trim().length >= 10,
          errorMessage:
            "Please enter a detailed description (minimum 10 characters)",
        },
        price: {
          required: true,
          validator: (value) => {
            const price = parseFloat(value);
            return !isNaN(price) && price >= 0;
          },
          errorMessage:
            "Please enter a valid price (must be a positive number)",
        },
        stock: {
          required: true,
          validator: (value) => {
            const stock = parseInt(value);
            return !isNaN(stock) && stock >= 0 && Number.isInteger(stock);
          },
          errorMessage:
            "Please enter a valid stock quantity (must be a whole number)",
        },
        category: {
          required: true,
          validator: (value) => value.trim().length >= 2,
          errorMessage: "Please enter a valid category",
        },
        image: {
          required: true,
          validator: function (value) {
            const fileInput = document.getElementById("image");
            if (!fileInput || !fileInput.files || fileInput.files.length === 0)
              return false;

            const file = fileInput.files[0];
            const validTypes = [
              "image/jpeg",
              "image/png",
              "image/jpg",
              "image/gif",
            ];
            if (!validTypes.includes(file.type)) return false;

            const maxSize = 5 * 1024 * 1024; // 5MB
            return file.size <= maxSize;
          },
          errorMessage:
            "Please select a valid image file (JPG, PNG, or GIF, max 5MB)",
        },
      },
    },
    adminEditBook: {
      formSelector: 'form[action="edit-book.php"]',
      fields: {
        title: {
          required: true,
          validator: (value) => value.trim().length >= 2,
          errorMessage:
            "Please enter a valid book title (minimum 2 characters)",
        },
        description: {
          required: true,
          validator: (value) => value.trim().length >= 10,
          errorMessage:
            "Please enter a detailed description (minimum 10 characters)",
        },
        price: {
          required: true,
          validator: (value) => {
            const price = parseFloat(value);
            return !isNaN(price) && price >= 0;
          },
          errorMessage:
            "Please enter a valid price (must be a positive number)",
        },
        stock: {
          required: true,
          validator: (value) => {
            const stock = parseInt(value);
            return !isNaN(stock) && stock >= 0 && Number.isInteger(stock);
          },
          errorMessage:
            "Please enter a valid stock quantity (must be a whole number)",
        },
        category: {
          required: true,
          validator: (value) => value.trim().length >= 2,
          errorMessage: "Please enter a valid category",
        },
        image: {
          required: false,
          validator: function (value) {
            const fileInput = document.getElementById("image");
            if (!fileInput || !fileInput.files || fileInput.files.length === 0)
              return true;

            const file = fileInput.files[0];
            const validTypes = [
              "image/jpeg",
              "image/png",
              "image/jpg",
              "image/gif",
            ];
            if (!validTypes.includes(file.type)) return false;

            const maxSize = 5 * 1024 * 1024; // 5MB
            return file.size <= maxSize;
          },
          errorMessage:
            "Please select a valid image file (JPG, PNG, or GIF, max 5MB)",
        },
      },
    },
  },

  // This function initializes the validation procecess
  initForm: function (formConfig) {
    // Find form elemnet
    const formElement = formConfig.formId
      ? document.getElementById(formConfig.formId)
      : document.querySelector(formConfig.formSelector);

    if (!formElement) return;

    // Set up fields and their HTML elements
    const fields = formConfig.fields || {};
    const fieldElements = {};

    // Find fields and setup event listeners
    for (const fieldName in fields) {
      const field =
        formElement.querySelector(`[name="${fieldName}"]`) ||
        formElement.querySelector(`#${fieldName}`);

      if (field) {
        fieldElements[fieldName] = field;

        // Input and blur events
        field.addEventListener("input", () =>
          this.validateField(field, fields[fieldName], getAllValues())
        );
        field.addEventListener("blur", () =>
          this.validateField(field, fields[fieldName], getAllValues())
        );
      }
    }

    // Get all values from form
    function getAllValues() {
      const values = {};
      for (const name in fieldElements) {
        values[name] = fieldElements[name].value;
      }
      return values;
    }

    // Form submission event
    formElement.addEventListener("submit", (e) => {
      let isValid = true;

      // Fields validation
      for (const fieldName in fields) {
        const field = fieldElements[fieldName];
        if (field) {
          const fieldIsValid = this.validateField(
            field,
            fields[fieldName],
            getAllValues()
          );
          isValid = isValid && fieldIsValid;
        }
      }

      if (!isValid) {
        e.preventDefault();

        // Scroll to first error
        const firstError = formElement.querySelector(".validation-error");
        if (firstError) {
          firstError.scrollIntoView({ behavior: "smooth", block: "center" });
        }
      }
    });
  },

  // Single field validation
  validateField: function (field, rules, allValues) {
    this.removeError(field);

    // Don't validate if field is not required and empty
    if (!rules.required && (!field.value || field.value.trim() === "")) {
      return true;
    }

    // Reqruied field check
    if (rules.required && (!field.value || field.value.trim() === "")) {
      this.showError(field, rules.errorMessage || "This field is required");
      return false;
    }

    // Check custom validator
    if (rules.validator && !rules.validator(field.value, allValues)) {
      this.showError(field, rules.errorMessage || "Invalid value");
      return false;
    }

    return true;
  },

  // Error message
  showError: function (field, message) {
    // Error element
    const errorElement = document.createElement("div");
    errorElement.className = "validation-error";
    errorElement.innerHTML = message;
    errorElement.style.color = "#e74c3c";
    errorElement.style.fontSize = "14px";
    errorElement.style.marginTop = "5px";

    // Add to field
    field.classList.add("error-field");
    field.style.borderColor = "#e74c3c";

    // Add to parent element
    field.parentElement.appendChild(errorElement);
  },

  // Remove error 
  removeError: function (field) {
    // Remove from field
    field.classList.remove("error-field");
    field.style.borderColor = "";

    // Remove message
    field.parentElement
      .querySelectorAll(".validation-error")
      .forEach((error) => error.remove());
  },

  // form init
  init: function () {
    // Contact 
    if (document.getElementById("contactForm")) {
      this.initForm(this.forms.contact);
    }

    // Signup 
    const signupForm = document.querySelector(
      'form[action="signup_process.php"]'
    );
    if (signupForm) {
      this.initForm(this.forms.signup);
    }

    // Login 
    const loginForm = document.querySelector('form[action="login.php"]');
    if (loginForm && !document.querySelector(".login-container")) {
      this.initForm(this.forms.login);
    }

    const adminLoginForm = document.querySelector(".login-form form");
    if (adminLoginForm && document.querySelector(".login-container")) {
      this.initForm(this.forms.login);
    }

    // Checkout 
    if (document.getElementById("shipping-form")) {
      this.initForm(this.forms.checkout.shipping);
    }

    if (document.getElementById("payment-form")) {
      this.initForm(this.forms.checkout.payment);
    }

    // Admin 
    const addBookForm = document.querySelector('form[action="dashboard.php"]');
    if (addBookForm) {
      this.initForm(this.forms.adminAddBook);
    }

    const editBookForm = document.querySelector('form[action="edit-book.php"]');
    if (editBookForm) {
      this.initForm(this.forms.adminEditBook);
    }
  },
};

// When DOM is loaded, init validator
document.addEventListener("DOMContentLoaded", function () {
  Validator.init();
});
