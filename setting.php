<?php require_once 'sections/header.php';
checkLogin(); ?>
<div class="container-fluid min-vh-100 d-flex flex-column">
    <div class="row flex-grow-1">
        <div class="col-lg-2 col-md-3 sidebar">
            <h2 class="logo">یادداشت ها</h2>
            <div class="devider"></div>
            <div class="searchbox">
                <form action="">
                    <a href="#"><i class="fas fa-magnifying-glass"></i></a>
                    <input type="text" placeholder="جستجو">
                </form>
            </div>
            <?php require_once 'sections/menu.php' ?>

            <div class="upgrade">
                <a href="#" class=""><i class="fas fa-trophy"></i>خرید نسخه کامل</a>
            </div>
        </div>
        <div class="col-lg-10 col-md-9 content g-0">
            <div class="bg">
                <a class="profile"><i class="fas fa-user"></i>مشاهده پروفایل</a>
                <div class="titles">
                    <h1 class="title">سلام عزیزم</h1>
                    <h2 class="title">بهترین برنامه، بی‌برنامگیه...</h2>
                </div>
            </div>

            <div class="row mycards mx-auto notes">
                <div class="col-lg-12">
                    <div class="box">
                        <h2><i class="fas fa-wrench"></i>تنظیمات</h2>
                        <form action="">
                            <div class="row p-4">
                                <div class="col-4"><input type="text" class="form-control" placeholder="نام شما"></div>
                                <div class="col-4"><input type="text" class="form-control" placeholder="عنوان اصلی"></div>
                                <div class="col-4"><input type="text" class="form-control" placeholder="عنوان فرعی"></div>
                            </div>
                            <input type="submit" class="btn btn-success ms-4" value="بروزرسانی">
                        </form>
                    </div>
                </div>


            </div>


        </div>
    </div>
</div>

<?php require_once 'sections/footer.php'; ?>