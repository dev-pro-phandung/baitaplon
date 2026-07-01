document.addEventListener("DOMContentLoaded", async function () {
  const urlParams = new URLSearchParams(window.location.search);
  const productId = urlParams.get("id");

  if (!productId) {
    alert("Không tìm thấy ID sản phẩm!");
    window.location.href = "index.php?act=admin_product_list";
    return;
  }

  // Chạy song song tải Danh mục & Thương hiệu trước
  await Promise.all([loadCategoriesToSelect(), loadBrandsToSelect()]);

  // Sau đó mới lấy dữ liệu Sản phẩm
  loadProductData(productId);
});

async function loadCategoriesToSelect() {
  const selectBox = document.getElementById("category_id");
  try {
    const response = await fetch("index.php?act=api_categories");
    const res = await response.json();
    if (res.status === 200) {
      let options = '<option value="">-- Chọn Danh mục --</option>';
      res.data.categories.forEach((cat) => {
        options += `<option value="${cat.id}">${cat.name}</option>`;
      });
      selectBox.innerHTML = options;
    }
  } catch (err) {
    console.error("Lỗi danh mục:", err);
  }
}

async function loadBrandsToSelect() {
  const selectBox = document.getElementById("brand_id");
  try {
    const response = await fetch("index.php?act=api_brands");
    const res = await response.json();
    if (res.status === 200) {
      let options = '<option value="0">-- Chọn Thương hiệu --</option>';
      res.data.brands.forEach((brand) => {
        options += `<option value="${brand.id}">${brand.name}</option>`;
      });
      selectBox.innerHTML = options;
    }
  } catch (err) {
    console.error("Lỗi thương hiệu:", err);
  }
}

async function loadProductData(id) {
  try {
    // Lấy text thô để kiểm tra trước (phòng trường hợp PHP in ra dòng Warning/Error làm hỏng JSON)
    const response = await fetch(`index.php?act=api_product_detail&id=${id}`);
    const rawText = await response.text();

    const res = JSON.parse(rawText);

    if (res.status === 200) {
      const product = res.data.info;

      // DÙNG TOÁN TỬ || '' ĐỂ CHỐNG SẬP NẾU DATABASE BỊ NULL DỮ LIỆU
      document.getElementById("product_id").value = product.id || "";
      document.getElementById("name").value = product.name || "";
      document.getElementById("category_id").value = product.category_id || "";
      document.getElementById("brand_id").value = product.brand_id || 0;
      document.getElementById("price").value = product.price || 0;
      document.getElementById("old_price").value = product.old_price || "";
      document.getElementById("discount").value = product.discount || "";
      document.getElementById("unit").value = product.unit || "";
      document.getElementById("description").value = product.description || "";
      document.getElementById("quantity").value = product.quantity || 0;
      document.getElementById("views").value = product.views || 0;

      if (product.is_new == 1) {
        document.getElementById("is_new").checked = true;
      }

      if (product.image) {
        document.getElementById("preview_box").style.display = "inline-block";
        document.getElementById("preview_img").src = "img/" + product.image;
      }
    } else {
      alert("Sản phẩm không tồn tại hoặc đã bị xóa!");
      window.location.href = "index.php?act=admin_product_list";
    }
  } catch (err) {
    // NẾU CÓ LỖI, NÓ SẼ BÁO RA ĐÂY THAY VÌ IM LẶNG
    console.error("Phát hiện lỗi khi đổ dữ liệu:", err);
    alert(
      "Lỗi tải dữ liệu! Hãy nhấn F12, chọn tab Console để xem chi tiết lỗi đỏ.",
    );
  }
}

