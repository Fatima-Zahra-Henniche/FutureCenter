document.addEventListener("DOMContentLoaded", () => {
  const tbody = document.querySelector("#groups-table tbody");
  const modal = document.getElementById("editModal");

  // Load groups
  async function loadGroups() {
    const data = await fetchJson("Groups_api.php?action=list");
    if (!data) return;
    tbody.innerHTML = "";

    data.forEach((g) => {
      const tr = document.createElement("tr");
      tr.innerHTML = `
        <td>${g.id}</td>
        <td>${g.nom}</td>
        <td>${g.prix_seance} دج</td>
        <td>${g.capacite_max}</td>
        <td>${g.nombre_etudiants || 0}</td>
        <td><button class="edit-btn" data-id="${g.id}">تعديل</button></td>
        <td><button class="delete-btn" data-id="${g.id}">حذف</button></td>
      `;
      tbody.appendChild(tr);
    });
  }

  loadGroups();

  // Add new group
  document.getElementById("add-btn").addEventListener("click", async () => {
    const nom = document.getElementById("nom").value.trim();
    const prix = document.getElementById("prix").value.trim();
    const capacite = document.getElementById("capacite").value.trim();

    if (!nom || prix === "") return alert("⚠️ أدخل اسم الفوج وسعر الحصة.");

    await fetchJson("Groups_api.php?action=add", {
      method: "POST",
      headers: { "Content-Type": "application/json" },
      body: JSON.stringify({ nom, prix, capacite }),
    });

    alert("✅ تم إنشاء الفوج بنجاح!");
    loadGroups();
  });

  let currentId = null;

  // Open edit modal
  document.addEventListener("click", (e) => {
    if (e.target.classList.contains("edit-btn")) {
      const tr = e.target.closest("tr");
      currentId = e.target.dataset.id;
      document.getElementById("editNom").value = tr.children[1].textContent;
      document.getElementById("editPrix").value = parseFloat(
        tr.children[2].textContent
      );
      document.getElementById("editCapacite").value = parseInt(
        tr.children[3].textContent
      );
      modal.style.display = "flex";
    }
  });

  // Save edit
  document.getElementById("saveEdit").addEventListener("click", async () => {
    const nom = document.getElementById("editNom").value.trim();
    const prix = parseFloat(document.getElementById("editPrix").value);
    const capacite = parseInt(document.getElementById("editCapacite").value);

    if (!nom || isNaN(prix)) return alert("⚠️ يرجى إدخال البيانات بشكل صحيح!");

    await fetchJson("Groups_api.php?action=edit", {
      method: "POST",
      headers: { "Content-Type": "application/json" },
      body: JSON.stringify({ id: currentId, nom, prix, capacite }),
    });

    modal.style.display = "none";
    alert("✅ تم التعديل بنجاح!");
    loadGroups();
  });

  document.getElementById("cancelEdit").addEventListener("click", () => {
    modal.style.display = "none";
  });

  modal.addEventListener("click", (e) => {
    if (e.target === modal) modal.style.display = "none";
  });

  // Delete group
  document.addEventListener("click", async (e) => {
    if (e.target.classList.contains("delete-btn")) {
      if (!confirm("هل أنت متأكد من الحذف؟")) return;
      const id = e.target.dataset.id;
      await fetch(`${API_URL}?action=delete`, {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify({ id }),
      });
      loadGroups();
    }
  });
});
