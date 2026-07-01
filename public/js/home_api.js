document.addEventListener("DOMContentLoaded", function () {
  loadHomePageData();
});

function loadHomePageData() {
  // Gọi đến API tổng của trang chủ
  fetch("http://localhost/baitap/public/index.php?act=api_home")
    .then((response) => response.json())
    .then((res) => {
      if (res.status === 200) {
        const data = res.data;

        // Gọi các hàm vẽ giao diện tương ứng với từng phần
        renderSliders(data.sliders);
        renderCategories(data.categories);

        // Dùng chung 1 hàm cho 3 khu vực sản phẩm khác nhau!
        renderProducts(data.best_sellers, "api-best-sellers");
        renderProducts(data.new_products, "api-new-products");
        renderProducts(data.suggestions, "api-all-products");
      } else {
        console.error("Lỗi từ server:", res.message);
      }
    })
    .catch((err) => {
      console.error("Lỗi kết nối API trang chủ:", err);
      document.getElementById("api-best-sellers").innerHTML =
        "<p>Lỗi mất kết nối máy chủ!</p>";
    });
}

// ---------------------------------------------------------
// CÁC HÀM VẼ GIAO DIỆN CHUYÊN DỤNG
// ---------------------------------------------------------

// 1. Hàm vẽ Sản Phẩm (Dùng chung cho Bán chạy, Mới, Tất cả)
function renderProducts(products, containerId) {
  const container = document.getElementById(containerId);
  if (!container) return;

  let html = "";
  if (products && products.length > 0) {
    products.forEach((sp) => {
      let formattedPrice = new Intl.NumberFormat("vi-VN").format(sp.price);

      html += `
                <div class="product-item" style="width: 20%; padding: 0 10px; margin-bottom: 20px;">
                    <div class="product-card" style="background: white; border: 1px solid #eee; transition: 0.3s; height: 100%; border-radius: 8px; overflow: hidden;">
                        <a href="index.php?act=detail&id=${sp.id}" class="product-img" style="display: block; position: relative; padding-top: 100%;">
                            <img src="img/${sp.image}" alt="${sp.name}" style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; object-fit: cover;">
                        </a>
                        <div class="product-info" style="padding: 10px; text-align: center;">
                            <h3 style="font-size: 14px; margin-bottom: 5px; height: 40px; overflow: hidden;">
                                <a href="index.php?act=detail&id=${sp.id}" style="color: #333; text-decoration: none;">${sp.name}</a>
                            </h3>
                            <div class="price" style="color: #d0011b; font-weight: bold; margin-bottom: 10px;">${formattedPrice}đ</div>
                            <button onclick="addToCartApi(${sp.id})" style="background: #d0011b; color: white; border: none; padding: 8px 15px; border-radius: 5px; cursor: pointer; width: 100%;">
                                <i class="fas fa-cart-plus"></i> Thêm vào giỏ
                            </button>
                        </div>
                    </div>
                </div>
            `;
    });
  } else {
    html =
      '<p style="text-align: center; width: 100%;">Chưa có sản phẩm nào.</p>';
  }
  container.innerHTML = html;
}

// 2. Hàm vẽ Slider
function renderSliders(sliders) {
  const slideContainer = document.getElementById("api-banner-slides");
  const tabContainer = document.getElementById("api-banner-tabs");
  if (!slideContainer || !tabContainer) return;

  let slideHtml = "";
  let tabHtml = "";

  if (sliders && sliders.length > 0) {
    sliders.forEach((slide, index) => {
      slideHtml += `
                <a href="${slide.link}" class="banner-slide" style="min-width: 100%; display: block;">
                    <img src="img/${slide.image}" alt="${slide.title}" style="width: 100%; display: block; object-fit: cover;">
                </a>
            `;
      let activeClass = index === 0 ? "active" : "";
      let title = slide.title ? slide.title : `Slide ${index + 1}`;
      tabHtml += `<li class="banner-tab ${activeClass}" onclick="window.goToSlide(${index})">${title}</li>`;
    });
  } else {
    slideHtml = '<img src="img/banner-default.png" style="width: 100%;">';
  }

  slideContainer.innerHTML = slideHtml;
  tabContainer.innerHTML = tabHtml;
}

// 3. Hàm vẽ Danh mục
function renderCategories(categories) {
  const container = document.getElementById("api-categories");
  if (!container) return;

  let html = "";
  if (categories && categories.length > 0) {
    categories.forEach((cat) => {
      if (cat.parent_id == 0) {
        html += `
                    <div class="category-item" style="min-width: 120px; text-align: center; border: 1px solid #ddd; padding: 10px; border-radius: 10px; background: white;">
                        <a href="index.php?act=category&id=${cat.id}" style="text-decoration: none; color: #333;">
                            <img src="img/${cat.image}" alt="${cat.name}" style="width: 60px; height: 60px; border-radius: 50%; object-fit: cover; margin-bottom: 10px;">
                            <p style="margin: 0; font-size: 14px; font-weight: bold;">${cat.name}</p>
                        </a>
                    </div>
                `;
      }
    });
  }
  container.innerHTML = html;
}
