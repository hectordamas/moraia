/**
 * MORAIA — Interactive E-Commerce Scripts
 * Vanilla JS & Microinteractions
 */

document.addEventListener('DOMContentLoaded', () => {
  initHeader();
  initHeroSlider();
  initSearch();
  initMobileNav();
  initMiniCart();
  initProductDetail();
});

/* ==========================================================================
   1. Header Sticky & Scroll Effect
   ========================================================================== */
function initHeader() {
  const header = document.querySelector('.site-header');
  if (!header) return;

  window.addEventListener('scroll', () => {
    if (window.scrollY > 30) {
      header.classList.add('scrolled');
    } else {
      header.classList.remove('scrolled');
    }
  }, { passive: true });
}

/* ==========================================================================
   2. Editorial Hero Slider
   ========================================================================== */
function initHeroSlider() {
  const slider = document.querySelector('.hero-slider-section');
  if (!slider) return;

  const slides = slider.querySelectorAll('.hero-slide');
  const dots = slider.querySelectorAll('.slider-dot');
  const prevBtn = slider.querySelector('.slider-prev');
  const nextBtn = slider.querySelector('.slider-next');

  if (slides.length <= 1) return;

  let currentSlide = 0;
  let autoplayTimer = null;
  const intervalTime = 6000;

  function goToSlide(index) {
    slides[currentSlide].classList.remove('active');
    if (dots[currentSlide]) dots[currentSlide].classList.remove('active');

    currentSlide = (index + slides.length) % slides.length;

    slides[currentSlide].classList.add('active');
    if (dots[currentSlide]) dots[currentSlide].classList.add('active');
  }

  function nextSlide() {
    goToSlide(currentSlide + 1);
  }

  function prevSlide() {
    goToSlide(currentSlide - 1);
  }

  function startAutoplay() {
    stopAutoplay();
    autoplayTimer = setInterval(nextSlide, intervalTime);
  }

  function stopAutoplay() {
    if (autoplayTimer) clearInterval(autoplayTimer);
  }

  // Event Listeners
  if (nextBtn) nextBtn.addEventListener('click', () => { nextSlide(); startAutoplay(); });
  if (prevBtn) prevBtn.addEventListener('click', () => { prevSlide(); startAutoplay(); });

  dots.forEach((dot, idx) => {
    dot.addEventListener('click', () => {
      goToSlide(idx);
      startAutoplay();
    });
  });

  slider.addEventListener('mouseenter', stopAutoplay);
  slider.addEventListener('mouseleave', startAutoplay);

  // Touch Swipe Support for Mobile
  let touchStartX = 0;
  let touchEndX = 0;

  slider.addEventListener('touchstart', (e) => {
    touchStartX = e.changedTouches[0].screenX;
  }, { passive: true });

  slider.addEventListener('touchend', (e) => {
    touchEndX = e.changedTouches[0].screenX;
    handleSwipe();
  }, { passive: true });

  function handleSwipe() {
    const diff = touchStartX - touchEndX;
    if (Math.abs(diff) > 45) {
      if (diff > 0) {
        nextSlide();
      } else {
        prevSlide();
      }
      startAutoplay();
    }
  }

  startAutoplay();
}

/* ==========================================================================
   3. Search Overlay Toggle
   ========================================================================== */
function initSearch() {
  const searchToggles = document.querySelectorAll('[data-toggle="search"]');
  const searchModal = document.querySelector('.search-modal');
  const searchInput = document.querySelector('.search-input');

  if (!searchModal) return;

  searchToggles.forEach(toggle => {
    toggle.addEventListener('click', (e) => {
      e.preventDefault();
      searchModal.classList.toggle('active');
      if (searchModal.classList.contains('active') && searchInput) {
        setTimeout(() => searchInput.focus(), 100);
      }
    });
  });

  document.addEventListener('click', (e) => {
    if (searchModal.classList.contains('active') && 
        !searchModal.contains(e.target) && 
        !Array.from(searchToggles).some(t => t.contains(e.target))) {
      searchModal.classList.remove('active');
    }
  });
}

