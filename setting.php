<?php require_once 'sections/header.php';
checkLogin();
$userData = getUserData();
?>
<div class="container-fluid min-vh-100 d-flex flex-column">
    <div class="row flex-grow-1">

        <div class="col-lg-2 col-md-3 sidebar">
            <h2 class="logo">یادداشت ها</h2>
            <div class="devider"></div>
            <div class="searchbox">
                <?php require_once 'sections/search.php' ?>
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
                    <h1 class="title"><?php echo $userData['title'] ?> <?php echo getUserDisplayname(); ?></h1>
                    <h2 class="title"><?php echo $userData['subtitle'] ?></h2>
                </div>
            </div>

            <div class="row mycards mx-auto notes">
                <div class="col-lg-12">
                    <div class="box">
                        <h2><i class="fas fa-wrench"></i>تنظیمات</h2>
                        <?php showMessage(); ?>
                        <form action="inc/functions.php" method="post">
                            <div class="row p-4">
                                <div class="col-4"><input type="text" name="display-name" value="<?php echo $userData['display_name']; ?>" class="form-control" placeholder="نام شما"></div>

                                <div class="col-4"><input type="text" name="title" value="<?php echo $userData['title']; ?>" class="form-control" placeholder="عنوان اصلی"></div>

                                <div class="col-4"><input type="text" name="subtitle" value="<?php echo $userData['subtitle']; ?>" class="form-control" placeholder="عنوان فرعی"></div>
                            </div>
                            <input type="submit" name="do-update" class="btn btn-success ms-4" value="بروزرسانی">
                        </form>
                    </div>
                </div>

                <!-- Category Management Section -->
                <div class="col-lg-12 mt-4">
                    <div class="box">
                        <h2><i class="fas fa-tags"></i>مدیریت دسته‌بندی‌ها</h2>
                        <div class="row p-4">
                            <div class="col-12">
                                <form action="inc/functions.php" method="post" class="mb-4">
                                    <div class="row">
                                        <div class="col-4">
                                            <input type="text" name="category-name" class="form-control" placeholder="نام دسته‌بندی" required>
                                        </div>
                                        <div class="col-4">
                                            <input type="color" name="category-color" class="form-control" value="#293462">
                                        </div>
                                        <div class="col-4">
                                            <button type="submit" name="add-category" class="btn btn-primary">افزودن دسته‌بندی</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                            <div class="col-12">
                                <div class="categories-list">
                                    <?php
                                    $categories = getUserCategories();
                                    foreach ($categories as $category) {
                                        echo '<div class="category-item" style="border-right: 4px solid ' . $category['color'] . '">';
                                        echo '<span class="category-name">' . $category['name'] . '</span>';
                                        echo '<a href="?delete-category=' . $category['id'] . '" class="btn btn-danger btn-sm" onclick="return confirm(\'آیا از حذف این دسته‌بندی اطمینان دارید؟\')"><i class="fas fa-trash"></i></a>';
                                        echo '</div>';
                                    }
                                    ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>


    </div>
</div>

<?php require_once 'sections/footer.php'; ?>