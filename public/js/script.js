/* =========================================================
   FILE: public/js/script.js
   Mô tả: Xử lý Slider đồng bộ, Chatbot, Modal VÀ Xem thêm (Load More)
   ========================================================= */

// --- KHAI BÁO BIẾN TOÀN CỤC CHO SLIDER ---
let slideInterval;
let currentSlide = 0;

document.addEventListener("DOMContentLoaded", function() {
    console.log("Website loaded!");

    // 1. KHỞI TẠO SLIDER
    const slidesContainer = document.getElementById('bannerSlides');
    if (slidesContainer) {
        startAutoSlide(); // Bắt đầu chạy tự động
    }

    // 2. KHỞI TẠO SỰ KIỆN CHATBOT (Nếu có)
    const chatBtn = document.querySelector('.chat-toggler');
    if(chatBtn) chatBtn.addEventListener('click', window.toggleChat);

    const closeChatBtn = document.querySelector('.chatbot-close');
    if(closeChatBtn) closeChatBtn.addEventListener('click', window.toggleChat);

    // ============================================================
    // 3. [MỚI] XỬ LÝ NÚT "XEM TẤT CẢ" (LOAD MORE)
    // ============================================================
    const loadMoreBtn = document.getElementById('loadMoreBtn');
    
    if (loadMoreBtn) {
        loadMoreBtn.addEventListener('click', function() {
            // Tìm khung chứa danh sách (Ưu tiên ID categoryList hoặc class product-list)
            const container = document.getElementById('categoryList') || document.querySelector('.product-list');
            
            if (container) {
                // Thêm class 'expanded' vào cha để CSS hiển thị các con bị ẩn
                container.classList.add('expanded');
                
                // Ẩn nút "Xem tất cả" đi sau khi đã bấm
                this.style.display = 'none';
            }
        });
    }
});

/* =========================================================
   CÁC HÀM XỬ LÝ SLIDER (Global)
   ========================================================= */

// Hàm hiển thị slide cụ thể
function showSlide(index) {
    const slidesContainer = document.getElementById('bannerSlides');
    const tabs = document.querySelectorAll('.banner-tab');
    const totalSlides = tabs.length;

    if (!slidesContainer) return;

    // Xử lý vòng lặp (nếu index vượt quá giới hạn)
    if (index >= totalSlides) currentSlide = 0;
    else if (index < 0) currentSlide = totalSlides - 1;
    else currentSlide = index;

    // 1. Di chuyển ảnh
    slidesContainer.style.transform = `translateX(-${currentSlide * 100}%)`;

    // 2. Cập nhật trạng thái Active cho Tab tiêu đề
    tabs.forEach(tab => tab.classList.remove('active'));
    if (tabs[currentSlide]) {
        tabs[currentSlide].classList.add('active');
    }
}

// Hàm chạy tự động
function startAutoSlide() {
    // Xóa interval cũ để tránh chạy chồng chéo
    if (slideInterval) clearInterval(slideInterval);
    
    slideInterval = setInterval(() => {
        showSlide(currentSlide + 1);
    }, 3000); // 3 giây đổi ảnh
}

// --- CÁC HÀM GỌI TỪ HTML (ONCLICK) ---

// 1. Khi bấm vào Tab tiêu đề
window.goToSlide = function(index) {
    clearInterval(slideInterval); // Dừng chạy tự động khi người dùng bấm
    showSlide(index);             // Chuyển đến slide muốn xem
    startAutoSlide();             // Khởi động lại chạy tự động
};

// 2. Khi bấm nút mũi tên trái/phải
window.moveSlide = function(direction) {
    clearInterval(slideInterval);
    showSlide(currentSlide + direction);
    startAutoSlide();
};


/* =========================================================
   CÁC HÀM GLOBAL KHÁC (CHATBOT & MODAL)
   ========================================================= */

// --- CHATBOT ---
window.toggleChat = function() {
    const chatbot = document.getElementById('chatbot');
    if(chatbot) {
        chatbot.classList.toggle('show');
        if(chatbot.classList.contains('show')) {
            setTimeout(() => document.getElementById('chatInput').focus(), 300);
        }
    }
}

window.handleEnter = function(e) { if(e.key === 'Enter') window.sendMessage(); }

window.sendQuickReply = function(text) { 
    document.getElementById('chatInput').value = text; 
    window.sendMessage(); 
}

window.sendMessage = function() {
    var input = document.getElementById('chatInput');
    var text = input.value.trim();
    if(!text) return;
    
    addMessage(text, 'user-message');
    input.value = '';
    
    setTimeout(() => {
        var response = getBotResponse(text);
        addMessage(response, 'bot-message');
    }, 600);
}

function addMessage(text, cls) {
    var div = document.createElement('div');
    div.className = 'message ' + cls;
    div.innerHTML = text;
    var body = document.getElementById('chatBody');
    body.appendChild(div);
    body.scrollTop = body.scrollHeight;
}

function getBotResponse(text) {
    text = text.toLowerCase();
    
    // Tìm kiếm sản phẩm
    if(typeof productData !== 'undefined' && Array.isArray(productData)) {
        var found = productData.filter(p => p.name.toLowerCase().includes(text));
        if(found.length > 0 && text.length > 2) {
            var p = found[0];
            var price = new Intl.NumberFormat().format(p.price);
            // Đường dẫn ảnh
            var imgSrc = (typeof BASE_URL !== 'undefined' ? BASE_URL : '') + "public/img/" + p.image;
            
            return `Tìm thấy: <b>${p.name}</b><br>
                    <img src="${imgSrc}" style="width:50px; border:1px solid #ddd; border-radius:4px;"><br>
                    Giá: <span style="color:#d0011b; font-weight:bold">${price}đ</span><br>
                    <a href="index.php?act=detail&id=${p.id}">Xem chi tiết</a>`;
        }
    }

    if(text.includes('ship')) return "Phí ship nội thành 20k, tỉnh 35k.";
    if(text.includes('chào')) return "Chào bạn, mình giúp gì được cho bạn?";
    return "Bạn liên hệ hotline 1800 1274 nhé!";
}

// --- MODAL ---
window.openProductModal = function(el) {
    var imgPath = el.getAttribute('data-image');
    document.getElementById('modal-img').src = imgPath;
    document.getElementById('modal-name').innerText = el.getAttribute('data-name');
    document.getElementById('modal-price').innerText = el.getAttribute('data-price');
    
    var codeElement = document.getElementById('modal-code');
    codeElement.innerText = el.getAttribute('data-code');
    codeElement.setAttribute('data-real-id', el.getAttribute('data-id'));
    
    document.getElementById('productModal').style.display = 'block';
}

window.closeProductModal = function() {
    document.getElementById('productModal').style.display = 'none';
}

window.updateQty = function(change) {
    var input = document.getElementById('modal-quantity');
    var val = parseInt(input.value) || 1;
    if (val + change >= 1) input.value = val + change;
}

window.addToCart = function() {
    var id = document.getElementById('modal-code').getAttribute('data-real-id');
    var qty = document.getElementById('modal-quantity').value;
    alert("Đã thêm sản phẩm ID " + id + " số lượng " + qty + " vào giỏ!");
    window.closeProductModal();
}

window.onclick = function(event) {
    if (event.target == document.getElementById('productModal')) window.closeProductModal();
}