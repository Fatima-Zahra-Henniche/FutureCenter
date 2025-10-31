document.addEventListener("DOMContentLoaded", function () {
  loadGroups();

  document.getElementById("group").addEventListener("change", loadStudents);
  document.getElementById("saveBtn").addEventListener("click", savePresence);
});

function loadGroups() {
  fetch("load_groups.php")
    .then((res) => res.json())
    .then((groups) => {
      const select = document.getElementById("group");
      select.innerHTML = "";
      groups.forEach((g) => {
        const opt = document.createElement("option");
        opt.value = g.id;
        opt.textContent = g.nom;
        select.appendChild(opt);
      });
      if (groups.length) loadStudents();
    });
}

function loadStudents() {
  const gid = document.getElementById("group").value;
  fetch("load_students.php?gid=" + gid)
    .then((res) => res.json())
    .then((students) => {
      const div = document.getElementById("students");
      div.innerHTML = "";
      students.forEach((s) => {
        const el = document.createElement("div");
        el.className = "student";
        el.innerHTML = `
          <input type="checkbox" value="${s.id}">
          ${s.nom} ${s.prenom}
        `;
        div.appendChild(el);
      });
    });
}

function savePresence() {
  const gid = document.getElementById("group").value;
  const date = document.getElementById("date").value;
  const timeStart = document.getElementById("time_start").value;
  const timeEnd = document.getElementById("time_end").value;

  const checked = Array.from(
    document.querySelectorAll("#students input:checked")
  ).map((c) => c.value);

  fetch("save_presence.php", {
    method: "POST",
    headers: { "Content-Type": "application/json" },
    body: JSON.stringify({ gid, date, timeStart, timeEnd, checked }),
  })
    .then((res) => res.json())
    .then((report) => {
      const tbody = document.querySelector("#report tbody");
      tbody.innerHTML = "";
      report.forEach((r) => {
        tbody.innerHTML += `
        <tr>
          <td>${r.nom}</td>
          <td>${r.prenom}</td>
          <td>${r.status}</td>
          <td>${r.before.toFixed(2)} DA</td>
          <td>${r.after.toFixed(2)} DA</td>
        </tr>
      `;
      });
      alert("تم حفظ الحضور وخصم السعر بنجاح");
    });
}