/* ==========================================================================
   4. Mobile Navigation Drawer
   ========================================================================== */
function initMobileNav() {
  const toggle = document.querySelector('.mobile-menu-toggle');
  const drawer = document.querySelector('.mobile-nav-drawer');
  const closeBtn = document.querySelector('.mobile-nav-close');
  const backdrop = document.querySelector('.drawer-backdrop');

  if (!toggle || !drawer) return;

  function openNav() {
    drawer.classList.add('active');
    if (backdrop) backdrop.classList.add('active');
    document.body.style.overflow = 'hidden';
  }

  function closeNav() {
    drawer.classList.remove('active');
    if (backdrop) backdrop.classList.remove('active');
    document.body.style.overflow = '';
  }

  toggle.addEventListener('click', openNav);
  if (closeBtn) closeBtn.addEventListener('click', closeNav);
  if (backdrop) backdrop.addEventListener('click', closeNav);

  // Accordion toggle inside mobile nav
  const accordionBtns = drawer.querySelectorAll('.mobile-accordion-btn');
  accordionBtns.forEach(btn => {
    btn.addEventListener('click', (e) => {
      e.preventDefault();
      const content = btn.nextElementSibling;
      const isOpen = btn.classList.contains('active');
      
      btn.classList.toggle('active');
      if (content) {
        content.classList.toggle('open');
        btn.setAttribute('aria-expanded', !isOpen);
      }
    });
  });
}

/* ==========================================================================
   5. Mini-Cart Drawer & AJAX Cart
   ========================================================================== */
function initMiniCart() {
  const cartToggles = document.querySelectorAll('[data-toggle="cart"]');
  const cartDrawer = document.querySelector('.drawer-cart');
  const cartClose = document.querySelector('.drawer-cart-close');
  const backdrop = document.querySelector('.drawer-backdrop');

  if (!cartDrawer) return;

  window.openMiniCart = function() {
    cartDrawer.classList.add('active');
    if (backdrop) backdrop.classList.add('active');
    document.body.style.overflow = 'hidden';
  };

  window.closeMiniCart = function() {
    cartDrawer.classList.remove('active');
    if (backdrop) backdrop.classList.remove('active');
    document.body.style.overflow = '';
  };

  cartToggles.forEach(btn => {
    btn.addEventListener('click', (e) => {
      e.preventDefault();
      window.openMiniCart();
    });
  });

  if (cartClose) cartClose.addEventListener('click', window.closeMiniCart);
  if (backdrop) backdrop.addEventListener('click', window.closeMiniCart);

  // Global Cart Event Delegation (for adding, modifying qty, removing)
  document.addEventListener('click', (e) => {
    // Add to cart from card or detail
    const addBtn = e.target.closest('[data-action="add-to-cart"]');
    if (addBtn) {
      e.preventDefault();
      const productId = addBtn.dataset.productId;
      const variantId = addBtn.dataset.variantId || getSelectedVariantId();
      const qtyInput = document.querySelector('#product-qty-input');
      const quantity = qtyInput ? parseInt(qtyInput.value, 10) : 1;

      addToCartAjax(productId, variantId, quantity, addBtn);
    }

    // Update quantity buttons in cart drawer / cart page
    const qtyBtn = e.target.closest('[data-action="cart-qty"]');
    if (qtyBtn) {
      e.preventDefault();
      const cartKey = qtyBtn.dataset.cartKey;
      const delta = parseInt(qtyBtn.dataset.delta, 10);
      updateCartQty(cartKey, delta);
    }

    // Remove item button
    const removeBtn = e.target.closest('[data-action="cart-remove"]');
    if (removeBtn) {
      e.preventDefault();
      const cartKey = removeBtn.dataset.cartKey;
      removeFromCart(cartKey);
    }
  });
}

function getSelectedVariantId() {
  const activeVariant = document.querySelector('.variant-pill.active');
  return activeVariant ? activeVariant.dataset.variantId : null;
}

