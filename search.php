<?php require_once 'sections/header.php';
checkLogin();
$userData = getUserData(); ?>
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
                        <h2><i class="fas fa-search"></i>جستجو</h2>
                        <ul class="list">
                            <?php
                            $searchResults = getSearchResult();
                            foreach ($searchResults as $searchResult) {
                            ?>
                                <li><?php echo $searchResult['note_text']; ?></li>
                            <?php } ?>
                        </ul>
                    </div>
                </div>


            </div>


        </div>
    </div>
</div>
<?php require_once 'sections/footer.php'; ?>