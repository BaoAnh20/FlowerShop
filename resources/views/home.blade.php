@extends('layouts.app')

@section('title', 'BAFlorist - Tiệm Hoa Tươi')

@section('content')
<style>
    @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;600;800&display=swap');
    
    .ba-hero-section {
        --fs: min(15vh, 10vw);
        position: relative;
        background: linear-gradient(rgba(0, 0, 0, 0.45), rgba(0, 0, 0, 0.45)), url('{{ asset('images/home2.png') }}') center/cover no-repeat;
        color: #ffffff;
        font-family: 'Inter', sans-serif;
        overflow: hidden;
        min-height: 480px;
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: center;
        transition: filter 0.8s ease;
        border-radius: 1rem;
        margin-bottom: 3rem;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.15);
    }
    .ba-hero-section.loaded {
        background: linear-gradient(rgba(0, 0, 0, 0.35), rgba(0, 0, 0, 0.35)), url('{{ asset('images/home2.png') }}') center/cover no-repeat;
    }
    .ba-bg-lines {
        position: absolute;
        inset: 0;
        width: 100%;
        height: 100%;
        pointer-events: none;
        opacity: 0;
        transition: opacity 1s ease;
        z-index: 1;
    }
    .ba-hero-section.loaded .ba-bg-lines {
        opacity: 0.8;
    }
    .ba-hero-container {
        position: relative;
        z-index: 2;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 800;
        font-size: var(--fs);
        line-height: 0.8;
        letter-spacing: -0.045em;
        text-transform: uppercase;
        user-select: none;
    }
    .ba-word-part {
        display: inline-block;
        color: #ffffff;
        text-shadow: 0 4px 20px rgba(0, 0, 0, 0.3);
        will-change: transform;
    }
    .ba-pill {
        position: relative;
        height: 1.81em;
        width: 0.012em;
        border-radius: 9999px;
        overflow: hidden;
        background-color: rgba(255, 255, 255, 0.3);
        display: flex;
        align-items: center;
        justify-content: center;
        box-shadow: 0 8px 32px rgba(0, 0, 0, 0.25);
        will-change: width, clip-path;
    }
    .ba-hairline-overlay {
        position: absolute;
        inset: 0;
        background-color: #ffffff;
        z-index: 5;
        transition: opacity 0.4s ease;
    }
    .ba-pill-image {
        position: absolute;
        height: 100%;
        width: 4.5em;
        max-width: none;
        object-fit: cover;
        object-position: center;
        z-index: 2;
        pointer-events: none;
    }
    @media (max-width: 720px) {
        .ba-hero-section {
            --fs: min(24vw, 12vh);
            min-height: 400px;
        }
        .ba-hero-container {
            flex-direction: column;
            align-items: center;
            gap: 0.03em;
        }
        .ba-pill {
            width: calc(100vw - 40px) !important;
            height: calc((100vw - 40px) / 2) !important;
        }
    }
</style>

<div class="container-fluid px-3 px-md-4 py-4">
    <div class="ba-hero-section" id="baHeroBanner">
        <svg class="ba-bg-lines" viewBox="0 0 1440 900" preserveAspectRatio="none">
            <g stroke="#ffffff" fill="none" stroke-linecap="round">
                <path d="M-100,800 C300,700 400,300 700,200 C1000,100 1200,400 1500,100" stroke-width="1.8" opacity="0.25"/>
                <path d="M-50,850 C250,750 450,350 750,250 C1050,150 1250,450 1550,150" stroke-width="2.5" opacity="0.35"/>
                <path d="M0,900 C200,800 500,400 800,300 C1100,200 1300,500 1600,200" stroke-width="1.2" opacity="0.2"/>
                <path d="M-80,750 C350,650 380,250 680,150 C980,50 1180,350 1480,50" stroke-width="3.2" opacity="0.4"/>
                <path d="M-120,950 C180,850 520,450 820,350 C1120,250 1320,550 1620,250" stroke-width="2.1" opacity="0.3"/>
                <path d="M-30,700 C280,600 420,200 720,100 C1020,0 1220,300 1520,0" stroke-width="3.9" opacity="0.45"/>
            </g>
        </svg>
        <div class="ba-hero-container" id="baHeroContainer">
            <span class="ba-word-part" id="baPart1">BA</span>
            <div class="ba-pill" id="baPill">
                <div class="ba-hairline-overlay" id="baHairlineOverlay"></div>
                <img class="ba-pill-image" src="{{ asset('images/home.png') }}" alt="BAFlorist Banner">
            </div>
            <span class="ba-word-part" id="baPart2">FLORIST</span>
        </div>
    </div>

    <!-- Danh mục  -->
