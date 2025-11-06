// Report.js

document.addEventListener("DOMContentLoaded", () => {
  const search = document.getElementById("search");
  const exactMatch = document.getElementById("exactMatch");
  const filterBtn = document.getElementById("filterBtn");

  // Set default dates (last month → today)
  const dateFrom = document.getElementById("dateFrom");
  const dateTo = document.getElementById("dateTo");
  const today = new Date().toISOString().split("T")[0];
  const lastMonth = new Date();
  lastMonth.setMonth(lastMonth.getMonth() - 1);
  dateFrom.value = lastMonth.toISOString().split("T")[0];
  dateTo.value = today;

  // 🧭 Tabs
  document.querySelectorAll(".tab").forEach((tab) => {
    tab.addEventListener("click", () => {
      document
        .querySelectorAll(".tab")
        .forEach((t) => t.classList.remove("active"));
      document
        .querySelectorAll(".tab-content")
        .forEach((c) => c.classList.remove("active"));
      tab.classList.add("active");
      document.getElementById(tab.dataset.tab).classList.add("active");
    });
  });

  // 🔄 Load data
  function loadReports() {
    const params = new URLSearchParams({
      from: dateFrom.value,
      to: dateTo.value,
      search: search.value.trim(),
      exact: exactMatch.checked ? "true" : "false",
    });

    fetch("load_Reports.php?" + params.toString())
      .then((res) => res.json())
      .then((data) => {
        fillTable("attendanceTable", data.attendance);
        fillTable("paymentsTable", data.payments);
        fillTable("studentsTable", data.students);
      });
  }

  // 📊 Fill any table
  function fillTable(tableId, rows) {
    const tbody = document.querySelector(`#${tableId} tbody`);
    tbody.innerHTML = "";
    if (!rows || rows.length === 0) {
      tbody.innerHTML = `<tr><td colspan="10">لا توجد بيانات</td></tr>`;
      return;
    }

    rows.forEach((row) => {
      const tr = document.createElement("tr");
      Object.values(row).forEach((value) => {
        const td = document.createElement("td");
        td.textContent = value ?? "";
        if (tableId === "studentsTable" && row.balance < 0)
          td.classList.add("negative");
        if (tableId === "studentsTable" && row.balance >= 0)
          td.classList.add("positive");
        if (tableId === "attendanceTable" && row.statut === "Present")
          td.style.color = "green";
        if (tableId === "attendanceTable" && row.statut === "Absent")
          td.style.color = "red";
        tr.appendChild(td);
      });
      tbody.appendChild(tr);
    });
  }

  // Search debounce
  let timer;
  search.addEventListener("input", () => {
    clearTimeout(timer);
    timer = setTimeout(loadReports, 500);
  });

  exactMatch.addEventListener("change", loadReports);
  filterBtn.addEventListener("click", loadReports);

  loadReports();
});
