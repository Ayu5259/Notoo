<!DOCTYPE html>
<html lang="fa" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>یادداشت‌های هوشمند - مدیریت کارها و یادداشت‌ها</title>
    <link href="styles/bootstrap.min.css" rel="stylesheet">
    <link href="styles/styles.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        body {
            margin: 0;
            padding: 0;
            overflow-x: hidden;
        }
        
        .hero-section {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 120px 0 100px 0;
            text-align: center;
            position: relative;
            overflow: hidden;
        }
        
        .hero-section::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1000 100" fill="rgba(255,255,255,0.1)"><polygon points="0,100 1000,0 1000,100"/></svg>');
            background-size: cover;
        }
        
        .hero-section .container {
            position: relative;
            z-index: 2;
        }
        
        .feature-card {
            background: white;
            border-radius: 20px;
            padding: 40px 30px;
            margin: 20px 0;
            box-shadow: 0 15px 35px rgba(0,0,0,0.1);
            transition: all 0.4s ease;
            border: 1px solid rgba(255,255,255,0.2);
            position: relative;
            overflow: hidden;
        }
        
        .feature-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }
        
        .feature-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 25px 50px rgba(0,0,0,0.15);
        }
        
        .feature-icon {
            font-size: 3.5rem;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            margin-bottom: 25px;
            display: block;
        }
        
        .cta-button {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 18px 45px;
            border-radius: 50px;
            text-decoration: none;
            font-weight: bold;
            transition: all 0.3s ease;
            display: inline-block;
            margin: 10px;
            border: none;
            position: relative;
            overflow: hidden;
        }
        
        .cta-button::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255,255,255,0.2), transparent);
            transition: left 0.5s;
        }
        
        .cta-button:hover::before {
            left: 100%;
        }
        
        .cta-button:hover {
            transform: translateY(-3px);
            box-shadow: 0 15px 30px rgba(0,0,0,0.3);
            color: white;
        }
        
        .stats-section {
            background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
            padding: 100px 0;
            position: relative;
        }
        
        .stat-item {
            text-align: center;
            margin: 30px 0;
            padding: 30px 20px;
            background: white;
            border-radius: 15px;
            box-shadow: 0 10px 25px rgba(0,0,0,0.1);
            transition: transform 0.3s ease;
        }
        
        .stat-item:hover {
            transform: translateY(-5px);
        }
        
        .stat-number {
            font-size: 3.5rem;
            font-weight: bold;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            display: block;
            margin-bottom: 10px;
        }
        
        .navbar-custom {
            background: rgba(255,255,255,0.95);
            backdrop-filter: blur(15px);
            box-shadow: 0 2px 20px rgba(0,0,0,0.1);
            transition: all 0.3s ease;
        }
        
        .navbar-custom .navbar-brand {
            font-weight: bold;
            font-size: 1.5rem;
        }
        
        .navbar-custom .nav-link {
            font-weight: 500;
            transition: color 0.3s ease;
        }
        
        .navbar-custom .nav-link:hover {
            color: #667eea !important;
        }
        
        .navbar-custom .btn {
            border-radius: 25px;
            padding: 8px 25px;
            font-weight: 500;
        }
        
        /* Responsive improvements */
        @media (max-width: 768px) {
            .hero-section {
                padding: 80px 0 60px 0;
            }
            
            .hero-section h1 {
                font-size: 2.5rem;
            }
            
            .feature-card {
                padding: 30px 20px;
            }
            
            .stat-number {
                font-size: 2.5rem;
            }
            
            .cta-button {
                padding: 15px 30px;
                font-size: 0.9rem;
            }
        }
        
        /* Animation for elements */
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        .feature-card, .stat-item {
            animation: fadeInUp 0.6s ease-out;
        }
        
        .feature-card:nth-child(1) { animation-delay: 0.1s; }
        .feature-card:nth-child(2) { animation-delay: 0.2s; }
        .feature-card:nth-child(3) { animation-delay: 0.3s; }
        .feature-card:nth-child(4) { animation-delay: 0.4s; }
        .feature-card:nth-child(5) { animation-delay: 0.5s; }
        .feature-card:nth-child(6) { animation-delay: 0.6s; }
    </style>