<div class="mb-5 py-2 px-md-2" id="categories">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3 class="fw-bold mb-0 text-primary-custom" style="font-size: 1.75rem;">Hoa cho mọi dịp</h3>
        <a href="{{ route('products.index') }}" class="text-decoration-none fw-semibold small text-primary-custom">Xem thêm <i class="fa-solid fa-arrow-right ms-1"></i></a>
    </div>
    <div class="row g-3 g-md-4 text-center">
        @if(isset($categories) && count($categories) > 0)
            @foreach($categories as $category)
                <div class="col-3 col-md-3 col-lg flex-grow-1">
                    <a href="{{ route('products.index', ['category_id' => $category->id]) }}" class="text-decoration-none group-item d-block">
                        <div class="rounded-circle overflow-hidden shadow-sm mx-auto mb-2 border border-2 bg-light d-flex align-items-center justify-content-center" style="width: 85px; height: 85px; border-color: rgba(123, 17, 19, 0.2) !important;">
                            @if(!empty($category->image))
                                @php
                                    $imgCat = $category->image;
                                    if (!Str::startsWith($imgCat, ['http://', 'https://'])) {
                                        if (!Str::startsWith($imgCat, 'storage/') && !Str::startsWith($imgCat, 'images/')) {
                                            $imgCat = asset('storage/' . $imgCat);
                                        } else {
                                            $imgCat = asset($imgCat);
                                        }
                                    }
                                @endphp
                                <img src="{{ $imgCat }}" class="w-100 h-100 object-fit-cover transition-scale" alt="{{ $category->name }}" onerror="this.src='https://images.unsplash.com/photo-1518709268805-4e9042af9f23?q=80&w=300'">
                            @else
                                <img src="https://images.unsplash.com/photo-1518709268805-4e9042af9f23?q=80&w=300" class="w-100 h-100 object-fit-cover transition-scale" alt="{{ $category->name }}">
                            @endif
                        </div>
                        <span class="fw-semibold small d-block text-dark text-truncate px-1">{{ $category->name }}</span>
                    </a>
                </div>
            @endforeach
        @else
            <div class="col-12 text-center text-muted py-3 small">Chưa có danh mục hoa nào trong cơ sở dữ liệu.</div>
        @endif
    </div>
