document.addEventListener("DOMContentLoaded", function () {
  loadStudents();

  document.getElementById("search").addEventListener("input", loadStudents);
  document
    .getElementById("assignBtn")
    .addEventListener("click", assignStudents);
  document
    .getElementById("unassignBtn")
    .addEventListener("click", unassignStudents);
});

let selectedStudents = new Set();

function loadStudents() {
  const keyword = document.getElementById("search").value;
  fetch("Search_student.php?search=" + encodeURIComponent(keyword))
    .then((res) => res.text())
    .then((html) => {
      document.getElementById("students").innerHTML = html;
      attachSelectionEvents();
    });
}

function attachSelectionEvents() {
  document.querySelectorAll(".student-card").forEach((card) => {
    card.addEventListener("click", () => {
      const id = card.dataset.studentId;
      if (selectedStudents.has(id)) {
        selectedStudents.delete(id);
        card.classList.remove("selected");
      } else {
        selectedStudents.add(id);
        card.classList.add("selected");
      }
    });
  });
}

function getSelectedIds() {
  return Array.from(selectedStudents);
}

function assignStudents() {
  const ids = getSelectedIds();
  const groupId = document.getElementById("group").value;
  if (ids.length === 0) return alert("اختر طالب واحد على الأقل");

  fetch("assign.php", {
    method: "POST",
    headers: { "Content-Type": "application/json" },
    body: JSON.stringify({ ids, groupId }),
  })
    .then((res) => res.text())
    .then((msg) => {
      alert(msg);
      selectedStudents.clear();
      loadStudents();
    });
}

function unassignStudents() {
  const ids = getSelectedIds();
  const groupId = document.getElementById("group").value;
  if (ids.length === 0) return alert("اختر طالب واحد على الأقل");

  fetch("unassign.php", {
    method: "POST",
    headers: { "Content-Type": "application/json" },
    body: JSON.stringify({ ids, groupId }),
  })
    .then((res) => res.text())
    .then((msg) => {
      alert(msg);
      selectedStudents.clear();
      loadStudents();
    });
}
