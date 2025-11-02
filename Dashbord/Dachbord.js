// dashboard.js
// Assumes the server exposes 3 endpoints that return JSON:
// 1) api/dashboard_stats.php -> { students: 123, groups: 12, payments_sum: 456.7 (optional) }
// 2) api/recent_students.php -> [ { id_etudiant, nom, prenom, niveau, date_inscription }, ... ]
// 3) api/active_groups.php -> [ { nom, students_count, prix_seance }, ... ]

const studentsCountEl = document.getElementById("students-count");
const groupsCountEl = document.getElementById("groups-count");
// const paymentsSumEl   = document.getElementById('payments-sum'); // optional
const recentBodyEl = document.getElementById("recent-students-body");
const groupsBodyEl = document.getElementById("active-groups-body");
const lastUpdatedEl = document.getElementById("last-updated");

async function fetchJson(url) {
  try {
    const res = await fetch(url, { cache: "no-store" });
    if (!res.ok) throw new Error(`HTTP ${res.status}`);
    return await res.json();
  } catch (err) {
    console.error("Fetch error:", url, err);
    return null;
  }
}

function formatDate(dstr) {
  if (!dstr) return "";
  // accept YYYY-MM-DD or ISO; show in dd/mm/yyyy
  const d = new Date(dstr);
  if (isNaN(d)) return dstr;
  const day = String(d.getDate()).padStart(2, "0");
  const month = String(d.getMonth() + 1).padStart(2, "0");
  const year = d.getFullYear();
  return `${day}/${month}/${year}`;
}

function clearChildren(el) {
  while (el.firstChild) el.removeChild(el.firstChild);
}

async function loadStats() {
  const data = await fetchJson("api/dashboard_stats.php");
  if (!data) return;
  studentsCountEl.textContent = data.students ?? "0";
  groupsCountEl.textContent = data.groups ?? "0";
  // if (data.payments_sum !== undefined) paymentsSumEl.textContent = Number(data.payments_sum).toLocaleString();
  updateLastUpdated();
}

async function loadRecentStudents() {
  const rows = await fetchJson("api/recent_students.php");
  if (!rows) return;
  clearChildren(recentBodyEl);
  rows.forEach((r) => {
    const tr = document.createElement("tr");
    const idTd = document.createElement("td");
    idTd.textContent = r.id_etudiant ?? "";
    const nomTd = document.createElement("td");
    nomTd.textContent = r.nom ?? "";
    const prenomTd = document.createElement("td");
    prenomTd.textContent = r.prenom ?? "";
    const niveauTd = document.createElement("td");
    niveauTd.textContent = r.niveau ?? "";
    const dateTd = document.createElement("td");
    dateTd.textContent = formatDate(r.date_inscription ?? "");
    tr.appendChild(idTd);
    tr.appendChild(nomTd);
    tr.appendChild(prenomTd);
    tr.appendChild(niveauTd);
    tr.appendChild(dateTd);
    recentBodyEl.appendChild(tr);
  });
}

async function loadActiveGroups() {
  const rows = await fetchJson("api/active_groups.php");
  if (!rows) return;
  clearChildren(groupsBodyEl);
  rows.forEach((g) => {
    const tr = document.createElement("tr");
    const nomTd = document.createElement("td");
    nomTd.textContent = g.nom ?? "";
    const countTd = document.createElement("td");
    countTd.textContent = g.students_count ?? 0;
    const prixTd = document.createElement("td");
    prixTd.textContent =
      g.prix_seance !== undefined
        ? `${Number(g.prix_seance).toLocaleString()} DA`
        : "0.00 DA";
    tr.appendChild(nomTd);
    tr.appendChild(countTd);
    tr.appendChild(prixTd);
    groupsBodyEl.appendChild(tr);
  });
}

function updateLastUpdated() {
  const now = new Date();
  lastUpdatedEl.textContent = now.toLocaleString();
}

async function refreshAll() {
  await Promise.all([loadStats(), loadRecentStudents(), loadActiveGroups()]);
}

// initial load
refreshAll();

// optionally refresh every N seconds (uncomment if you want auto-refresh)
setInterval(refreshAll, 240_000); // every 240s
