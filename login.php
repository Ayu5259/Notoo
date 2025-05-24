<?php require_once 'sections/header.php'; ?>
<div class="container-fluid min-vh-100 d-flex flex-column">
    <div class="row flex-grow-1">
        <div class="col-lg-2 col-md-3 sidebar">
            <h2 class="logo">یادداشت ها</h2>
            <div class="devider"></div>
            <?php require_once 'sections/menu.php' ?>

        </div>
        <div class="col-lg-10 col-md-9 content g-0">
            <div class="bg">
            </div>

            <div class="row mycards mx-auto">
                <div class="col-5 mx-auto">
                    <div class="box notes shadow-md">
                        <h2><i class="fas fa-user"></i>ورود به حساب کاربری</h2>
                        <hr>
                        <?php showMessage(); ?>
                        <form action="inc/functions.php" method="post" class="text-center">
                            <input type="text" name="username" class="form-control w-75 mx-auto" placeholder="نام کاربری">
                            <input type="text" name="password" class="form-control w-75 mx-auto mt-2" placeholder="کلمه عبور">
                            <input type="submit" name="do-login" value="ورود به حساب کاربری" class="btn btn-success w-75 mt-3">
                        </form>
                    </div>
                </div>
            </div>


        </div>
    </div>
</div>
<?php require_once 'sections/footer.php'; ?>