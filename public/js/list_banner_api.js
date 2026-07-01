document.addEventListener("DOMContentLoaded", function () {
  loadBanners();
});

/**
 * 1. Hàm gọi API lấy danh sách Banner
 */
function loadBanners(keyword = "") {
  const tbody = document.getElementById("api-banner-list");
  const clearBtn = document.getElementById("clearSearchBtn");

  // Hiện/ẩn nút Xóa bộ lọc
  if (keyword !== "") {
    clearBtn.style.display = "flex";
  } else {
    clearBtn.style.display = "none";
  }

  // Gọi API (Endpoint: api_banners)
  fetch(`index.php?act=api_banners`)
    .then((response) => response.json())
    .then((res) => {
      if (res.status === 200) {
        let banners = res.data.banners;

        // Lọc dữ liệu bằng Javascript nếu có gõ tìm kiếm
        if (keyword !== "") {
          banners = banners.filter((bn) =>
            bn.title.toLowerCase().includes(keyword.toLowerCase()),
          );
        }

        renderTable(banners, keyword);
      } else {
        tbody.innerHTML = `<tr><td colspan="6" style="padding:30px; text-align:center; color: red;">Lỗi: ${res.message}</td></tr>`;
      }
    })
    .catch((err) => {
      console.error("Lỗi API:", err);
      tbody.innerHTML = `<tr><td colspan="6" style="padding:30px; text-align:center; color: red;">Mất kết nối đến máy chủ!</td></tr>`;
    });
}

/**
 * 2. Hàm vẽ giao diện hàng <tr> (Đúng 6 cột)
 */
function renderTable(banners, keyword) {
  const tbody = document.getElementById("api-banner-list");
  let html = "";

  if (banners.length === 0) {
    html = `
        <tr>
            <td colspan="6" style="padding:30px; text-align:center; color: #666;">
                <img src="../public/img/no-banner.png" style="width: 50px; opacity: 0.5; margin-bottom: 10px; display: block; margin: 0 auto;" onerror="this.style.display='none'">
                ${keyword !== "" ? `Không tìm thấy banner: "<strong>${keyword}</strong>"` : "Chưa có banner nào."}
            </td>
        </tr>
    `;
  } else {
    banners.forEach((bn) => {
      html += `
            <tr style="border-bottom: 1px solid #eee;">
                <td style="padding:10px; text-align:center;">#${bn.id}</td>
                <td style="padding:10px; text-align:center;">
                    <img src="img/${bn.image}" style="height: 50px; border-radius: 4px; border: 1px solid #ddd;" onerror="this.src='img/no-image.png'">
                </td>
                <td style="padding:10px;">
                    <strong>${bn.title}</strong>
                </td>
                <td style="padding:10px;">
                    <a href="${bn.link}" target="_blank" style="color: #007bff; text-decoration: none;">${bn.link}</a>
                </td>
                <td style="padding:10px; text-align:center;">
                    <span style="background: #e9ecef; padding: 2px 8px; border-radius: 10px; font-size: 12px;">${bn.position}</span>
                </td>
                <td style="padding:10px; text-align:center;">
                    <div style="display: flex; justify-content: center; gap: 5px;">
                        <a href="index.php?act=admin_banner_edit&id=${bn.id}" title="Sửa" style="background:#ffc107; color:black; padding:6px 10px; border-radius:3px; text-decoration: none;">
                            <i class="fas fa-edit"></i>
                        </a>
                        <button onclick="deleteBanner(${bn.id})" title="Xóa" style="background:#dc3545; color:white; padding:6px 10px; border-radius:3px; border:none; cursor:pointer;">
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

/**
 * 3. Xử lý tìm kiếm và Xóa bộ lọc
 */
function handleSearch(event) {
  event.preventDefault();
  const keyword = document.getElementById("searchInput").value.trim();
  loadBanners(keyword);
}

function clearSearch() {
  document.getElementById("searchInput").value = "";
  loadBanners("");
}

/**
 * 4. Gọi API Xóa banner
 */
function deleteBanner(id) {
  if (confirm("CẢNH BÁO: Bạn có chắc chắn muốn xóa banner này?")) {
    let formData = new FormData();
    formData.append("id", id);

    fetch("index.php?act=api_banner_delete", {
      method: "POST",
      body: formData,
    })
      .then((response) => response.json())
      .then((res) => {
        if (res.status === 200) {
          alert("Đã xóa banner thành công!");
          const currentKeyword = document
            .getElementById("searchInput")
            .value.trim();
          loadBanners(currentKeyword);
        } else {
          alert("Lỗi: " + res.message);
        }
      })
      .catch((err) => alert("Lỗi kết nối khi xóa banner!"));
  }
}
