<html>
<head>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@700&family=Montserrat:wght@400&display=swap');

        body {
            background: linear-gradient(-45deg, #ee7752, #e73c7e, #23a6d5, #23d5ab);
            background-size: 400% 400%;
            animation: gradient 15s ease infinite;
            font-family: 'Montserrat', sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
            overflow: hidden; 
        }

        @keyframes gradient {
            0% { background-position: 0% 50%; }
            50% { background-position: 100% 50%; }
            100% { background-position: 0% 50%; }
        }

        .container {
            text-align: center;
            background: rgba(255, 255, 255, 0.1);
            padding: 40px 60px;
            border-radius: 20px;
            box-shadow: 0 8px 32px 0 rgba(31, 38, 135, 0.37);
            backdrop-filter: blur(4px);
            -webkit-backdrop-filter: blur(4px);
            border: 1px solid rgba(255, 255, 255, 0.18);
            position: relative;
            z-index: 1; 
        }

        h1 {
            font-family: 'Poppins', sans-serif;
            color: #ffffff;
            font-size: 3.5em;
            margin-bottom: 10px;
            animation: slideInDown 1s ease-out;
            text-shadow: 2px 2px 4px rgba(0,0,0,0.2);
        }

        p {
            color: #f0f0f0;
            font-size: 1.4em;
            margin-top: 0;
            opacity: 0;
            animation: fadeIn 1s ease-in 0.5s forwards;
            text-shadow: 1px 1px 2px rgba(0,0,0,0.2);
        }

        .checkmark {
            width: 100px;
            height: 100px;
            border-radius: 50%;
            display: block;
            stroke-width: 2;
            stroke: #fff;
            stroke-miterlimit: 10;
            box-shadow: inset 0px 0px 0px #fff;
            animation: fill .4s ease-in-out .4s forwards, scale .3s ease-in-out .9s both;
            margin: 0 auto 20px auto;
        }

        .checkmark__circle {
            stroke-dasharray: 166;
            stroke-dashoffset: 166;
            stroke-width: 2;
            stroke-miterlimit: 10;
            stroke: #7ac142;
            fill: none;
            animation: stroke 0.6s cubic-bezier(0.65, 0, 0.45, 1) forwards;
        }

        .checkmark__check {
            transform-origin: 50% 50%;
            stroke-dasharray: 48;
            stroke-dashoffset: 48;
            animation: stroke 0.3s cubic-bezier(0.65, 0, 0.45, 1) 0.8s forwards;
        }

        @keyframes stroke {
            100% {
                stroke-dashoffset: 0;
            }
        }

        @keyframes scale {
            0%, 100% {
                transform: none;
            }
            50% {
                transform: scale3d(1.1, 1.1, 1);
            }
        }

        @keyframes fill {
            100% {
                box-shadow: inset 0px 0px 0px 50px #7ac142;
            }
        }

        @keyframes slideInDown {
            from {
                transform: translateY(-50px);
                opacity: 0;
            }
            to {
                transform: translateY(0);
                opacity: 1;
            }
        }

        @keyframes fadeIn {
            to {
                opacity: 1;
            }
        }

        #confetti-canvas {
            position: absolute; 
            top: 0;
            left: 0;
            z-index: -1; 
        }

    </style>
    
    <link rel="icon" href="assets/razerLogo.png" type="image/png">
</head>
<body>

    <div class="container">
        <svg class="checkmark" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 52 52">
            <circle class="checkmark__circle" cx="26" cy="26" r="25" fill="none"/>
            <path class="checkmark__check" fill="none" d="M14.1 27.2l7.1 7.2 16.7-16.8"/>
        </svg>

        <?php

            // session_start();

            if (isset($_SESSION['user'])) {
                $user = $_SESSION['user'];
                $customerName = htmlspecialchars($user['name']); 
            } else {
                $customerName = "Valued Customer";
            }

            echo "<h1>Thank You, $customerName!</h1>";
            echo "<p>Your order has been received and is now being processed.</p>";
        ?>
    </div>

    <canvas id="confetti-canvas"></canvas>

    <script>
        const canvas = document.getElementById('confetti-canvas');
        const ctx = canvas.getContext('2d');
        canvas.width = window.innerWidth;
        canvas.height = window.innerHeight;
        let particles = [];

        const colors = ["#e73c7e", "#23a6d5", "#ee7752", "#23d5ab", "#ffffff"];

        function createParticles() {
            const particleCount = 200;
            for (let i = 0; i < particleCount; i++) {
                particles.push({
                    x: Math.random() * canvas.width,
                    y: Math.random() * canvas.height - canvas.height,
                    color: colors[Math.floor(Math.random() * colors.length)],
                    radius: Math.random() * 5 + 2,
                    speed: Math.random() * 5 + 1,
                    angle: Math.random() * Math.PI * 2,
                });
            }
        }

        function animateParticles() {
            ctx.clearRect(0, 0, canvas.width, canvas.height);
            for (let i = 0; i < particles.length; i++) {
                let p = particles[i];
                p.y += p.speed;
                p.x += Math.sin(p.angle + p.y * 0.1);
                ctx.beginPath();
                ctx.fillStyle = p.color;
                ctx.arc(p.x, p.y, p.radius, 0, Math.PI * 2);
                ctx.fill();

                if (p.y > canvas.height) {
                    particles.splice(i, 1);
                }
            }

            if (particles.length > 0) {
                requestAnimationFrame(animateParticles);
            }
        }

        window.onload = () => {
            createParticles();
            animateParticles();
        };

        window.onresize = () => {
            canvas.width = window.innerWidth;
            canvas.height = window.innerHeight;
        };
    </script>

</body>
</html>