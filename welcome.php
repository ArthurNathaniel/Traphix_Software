<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Luster Cleaning Solutions</title>
    <?php include "cdn.php"; ?>
    <link rel="stylesheet" href="./css/base.css">
   <style>
    .swiper {
    width: 100%;
    height: 100%;
}

.swiper-slide {
    text-align: center;
    font-size: 18px;
    display: flex;
    justify-content: center;
    align-items: center;
}

.swiper-slide img {
    display: block;
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.home_all {
    height: 100vh;
    display: flex;
    flex-direction: column;
    justify-content: center;
    padding: 0 7%;
    position: relative;
    overflow-x: hidden;
}

.home_btn {
    position: absolute;
    bottom: 50px;
    width: 100%;
}

.home_btn button {
    width: 86%;
    height: 50px;
    display: flex;
    justify-content: center;
    align-items: center;
    gap: 30px;
    color: #fff;
    background-color:#25624d;
    border: none;

}

.home_btn a {
    text-decoration: none;
}

.welcome_all{
    padding: 0 7%;
    padding-block: 50px;
}

.welcome_title{
    text-align: center;
}
.welcome_title p{
    margin-top: 20px;
}
.welcome_swiper{
    margin-top: 50px;
}

.welcome_swiper img{
  /* object-fit: contain; */
    height: 300px;
}

.welcome_all span{
    color:#25624d;
}

.swiper-pagination-bullet {
    width: var(--swiper-pagination-bullet-width,var(--swiper-pagination-bullet-size, 23px));
    height: var(--swiper-pagination-bullet-height,var(--swiper-pagination-bullet-size,10px));
    display: inline-block;
    border-radius: var(--swiper-pagination-bullet-border-radius,10%);
    background: var(--swiper-pagination-bullet-inactive-color,#25624d);
    /* opacity: var(--swiper-pagination-bullet-inactive-opacity, .52); */
}

.bullets{
    margin-top: 50px;
}

.account_btns{
    display: flex;
    flex-direction: column;
    justify-content: center;
    align-items: center;
    margin-top: 40px;
    width: 100%;
}
.account_btns a{
    width: 100%;
}
.account_btns button{
    width: 100%;
    height: 50px;
    margin-bottom: 20px;
    border: 2px solid#25624d;
    background-color: transparent;
    color:#25624d;
}

.up{
    background-color:#25624d !important;
    color: #fff !important;
}


   </style>
</head>

<body>
    <div class="welcome_all">
        <div class="welcome_title">
            <h1><span>Luster Cleaning Solutions</span> </h1>
            <p>
                The ultimate mobile tool for financial secretaries to efficiently manage and optimize cash flow.
            </p>
        </div>
        <div class="welcome_swiper">
            <div class="swiper mySwiper">
                <div class="swiper-wrapper">
                    <div class="swiper-slide">
                        <img src="./images/hero_1.jpg" alt="">
                    </div>
                    <div class="swiper-slide">
                        <img src="./images/about.jpg" alt="">
                    </div>
                </div>

                <div class="bullets"></div>
                <div class="swiper-pagination"></div>
            </div>
        </div>
        <div class="account_btns">
<!-- <a href="signup.php">
    <button class="up">Sign Up</button>
</a> -->
<a href="login.php">
    <button>Log In</button>
</a>
        </div>
    </div>
    <script>
        var swiper = new Swiper(".mySwiper", {
            loop: true,
            spaceBetween: 30,
            centeredSlides: true,
            autoplay: {
                delay: 2500,
                disableOnInteraction: false,
            },
            pagination: {
                el: ".swiper-pagination",
                clickable: true,
            },
            navigation: {
                nextEl: ".swiper-button-next",
                prevEl: ".swiper-button-prev",
            },
        });
    </script>
</body>

</html>