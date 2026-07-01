document.addEventListener("DOMContentLoaded", function () {
  loadProducts();
});

function loadProducts(keyword = "") {
  const tbody = document.getElementById("api-product-list");
  const clearBtn = document.getElementById("clearSearchBtn");

  if (keyword !== "") {
    clearBtn.style.display = "flex";
  } else {
    clearBtn.style.display = "none";
  }

  fetch(`index.php?act=api_products&keyword=${encodeURIComponent(keyword)}`)
    .then((response) => response.json())
    .then((res) => {
      if (res.status === 200) {
        const products = res.data.products;
        renderTable(products, keyword);
      } else {
        tbody.innerHTML = `<tr><td colspan="7" style="padding:30px; text-align:center; color: red;">Lỗi: ${res.message}</td></tr>`;
      }
    })
    .catch((err) => {
      console.error("Lỗi API:", err);
      tbody.innerHTML = `<tr><td colspan="7" style="padding:30px; text-align:center; color: red;">Mất kết nối đến máy chủ!</td></tr>`;
    });
}

function renderTable(products, keyword) {
  const tbody = document.getElementById("api-product-list");
  let html = "";

  if (products.length === 0) {
    html = `
        <tr>
            <td colspan="7" style="padding:30px; text-align:center; color: #666;">
                <img src="../public/img/no-product.png" style="width: 50px; opacity: 0.5; margin-bottom: 10px; display: block; margin: 0 auto;" onerror="this.style.display='none'">
                ${keyword !== "" ? `Không tìm thấy sản phẩm nào với từ khóa: "<strong>${keyword}</strong>"` : "Chưa có sản phẩm nào trong kho."}
            </td>
        </tr>
    `;
  } else {
    products.forEach((sp) => {
      let formattedPrice = new Intl.NumberFormat("vi-VN").format(sp.price);
      let stockHtml =
        sp.quantity > 0
          ? `<span style="color: green; font-weight: bold;">${sp.quantity}</span>`
          : `<span style="color: red; font-weight: bold;">Hết hàng</span>`;

      let badgeNew =
        sp.is_new == 1
          ? `<span style="background:red; color:white; font-size:10px; padding:2px 4px; border-radius:3px; margin-left:5px;">MỚI</span>`
          : "";

      html += `
            <tr style="border-bottom: 1px solid #eee;">
                <td style="padding:10px; text-align:center;">#${sp.id}</td>
                <td style="padding:10px; text-align:center;">
                    <img src="img/${sp.image}" style="width: 50px; height: 50px; object-fit: cover; border-radius: 4px; border: 1px solid #ddd;" onerror="this.src='img/no-image.png'">
                </td>
                <td style="padding:10px;">
                    <strong>${sp.name}</strong> ${badgeNew}
                </td>
                <td style="padding:10px; text-align:center; color: #d0011b; font-weight:bold;">
                    ${formattedPrice}đ
                </td>
                <td style="padding:10px; text-align:center;">${stockHtml}</td>
                <td style="padding:10px; text-align:center; color: #666;">${sp.views}</td>
                <td style="padding:10px; text-align:center;">
                    <div style="display: flex; justify-content: center; gap: 5px;">
                        <a href="index.php?act=admin_product_detail&id=${sp.id}" title="Xem chi tiết" style="background:#17a2b8; color:white; padding:6px 10px; border-radius:3px; text-decoration: none;">
                            <i class="fas fa-eye"></i>
                        </a>
                        <a href="index.php?act=admin_product_edit&id=${sp.id}" title="Sửa" style="background:#ffc107; color:black; padding:6px 10px; border-radius:3px; text-decoration: none;">
                            <i class="fas fa-edit"></i>
                        </a>
                        <button onclick="deleteProduct(${sp.id})" title="Xóa" style="background:#dc3545; color:white; padding:6px 10px; border-radius:3px; border:none; cursor:pointer;">
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

function handleSearch(event) {
  event.preventDefault();
  const keyword = document.getElementById("searchInput").value.trim();
  loadProducts(keyword);
}

function clearSearch() {
  document.getElementById("searchInput").value = "";
  loadProducts("");
}

async function deleteProduct(id) {
  if (
    confirm(
      "CẢNH BÁO: Bạn có chắc chắn muốn xóa sản phẩm này không? Hành động này không thể hoàn tác!",
    )
  ) {
    try {
      const response = await fetch(
        `index.php?act=api_product_delete&id=${id}`,
        {
          method: "DELETE",
        },
      );

      const rawText = await response.text();
      let res;
      try {
        res = JSON.parse(rawText);
      } catch (parseError) {
        console.error("Lỗi Server trả về HTML thay vì JSON:", rawText);
        alert(
          "Không thể xóa! Rất có thể sản phẩm này đã có khách mua (nằm trong bảng Chi tiết đơn hàng). Bấm F12 sang tab Console để xem lỗi gốc.",
        );
        return;
      }

      if (res.status === 200) {
        alert("🎉 Đã xóa sản phẩm thành công!");

        const currentKeyword = document
          .getElementById("searchInput")
          .value.trim();
        loadProducts(currentKeyword);
      } else {
        alert("Lỗi: " + res.message);
      }
    } catch (err) {
      console.error("Lỗi gọi API Delete:", err);
      alert("Mất kết nối đến máy chủ khi xóa sản phẩm!");
    }
  }
}