function addToCartAjax(productId, variantId, quantity, buttonEl) {
  const originalHtml = buttonEl ? buttonEl.innerHTML : '';
  if (buttonEl) {
    buttonEl.disabled = true;
    buttonEl.innerHTML = '<span class="spinner"></span> Agregando...';
  }

  fetch('/cart/add', {
    method: 'POST',
    headers: {
      'Content-Type': 'application/json',
      'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || ''
    },
    body: JSON.stringify({
      product_id: productId,
      variant_id: variantId,
      quantity: quantity
    })
  })
  .then(res => res.json())
  .then(data => {
    if (data.success) {
      updateCartUI(data);
      showToast('✨ Producto añadido a la bolsa', 'success');
      window.openMiniCart();
    } else {
      showToast(data.message || 'Error al añadir a la bolsa', 'error');
    }
  })
  .catch(err => {
    console.error('Cart error:', err);
    showToast('Ocurrió un error. Intenta de nuevo.', 'error');
  })
  .finally(() => {
    if (buttonEl) {
      buttonEl.disabled = false;
      buttonEl.innerHTML = originalHtml;
    }
  });
}

function updateCartQty(cartKey, delta) {
  fetch('/cart/update', {
    method: 'POST',
    headers: {
      'Content-Type': 'application/json',
      'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || ''
    },
    body: JSON.stringify({
      cart_key: cartKey,
      delta: delta
    })
  })
  .then(res => res.json())
  .then(data => {
    if (data.success) {
      updateCartUI(data);
    }
  });
}

function removeFromCart(cartKey) {
  fetch('/cart/remove', {
    method: 'POST',
    headers: {
      'Content-Type': 'application/json',
      'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || ''
    },
    body: JSON.stringify({
      cart_key: cartKey
    })
  })
  .then(res => res.json())
  .then(data => {
    if (data.success) {
      updateCartUI(data);
      showToast('Producto eliminado de la bolsa', 'info');
    }
  });
}

let currentShippingFee = 3.00;
let currentCartSubtotal = 0;

// Initialize cart subtotal from DOM if present
document.addEventListener('DOMContentLoaded', () => {
  const stEl = document.querySelector('.drawer-cart-subtotal');
  if (stEl) {
    currentCartSubtotal = parseFloat(stEl.textContent.replace(/[^0-9.]/g, '')) || 0;
  }
});

function updateCartUI(data) {
  // Update badge count
  document.querySelectorAll('.cart-count-badge').forEach(badge => {
    badge.textContent = data.cartCount || 0;
    badge.style.display = (data.cartCount > 0) ? 'flex' : 'none';
  });

  if (typeof data.subtotal !== 'undefined') {
    currentCartSubtotal = parseFloat(data.subtotal) || 0;
  }

  // Update Mini Cart Drawer body if returned
  const drawerBody = document.querySelector('.drawer-cart-items');
  const drawerSubtotals = document.querySelectorAll('.drawer-cart-subtotal');
  const drawerStep1Footer = document.querySelector('#drawer-step-1-footer');

  if (drawerBody && data.cartHtml) {
    drawerBody.innerHTML = data.cartHtml;
  }
  if (drawerSubtotals && data.subtotalFormatted) {
    drawerSubtotals.forEach(st => st.textContent = data.subtotalFormatted);
  }
  if (drawerStep1Footer) {
    drawerStep1Footer.style.display = (data.cartCount > 0) ? 'block' : 'none';
  }

  updateDrawerTotals();

  // If cart is empty and user was on step 2, return to step 1
  if (data.cartCount <= 0 && typeof window.goToDrawerStep === 'function') {
    window.goToDrawerStep(1);
  }

  // Update Cart Page Table if on /cart
  const cartPageItems = document.querySelector('.cart-page-items');
  const cartPageSubtotal = document.querySelector('.cart-page-subtotal');
  if (cartPageItems && data.pageCartHtml) {
    cartPageItems.innerHTML = data.pageCartHtml;
  }
  if (cartPageSubtotal && data.subtotalFormatted) {
    cartPageSubtotal.textContent = data.subtotalFormatted;
  }
}

/* ==========================================================================
   Multi-Step In-Drawer Checkout Functions
   ========================================================================== */
