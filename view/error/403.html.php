<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>403 | ISM</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: "Segoe UI", sans-serif
        }

        :root {
            --brown: #4a2e16;
            --orange: #ff9d33;
            --blue: #2d73d2;
            --glass: rgba(255, 255, 255, .14);
            --radius: 22px
        }

        body {
            min-height: 100vh;
            background: linear-gradient(135deg, var(--blue), var(--violet, #6f58d7));
            display: flex;
            align-items: center;
            justify-content: center;
            color: #fff;
            text-align: center;
            position: relative;
            overflow: hidden
        }

        body::before {
            content: "";
            position: absolute;
            inset: 0;
            backdrop-filter: blur(8px)
        }

        .box {
            position: relative;
            z-index: 2;
            background: var(--glass);
            padding: 60px 48px;
            border-radius: var(--radius);
            border: 1px solid rgba(255, 255, 255, .22);
            box-shadow: 0 8px 28px rgba(0, 0, 0, .15)
        }

        h1 {
            font-size: 7rem;
            font-weight: 900;
            line-height: 1.1;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 12px;
            background: linear-gradient(90deg, var(--orange), #fff, var(--brown));
            -webkit-background-clip: text;
            color: transparent;
            animation: hue 6s linear infinite
        }

        @keyframes hue {
            to {
                filter: hue-rotate(360deg)
            }
        }

        h1 i {
            font-size: 2.4rem
        }

        @media(max-width:480px) {
            h1 {
                font-size: 4.6rem
            }
        }

        p {
            margin: 22px 0;
            font-size: 1.05rem;
            opacity: .85
        }

        a {
            display: inline-block;
            margin-top: 10px;
            padding: 14px 28px;
            border-radius: var(--radius);
            background: var(--orange);
            color: #fff;
            font-weight: 700;
            text-decoration: none;
            transition: transform .3s box-shadow .3s
        }

        a:hover {
            transform: translateY(-4px);
            box-shadow: 0 6px 16px rgba(0, 0, 0, .22)
        }
    </style>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head>

<body>
    <div class="box">
        <h1><i class="fa-solid fa-lock"></i>403</h1>
        <p>Accès refusé ! Vous n’êtes pas autorisé·e à consulter cette page.</p>
    </div>
</body>

</html>