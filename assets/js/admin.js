document.addEventListener("DOMContentLoaded", () => {
    // Toggle Sidebar Menu
    const sidebarToggle = document.querySelector(".sidebar-toggle");
    const sidebar = document.querySelector(".dashboard-sidebar");

    if (sidebarToggle && sidebar) {
        sidebarToggle.addEventListener("click", () => {
            sidebar.classList.toggle("collapsed");
        });
    }

    // Confirm Delete Action
    const deleteButtons = document.querySelectorAll(".btn-delete");
    deleteButtons.forEach((button) => {
        button.addEventListener("click", (event) => {
            const confirmation = confirm("Are you sure you want to delete this item?");
            if (!confirmation) {
                event.preventDefault();
            }
        });
    });

    // Manage Request Status
    const requestActions = document.querySelectorAll(".request-action");
    requestActions.forEach((action) => {
        action.addEventListener("click", (event) => {
            const confirmation = confirm(`Are you sure you want to ${action.dataset.action} this request?`);
            if (!confirmation) {
                event.preventDefault();
            }
        });
    });

    // AJAX Search for Products or Users
    const searchInput = document.querySelector("#search-input");
    const searchResultsContainer = document.querySelector("#search-results");

    if (searchInput && searchResultsContainer) {
        searchInput.addEventListener("input", () => {
            const query = searchInput.value.trim();
            const endpoint = searchInput.dataset.endpoint; // Example: /php/admin/search_products.php

            if (query.length > 2) {
                fetch(`${endpoint}?query=${encodeURIComponent(query)}`)
                    .then((response) => response.json())
                    .then((data) => {
                        renderSearchResults(data, searchResultsContainer);
                    })
                    .catch((error) => {
                        console.error("Error fetching search results:", error);
                    });
            } else {
                searchResultsContainer.innerHTML = "";
            }
        });
    }

    function renderSearchResults(data, container) {
        container.innerHTML = "";
        if (data.length > 0) {
            data.forEach((item) => {
                const itemElement = document.createElement("div");
                itemElement.classList.add("search-result-item");
                itemElement.textContent = item.name; // Customize based on API response
                container.appendChild(itemElement);
            });
        } else {
            container.innerHTML = "<p>No results found.</p>";
        }
    }

    // Show/Hide Branding Logo Preview
    const brandingLogoInput = document.querySelector("#branding-logo");
    const brandingLogoPreview = document.querySelector("#branding-logo-preview");

    if (brandingLogoInput && brandingLogoPreview) {
        brandingLogoInput.addEventListener("change", (event) => {
            const file = event.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = () => {
                    brandingLogoPreview.src = reader.result;
                };
                reader.readAsDataURL(file);
            }
        });
    }
});

document.addEventListener("DOMContentLoaded", () => {
    const sidebarToggle = document.querySelector(".sidebar-toggle");
    const sidebar = document.querySelector(".dashboard-sidebar");

    if (sidebarToggle && sidebar) {
        sidebarToggle.addEventListener("click", () => {
            sidebar.classList.toggle("collapsed");
        });
    }
});
