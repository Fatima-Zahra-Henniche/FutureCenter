document.addEventListener("DOMContentLoaded", () => {
  const API_URL = "groups_api.php";

  const tabs = document.querySelectorAll(".tab-button");
  const contents = document.querySelectorAll(".tab-content");

  tabs.forEach((tab) => {
    tab.addEventListener("click", () => {
      tabs.forEach((t) => t.classList.remove("active"));
      contents.forEach((c) => c.classList.remove("active"));
      tab.classList.add("active");
      document.getElementById(tab.dataset.tab).classList.add("active");
    });
  });

  // 🔹 Load groups
  async function loadGroups() {
    const res = await fetch(`${API_URL}?action=list`);
    const data = await res.json();
    const tbody = document.querySelector("#groups-table tbody");
    tbody.innerHTML = "";

    data.forEach((g) => {
      const row = document.createElement("tr");
      row.innerHTML = `
        <td>${g.id}</td>
        <td>${g.nom}</td>
        <td>${parseFloat(g.prix_seance).toFixed(2)} DA</td>
        <td>${g.capacite_max}</td>
        <td>0</td>
        <td><button class="edit-btn" data-id="${g.id}">تعديل</button></td>
        <td><button class="delete-btn" data-id="${g.id}">حذف</button></td>
      `;
      tbody.appendChild(row);
    });
  }

  loadGroups();

  // 🔹 Add new group
  document.getElementById("add-btn").addEventListener("click", async () => {
    const nom = document.getElementById("nom").value.trim();
    const prix = parseFloat(document.getElementById("prix").value.trim());
    const capacite = parseInt(document.getElementById("capacite").value.trim());

    if (!nom || isNaN(prix)) {
      alert("اسم الفوج وسعر الحصة مطلوبان!");
      return;
    }

    await fetch(`${API_URL}?action=add`, {
      method: "POST",
      headers: { "Content-Type": "application/json" },
      body: JSON.stringify({ nom, prix, capacite }),
    });

    alert("✅ تم إنشاء الفوج بنجاح!");
    document.getElementById("nom").value = "";
    document.getElementById("prix").value = "";
    document.getElementById("capacite").value = "20";
    loadGroups();
  });

  // 🔹 Delete
  document.addEventListener("click", async (e) => {
    if (e.target.classList.contains("delete-btn")) {
      if (confirm("هل أنت متأكد من حذف هذا الفوج؟")) {
        const id = e.target.dataset.id;
        await fetch(`${API_URL}?action=delete`, {
          method: "POST",
          headers: { "Content-Type": "application/json" },
          body: JSON.stringify({ id }),
        });
        loadGroups();
      }
    }
  });
});
