document.addEventListener("DOMContentLoaded", function () {
  loadCategories();
});

// Hàm gọi API lấy danh sách danh mục (có kèm từ khóa nếu có)
function loadCategories(keyword = "") {
  const tbody = document.getElementById("api-category-list");
  const clearBtn = document.getElementById("clearSearchBtn");

  // Hiện/ẩn nút Xóa bộ lọc
  if (keyword !== "") {
    clearBtn.style.display = "flex";
  } else {
    clearBtn.style.display = "none";
  }

  // Gọi API (Dùng đúng tên endpoint đã định nghĩa trong ApiCategoryController)
  fetch(`index.php?act=api_categories`)
    .then((response) => response.json())
    .then((res) => {
      if (res.status === 200) {
        let categories = res.data.categories;

        // Lọc dữ liệu bằng Javascript nếu có gõ tìm kiếm
        if (keyword !== "") {
          categories = categories.filter((cat) =>
            cat.name.toLowerCase().includes(keyword.toLowerCase()),
          );
        }

        renderTable(categories, keyword);
      } else {
        tbody.innerHTML = `<tr><td colspan="5" style="padding:30px; text-align:center; color: red;">Lỗi: ${res.message}</td></tr>`;
      }
    })
    .catch((err) => {
      console.error("Lỗi API:", err);
      tbody.innerHTML = `<tr><td colspan="5" style="padding:30px; text-align:center; color: red;">Mất kết nối đến máy chủ!</td></tr>`;
    });
}

// Hàm vẽ giao diện các hàng <tr> (Đúng 5 cột)
function renderTable(categories, keyword) {
  const tbody = document.getElementById("api-category-list");
  let html = "";

  if (categories.length === 0) {
    html = `
            <tr>
                <td colspan="5" style="padding:30px; text-align:center; color: #666;">
                    <img src="../public/img/no-category.png" style="width: 50px; opacity: 0.5; margin-bottom: 10px; display: block; margin: 0 auto;" onerror="this.style.display='none'">
                    ${keyword !== "" ? `Không tìm thấy danh mục nào với từ khóa: "<strong>${keyword}</strong>"` : "Chưa có danh mục nào."}
                </td>
            </tr>
        `;
  } else {
    // Tạo một object map để tra cứu tên danh mục cha cho hiển thị đẹp hơn
    const catMap = {};
    categories.forEach((c) => (catMap[c.id] = c.name));

    categories.forEach((cat) => {
      // Hiển thị danh mục cha: Nếu là 0 thì in chữ "Danh mục gốc", nếu khác 0 thì in tên danh mục cha
      let parentName =
        cat.parent_id == 0
          ? '<span style="color: #28a745; font-weight: bold;">Danh mục gốc</span>'
          : `<span style="color: #666;">${catMap[cat.parent_id] || "ID: " + cat.parent_id}</span>`;

      html += `
                <tr style="border-bottom: 1px solid #eee;">
                    <td style="padding:10px; text-align:center;">#${cat.id}</td>
                    <td style="padding:10px;">
                        <strong>${cat.name}</strong>
                    </td>
                    <td style="padding:10px; text-align:center;">
                        <img src="img/${cat.image}" style="width: 50px; height: 50px; object-fit: cover; border-radius: 4px; border: 1px solid #ddd;" onerror="this.style.display='none'">
                    </td>
                    <td style="padding:10px; text-align:center;">
                        ${parentName}
                    </td>
                    <td style="padding:10px; text-align:center;">
                        <div style="display: flex; justify-content: center; gap: 5px;">
                            <a href="index.php?act=admin_category_edit&id=${cat.id}" title="Sửa" style="background:#ffc107; color:black; padding:6px 10px; border-radius:3px; text-decoration: none;">
                                <i class="fas fa-edit"></i>
                            </a>
                            <button onclick="deleteCategory(${cat.id})" title="Xóa" style="background:#dc3545; color:white; padding:6px 10px; border-radius:3px; border:none; cursor:pointer;">
                                <i class="fas fa-trash"></i>
                            </button>
                        </div>
                    </td>
                </tr>
            `;
    });
  }
  tbody.innerHTML = html;
}

// Xử lý sự kiện Submit Form Tìm kiếm
function handleSearch(event) {
  event.preventDefault();
  const keyword = document.getElementById("searchInput").value.trim();
  loadCategories(keyword);
}

// Xóa bộ lọc tìm kiếm
function clearSearch() {
  document.getElementById("searchInput").value = "";
  loadCategories("");
}

// Gọi API Xóa danh mục
function deleteCategory(id) {
  if (
    confirm(
      "CẢNH BÁO: Bạn có chắc chắn muốn xóa danh mục này không? Hành động này không thể hoàn tác!",
    )
  ) {
    let formData = new FormData();
    formData.append("id", id);

    // Gọi đúng endpoint đã thiết lập
    fetch("index.php?act=api_category_delete", {
      method: "POST",
      body: formData,
    })
      .then((response) => response.json())
      .then((res) => {
        if (res.status === 200) {
          alert("Đã xóa danh mục thành công!");
          const currentKeyword = document
            .getElementById("searchInput")
            .value.trim();
          loadCategories(currentKeyword);
        } else {
          alert("Lỗi: " + res.message);
        }
      })
      .catch((err) => alert("Lỗi kết nối khi xóa danh mục!"));
  }
}
