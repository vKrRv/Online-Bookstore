
    // Delete button 
    document.querySelectorAll('.delete-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            if (confirm('Are you sure you want to delete this item?')) {
                const row = this.closest('tr');
                row.remove();
            }
        });
    });

    // edit button
    document.querySelectorAll('.edit-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            const row = this.closest('tr');
            const title = row.children[0].textContent.trim();
            const author = row.children[1].textContent.trim();
            const price = row.children[2].textContent.trim().replace('', '').trim(); // رمز العملة

            // Find the form and populate it with the row data
            const form = document.querySelector('.admin-form');
            form.querySelector('input[placeholder="Book Title"]').value = title;
            form.querySelector('input[placeholder="Author"]').value = author;
            form.querySelector('input[placeholder="Price (e.g., 49.99)"]').value = price;

            // change the button text to "Update Book"
            const submitButton = form.querySelector('button[type="submit"]');
            submitButton.textContent = "Update Book";

            // update the click handler to update instead of adding a new book
            submitButton.onclick = function(e) {
                e.preventDefault();
                row.children[0].textContent = form.querySelector('input[placeholder="Book Title"]').value;
                row.children[1].textContent = form.querySelector('input[placeholder="Author"]').value;
                row.children[2].innerHTML = `<span class="symbol">&#xea;</span> ${form.querySelector('input[placeholder="Price (e.g., 49.99)"]').value}`;
                submitButton.textContent = "Add Book";
                form.reset();
                submitButton.onclick = null; 
            }
        });
    });

