<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Luster Cleaning Solutions</title>
    <?php include "cdn.php"; ?>
    <link rel="stylesheet" href="./css/base.css">
    <link rel="stylesheet" href="./css/index.css">
    <style>
        .home_all{
            height: 100vh;
            display: flex;
            flex-direction: column;
            justify-content: center; 
          padding: 0 7%;
          position: relative;
        }
        .welcome_text{
            text-align: center;
            margin-top: 20px;
        }
        .welcome_text h2{
            color: #25624d;
            margin-bottom: 20px;
        }
        .home_btn{
            position: absolute;
         bottom: 50px;
         width: 86%;
        }
        .home_btn button{
            width: 100%;
            height: 50px;
            background-color: #25624d;
           color: #fff;
           border: none;
        }
        img{
            height: 250px;
            width: 100%;
            object-fit: contain;
        }
    </style>
</head>
<body>
    <div class="home_all">
       <img src="./images/logo.png" alt="">
        <div class="welcome_text">
            <h2 id="typedText"></h2>
            <p id="typedHeading">Welcome to Luster Cleaning Solution app, your dedicated companion for mastering financial efficiency with ease.</p>
        </div>
        <div class="home_btn" id="typedHeadings">
            <a href="welcome.php">
                <button>Next <i class="fa-solid fa-arrow-right-long"></i></button>
            </a>
        </div>
    </div>

    <!-- Include Anime.js library -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/animejs/3.2.1/anime.min.js"></script>
    <!-- Include Typed.js library -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/typed.js/2.0.12/typed.min.js"></script>

    <!-- Your custom script for animation -->
    <script>
        var logoAnimation = anime({
            targets: '.logo',
            translateY: [-70, 0],
            opacity: [0, 1],
            duration: 1500, // Animation duration in milliseconds
            easing: 'easeOutExpo', // Easing function
            delay: 500 // Delay before the animation starts
        });

        // Typed.js initialization
        var typed = new Typed('#typedText', {
            strings: ["STAY GREEN, STAY CLEAN"],
            typeSpeed: 50, // Typing speed in milliseconds
            backSpeed: 20, // Backspacing speed in milliseconds
            loop: false, // Whether to loop the typing animation
            showCursor:false,
        });

        var welcomeHeadingAnimation = anime({
            targets: '#typedHeading',
            translateY: [50, 0],
            opacity: [0, 1],
            duration: 1500, // Animation duration in milliseconds
            easing: 'easeOutExpo', // Easing function
            delay: 700 // Delay before the animation starts
        });

        var welcomeHeadingAnimation = anime({
            targets: '#typedHeadings',
            translateY: [0, 10],
            opacity: [0, 1],
            duration: 500, // Animation duration in milliseconds
            easing: 'easeOutExpo', // Easing function
            delay: 800 // Delay before the animation starts
        });
    </script>
</body>
</html>