async function submitProductForm(event) {
  event.preventDefault();

  // 2. Lấy dữ liệu từ form
  const form = document.getElementById("addProductForm");
  const formData = new FormData(form);

  const submitBtn = form.querySelector(".btn-save");
  const originalBtnText = submitBtn.innerHTML;
  submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> ĐANG XỬ LÝ...';
  submitBtn.disabled = true;

  try {
    const response = await fetch("index.php?act=api_product_add", {
      method: "POST",
      body: formData,
    });

    const res = await response.json();

    if (res.status === 201 || res.status === 200) {
      alert("🎉 Thêm sản phẩm thành công!");
      window.location.href = "index.php?act=admin_product_list";
    } else {
      alert("Lỗi: " + res.message);
      submitBtn.innerHTML = originalBtnText;
      submitBtn.disabled = false;
    }
  } catch (error) {
    console.error("Lỗi kết nối:", error);
    alert("Mất kết nối đến máy chủ! Vui lòng thử lại.");
    submitBtn.innerHTML = originalBtnText;
    submitBtn.disabled = false;
  }
}
document.addEventListener("DOMContentLoaded", function () {
  loadCategoriesToSelect();
  loadBrandsToSelect();
});

function loadCategoriesToSelect() {
  const selectBox = document.getElementById("category_id");

  fetch("index.php?act=api_categories")
    .then((response) => response.json())
    .then((res) => {
      if (res.status === 200) {
        const categories = res.data.categories;
        let options = '<option value="">-- Chọn Danh mục --</option>';

        categories.forEach((cat) => {
          options += `<option value="${cat.id}">${cat.name}</option>`;
        });

        selectBox.innerHTML = options;
      } else {
        selectBox.innerHTML = '<option value="">Lỗi tải dữ liệu</option>';
      }
    })
    .catch((err) => {
      console.error("Lỗi:", err);
      selectBox.innerHTML = '<option value="">Mất kết nối máy chủ</option>';
    });
}

function loadBrandsToSelect() {
  const selectBox = document.getElementById("brand_id");

  fetch("index.php?act=api_brands")
    .then((response) => response.json())
    .then((res) => {
      if (res.status === 200) {
        const brands = res.data.brands;
        let options = '<option value="0">-- Chọn Thương hiệu --</option>';

        brands.forEach((brand) => {
          options += `<option value="${brand.id}">${brand.name}</option>`;
        });

        selectBox.innerHTML = options;
      } else {
        selectBox.innerHTML = '<option value="0">Lỗi tải dữ liệu</option>';
      }
    })
    .catch((err) => {
      console.error("Lỗi:", err);
      selectBox.innerHTML = '<option value="0">Mất kết nối máy chủ</option>';
    });
}

document.addEventListener("DOMContentLoaded", function () {
  // Ngay khi load trang, gọi API để lấy các danh mục cũ nhét vào ô Select
  loadCategoriesToSelect();
});

// 1. Hàm đổ dữ liệu vào Select Danh mục cha
async function loadCategoriesToSelect() {
  const selectBox = document.getElementById("parent_id");
  try {
    const response = await fetch("index.php?act=api_categories");
    const res = await response.json();

    if (res.status === 200) {
      let options = '<option value="0">-- Là danh mục gốc --</option>';
      res.data.categories.forEach((cat) => {
        // Chỉ hiển thị các danh mục vào select để chọn làm cha
        options += `<option value="${cat.id}">${cat.name}</option>`;
      });
      selectBox.innerHTML = options;
    } else {
      selectBox.innerHTML = '<option value="0">-- Là danh mục gốc --</option>';
    }
  } catch (err) {
    console.error("Lỗi tải danh mục:", err);
    selectBox.innerHTML = '<option value="0">-- Lỗi tải dữ liệu --</option>';
  }
}

// 2. Hàm Xử lý Gửi Form lên API
async function submitCategoryForm(event) {
  event.preventDefault(); // Chặn tải lại trang

  const form = document.getElementById("addCategoryForm");
  const formData = new FormData(form);

  // Đổi trạng thái nút bấm thành Đang xử lý
  const submitBtn = document.getElementById("btn-submit");
  const originalBtnText = submitBtn.innerHTML;
  submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> ĐANG XỬ LÝ...';
  submitBtn.disabled = true;

  try {
    const response = await fetch("index.php?act=api_category_add", {
      method: "POST",
      body: formData,
    });

    const res = await response.json();

    if (res.status === 201 || res.status === 200) {
      alert("🎉 Thêm danh mục thành công!");
      // Chuyển hướng về trang danh sách
      window.location.href = "index.php?act=admin_category_list";
    } else {
      alert("Lỗi: " + res.message);
      // Phục hồi nút bấm
      submitBtn.innerHTML = originalBtnText;
      submitBtn.disabled = false;
    }
  } catch (error) {
    console.error("Lỗi kết nối:", error);
    alert("Mất kết nối đến máy chủ! Vui lòng thử lại.");
    submitBtn.innerHTML = originalBtnText;
    submitBtn.disabled = false;
  }
}
