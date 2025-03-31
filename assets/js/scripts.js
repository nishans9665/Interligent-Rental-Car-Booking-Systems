  // Add a scroll event listener to change the navbar style on scroll
  window.addEventListener('scroll', function() {
    const navbar = document.getElementById('navigation_bar');
    if (window.scrollY > 50) {
      navbar.classList.add('navbar-scrolled'); // Add a class when scrolled
    } else {
      navbar.classList.remove('navbar-scrolled'); // Remove the class when at the top
    }
  });

  

  document.addEventListener('DOMContentLoaded', function () {
    const searchInput = document.getElementById('search-input');
    const searchResults = document.getElementById('search-results');
  
    searchInput.addEventListener('input', function () {
      const query = this.value.trim();
  
      if (query.length > 2) { // Only search if the query has at least 3 characters
        fetchSearchResults(query);
      } else {
        searchResults.innerHTML = ''; // Clear results if the query is too short
        searchResults.style.display = 'none';
      }
    });
  
    function fetchSearchResults(query) {
      fetch(`search-script.php?query=${encodeURIComponent(query)}`)
        .then(response => response.json())
        .then(data => {
          if (data.length > 0) {
            const resultsHTML = data.map(item => `
              <li onclick="selectResult('${item.name}')">
                ${item.name}
              </li>
            `).join('');
            searchResults.innerHTML = `<ul>${resultsHTML}</ul>`;
            searchResults.style.display = 'block';
          } else {
            searchResults.innerHTML = '<ul><li>No results found</li></ul>';
            searchResults.style.display = 'block';
          }
        })
        .catch(error => {
          console.error('Error fetching search results:', error);
        });
    }
  
    // Function to handle selection of a result
    window.selectResult = function (selectedText) {
      searchInput.value = selectedText;
      searchResults.innerHTML = '';
      searchResults.style.display = 'none';
    };
  
    // Hide results when clicking outside
    document.addEventListener('click', function (event) {
      if (!searchResults.contains(event.target) && event.target !== searchInput) {
        searchResults.style.display = 'none';
      }
    });
  });