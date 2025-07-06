<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>404 | ISM</title>
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
            background: linear-gradient(130deg, var(--brown), var(--orange));
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
            font-size: 8rem;
            font-weight: 900;
            line-height: 1;
            background: linear-gradient(90deg, var(--orange), #fff, var(--blue));
            -webkit-background-clip: text;
            color: transparent;
            animation: hue 6s linear infinite
        }

        @keyframes hue {
            to {
                filter: hue-rotate(360deg)
            }
        }

        p {
            margin: 24px 0;
            font-size: 1.05rem;
            opacity: .85
        }

        a {
            display: inline-block;
            margin-top: 10px;
            padding: 14px 28px;
            border-radius: var(--radius);
            background: var(--blue);
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
</head>

<body>
    <div class="box">
        <h1>404</h1>
        <p>Oups ! La page que vous cherchez n’existe pas ou a été déplacée.</p>
    </div>
</body>

</html>