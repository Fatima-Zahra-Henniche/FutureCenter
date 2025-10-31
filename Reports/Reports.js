class ReportsPage {
  constructor() {
    this.searchTimer = null;
    this.init();
  }

  init() {
    this.setDefaultDates();
    this.bindEvents();
    this.loadReports();
  }

  setDefaultDates() {
    const today = new Date();
    const lastMonth = new Date();
    lastMonth.setMonth(today.getMonth() - 1);

    document.getElementById("date-from").value = this.formatDate(lastMonth);
    document.getElementById("date-to").value = this.formatDate(today);
  }

  formatDate(date) {
    return date.toISOString().split("T")[0];
  }

  bindEvents() {
    // Tab switching
    document.querySelectorAll(".tab-header").forEach((tab) => {
      tab.addEventListener("click", () => this.switchTab(tab.dataset.tab));
    });

    // Filter button
    document.getElementById("filter-btn").addEventListener("click", () => {
      this.loadReports();
    });

    // Search input with debounce
    document.getElementById("search-input").addEventListener("input", () => {
      this.debounceSearch();
    });

    // Exact match checkbox
    document.getElementById("exact-match").addEventListener("change", () => {
      this.debounceSearch();
    });
  }

  switchTab(tabName) {
    // Update tab headers
    document.querySelectorAll(".tab-header").forEach((tab) => {
      tab.classList.remove("active");
    });
    document.querySelector(`[data-tab="${tabName}"]`).classList.add("active");

    // Update tab content
    document.querySelectorAll(".tab-content").forEach((content) => {
      content.classList.remove("active");
    });
    document.getElementById(`${tabName}-tab`).classList.add("active");
  }

  debounceSearch() {
    clearTimeout(this.searchTimer);
    this.searchTimer = setTimeout(() => {
      this.searchStudentReports();
    }, 500);
  }

  async loadReports() {
    const dateFrom = document.getElementById("date-from").value;
    const dateTo = document.getElementById("date-to").value;

    try {
      // Simulate API calls - replace with actual API endpoints
      const [attendanceData, paymentsData, studentsData] = await Promise.all([
        this.fetchAttendanceData(dateFrom, dateTo),
        this.fetchPaymentsData(dateFrom, dateTo),
        this.fetchStudentsData(),
      ]);

      this.fillTables(attendanceData, paymentsData, studentsData);
      this.updateTabTitles(attendanceData, paymentsData, studentsData);
    } catch (error) {
      console.error("Error loading reports:", error);
      this.showError("حدث خطأ في تحميل البيانات");
    }
  }

  async searchStudentReports() {
    const searchText = document.getElementById("search-input").value.trim();
    const exactMatch = document.getElementById("exact-match").checked;
    const dateFrom = document.getElementById("date-from").value;
    const dateTo = document.getElementById("date-to").value;

    if (!searchText) {
      this.loadReports();
      return;
    }

    try {
      // Simulate search API calls
      const [attendanceData, paymentsData, studentsData] = await Promise.all([
        this.fetchAttendanceData(dateFrom, dateTo, searchText, exactMatch),
        this.fetchPaymentsData(dateFrom, dateTo, searchText, exactMatch),
        this.fetchStudentsData(searchText, exactMatch),
      ]);

      this.fillTables(attendanceData, paymentsData, studentsData);
      this.updateTabTitles(attendanceData, paymentsData, studentsData);
    } catch (error) {
      console.error("Error searching reports:", error);
      this.showError("حدث خطأ في البحث");
    }
  }

  // Mock data functions - replace with actual API calls
  async fetchAttendanceData(
    dateFrom,
    dateTo,
    searchText = "",
    exactMatch = false
  ) {
    const url = `get_report.php?from=${dateFrom}&to=${dateTo}&search=${encodeURIComponent(
      searchText
    )}&exact=${exactMatch}`;
    const response = await fetch(url);
    const data = await response.json();
    return data.attendance;
  }

  async fetchPaymentsData(
    dateFrom,
    dateTo,
    searchText = "",
    exactMatch = false
  ) {
    const url = `get_report.php?from=${dateFrom}&to=${dateTo}&search=${encodeURIComponent(
      searchText
    )}&exact=${exactMatch}`;
    const response = await fetch(url);
    const data = await response.json();
    return data.payments;
  }

  async fetchStudentsData(searchText = "", exactMatch = false) {
    const url = `get_report.php?search=${encodeURIComponent(
      searchText
    )}&exact=${exactMatch}`;
    const response = await fetch(url);
    const data = await response.json();
    return data.students;
  }

  fillTables(attendanceData, paymentsData, studentsData) {
    this.fillTable("attendance-table", attendanceData, 6); // status column index
    this.fillTable("payments-table", paymentsData);
    this.fillTable("students-table", studentsData, 6); // balance column index
  }

  fillTable(tableId, data, statusCol = null, balanceCol = null) {
    const table = document.getElementById(tableId);
    const tbody = table.querySelector("tbody");
    tbody.innerHTML = "";

    data.forEach((rowData) => {
      const row = document.createElement("tr");

      rowData.forEach((value, colIndex) => {
        const cell = document.createElement("td");
        cell.textContent = value !== null ? value : "";
        cell.style.textAlign = "center";

        // Apply status colors
        if (statusCol !== null && colIndex === statusCol) {
          if (value === "Present") {
            cell.className = "status-present";
          } else if (value === "Absent") {
            cell.className = "status-absent";
          }
        }

        // Apply balance colors
        if (balanceCol !== null && colIndex === balanceCol) {
          const balance = parseFloat(value);
          if (balance < 0) {
            cell.className = "balance-negative";
          }
        }

        row.appendChild(cell);
      });

      tbody.appendChild(row);
    });
  }

  updateTabTitles(attendance, payments, students) {
    document.querySelector(
      '[data-tab="attendance"]'
    ).textContent = `تقرير الحضور (${attendance.length})`;
    document.querySelector(
      '[data-tab="payments"]'
    ).textContent = `تقرير المدفوعات (${payments.length})`;
    document.querySelector(
      '[data-tab="students"]'
    ).textContent = `تقرير الطلاب (${students.length})`;
  }

  showError(message) {
    // Simple error display - you might want to use a more sophisticated notification system
    alert(message);
  }
}

// Initialize the reports page when DOM is loaded
document.addEventListener("DOMContentLoaded", () => {
  new ReportsPage();
});