</div>

    <div class="mb-5 py-2 px-md-2">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h3 class="fw-bold mb-0 text-primary-custom" style="font-size: 1.75rem;">Sản phẩm nổi bật</h3>
            <a href="{{ route('products.index') }}" class="text-decoration-none fw-semibold text-primary-custom">Xem tất cả <i class="fa-solid fa-arrow-right ms-1"></i></a>
        </div>
        <div class="row g-3 g-md-4" id="featuredProductsContainer">
            <div class="col-12 text-center py-5 text-muted">
                <i class="fa-solid fa-spinner fa-spin fa-2x mb-2 text-danger"></i>
                <p class="small mb-0">Đang tải danh sách hoa tươi...</p>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    document.addEventListener("DOMContentLoaded", function() {
        const heroSection = document.getElementById('baHeroBanner');
        const pill = document.getElementById('baPill');
        const hairlineOverlay = document.getElementById('baHairlineOverlay');
        const part1 = document.getElementById('baPart1');
        const part2 = document.getElementById('baPart2');
        const prefersReducedMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;

        function runReveal() {
            const isMobile = window.innerWidth <= 720;
            const duration = 2100;
            const startTime = performance.now();
            heroSection.classList.add('loaded');

            if (prefersReducedMotion) {
                pill.style.width = isMobile ? 'calc(100vw - 40px)' : '3.62em';
                pill.style.height = isMobile ? 'calc((100vw - 40px) / 2)' : '1.81em';
                hairlineOverlay.style.opacity = '0';
                return;
            }

            function updateAnimation(currentTime) {
                let elapsed = currentTime - startTime;
                let progress = Math.min(elapsed / duration, 1);
                let wVal, clipVal, gapVal;

                if (!isMobile) {
                    if (progress <= 0.14) {
                        wVal = 0.012;
                        clipVal = 50 - (progress / 0.14) * 50;
                        gapVal = 0.026;
                    } else if (progress <= 0.30) {
                        let p = (progress - 0.14) / 0.16;
                        wVal = 0.012 + p * (0.16 - 0.012);
                        clipVal = 0;
                        gapVal = 0.026 + p * (0.035);
                    } else if (progress <= 0.58) {
                        let p = (progress - 0.30) / 0.28;
                        wVal = 0.16 + p * (1.81 - 0.16);
                        clipVal = 0;
                        gapVal = 0.05 + p * (0.035);
                    } else {
                        let p = (progress - 0.58) / 0.42;
                        let easeVal = 1 - Math.pow(1 - p, 3);
                        wVal = 1.81 + easeVal * (4.2 - 1.81);
                        clipVal = 0;
                        gapVal = 0.085;
                    }
                    pill.style.width = wVal + 'em';
                    pill.style.height = '1.81em';
                    pill.style.clipPath = `inset(${clipVal}% 0)`;
                    part1.style.marginRight = gapVal + 'em';
                    part2.style.marginLeft = gapVal + 'em';
                } else {
                    if (progress <= 0.14) {
                        clipVal = 50 - (progress / 0.14) * 50;
                        pill.style.height = '1.5px';
                    } else if (progress <= 0.58) {
                        let p = (progress - 0.14) / 0.44;
                        pill.style.height = (1.5 + p * 50) + 'px';
                        clipVal = 0;
                    } else {
                        pill.style.height = `calc((100vw - 40px) / 2)`;
                        clipVal = 0;
                    }
                    pill.style.width = 'calc(100vw - 40px)';
                    pill.style.clipPath = `inset(0 ${clipVal}%)`;
                }

                if (progress < 0.14) {
                    hairlineOverlay.style.opacity = '1';
                } else if (progress <= 0.30) {
                    hairlineOverlay.style.opacity = 1 - (progress - 0.14) / 0.16;
                } else {
                    hairlineOverlay.style.opacity = '0';
                }

                if (elapsed < duration) {
                    requestAnimationFrame(updateAnimation);
                }
            }
            requestAnimationFrame(updateAnimation);
        }

        setTimeout(() => runReveal(), 200);

        // 2.lấy sản phẩm nổi bật
        loadFeaturedProducts();
    });

    async function loadFeaturedProducts() {
        const container = document.getElementById('featuredProductsContainer');
        try {
            const result = await window.apiService.getProducts();
            if (result.ok && result.data.success && result.data.data.data.length > 0) {
                const products = result.data.data.data.slice(0, 6);
                let html = '';
                products.forEach(product => {
                    let imgSrc = product.image;
                    if (!imgSrc.startsWith('http')) {
                        if (!imgSrc.startsWith('storage/') && !imgSrc.startsWith('images/')) {
                            imgSrc = '/storage/images/' + imgSrc;
                        } else if (imgSrc.startsWith('images/')) {
                            imgSrc = '/storage/' + imgSrc;
                        } else {
                            imgSrc = '/' + imgSrc;
                        }
                    }
                    html += `
                        <div class="col-6 col-sm-4 col-md-3 col-lg-2">
                            <div class="card h-100 border-0 shadow-sm rounded-4 overflow-hidden product-card">
                                <a href="/product/${product.id}" class="text-decoration-none">
                                    <div class="position-relative overflow-hidden bg-light" style="aspect-ratio: 1/1;">
                                        <img src="${imgSrc}" class="w-100 h-100 object-fit-cover" alt="${product.name}" onerror="this.src='https://images.unsplash.com/photo-1561181286-d3fee7d55364?q=80&w=600'">
                                    </div>
                                </a>
                                <div class="card-body p-3 text-center d-flex flex-column justify-content-between">
                                    <div>
                                        <a href="/product/${product.id}" class="text-decoration-none text-dark">
                                            <h6 class="card-title text-truncate small fw-semibold mb-1" title="${product.name}">${product.name}</h6>
                                        </a>
                                        <span class="text-muted small d-block mb-1" style="font-size: 0.75rem;">${product.category ? product.category.name : 'Hoa tươi'}</span>
                                    </div>
                                    <div class="text-danger fw-bold small">
                                        ${Number(product.price).toLocaleString('vi-VN')} đ
                                    </div>
                                </div>
                            </div>
                        </div>
                    `;
                });
                container.innerHTML = html;
            } else {
                container.innerHTML = '<div class="col-12 text-center text-muted py-4">Chưa có sản phẩm hoa nào được cập nhật trong hệ thống.</div>';
            }
        } catch (error) {
            console.error('Lỗi tải sản phẩm:', error);
            container.innerHTML = '<div class="col-12 text-center text-danger py-4">Lỗi kết nối khi tải danh sách sản phẩm.</div>';
        }
    }
</script>
@endpush
@endsection