window.goToDrawerStep = function(step) {
  const cartBadge = document.querySelector('.cart-count-badge');
  const count = cartBadge ? parseInt(cartBadge.textContent, 10) : 0;
  
  if (step > 1 && count <= 0 && step !== 3) {
    showToast('Tu bolsa de compras está vacía', 'info');
    step = 1;
  }

  // Update indicators
  for (let i = 1; i <= 3; i++) {
    const indicator = document.getElementById(`step-indicator-${i}`);
    const panel = document.getElementById(`drawer-panel-${i}`);
    if (indicator) {
      indicator.classList.remove('active', 'done');
      if (i === step) indicator.classList.add('active');
      else if (i < step) indicator.classList.add('done');
    }
    if (panel) {
      panel.classList.toggle('active', i === step);
    }
  }

  // Update step connectors
  const conn12 = document.getElementById('step-connector-1-2');
  const conn23 = document.getElementById('step-connector-2-3');
  if (conn12) conn12.classList.toggle('done', step >= 2);
  if (conn23) conn23.classList.toggle('done', step >= 3);

  // Update header title
  const title = document.getElementById('drawer-main-title');
  if (title) {
    if (step === 1) title.textContent = 'Tu Bolsa de Compras';
    else if (step === 2) title.textContent = 'Checkout & Entrega';
    else if (step === 3) title.textContent = '¡Orden Confirmada!';
  }
};

window.selectDrawerDelivery = function(method, fee, element) {
  currentShippingFee = fee;
  document.querySelectorAll('.drawer-delivery-card').forEach(card => card.classList.remove('active'));
  if (element) {
    element.classList.add('active');
    const radio = element.querySelector('input[type="radio"]');
    if (radio) radio.checked = true;
  }

  const feeDisplay = document.getElementById('drawer-shipping-fee-display');
  if (feeDisplay) {
    feeDisplay.textContent = fee > 0 ? `$${fee.toFixed(2)}` : (method === 'pickup' ? 'Gratis' : 'Cobro Destino');
  }

  updateDrawerTotals();
};

function updateDrawerTotals() {
  const totalDisplay = document.getElementById('drawer-total-display');
  if (totalDisplay) {
    const total = currentCartSubtotal + currentShippingFee;
    totalDisplay.textContent = `$${total.toFixed(2)}`;
  }
}

window.toggleDrawerGift = function(checked) {
  const giftFields = document.getElementById('drawer-gift-fields');
  if (giftFields) {
    giftFields.style.display = checked ? 'block' : 'none';
  }
};

window.submitDrawerCheckout = function(e) {
  e.preventDefault();
  const form = document.getElementById('drawer-checkout-form');
  const submitBtn = document.getElementById('drawer-submit-btn');
  const errorBox = document.getElementById('drawer-checkout-error');

  if (!form) return;
  if (errorBox) {
    errorBox.style.display = 'none';
    errorBox.textContent = '';
  }

  if (!form.checkValidity()) {
    form.reportValidity();
    return;
  }

  const origBtnHtml = submitBtn ? submitBtn.innerHTML : '';
  if (submitBtn) {
    submitBtn.disabled = true;
    submitBtn.innerHTML = '<span class="spinner"></span> Procesando orden...';
  }

  const formData = new FormData(form);

  fetch('/checkout', {
    method: 'POST',
    headers: {
      'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || '',
      'Accept': 'application/json'
    },
    body: formData
  })
  .then(async res => {
    const data = await res.json();
    if (!res.ok) {
      throw new Error(data.message || 'Error al procesar el pedido. Revisa los datos ingresados.');
    }
    return data;
  })
  .then(data => {
    if (data.success) {
      const codeEl = document.getElementById('drawer-order-code-display');
      const totalEl = document.getElementById('drawer-order-total-display');
      const waBtn = document.getElementById('drawer-whatsapp-btn');

      if (codeEl) codeEl.textContent = data.order_code;
      if (totalEl) totalEl.textContent = data.total_formatted;
      if (waBtn && data.whatsapp_url) {
        waBtn.href = data.whatsapp_url;
      }

      document.querySelectorAll('.cart-count-badge').forEach(badge => {
        badge.textContent = '0';
        badge.style.display = 'none';
      });

      window.goToDrawerStep(3);
      showToast('✨ ¡Pedido registrado con éxito!', 'success');
    } else {
      throw new Error(data.message || 'Ocurrió un error al procesar el pedido.');
    }
  })
  .catch(err => {
    console.error('Checkout error:', err);
    if (errorBox) {
      errorBox.textContent = err.message || 'Error al procesar la orden.';
      errorBox.style.display = 'block';
    } else {
      showToast(err.message, 'error');
    }
  })
  .finally(() => {
    if (submitBtn) {
      submitBtn.disabled = false;
      submitBtn.innerHTML = origBtnHtml;
    }
  });
};

