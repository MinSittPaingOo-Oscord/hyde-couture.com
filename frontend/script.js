// Product data for each category (3 products per slide)
const productData = {
    tshirt: [
      {
        image: "img/T-shirt.jpg",
        alt: "T-shirt 1",
        price: "Ks 15,000",
        oldPrice: "Ks 18,000",
        description: "Short sleeve casual tee",
      },
      {
        image: "img/T-shirt.jpg",
        alt: "T-shirt 2",
        price: "Ks 15,000",
        oldPrice: "Ks 18,000",
        description: "Short sleeve casual tee",
      },
      {
        image: "img/T-shirt.jpg",
        alt: "T-shirt 3",
        price: "Ks 15,000",
        oldPrice: "Ks 18,000",
        description: "Short sleeve casual tee",
      },
      {
        image: "img/T-shirt.jpg",
        alt: "T-shirt 4",
        price: "Ks 16,000",
        oldPrice: "Ks 19,000",
        description: "Premium cotton tee",
      },
      {
        image: "img/T-shirt.jpg",
        alt: "T-shirt 5",
        price: "Ks 17,000",
        oldPrice: "Ks 20,000",
        description: "Graphic print tee",
      },
      {
        image: "img/T-shirt.jpg",
        alt: "T-shirt 6",
        price: "Ks 18,000",
        oldPrice: "Ks 21,000",
        description: "Oversized fit tee",
      },
    ],
    tops: [
      {
        image: "img/Top.jpg",
        alt: "Top 1",
        price: "Ks 25,000",
        oldPrice: "Ks 28,000",
        description: "Casual summer top",
      },
      {
        image: "img/Top.jpg",
        alt: "Top 2",
        price: "Ks 22,000",
        oldPrice: "Ks 25,000",
        description: "Elegant evening top",
      },
      {
        image: "img/Top.jpg",
        alt: "Top 3",
        price: "Ks 28,000",
        oldPrice: "Ks 32,000",
        description: "Designer blouse",
      },
      {
        image: "img/Top.jpg",
        alt: "Top 4",
        price: "Ks 30,000",
        oldPrice: "Ks 35,000",
        description: "Silk evening top",
      },
    ],
    pants: [
      {
        image: "img/pant_new.jpg",
        alt: "Pants 1",
        price: "Ks 35,000",
        oldPrice: "Ks 40,000",
        description: "Designer trousers",
      },
      {
        image: "img/pant_new.jpg",
        alt: "Pants 2",
        price: "Ks 32,000",
        oldPrice: "Ks 38,000",
        description: "Casual jeans",
      },
      {
        image: "img/pant_new.jpg",
        alt: "Pants 3",
        price: "Ks 38,000",
        oldPrice: "Ks 45,000",
        description: "Formal trousers",
      },
    ],
    dress: [
      {
        image: "img/dress_5.jpg",
        alt: "Dress 1",
        price: "Ks 45,000",
        oldPrice: "Ks 50,000",
        description: "Evening gown",
      },
      {
        image: "img/dress_5.jpg",
        alt: "Dress 2",
        price: "Ks 38,000",
        oldPrice: "Ks 42,000",
        description: "Summer dress",
      },
      {
        image: "img/dress_5.jpg",
        alt: "Dress 3",
        price: "Ks 52,000",
        oldPrice: "Ks 58,000",
        description: "Cocktail dress",
      },
    ],
    jacket: [
      {
        image: "img/jacket_new.jpg",
        alt: "Jacket 1",
        price: "Ks 55,000",
        oldPrice: "Ks 60,000",
        description: "Leather Jacket",
      },
      {
        image: "img/jacket_new.jpg",
        alt: "Jacket 2",
        price: "Ks 45,000",
        oldPrice: "Ks 50,000",
        description: "Denim Jacket",
      },
      {
        image: "img/jacket_new.jpg",
        alt: "Jacket 3",
        price: "Ks 65,000",
        oldPrice: "Ks 70,000",
        description: "Winter Coat",
      },
    ],
    accessories: [
      {
        image: "img/Jewellery.jpg",
        alt: "Accessory 1",
        price: "Ks 10,000",
        oldPrice: "Ks 12,000",
        description: "Designer Belt",
      },
      {
        image: "img/Jewellery.jpg",
        alt: "Accessory 2",
        price: "Ks 8,000",
        oldPrice: "Ks 10,000",
        description: "Leather Wallet",
      },
      {
        image: "img/product3.jpg",
        alt: "Accessory 3",
        price: "Ks 15,000",
        oldPrice: "Ks 18,000",
        description: "Silk Scarf",
      },
    ],
  };
  
  // New Arrivals Data
  const newArrivalsData = [
    {
      image: "img/Dress_1.jpg",
      alt: "New Arrival 1",
      price: "Ks 45,000",
      oldPrice: "Ks 50,000",
      description: "Elegant Evening Dress",
    },
    {
      image: "img/Dress_1.jpg",
      alt: "New Arrival 2",
      price: "Ks 38,000",
      oldPrice: "Ks 42,000",
      description: "Summer Floral Dress",
    },
    {
      image: "img/Dress_1.jpg",
      alt: "New Arrival 3",
      price: "Ks 52,000",
      oldPrice: "Ks 58,000",
      description: "Cocktail Party Dress",
    },
    {
      image: "img/Dress_2.jpg",
      alt: "New Arrival 4",
      price: "Ks 28,000",
      oldPrice: "Ks 32,000",
      description: "Designer Silk Top",
    },
    {
      image: "img/Dress_2.jpg",
      alt: "New Arrival 5",
      price: "Ks 35,000",
      oldPrice: "Ks 40,000",
      description: "Tailored Trousers",
    },
    {
      image: "img/Dress_2.jpg",
      alt: "New Arrival 6",
      price: "Ks 22,000",
      oldPrice: "Ks 25,000",
      description: "Casual Graphic Tee",
    },
  ];
  
  // All Products Data
  const allProductsData = [
    {
      image: "img/Dress_3.jpg",
      alt: "Product 1",
      price: "Ks 25,000",
      oldPrice: "Ks 28,000",
      description: "Casual Cotton Shirt",
    },
    {
      image: "img/Dress_3.jpg",
      alt: "Product 2",
      price: "Ks 32,000",
      oldPrice: "Ks 36,000",
      description: "Formal Office Shirt",
    },
    {
      image: "img/Dress_3.jpg",
      alt: "Product 3",
      price: "Ks 28,000",
      oldPrice: "Ks 32,000",
      description: "Designer Button Shirt",
    },
    {
      image: "img/dress_4.jpg",
      alt: "Product 4",
      price: "Ks 45,000",
      oldPrice: "Ks 50,000",
      description: "Evening Gown",
    },
    {
      image: "img/dress_4.jpg",
      alt: "Product 5",
      price: "Ks 32,000",
      oldPrice: "Ks 38,000",
      description: "Casual Jeans",
    },
    {
      image: "img/dress_4.jpg",
      alt: "Product 6",
      price: "Ks 18,000",
      oldPrice: "Ks 21,000",
      description: "Basic White Tee",
    },
    {
      image: "img/dress_5.jpg",
      alt: "Product 7",
      price: "Ks 22,000",
      oldPrice: "Ks 25,000",
      description: "Evening Top",
    },
    {
      image: "img/dress_5.jpg",
      alt: "Product 8",
      price: "Ks 65,000",
      oldPrice: "Ks 70,000",
      description: "Winter Coat",
    },
    {
      image: "img/dress_5.jpg",
      alt: "Product 9",
      price: "Ks 52,000",
      oldPrice: "Ks 58,000",
      description: "Cocktail Dress",
    },
  ];
  
  // Category names mapping
  const categoryNames = {
    tshirt: "T-SHIRTS",
    tops: "TOPS",
    pants: "PANTS",
    dress: "DRESSES",
    jacket: "JACKETS",
    accessories: "ACCESSORIES",
  };
  
  // Carousel instances
  let productCarousel = null;
  let newArrivalsCarousel = null;
  let allProductsCarousel = null;
  let currentCategory = "tshirt";
  
  // DOM Elements
  const elements = {
    videoSlider: null,
    categorySlider: null,
    productSlider: null,
    newArrivalsSlider: null,
    allProductsSlider: null,
  };
  
  // Main initialization
  document.addEventListener("DOMContentLoaded", function () {
    initializeSliders();
    setupEventListeners();
    loadAllProductSections();
  });
  
  // Initialize all Bootstrap carousels
  function initializeSliders() {
    // Video Slider
    elements.videoSlider = new bootstrap.Carousel("#videoSlider", {
      interval: 5000,
      wrap: true,
    });
  
    // Handle video playback on slide change
    document
      .getElementById("videoSlider")
      .addEventListener("slid.bs.carousel", handleVideoPlayback);
  
    // Category Slider for mobile
    if (document.getElementById("categorySlider")) {
      elements.categorySlider = new bootstrap.Carousel("#categorySlider", {
        interval: false,
        wrap: true,
      });
    }
  }
  
  // Setup all event listeners
  function setupEventListeners() {
    // Category click handlers
    document.querySelectorAll(".category-card").forEach((card) => {
      card.addEventListener("click", handleCategoryClick);
    });
  
    // Navigation buttons for product sliders
    setupNavigationButtons();
  
    // View More buttons
    document.querySelectorAll(".view-btn").forEach((button) => {
      button.addEventListener("click", handleViewMoreClick);
    });
  }
  
  // Load all product sections
  function loadAllProductSections() {
    loadProductsForCategory("tshirt");
    loadNewArrivals();
    loadAllProducts();
  }
  
  // Handle category card clicks
  function handleCategoryClick() {
    const category = this.getAttribute("data-category");
    currentCategory = category;
  
    // Update active state
    document.querySelectorAll(".category-card").forEach((c) => {
      c.classList.remove("active");
    });
    this.classList.add("active");
  
    // Update title
    document.getElementById("categoryTitle").textContent =
      categoryNames[category];
  
    // Load products for this category
    loadProductsForCategory(category);
  }
  
  // Handle video playback
  function handleVideoPlayback() {
    const activeVideo = this.querySelector(".carousel-item.active video");
    if (activeVideo) {
      activeVideo.play();
    }
  }
  
  // Setup navigation buttons for all sliders
  function setupNavigationButtons() {
    // Category products slider
    document.getElementById("productPrevBtn")?.addEventListener("click", () => {
      productCarousel?.prev();
    });
    document.getElementById("productNextBtn")?.addEventListener("click", () => {
      productCarousel?.next();
    });
  
    // New Arrivals slider
    document
      .getElementById("newArrivalsPrevBtn")
      ?.addEventListener("click", () => {
        newArrivalsCarousel?.prev();
      });
    document
      .getElementById("newArrivalsNextBtn")
      ?.addEventListener("click", () => {
        newArrivalsCarousel?.next();
      });
  
    // All Products slider
    document
      .getElementById("allProductsPrevBtn")
      ?.addEventListener("click", () => {
        allProductsCarousel?.prev();
      });
    document
      .getElementById("allProductsNextBtn")
      ?.addEventListener("click", () => {
        allProductsCarousel?.next();
      });
  }
  
  // Handle View More button clicks
  function handleViewMoreClick() {
    const sectionTitle = this.closest("section").querySelector("h2").textContent;
    console.log(`Viewing more ${sectionTitle}...`);
    // Add your logic here (redirect, show more products, etc.)
  }
  
  // Load products for a specific category
  function loadProductsForCategory(category) {
    const products = productData[category] || [];
    const container = document.getElementById("productSliderInner");
    const prevBtn = document.getElementById("productPrevBtn");
    const nextBtn = document.getElementById("productNextBtn");
  
    createProductSlider(container, products, "productSlider");
  
    // Reinitialize the carousel
    if (productCarousel) {
      productCarousel.dispose();
    }
  
    productCarousel = new bootstrap.Carousel("#productSlider", {
      interval: false,
      wrap: true,
      touch: true,
    });
  
    updateSliderControls(products, prevBtn, nextBtn);
    updateSlideIndicators(products, "#productSlider", ".slide-indicators");
  }
  
  // Load New Arrivals
  function loadNewArrivals() {
    const container = document.getElementById("newArrivalsSliderInner");
    const prevBtn = document.getElementById("newArrivalsPrevBtn");
    const nextBtn = document.getElementById("newArrivalsNextBtn");
  
    createProductSlider(container, newArrivalsData, "newArrivalsSlider");
  
    if (newArrivalsCarousel) {
      newArrivalsCarousel.dispose();
    }
  
    newArrivalsCarousel = new bootstrap.Carousel("#newArrivalsSlider", {
      interval: false,
      wrap: true,
      touch: true,
    });
  
    updateSliderControls(newArrivalsData, prevBtn, nextBtn);
    updateSlideIndicators(
      newArrivalsData,
      "#newArrivalsSlider",
      "#newArrivalsIndicators"
    );
  }
  
  // Load All Products
  function loadAllProducts() {
    const container = document.getElementById("allProductsSliderInner");
    const prevBtn = document.getElementById("allProductsPrevBtn");
    const nextBtn = document.getElementById("allProductsNextBtn");
  
    createProductSlider(container, allProductsData, "allProductsSlider");
  
    if (allProductsCarousel) {
      allProductsCarousel.dispose();
    }
  
    allProductsCarousel = new bootstrap.Carousel("#allProductsSlider", {
      interval: false,
      wrap: true,
      touch: true,
    });
  
    updateSliderControls(allProductsData, prevBtn, nextBtn);
    updateSlideIndicators(
      allProductsData,
      "#allProductsSlider",
      "#allProductsIndicators"
    );
  }
  
  // Create product slider with given data
  function createProductSlider(container, products, sliderId) {
    // Clear existing slides
    container.innerHTML = "";
  
    if (products.length === 0) {
      container.innerHTML =
        '<div class="text-center py-5"><p>No products available</p></div>';
      return;
    }
  
    // Group products into slides (3 products per slide)
    const productsPerSlide = 3;
    const totalSlides = Math.ceil(products.length / productsPerSlide);
  
    for (let slideIndex = 0; slideIndex < totalSlides; slideIndex++) {
      const startIndex = slideIndex * productsPerSlide;
      const endIndex = startIndex + productsPerSlide;
      const slideProducts = products.slice(startIndex, endIndex);
  
      const slide = createProductSlide(slideProducts, slideIndex === 0, sliderId);
      container.appendChild(slide);
    }
  }
  
  // Create a single product slide
  function createProductSlide(products, isActive, sliderId) {
    const slideDiv = document.createElement("div");
    slideDiv.className = `carousel-item ${isActive ? "active" : ""}`;
  
    const rowDiv = document.createElement("div");
    rowDiv.className = "row justify-content-center g-4";
  
    products.forEach((product) => {
      const colDiv = document.createElement("div");
      colDiv.className = "col-md-4";
  
      colDiv.innerHTML = `
        <div class="product-card">
          <div class="product-image-wrapper">
            <img src="${product.image}" alt="${product.alt}" class="img-fluid" 
                 onerror="this.src='https://via.placeholder.com/300x400?text=Product+Image';" />
          </div>
          <div class="product-info">
            <p class="price">${product.price} <span>${product.oldPrice}</span></p>
            <p class="product-description">${product.description}</p>
          </div>
        </div>
      `;
  
      rowDiv.appendChild(colDiv);
    });
  
    slideDiv.appendChild(rowDiv);
    return slideDiv;
  }
  
  // Update slider control buttons visibility
  function updateSliderControls(products, prevBtn, nextBtn) {
    if (!prevBtn || !nextBtn) return;
  
    const productsPerSlide = 3;
    const totalSlides = Math.ceil(products.length / productsPerSlide);
  
    if (totalSlides <= 1) {
      prevBtn.style.display = "none";
      nextBtn.style.display = "none";
    } else {
      prevBtn.style.display = "flex";
      nextBtn.style.display = "flex";
    }
  }
  
  // Update slide indicators
  function updateSlideIndicators(products, sliderSelector, indicatorsSelector) {
    const slides = document.querySelectorAll(`${sliderSelector} .carousel-item`);
    const indicatorsContainer = document.querySelector(indicatorsSelector);
  
    if (!indicatorsContainer || slides.length === 0) return;
  
    indicatorsContainer.innerHTML = "";
  
    slides.forEach((_, index) => {
      const indicator = document.createElement("button");
      indicator.className = `slide-indicator ${index === 0 ? "active" : ""}`;
      indicator.setAttribute("aria-label", `Go to slide ${index + 1}`);
      indicator.addEventListener("click", () => {
        goToSlide(sliderSelector, index);
      });
      indicatorsContainer.appendChild(indicator);
    });
  
    // Update active indicator on slide change
    const sliderElement = document.querySelector(sliderSelector);
    if (sliderElement) {
      sliderElement.addEventListener("slid.bs.carousel", function () {
        updateActiveIndicator(this, indicatorsSelector);
      });
    }
  }
  
  // Go to specific slide
  function goToSlide(sliderSelector, index) {
    let carousel;
  
    switch (sliderSelector) {
      case "#productSlider":
        carousel = productCarousel;
        break;
      case "#newArrivalsSlider":
        carousel = newArrivalsCarousel;
        break;
      case "#allProductsSlider":
        carousel = allProductsCarousel;
        break;
    }
  
    if (carousel) {
      carousel.to(index);
    }
  }
  
  // Update active indicator
  function updateActiveIndicator(sliderElement, indicatorsSelector) {
    const activeSlide = sliderElement.querySelector(".carousel-item.active");
    const slides = sliderElement.querySelectorAll(".carousel-item");
    const slideIndex = Array.from(slides).indexOf(activeSlide);
    const indicators = document.querySelectorAll(
      `${indicatorsSelector} .slide-indicator`
    );
  
    indicators.forEach((indicator, index) => {
      indicator.classList.toggle("active", index === slideIndex);
    });
  }
  
  // Handle window resize for responsive adjustments
  window.addEventListener("resize", function () {
    // Reinitialize sliders on resize for better responsiveness
    if (productCarousel) {
      productCarousel._setActiveIndicator();
    }
    if (newArrivalsCarousel) {
      newArrivalsCarousel._setActiveIndicator();
    }
    if (allProductsCarousel) {
      allProductsCarousel._setActiveIndicator();
    }
  });
  
  // Error handling for missing images
  function handleImageError(img) {
    img.src = "https://via.placeholder.com/300x400?text=Product+Image";
    img.alt = "Image not available";
  }
  
  // Export functions if needed (for modular approach)
  if (typeof module !== "undefined" && module.exports) {
    module.exports = {
      productData,
      newArrivalsData,
      allProductsData,
      categoryNames,
      loadProductsForCategory,
      loadNewArrivals,
      loadAllProducts,
    };
  }
