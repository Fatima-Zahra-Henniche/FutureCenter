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

function loadStudents() {
  const keyword = document.getElementById("search").value;
  fetch("load_students.php?search=" + keyword)
    .then((res) => res.text())
    .then((html) => (document.getElementById("students").innerHTML = html));
}

function getSelectedIds() {
  return Array.from(document.querySelectorAll(".student input:checked")).map(
    (c) => c.value
  );
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
      loadStudents();
    });
}
