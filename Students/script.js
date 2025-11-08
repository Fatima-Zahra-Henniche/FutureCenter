document.addEventListener("DOMContentLoaded", function () {
  const form = document.getElementById("searchForm");
  const searchInput = document.getElementById("search");
  const niveauFilter = document.getElementById("niveau_filter");
  const exactMatch = document.getElementById("exact_match");
  const resultsDiv = document.getElementById("searchResults");

  // ✅ Switch between tabs
  window.showTab = function (event, tabId) {
    document
      .querySelectorAll(".tab-content")
      .forEach((tab) => tab.classList.remove("active"));
    document
      .querySelectorAll(".tab-btn")
      .forEach((btn) => btn.classList.remove("active"));
    document.getElementById(tabId).classList.add("active");
    event.currentTarget.classList.add("active");
  };

  // ✅ Load balances dynamically
  async function loadBalances() {
    const cells = document.querySelectorAll(".balance-cell");
    for (const cell of cells) {
      const id = cell.dataset.id;
      try {
        const res = await fetch(`Student_api.php?action=balance&id=${id}`);
        const data = await res.json();
        if (data.balance !== undefined) {
          const balance = Number(data.balance).toFixed(2);
          cell.innerHTML = `<strong>${balance} دج</strong>`;
          cell.style.color = balance < 0 ? "#e74c3c" : "#27ae60";
        } else {
          cell.innerHTML = "<strong>0.00 دج</strong>";
        }
      } catch {
        cell.innerHTML = '<span style="color:red">خطأ</span>';
      }
    }
  }

  // ✅ Perform AJAX search
  async function performSearch() {
    const formData = new FormData(form);
    const params = new URLSearchParams(formData);
    resultsDiv.innerHTML =
      '<div class="loading-msg"><i class="fas fa-spinner fa-spin"></i> جاري البحث...</div>';

    try {
      const res = await fetch("Search_student.php?" + params.toString());
      const html = await res.text();
      resultsDiv.innerHTML = html;
      await loadBalances();
    } catch (err) {
      resultsDiv.innerHTML =
        '<p style="color:red;text-align:center;">حدث خطأ أثناء تحميل البيانات</p>';
      console.error("Search error:", err);
    }
  }

  // ✅ Trigger on input change
  let timer;
  function delayedSearch() {
    clearTimeout(timer);
    timer = setTimeout(performSearch, 400);
  }

  searchInput.addEventListener("input", delayedSearch);
  niveauFilter.addEventListener("change", performSearch);
  exactMatch.addEventListener("change", performSearch);

  // ✅ Load initial data
  performSearch();
});