async function submitEditForm(event) {
  event.preventDefault();
  const form = document.getElementById("editProductForm");
  const formData = new FormData(form);

  const submitBtn = form.querySelector(".btn-save");
  const originalBtnText = submitBtn.innerHTML;
  submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> ĐANG XỬ LÝ...';
  submitBtn.disabled = true;

  try {
    const response = await fetch("index.php?act=api_product_update", {
      method: "POST",
      body: formData,
    });

    const res = await response.json();

    if (res.status === 200) {
      alert("🎉 Cập nhật sản phẩm thành công!");
      window.location.href = "index.php?act=admin_product_list";
    } else {
      alert("Lỗi: " + res.message);
      submitBtn.innerHTML = originalBtnText;
      submitBtn.disabled = false;
    }
  } catch (error) {
    alert("Mất kết nối đến máy chủ! Vui lòng thử lại.");
    submitBtn.innerHTML = originalBtnText;
    submitBtn.disabled = false;
  }
}

document.addEventListener("DOMContentLoaded", function () {
  // 1. Lấy ID danh mục từ thanh URL
  const urlParams = new URLSearchParams(window.location.search);
  const catId = urlParams.get("id");

  if (!catId) {
    alert("Không tìm thấy ID danh mục!");
    window.location.href = "index.php?act=admin_category_list";
    return;
  }

  // 2. Gọi hàm tải dữ liệu
  loadCategoryData(catId);
});

async function loadCategoryData(targetId) {
  try {
    // Gọi API lấy toàn bộ danh mục
    const response = await fetch("index.php?act=api_categories");
    const res = await response.json();

    if (res.status === 200) {
      const categories = res.data.categories;
      let currentCategory = null;
      let optionsHtml = '<option value="0">--- Là danh mục gốc ---</option>';

      // Duyệt mảng: Tìm danh mục hiện tại VÀ ghép thẻ Option cho danh mục cha
      categories.forEach((cat) => {
        if (cat.id == targetId) {
          currentCategory = cat; // Lưu lại dữ liệu của danh mục đang sửa
        } else {
          // Các danh mục khác thì đưa vào ô Chọn danh mục cha
          optionsHtml += `<option value="${cat.id}">${cat.name}</option>`;
        }
      });

      // Nếu không tìm thấy danh mục này trong Database
      if (!currentCategory) {
        alert("Danh mục không tồn tại hoặc đã bị xóa!");
        window.location.href = "index.php?act=admin_category_list";
        return;
      }

      // 3. Đổ dữ liệu vào Form
      document.getElementById("parent_id").innerHTML = optionsHtml;
      document.getElementById("category_id").value = currentCategory.id;
      document.getElementById("name").value = currentCategory.name;
      document.getElementById("parent_id").value = currentCategory.parent_id;

      // Xử lý hiển thị ảnh cũ
      const previewImg = document.getElementById("preview_img");
      if (currentCategory.image) {
        previewImg.src = "img/" + currentCategory.image;
      } else {
        previewImg.src = "img/no-image.png"; // Ảnh mặc định nếu chưa có
      }
      previewImg.style.display = "block";
    } else {
      alert("Lỗi tải dữ liệu danh mục!");
    }
  } catch (err) {
    console.error("Lỗi API:", err);
    alert("Mất kết nối đến máy chủ!");
  }
}

// 4. Xử lý Gửi Form Cập nhật lên API
async function submitEditCategory(event) {
  event.preventDefault();
  const form = document.getElementById("editCategoryForm");
  const formData = new FormData(form);

  const submitBtn = form.querySelector(".btn-save");
  const originalBtnText = submitBtn.innerHTML;
  submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> ĐANG XỬ LÝ...';
  submitBtn.disabled = true;

  try {
    const response = await fetch("index.php?act=api_category_update", {
      method: "POST",
      body: formData,
    });

    const res = await response.json();

    if (res.status === 200) {
      alert("🎉 Cập nhật danh mục thành công!");
      window.location.href = "index.php?act=admin_category_list";
    } else {
      alert("Lỗi: " + res.message);
      submitBtn.innerHTML = originalBtnText;
      submitBtn.disabled = false;
    }
  } catch (error) {
    console.error("Lỗi Update:", error);
    alert("Mất kết nối đến máy chủ! Vui lòng thử lại.");
    submitBtn.innerHTML = originalBtnText;
    submitBtn.disabled = false;
  }
}
