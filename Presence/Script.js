document.addEventListener("DOMContentLoaded", function () {
  loadGroups();

  document.getElementById("group").addEventListener("change", loadStudents);
  document.getElementById("saveBtn").addEventListener("click", savePresence);
});

function loadGroups() {
  fetch("loadGroups.php")
    .then((res) => res.json())
    .then((groups) => {
      const select = document.getElementById("group");
      select.innerHTML = "";

      // Add default option
      const defaultOpt = document.createElement("option");
      defaultOpt.value = "";
      defaultOpt.textContent = "اختر الفوج";
      defaultOpt.disabled = true;
      defaultOpt.selected = true;
      select.appendChild(defaultOpt);

      groups.forEach((g) => {
        const opt = document.createElement("option");
        opt.value = g.id;
        opt.textContent = g.nom;
        select.appendChild(opt);
      });
    })
    .catch((error) => {
      console.error("Error loading groups:", error);
      alert("حدث خطأ في تحميل الأفواج");
    });
}

function loadStudents() {
  const gid = document.getElementById("group").value;

  if (!gid) {
    document.getElementById("students").innerHTML =
      "<p>يرجى اختيار الفوج أولاً</p>";
    return;
  }

  fetch("loadStudents.php?gid=" + gid)
    .then((res) => {
      if (!res.ok) {
        throw new Error("Network response was not ok");
      }
      return res.json();
    })
    .then((students) => {
      const container = document.getElementById("students");

      if (students.length === 0) {
        container.innerHTML = "<p>لا يوجد طلاب في هذا الفوج</p>";
        return;
      }

      container.innerHTML = "";
      students.forEach((s) => {
        const studentDiv = document.createElement("div");
        studentDiv.className = "student-item";
        studentDiv.innerHTML = `
          <div class="student-info">
            <div class="student-avatar">${s.nom.charAt(0)}</div>
            <div class="student-details">
              <div class="student-name">${s.nom} ${s.prenom}</div>
              <div class="student-id">${s.id}</div>
            </div>
          </div>
          <div class="attendance-controls">
            <button class="attendance-btn btn-present" data-student-id="${
              s.id
            }">
              <i class="fas fa-check"></i> حاضر
            </button>
            <button class="attendance-btn btn-absent active" data-student-id="${
              s.id
            }">
              <i class="fas fa-times"></i> غائب
            </button>
          </div>
        `;
        container.appendChild(studentDiv);
      });

      // Initialize attendance data and stats
      initializeAttendanceData(students);
    })
    .catch((error) => {
      console.error("Error loading students:", error);
      document.getElementById("students").innerHTML =
        "<p>حدث خطأ في تحميل الطلاب</p>";
    });
}

function initializeAttendanceData(students) {
  const attendanceData = {};
  students.forEach((student) => {
    attendanceData[student.id] = "absent";
  });

  // Store in global variable or data attribute
  document.getElementById("students").dataset.attendance =
    JSON.stringify(attendanceData);
  updateSummaryStats();
}

function updateSummaryStats() {
  const attendanceData = JSON.parse(
    document.getElementById("students").dataset.attendance || "{}"
  );
  const total = Object.keys(attendanceData).length;
  const present = Object.values(attendanceData).filter(
    (status) => status === "present"
  ).length;
  const absent = total - present;
  const rate = total > 0 ? Math.round((present / total) * 100) : 0;

  document.getElementById("total-students").textContent = total;
  document.getElementById("present-count").textContent = present;
  document.getElementById("absent-count").textContent = absent;
  document.getElementById("attendance-rate").textContent = rate + "%";
}

// Add event delegation for attendance buttons
document.addEventListener("click", function (e) {
  if (e.target.closest(".attendance-btn")) {
    const btn = e.target.closest(".attendance-btn");
    const studentId = btn.dataset.studentId;
    const isPresent = btn.classList.contains("btn-present");

    // Update button states
    const parent = btn.parentElement;
    parent.querySelector(".btn-present").classList.remove("active");
    parent.querySelector(".btn-absent").classList.remove("active");
    btn.classList.add("active");

    // Update attendance data
    const attendanceData = JSON.parse(
      document.getElementById("students").dataset.attendance || "{}"
    );
    attendanceData[studentId] = isPresent ? "present" : "absent";
    document.getElementById("students").dataset.attendance =
      JSON.stringify(attendanceData);

    updateSummaryStats();
  }
});

function savePresence() {
  const gid = document.getElementById("group").value;
  const date = document.getElementById("date").value;
  const timeStart = document.getElementById("time_start").value;
  const timeEnd = document.getElementById("time_end").value;

  if (!gid || !date) {
    alert("يرجى ملء جميع الحقول المطلوبة");
    return;
  }

  const attendanceData = JSON.parse(
    document.getElementById("students").dataset.attendance || "{}"
  );
  const checked = Object.keys(attendanceData).filter(
    (id) => attendanceData[id] === "present"
  );

  fetch("SavePresence.php", {
    method: "POST",
    headers: { "Content-Type": "application/json" },
    body: JSON.stringify({ gid, date, timeStart, timeEnd, checked }),
  })
    .then((res) => {
      if (!res.ok) {
        throw new Error("Network response was not ok");
      }
      return res.json();
    })
    .then((report) => {
      // Check if response is an error
      if (report.error) {
        alert("خطأ: " + report.error);
        return;
      }

      const tbody = document.querySelector("#report tbody");
      tbody.innerHTML = "";
      report.forEach((r) => {
        const statusClass =
          r.status === "Present" ? "status-present" : "status-absent";
        const statusText = r.status === "Present" ? "حاضر" : "غائب";
        const beforeClass =
          r.before >= 0 ? "balance-positive" : "balance-negative";
        const afterClass =
          r.after >= 0 ? "balance-positive" : "balance-negative";

        tbody.innerHTML += `
          <tr>
            <td>${r.nom}</td>
            <td>${r.prenom}</td>
            <td class="${statusClass}">${statusText}</td>
            <td class="${beforeClass}">${r.before.toFixed(2)} DA</td>
            <td class="${afterClass}">${r.after.toFixed(2)} DA</td>
          </tr>
        `;
      });
      alert("تم حفظ الحضور وخصم السعر بنجاح");
      // Reload students to reset the interface
      loadStudents();
    })
    .catch((error) => {
      console.error("Error saving presence:", error);
      alert("حدث خطأ في حفظ البيانات: " + error.message);
    });
}