window.clearCartAjax = function() {
  if (!confirm('¿Estás segura de que deseas vaciar tu bolsa?')) return;

  fetch('/cart/clear', {
    method: 'POST',
    headers: {
      'Content-Type': 'application/json',
      'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || ''
    }
  })
  .then(res => res.json())
  .then(data => {
    if (data.success) {
      updateCartUI(data);
      window.goToDrawerStep(1);
      showToast('Bolsa de compras vaciada', 'info');
    }
  })
  .catch(err => console.error('Clear cart error:', err));
};

/* ==========================================================================
   6. Product Detail Gallery & Variant Switcher
   ========================================================================== */
function initProductDetail() {
  // Thumbnail switcher
  const thumbs = document.querySelectorAll('.product-thumb-btn');
  const mainImg = document.querySelector('.product-main-img');

  thumbs.forEach(thumb => {
    thumb.addEventListener('click', () => {
      thumbs.forEach(t => t.classList.remove('active'));
      thumb.classList.add('active');
      if (mainImg) {
        mainImg.src = thumb.dataset.fullImg;
      }
    });
  });

  // Variant selector pills
  const variantPills = document.querySelectorAll('.variant-pill');
  variantPills.forEach(pill => {
    pill.addEventListener('click', () => {
      const group = pill.closest('.variant-group');
      if (group) {
        group.querySelectorAll('.variant-pill').forEach(p => p.classList.remove('active'));
      }
      pill.classList.add('active');

      // Update price if variant has price modifier
      const priceMod = parseFloat(pill.dataset.priceMod || 0);
      const basePrice = parseFloat(document.querySelector('#base-product-price')?.value || 0);
      const priceEl = document.querySelector('.product-info-price');
      if (priceEl && basePrice) {
        const finalPrice = basePrice + priceMod;
        priceEl.textContent = '$' + finalPrice.toFixed(2);
      }
    });
  });

  // Quantity Counter on detail page
  const qtyInput = document.querySelector('#product-qty-input');
  const qtyMinus = document.querySelector('#product-qty-minus');
  const qtyPlus = document.querySelector('#product-qty-plus');

  if (qtyInput && qtyMinus && qtyPlus) {
    qtyMinus.addEventListener('click', () => {
      let val = parseInt(qtyInput.value, 10) || 1;
      if (val > 1) qtyInput.value = val - 1;
    });

    qtyPlus.addEventListener('click', () => {
      let val = parseInt(qtyInput.value, 10) || 1;
      qtyInput.value = val + 1;
    });
  }
}

/* ==========================================================================
   7. Toast Notification Utility
   ========================================================================== */
window.showToast = function(message, type = 'success') {
  let container = document.querySelector('.toast-container');
  if (!container) {
    container = document.createElement('div');
    container.className = 'toast-container';
    document.body.appendChild(container);
  }

  const toast = document.createElement('div');
  toast.className = `toast toast-${type}`;
  toast.innerHTML = `<span>${message}</span>`;
  container.appendChild(toast);

  // Trigger animation
  setTimeout(() => toast.classList.add('show'), 10);

  // Remove after 3.5 seconds
  setTimeout(() => {
    toast.classList.remove('show');
    setTimeout(() => toast.remove(), 300);
  }, 3500);
};
