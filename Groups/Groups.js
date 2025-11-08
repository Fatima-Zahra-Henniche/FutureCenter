// Add this at the top
async function fetchJson(url, options = {}) {
  try {
    const response = await fetch(url, options);
    if (!response.ok) {
      throw new Error(`HTTP error! status: ${response.status}`);
    }
    return await response.json();
  } catch (error) {
    console.error("Fetch error:", error);
    return null;
  }
}

document.addEventListener("DOMContentLoaded", () => {
  const tbody = document.querySelector("#groups-table tbody");
  const modal = document.getElementById("editModal");

  // Tab switching functionality
  document.querySelectorAll(".tab-button").forEach((button) => {
    button.addEventListener("click", () => {
      document
        .querySelectorAll(".tab-button")
        .forEach((btn) => btn.classList.remove("active"));
      document
        .querySelectorAll(".tab-content")
        .forEach((content) => content.classList.remove("active"));

      button.classList.add("active");
      const tabId = button.getAttribute("data-tab");
      document.getElementById(tabId).classList.add("active");
    });
  });

  // Search functionality
  document.getElementById("search").addEventListener("input", (e) => {
    const searchTerm = e.target.value.toLowerCase();
    const rows = document.querySelectorAll("#groups-table tbody tr");

    rows.forEach((row) => {
      const groupName = row.cells[1].textContent.toLowerCase();
      if (groupName.includes(searchTerm)) {
        row.style.display = "";
      } else {
        row.style.display = "none";
      }
    });
  });

  // Load groups function (your existing code)
  async function loadGroups() {
    try {
      const rows = await fetchJson("Groups_api.php?action=list");
      if (!rows) return;

      console.log("Groups data:", rows);

      tbody.innerHTML = "";

      rows.forEach((g) => {
        const tr = document.createElement("tr");
        tr.innerHTML = `
          <td>${g.id}</td>
          <td>${g.nom ?? ""}</td>
          <td>${
            g.prix_seance !== undefined
              ? Number(g.prix_seance).toLocaleString() + " DA"
              : "0.00 DA"
          }</td>
          <td>${g.capacite_max ?? 0}</td>
          <td>${g.students_count ?? 0}</td>
          <td>
            <div class="action-buttons">
              <button class="edit-btn" data-id="${g.id}">
                <i class="fas fa-edit"></i> تعديل
              </button>
              <button class="delete-btn" data-id="${g.id}">
                <i class="fas fa-trash-alt"></i> حذف
              </button>
            </div>
          </td>
          `;
        tbody.appendChild(tr);
      });
    } catch (error) {
      console.error("Error loading groups:", error);
    }
  }

  // Rest of your existing code remains the same...
  loadGroups();

  // Add new group
  document.getElementById("add-btn").addEventListener("click", async () => {
    const nom = document.getElementById("nom").value.trim();
    const prix = document.getElementById("prix").value.trim();
    const capacite = document.getElementById("capacite").value.trim();

    if (!nom || prix === "") return alert("⚠️ أدخل اسم الفوج وسعر الحصة.");

    console.log({ nom, prix, capacite });

    await fetchJson("Groups_api.php?action=add", {
      method: "POST",
      headers: { "Content-Type": "application/json" },
      body: JSON.stringify({ nom, prix, capacite }),
    });

    // ✅ Clear the form fields
    document.getElementById("nom").value = "";
    document.getElementById("prix").value = "";
    document.getElementById("capacite").value = "20"; // reset to default

    // Optional: focus again on the first input
    document.getElementById("nom").focus();

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
      await fetchJson("Groups_api.php?action=delete", {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify({ id }),
      });
      loadGroups();
    }
  });
});