</head>
<body>
    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-custom fixed-top">
        <div class="container">
            <a class="navbar-brand fw-bold" href="#">
                <i class="fas fa-sticky-note text-primary"></i> یادداشت‌های هوشمند
            </a>
            <div class="navbar-nav ms-auto">
                <a class="nav-link" href="#features">امکانات</a>
                <a class="nav-link" href="#about">درباره ما</a>
                <a class="nav-link" href="login.php">ورود</a>
                <a class="nav-link btn btn-primary text-white px-3" href="register.php">ثبت نام</a>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section class="hero-section">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-6">
                    <h1 class="display-4 fw-bold mb-4">مدیریت هوشمند کارها و یادداشت‌ها</h1>
                    <p class="lead mb-4">با استفاده از ابزارهای پیشرفته، کارهای خود را سازماندهی کنید و بهره‌وری خود را افزایش دهید.</p>
                    <div class="d-flex flex-wrap justify-content-center justify-content-lg-start">
                        <a href="register.php" class="cta-button">
                            <i class="fas fa-rocket me-2"></i>شروع رایگان
                        </a>
                        <a href="#features" class="cta-button" style="background: transparent; border: 2px solid white;">
                            <i class="fas fa-play me-2"></i>مشاهده امکانات
                        </a>
                    </div>
                </div>
                <div class="col-lg-6">
                    <img src="images/fff.png" alt="یادداشت‌های هوشمند" class="img-fluid rounded-3 shadow-lg">
                </div>
            </div>
        </div>
    </section>

    <!-- Features Section -->
    <section id="features" class="py-5">
        <div class="container">
            <div class="text-center mb-5">
                <h2 class="display-5 fw-bold">امکانات ویژه</h2>
                <p class="lead text-muted">همه آنچه برای مدیریت بهتر کارها نیاز دارید</p>
            </div>
            <div class="row">
                <div class="col-lg-4">
                    <div class="feature-card text-center">
                        <div class="feature-icon">
                            <i class="fas fa-calendar-alt"></i>
                        </div>
                        <h4>زمان‌بندی هوشمند</h4>
                        <p>کارهای خود را با تاریخ و ساعت مشخص برنامه‌ریزی کنید و یادآوری‌های خودکار دریافت کنید.</p>
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="feature-card text-center">
                        <div class="feature-icon">
                            <i class="fas fa-tags"></i>
                        </div>
                        <h4>دسته‌بندی پیشرفته</h4>
                        <p>یادداشت‌ها و کارها را در دسته‌های مختلف سازماندهی کنید و به راحتی پیدا کنید.</p>
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="feature-card text-center">
                        <div class="feature-icon">
                            <i class="fas fa-search"></i>
                        </div>
                        <h4>جستجوی سریع</h4>
                        <p>با استفاده از جستجوی پیشرفته، به سرعت یادداشت‌ها و کارهای مورد نظر خود را پیدا کنید.</p>
                    </div>
                </div>
            </div>
            <div class="row mt-4">
                <div class="col-lg-4">
                    <div class="feature-card text-center">
                        <div class="feature-icon">
                            <i class="fas fa-chart-line"></i>
                        </div>
                        <h4>گزارش‌گیری</h4>
                        <p>از پیشرفت کارهای خود گزارش‌های دقیق دریافت کنید و عملکرد خود را بهبود دهید.</p>
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="feature-card text-center">
                        <div class="feature-icon">
                            <i class="fas fa-mobile-alt"></i>
                        </div>
                        <h4>دسترسی همه جا</h4>
                        <p>از هر دستگاه و هر مکان به یادداشت‌ها و کارهای خود دسترسی داشته باشید.</p>
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="feature-card text-center">
                        <div class="feature-icon">
                            <i class="fas fa-shield-alt"></i>
                        </div>
                        <h4>امنیت بالا</h4>
                        <p>اطلاعات شما با بالاترین سطح امنیت محافظت می‌شود و فقط شما به آن‌ها دسترسی دارید.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Stats Section -->
    <section class="stats-section">
        <div class="container">
            <div class="row">
                <div class="col-lg-3 col-md-6">
                    <div class="stat-item">
                        <div class="stat-number">1000+</div>
                        <div class="text-muted">کاربر فعال</div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6">
                    <div class="stat-item">
                        <div class="stat-number">50K+</div>
                        <div class="text-muted">یادداشت ایجاد شده</div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6">
                    <div class="stat-item">
                        <div class="stat-number">99%</div>
                        <div class="text-muted">رضایت کاربران</div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6">
                    <div class="stat-item">
                        <div class="stat-number">24/7</div>
                        <div class="text-muted">پشتیبانی</div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- About Section -->
    <section id="about" class="py-5">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-6">
                    <h2 class="display-6 fw-bold mb-4">درباره پروژه</h2>
                    <p class="lead mb-4">این پروژه یک سیستم مدیریت یادداشت‌ها و کارها است که برای پروژه پایانی برنامه‌نویسی وب طراحی شده است.</p>
                    <p class="mb-4">امکانات اصلی این سیستم شامل:</p>
                    <ul class="list-unstyled">
                        <li><i class="fas fa-check text-success me-2"></i>مدیریت کاربران (بیننده، ثبت‌نام شده، ادمین)</li>
                        <li><i class="fas fa-check text-success me-2"></i>زمان‌بندی کارها با تاریخ و ساعت</li>
                        <li><i class="fas fa-check text-success me-2"></i>دسته‌بندی و جستجوی پیشرفته</li>
                        <li><i class="fas fa-check text-success me-2"></i>گزارش‌گیری و آمار</li>
                        <li><i class="fas fa-check text-success me-2"></i>رابط کاربری مدرن و زیبا</li>
                    </ul>
                </div>
                <div class="col-lg-6">
                    <div class="bg-light p-4 rounded-3">
                        <h4 class="mb-3">تکنولوژی‌های استفاده شده:</h4>
                        <div class="row">
                            <div class="col-6">
                                <div class="d-flex align-items-center mb-2">
                                    <i class="fab fa-php text-primary me-2"></i>
                                    <span>PHP</span>
                                </div>
                                <div class="d-flex align-items-center mb-2">
                                    <i class="fab fa-js text-warning me-2"></i>
                                    <span>JavaScript</span>
                                </div>
                                <div class="d-flex align-items-center mb-2">
                                    <i class="fab fa-bootstrap text-primary me-2"></i>
                                    <span>Bootstrap</span>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="d-flex align-items-center mb-2">
                                    <i class="fas fa-database text-success me-2"></i>
                                    <span>MySQL</span>
                                </div>
                                <div class="d-flex align-items-center mb-2">
                                    <i class="fab fa-html5 text-danger me-2"></i>
                                    <span>HTML5</span>
                                </div>
                                <div class="d-flex align-items-center mb-2">
                                    <i class="fab fa-css3-alt text-info me-2"></i>
                                    <span>CSS3</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-dark text-white py-4">
        <div class="container">
            <div class="row">
                <div class="col-lg-6">
                    <h5>یادداشت‌های هوشمند</h5>
                    <p>مدیریت هوشمند کارها و یادداشت‌ها</p>
                </div>
                <div class="col-lg-6 text-lg-end">
                    <p>&copy; 2024 تمامی حقوق محفوظ است.</p>
                </div>
            </div>
        </div>
    </footer>

    <script src="scripts/bootstrap.bundle.min.js"></script>
</body>
</html> 